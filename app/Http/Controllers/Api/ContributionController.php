<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Payment;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContributionController extends Controller {
    /**
     * Display the list of all contributions.
     *
     * @return JsonResponse
     */
    public function MyContribution(): JsonResponse {
        try {
            $userId = Auth::id();

            if (!$userId) {
                return Helper::jsonResponse(false, "User not authenticated", 401);
            }

            //* Get the list of collections the user has contributed to
            $contributions = Payment::where('user_id', $userId)
                ->with(['collection' => function ($query) {
                    $query->select('id', 'name', 'description', 'target_amount', 'deadline', 'image');
                }])
                ->orderBy('created_at', 'desc')
                ->get();

            if ($contributions->isEmpty()) {
                return Helper::jsonResponse(true, 'No contributions found', 200, []);
            }

            //! Group contributions by collection_id and sum the contribution amounts
            $groupedContributions = $contributions->groupBy('collection_id')->map(function ($group) {
                $collection = $group->first()->collection;

                //* Calculate collected amount for the collection
                $collected = Payment::where('collection_id', $collection->id)->sum('amount');

                //* Get unique participants for this collection
                $uniqueUserIds   = Payment::where('collection_id', $collection->id)->pluck('user_id')->filter()->unique()->count();
                $nullUserIdCount = Payment::where('collection_id', $collection->id)->whereNull('user_id')->count();
                $participants    = $uniqueUserIds + $nullUserIdCount; // Total participants

                return [
                    'collection_id'      => $collection->id,
                    'collection_name'    => $collection->name,
                    'image'              => $collection->image,
                    'target_amount'      => number_format($collection->target_amount, 2, '.', ''),
                    'deadline'           => $collection->deadline,
                    'collected'          => number_format($collected, 2, '.', ''),
                    'participants'       => (int) $participants,
                    'contributed_amount' => number_format($group->sum('amount'), 2, '.', ''),
                ];
            })->values();

            return Helper::jsonResponse(true, 'Contributions retrieved successfully', 200, $groupedContributions);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while retrieving your contributions.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get the contribution details for a specific collection.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function ContributionDetails(int $id): JsonResponse {
        try {
            $userId = Auth::id();

            if (!$userId) {
                return Helper::jsonResponse(false, 'User not authenticated', 401);
            }

            //* Retrieve the collection details
            $collection = Collection::find($id);

            if (!$collection) {
                return Helper::jsonResponse(false, 'Collection not found', 404);
            }

            //* Get all payments related to the collection
            $allContributions = Payment::where('collection_id', $collection->id)
                ->get(['amount', 'user_id', 'name']);

            //! Calculate total collected amount
            $collected = $allContributions->sum('amount');

            //* Get unique participants count based on user_id
            $participants = $allContributions->pluck('user_id')->filter()->unique()->count() + $allContributions->whereNull('user_id')->count();

            //* Aggregate user contributions by user_id
            $aggregatedContributions = $allContributions->groupBy('user_id')->flatMap(function ($group) {
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

            //! Prepare the response data
            $response = [
                'collection_name'    => $collection->name,
                'description'        => $collection->description,
                'image'              => $collection->image,
                'target_amount'      => number_format($collection->target_amount, 2, '.', ''),
                'deadline'           => $collection->deadline,
                'collected'          => number_format($collected, 2, '.', ''),
                'participants'       => (int) $participants,
                'user_contributions' => $aggregatedContributions,
            ];

            return Helper::jsonResponse(true, 'Contribution details retrieved successfully', 200, $response);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while retrieving the collection details.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Search for contributions by collection name.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function SearchContributions(Request $request): JsonResponse {
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

        $searchQuery = $request->input('query');

        try {
            $contributions = Payment::where('user_id', $userId)
                ->whereHas('collection', function ($query) use ($searchQuery) {
                    $query->where('name', 'LIKE', '%' . $searchQuery . '%')
                        ->where('status', 'active')
                        ->whereNull('deleted_at');
                })
                ->with(['collection' => function ($query) {
                    $query->select('id', 'name', 'description', 'target_amount', 'deadline', 'image');
                }])
                ->get();

            if ($contributions->isEmpty()) {
                return Helper::jsonResponse(true, 'No contributions found', 200, []);
            }

            $groupedContributions = $contributions->groupBy('collection_id')->map(function ($group) {
                $collection = $group->first()->collection;

                $collected = Payment::where('collection_id', $collection->id)->sum('amount');

                $participants = Payment::where('collection_id', $collection->id)
                    ->distinct('user_id')
                    ->count('user_id');

                return [
                    'collection_id'      => $collection->id,
                    'collection_name'    => $collection->name,
                    'image'              => $collection->image,
                    'target_amount'      => number_format($collection->target_amount, 2, '.', ''),
                    'deadline'           => $collection->deadline,
                    'collected'          => number_format($collected, 2, '.', ''),
                    'participants'       => (int) $participants,
                    'contributed_amount' => number_format($group->sum('amount'), 2, '.', ''),
                ];
            })->values();

            return Helper::jsonResponse(true, 'Contributions retrieved successfully', 200, $groupedContributions);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while searching for contributions.', 500, ['error' => $e->getMessage()]);
        }
    }
}
