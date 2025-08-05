<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller {
    /**
     * Display the user profile.
     *
     * @return JsonResponse
     */
    public function ShowProfile(): JsonResponse {
        try {
            $user = Auth::user();
            if (!$user) {
                return Helper::jsonResponse(false, "User not authenticated", 401);
            }

            if (!$user->email_verified_at) {
                return Helper::jsonResponse(false, "User is not verified", 403);
            }

            return Helper::jsonResponse(true, "User profile fetched successfully", 200, $user);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while fetching user profile.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Update the user profile.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function EditProfile(Request $request) {
        $validator = Validator::make($request->all(), [
            'avatar'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'name'    => 'nullable|string|max:255',
            'phone'   => 'nullable|string|max:25',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return Helper::jsonResponse(false, 'Validation failed', 422, $validator->errors());
        }

        try {
            $user = Auth::user();

            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    Helper::fileDelete($user->avatar);
                }

                $avatar       = $request->file('avatar');
                $avatarName   = time() . '.' . $avatar->getClientOriginalExtension();
                $avatarPath   = Helper::fileUpload($avatar, 'avatars', $avatarName);
                $user->avatar = $avatarPath;
            }

            $user->fill($request->only([
                'name',
                'phone',
                'address',
            ]));

            $user->save();

            return Helper::jsonResponse(true, 'Profile updated successfully', 200, $user);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while updating profile.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Delete the user account.
     *
     * @return JsonResponse
     */
    public function DeleteAccount(): JsonResponse {
        try {
            $user = Auth::user();
            if ($user) {
                if ($user->avatar) {
                    Helper::fileDelete(public_path($user->avatar));
                }
                $user->delete();
                return Helper::jsonResponse(true, 'Account deleted successfully', 200);
            }
            return Helper::jsonResponse(false, 'Unauthorized Access', 404);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while deleting the account.', 500, ['error' => $e->getMessage()]);
        }
    }
}
