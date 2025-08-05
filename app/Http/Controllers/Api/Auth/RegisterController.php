<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller {
    /**
     * Register a new user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function Register(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|between:2,255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return Helper::jsonResponse(false, 'Validation failed', 422, $validator->errors());
        }

        try {
            $otp          = rand(1000, 9999);
            $otpExpiresAt = now()->addMinutes(60);

            $user = User::create([
                'name'           => $request->input('name'),
                'email'          => $request->input('email'),
                'password'       => Hash::make($request->input('password')),
                'otp'            => $otp,
                'otp_expires_at' => $otpExpiresAt,
            ]);
            //* Send OTP email
            Mail::to($user->email)->send(new OTPMail($user->otp));

            //? Generate an access token for the user
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status'     => true,
                'message'    => 'User Successfully Registered',
                'code'       => 200,
                'token_type' => 'bearer',
                'token'      => $token,
                'data'       => $user,
            ], 200);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while register new account', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Verify user's email
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function VerifyEmail(Request $request): JsonResponse {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'otp'   => 'required|digits:4',
            ]);

            if ($validator->fails()) {
                return Helper::jsonResponse(false, 'Validation failed', 422, $validator->errors());
            }

            $user = User::where('email', $request->input('email'))->first();

            //! Check if email has already been verified
            if ($user->email_verified_at !== null) {
                return Helper::jsonResponse(false, 'Your email has already been verified. Please login to continue.', 409);
            }

            if ($user->otp !== $request->input('otp')) {
                return Helper::jsonResponse(false, 'Invalid OTP. Please try again.', 422);
            }

            if (now()->greaterThan($user->otp_expires_at)) {
                return Helper::jsonResponse(false, 'OTP has expired. Please request a new OTP.', 422);
            }

            //* Verify the email
            $user->email_verified_at = now();
            $user->otp               = null;
            $user->otp_expires_at    = null;
            $user->save();

            //? Generate an access token for the user
            $token = $user->createToken('email-verification-token')->plainTextToken;

            return response()->json([
                'status'     => true,
                'message'    => 'Email verified successfully.',
                'code'       => 200,
                'token_type' => 'bearer',
                'token'      => $token,
                'data'       => $user,
            ], 200);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An unexpected error occurred.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Resend OTP to user's email
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function ResendOtp(Request $request): JsonResponse {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
            ]);

            if ($validator->fails()) {
                return Helper::jsonResponse(false, 'Validation failed', 422, $validator->errors());
            }

            $user = User::where('email', $request->input('email'))->first();
            if (!$user) {
                return Helper::jsonResponse(false, 'User not found.', 404);
            }

            if ($user->email_verified_at) {
                return Helper::jsonResponse(false, 'Email already verified.', 400);
            }

            $newOtp               = rand(1000, 9999);
            $otpExpiresAt         = now()->addMinutes(60);
            $user->otp            = $newOtp;
            $user->otp_expires_at = $otpExpiresAt;
            $user->save();

            //* Send the new OTP to the user's email
            Mail::to($user->email)->send(new OTPMail($user->otp));

            return Helper::jsonResponse(true, 'A new OTP has been sent to your email.', 200);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An unexpected error occurred.', 500, ['error' => $e->getMessage()]);
        }
    }
}
