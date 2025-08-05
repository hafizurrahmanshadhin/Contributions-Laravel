<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;

class LogoutController extends Controller {
    /**
     * Logout user
     *
     * @return JsonResponse
     */
    public function Logout(): JsonResponse {
        try {
            if (auth()->check()) {
                auth()->user()->tokens()->delete();
                return Helper::jsonResponse(true, 'Logout successfully', 200);
            } else {
                return Helper::jsonResponse(false, 'User not authenticated', 401);
            }
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred during logout.', 500, ['error' => $e->getMessage()]);
        }
    }
}
