<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class CollectionController extends Controller {
    /**
     * Display a listing of the testimonials.
     *
     * @param Request $request
     * @return View|JsonResponse
     * @throws Exception
     */
    public function index(Request $request): View | JsonResponse {
        if ($request->ajax()) {
            $data = Collection::with('user')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user_name', function ($data) {
                    if ($data->user) {
                        return $data->user->name;
                    } else {
                        return '';
                    }
                })
                ->addColumn('image', function ($data) {
                    $defaultImage = asset('frontend/profile-avatar.png');
                    if ($data->image) {
                        $url = asset($data->image);
                    } else {
                        $url = $defaultImage;
                    }
                    return '<img src="' . $url . '" alt="Image" width="50px" height="50px">';
                })
                ->addColumn('description', function ($data) {
                    $description      = $data->description;
                    $shortDescription = strlen($description) > 20 ? substr($description, 0, 20) . '...' : $description;
                    return '<p>' . $shortDescription . '</p>';
                })
                ->addColumn('status', function ($data) {
                    $status = ' <div class="form-check form-switch" style="margin-left:40px;">';
                    $status .= ' <input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" getAreaid="' . $data->id . '" name="status"';
                    if ($data->status == "active") {
                        $status .= "checked";
                    }
                    $status .= '><label for="customSwitch' . $data->id . '" class="form-check-label" for="customSwitch"></label></div>';

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    $viewButton = '<button type="button" class="btn btn-primary btn-sm" onclick="viewCollectionDetails(' . $data->id . ')">
                                       <i class="lni lni-eye"></i>
                                   </button>';
                    return $viewButton;
                })
                ->rawColumns(['user_name', 'image', 'description', 'status', 'action'])
                ->make();
        }
        return view('backend.layouts.collections.index');
    }

    /**
     * Display the specified collection details.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function view($id): JsonResponse {
        try {
            // Retrieve collection with user and donations
            $collection = Collection::with('user', 'donations.user')->findOrFail($id);

            // Get all contributions (donations) related to the collection
            $contributions = $collection->donations()->with('user')->get();

            // Calculate total contributions (current balance)
            $totalContributions = $contributions->sum('amount');

            // Calculate remaining amount to reach the target
            $remainingAmount = max($collection->target_amount - $totalContributions, 0);

            return response()->json([
                'status'              => true,
                'data'                => $collection,
                'contributions'       => $contributions,
                'total_contributions' => $totalContributions,
                'remaining_amount'    => $remainingAmount,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Collection not found',
            ], 404);
        }
    }

    /**
     * Toggle the status of the specified testimonial.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function status(int $id): JsonResponse {
        $data = Collection::findOrFail($id);

        if ($data->status == 'active') {
            $data->status = 'inactive';
            $data->save();
            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
                'data'    => $data,
            ]);
        } else {
            $data->status = 'active';
            $data->save();
            return response()->json([
                'success' => true,
                'message' => 'Published Successfully.',
                'data'    => $data,
            ]);
        }
    }
}
