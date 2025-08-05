<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Payment;
use App\Models\User;
use App\Models\Withdraw;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class CollectionController extends Controller {
    /**
     * Display the list of all collections in desc order.
     *
     * @return JsonResponse
     */
    public function MyCollections(): JsonResponse {
        $userId = Auth::id();

        if (!$userId) {
            return Helper::jsonResponse(false, "User not authenticated", 401);
        }

        try {
            $collections = Collection::where('user_id', $userId)
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->get();

            if ($collections->isEmpty()) {
                return Helper::jsonResponse(true, 'No collections found', 200, []);
            }

            //* Add dynamic 'collected' and 'participants' fields
            $collections = $collections->map(function ($collection) {
                $payments              = Payment::where('collection_id', $collection->id)->get();
                $collection->collected = number_format($payments->sum('amount'), 2, '.', '');

                //* Calculate unique participants
                $uniqueUserIds            = $payments->pluck('user_id')->filter()->unique()->count();
                $nullUserIdCount          = $payments->whereNull('user_id')->count();
                $collection->participants = $uniqueUserIds + $nullUserIdCount;
                return $collection;
            });

            return Helper::jsonResponse(true, 'Collections retrieved successfully', 200, $collections);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while fetching the collections.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified collection details.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function SingleCollectionDetails(int $id): JsonResponse {
        try {
            $collection = Collection::find($id);

            if (!$collection) {
                return Helper::jsonResponse(false, 'Collection not found', 404);
            }

            //* Fetch all payments and withdraws related to this collection
            $payments  = Payment::where('collection_id', $collection->id)->get();
            $withdraws = Withdraw::where('collection_id', $collection->id)->get();

            //? Calculate the total collected amount
            $collection->collected = number_format($payments->sum('amount'), 2, '.', '');

            //* Count unique participants: distinct user_id excluding NULL
            $uniqueUserIds = $payments->pluck('user_id')->filter()->unique()->count();

            //* Count separate entries with NULL user_id
            $nullUserIdCount = $payments->whereNull('user_id')->count();

            //* Total participants including NULL user_id as separate participants
            $collection->participants = $uniqueUserIds + $nullUserIdCount;

            $link             = URL::route('collection.donate', ['collection_id' => $collection->id]);
            $collection->link = $link;

            //! Prepare the list of contributors by aggregating contributions by user_id
            $contributors = $payments->groupBy('user_id')->flatMap(function ($group) {
                $userId = $group->first()->user_id;

                if ($userId === null) {
                    return $group->map(function ($payment) {
                        return [
                            'name'   => $payment->name,
                            'amount' => number_format($payment->amount, 2, '.', ''),
                        ];
                    });
                } else {
                    $userName = User::find($userId)->name;

                    return [
                        [
                            'name'   => $userName,
                            'amount' => number_format($group->sum('amount'), 2, '.', ''),
                        ],
                    ];
                }
            })->values();

            //* Add contributors to the collection data
            $collection->contributors = $contributors;

            //! Prepare the list of withdraws with status, amount, and date
            $withdrawDetails = $withdraws->map(function ($withdraw) {
                return [
                    'status' => $withdraw->status,
                    'amount' => number_format($withdraw->amount, 2, '.', ''),
                    'date'   => $withdraw->created_at->format('Y-m-d H:i:s'),
                ];
            })->values();

            //* Add withdraws to the collection data
            $collection->withdraws = $withdrawDetails;

            return Helper::jsonResponse(true, 'Collection details retrieved successfully', 200, $collection);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while retrieving the collection details.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Store a newly created collection in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function Create(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            "name"          => "required|string|max:255",
            "description"   => "required|string",
            "target_amount" => "required|numeric",
            "deadline"      => "required|date",
        ]);

        if ($validator->fails()) {
            return Helper::jsonResponse(false, 'Validation failed', 422, $validator->errors());
        }

        $userId = Auth::id();

        if (!$userId) {
            return Helper::jsonResponse(false, "User not authenticated", 401);
        }

        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = Helper::fileUpload($image, 'collections', $imageName);
        } else {
            $imagePath = null;
        }

        try {
            $collection = Collection::create([
                'user_id'       => $userId,
                'image'         => $imagePath,
                'name'          => $request->name,
                'description'   => $request->description,
                'target_amount' => $request->target_amount,
                'deadline'      => $request->deadline,
            ]);

            return Helper::jsonResponse(true, 'Collection created successfully', 200, $collection);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while creating the collection.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified collection in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function Update(Request $request, int $id): JsonResponse {
        $collection = Collection::findOrFail($id);

        if (!$collection) {
            return Helper::jsonResponse(false, 'Collection not found', 404);
        }

        if (Auth::id() !== $collection->user_id) {
            return Helper::jsonResponse(false, 'You are not authorized to update this collection', 403);
        }

        $validator = Validator::make($request->all(), [
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            "name"          => "nullable|string|max:255",
            "description"   => "nullable|string",
            "target_amount" => "nullable|numeric",
            "deadline"      => "nullable|date",
        ]);

        if ($validator->fails()) {
            return Helper::jsonResponse(false, 'Validation failed', 422, $validator->errors());
        }

        if ($request->hasFile('image')) {
            if ($collection->image) {
                Helper::fileDelete(public_path($collection->image));
            }

            $image     = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = Helper::fileUpload($image, 'collections', $imageName);
        } else {
            $imagePath = $collection->image;
        }

        try {
            $collection->update([
                'image'         => $imagePath,
                'name'          => $request->name ?? $collection->name,
                'description'   => $request->description ?? $collection->description,
                'target_amount' => $request->target_amount ?? $collection->target_amount,
                'deadline'      => $request->deadline ?? $collection->deadline,
            ]);

            return Helper::jsonResponse(true, 'Collection updated successfully', 200, $collection);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while updating the collection.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified collection from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function Destroy(int $id): JsonResponse {
        try {
            $collection = Collection::find($id);

            if (!$collection) {
                return Helper::jsonResponse(false, 'Collection not found.', 404);
            }

            if ($collection->user_id !== Auth::id()) {
                return Helper::jsonResponse(false, 'Unauthorized to delete this collection.', 403);
            }

            if ($collection->image) {
                Helper::fileDelete(public_path($collection->image));
            }

            $collection->delete();
            return Helper::jsonResponse(true, 'Collection deleted successfully.', 200);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while deleting the collection.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Search for collections by name.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function SearchCollections(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return Helper::jsonResponse(false, 'Validation failed', 422, $validator->errors());
        }

        $userId = Auth::id();

        if (!$userId) {
            return Helper::jsonResponse(false, "User not authenticated", 401);
        }

        $query = $request->input('query');

        try {
            $collections = Collection::where('user_id', $userId)
                ->where('name', 'LIKE', '%' . $query . '%')
                ->where('status', 'active')
                ->get();

            if ($collections->isEmpty()) {
                return Helper::jsonResponse(true, 'No collections found', 200, []);
            }

            //* Add dynamic 'collected' and 'participants' fields
            $collections = $collections->map(function ($collection) {
                $payments                 = Payment::where('collection_id', $collection->id)->get();
                $collection->collected    = number_format($payments->sum('amount'), 2, '.', '');
                $collection->participants = (int) $payments->count();
                return $collection;
            });

            return Helper::jsonResponse(true, 'Collections retrieved successfully', 200, $collections);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while searching for collections.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Generate a unique link for the specified collection.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function GenerateLink(int $id): JsonResponse {
        try {
            $collection = Collection::find($id);

            if (!$collection) {
                return Helper::jsonResponse(false, 'Collection not found', 404);
            }

            $link = URL::route('collection.donate', ['collection_id' => $collection->id]);

            return Helper::jsonResponse(true, 'Collection link generated successfully', 200, ['link' => $link]);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while generating the collection link.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get the balance of the specified collection.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function CollectionBalance(int $id): JsonResponse {
        try {
            $user = auth()->user();

            $collection = Collection::find($id);
            if (!$collection) {
                return Helper::jsonResponse(false, 'Collection not found', 404);
            }

            if ($collection->user_id !== $user->id) {
                return Helper::jsonResponse(false, 'You are not authorized to view this collection balance', 403);
            }

            //* Calculate total payments for the collection
            $payments      = Payment::where('collection_id', $collection->id)->get();
            $totalPayments = $payments->sum('amount');

            //* Calculate total withdrawals for the collection
            $totalWithdrawals = Withdraw::where('collection_id', $collection->id)
                ->where('status', 'approved')
                ->sum('amount');

            //* Calculate the remaining balance
            $balance = $totalPayments - $totalWithdrawals;

            return Helper::jsonResponse(true, 'Collection balance retrieved successfully', 200, [
                'balance' => number_format($balance, 2, '.', ''),
            ]);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while retrieving the collection balance.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Create a new withdraw request for the specified collection.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function withdrawBalance(Request $request, int $id): JsonResponse {
        $request->validate([
            'amount'       => 'required|numeric|min:0.01',
            'bank_account' => 'required|string',
            'account_type' => 'required|string',
            'account_name' => 'required|string',
        ]);

        $userId      = auth()->id();
        $amount      = $request->input('amount');
        $bankAccount = $request->input('bank_account');
        $accountType = $request->input('account_type');
        $userName    = $request->input('account_name');

        try {
            $collection = Collection::find($id);
            if (!$collection) {
                return Helper::jsonResponse(false, 'Collection not found', 404);
            }

            //* Calculate total payments for the collection
            $totalPayments = Payment::where('collection_id', $collection->id)->sum('amount');

            //* Calculate total approved withdrawals for the collection
            $totalWithdrawals = Withdraw::where('collection_id', $collection->id)
                ->where('status', 'approved')
                ->sum('amount');

            //* Calculate the available balance
            $balance = $totalPayments - $totalWithdrawals;

            if ($amount > $balance) {
                return Helper::jsonResponse(false, 'Insufficient balance', 400);
            }

            Withdraw::create([
                'user_id'       => $userId,
                'collection_id' => $collection->id,
                'amount'        => $amount,
                'bank_account'  => $bankAccount,
                'account_type'  => $accountType,
                'account_name'  => $userName,
            ]);

            return Helper::jsonResponse(true, 'Withdraw request created successfully', 200);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while processing the withdraw request', 500, ['error' => $e->getMessage()]);
        }
    }
}
