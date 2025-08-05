<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Withdraw;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class WithdrawRequestController extends Controller {
    /**
     * Display a listing of withdraw requests.
     *
     * @param Request $request
     * @return View|JsonResponse
     * @throws Exception
     */
    public function index(Request $request): View | JsonResponse {
        if ($request->ajax()) {
            $data = Withdraw::with('user')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user_name', function ($data) {
                    return $data->user ? $data->user->name : '';
                })
                ->addColumn('status', function ($data) {
                    $statusOptions = [
                        'pending'  => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ];
                    $statusDropdown = '<select class="form-select" onchange="changeStatus(' . $data->id . ', this.value)">';
                    foreach ($statusOptions as $value => $label) {
                        $selected = $data->status == $value ? 'selected' : '';
                        $statusDropdown .= '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
                    }
                    $statusDropdown .= '</select>';
                    return $statusDropdown;
                })
                ->rawColumns(['user_name', 'status'])
                ->make();
        }
        return view('backend.layouts.withdraw-request.index');
    }

    /**
     * Update the specified withdraw request.
     *
     * @param Request $request
     * @param int $id
     */
    public function status(Request $request, int $id) {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        try {
            $withdraw = Withdraw::findOrFail($id);

            if ($withdraw->status === 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'This withdrawal request has already been approved and cannot be changed.',
                ], 403);
            }

            // Update the status
            $withdraw->status = $request->input('status');
            $withdraw->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.',
                'data'    => $withdraw,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the status.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
