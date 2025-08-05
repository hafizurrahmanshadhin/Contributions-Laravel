<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Mail\OTPMail;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller {
    /**
     * Send OTP to user email
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function SendOTP(Request $request): JsonResponse {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
            ]);

            if ($validator->fails()) {
                return Helper::jsonResponse(false, 'Validation failed', 422, $validator->errors());
            }

            $email = $request->input('email');
            $otp   = rand(1000, 9999);
            $user  = User::where('email', $email)->first();

            if ($user) {
                Mail::to($email)->send(new OTPMail($otp));
                $user->update([
                    'otp'            => $otp,
                    'otp_expires_at' => Carbon::now()->addMinutes(60),
                ]);
                return Helper::jsonResponse(true, 'OTP Code Sent Successfully Please Check Your Email.', 200);
            } else {
                return Helper::jsonResponse(false, 'Invalid Email Address', 404);
            }
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Verify OTP
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function VerifyOTP(Request $request): JsonResponse {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'otp'   => 'required|digits:4',
            ]);

            if ($validator->fails()) {
                return Helper::jsonResponse(false, 'Validation failed', 422, $validator->errors());
            }

            $email = $request->input('email');
            $otp   = $request->input('otp');

            $user = User::where('email', $email)->first();
            if (!$user) {
                return Helper::jsonResponse(false, 'User not found', 404);
            }

            if (now()->gt($user->otp_expires_at)) {
                return Helper::jsonResponse(false, 'OTP has expired.', 400);
            }

            if ($user->otp !== $otp) {
                return Helper::jsonResponse(false, 'Invalid OTP', 400);
            }

            $user->update([
                'otp'             => null,
                'otp_expires_at'  => null,
                'is_otp_verified' => true,
                'otp_verified_at' => now(),
            ]);

            return Helper::jsonResponse(true, 'OTP verified successfully', 200);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred during OTP verification', 500);
        }
    }

    /**
     * Reset Password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function ResetPassword(Request $request): JsonResponse {
        try {
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email|exists:users,email',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return Helper::jsonResponse(false, 'Validation failed', 422, $validator->errors());
            }

            $email       = $request->input('email');
            $newPassword = $request->input('password');

            $user = User::where('email', $email)->first();
            if (!$user) {
                return Helper::jsonResponse(false, 'User not found', 404);
            }

            if (!$user->is_otp_verified) {
                return Helper::jsonResponse(false, 'OTP not verified', 400);
            }

            if (now()->gt($user->otp_expires_at)) {
                return Helper::jsonResponse(false, 'OTP expired', 400);
            }

            $user->update([
                'password'        => Hash::make($newPassword),
                'is_otp_verified' => false,
                'otp'             => null,
                'otp_expires_at'  => null,
            ]);

            Auth::login($user);
            $token = $user->createToken('reset_password')->plainTextToken;

            return response()->json([
                'status'     => true,
                'message'    => 'Password rest Successfully',
                'code'       => 200,
                'token_type' => 'bearer',
                'token'      => $token,
                'data'       => $user,
            ], 200);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred during password reset', 500);
        }
    }
}
