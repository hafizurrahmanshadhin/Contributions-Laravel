<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller {
    /**
     * Login user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function Login(Request $request): JsonResponse {
        try {
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email|exists:users,email',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return Helper::jsonResponse(false, 'Validation failed', 422, $validator->errors());
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return Helper::jsonResponse(false, 'User not found', 404);
            }

            //! Check the password
            if (!Hash::check($request->password, $user->password)) {
                return Helper::jsonResponse(false, 'Invalid password', 401);
            }

            //? Check if the email is verified before login is successful
            if (!$user->email_verified_at) {
                return Helper::jsonResponse(false, 'Email not verified. Please verify your email before logging in.', 403);
            }

            //* Generate token if email is verified
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status'     => true,
                'message'    => 'Login successful',
                'code'       => 200,
                'token_type' => 'bearer',
                'token'      => $token,
                'data'       => $user,
            ], 200);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred during login.', 500, ['error' => $e->getMessage()]);
        }
    }
}
