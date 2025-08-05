<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\FirebaseToken;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FirebaseTokenController extends Controller {
    /**
     * Store the Firebase token for the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function StoreFirebaseToken(Request $request): JsonResponse {
        try {
            $userId = Auth::id();

            $validator = Validator::make($request->all(), [
                'token'     => 'required|string',
                'device_id' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return Helper::jsonResponse(false, $validator->errors()->first(), 400);
            }

            //! Update or create token for the authenticated user
            FirebaseToken::updateOrCreate(
                ['user_id' => $userId, 'device_id' => $request->device_id],
                ['token' => $request->token]
            );

            return Helper::jsonResponse(true, 'Token saved successfully', 200);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to store Firebase token', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get the Firebase token for the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function GetFirebaseToken(Request $request): JsonResponse {
        try {
            $userId = Auth::id();

            $validator = Validator::make($request->all(), [
                'device_id' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return Helper::jsonResponse(false, $validator->errors()->first(), 400);
            }

            $token = FirebaseToken::where('user_id', $userId)
                ->when($request->device_id, function ($query, $deviceId) {
                    return $query->where('device_id', $deviceId);
                })
                ->first();

            if (!$token) {
                return Helper::jsonResponse(true, 'No records found', 200, []);
            }

            return Helper::jsonResponse(true, 'Token fetched successfully', 200, $token);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to fetch Firebase token', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Delete the Firebase token for the provided user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function DeleteFirebaseToken(Request $request): JsonResponse {
        try {
            $validator = Validator::make($request->all(), [
                'user_id'   => 'required|exists:users,id',
                'device_id' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return Helper::jsonResponse(false, $validator->errors()->first(), 400);
            }

            //* Retrieve the user_id from the request
            $userId = $request->user_id;

            //? If a device_id is provided, delete the token for that specific device
            if ($request->has('device_id')) {
                $token = FirebaseToken::where('user_id', $userId)
                    ->where('device_id', $request->device_id)
                    ->first();

                if ($token) {
                    $token->delete();
                    return Helper::jsonResponse(true, 'Token for the specified device deleted successfully', 200);
                }
                return Helper::jsonResponse(false, 'No records found for the specified device', 404);
            }

            //! If no device_id is provided, delete all tokens for the user
            FirebaseToken::where('user_id', $userId)->delete();

            return Helper::jsonResponse(true, 'All tokens for the user deleted successfully', 200);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to delete Firebase token(s)', 500, ['error' => $e->getMessage()]);
        }
    }
}
