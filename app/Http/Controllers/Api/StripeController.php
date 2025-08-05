<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class StripeController extends Controller {
    /**
     * Get stripe keys
     *
     * @return JsonResponse
     */
    public function GetStripeKeys(): JsonResponse {
        $stripePublicKey = env('STRIPE_PUBLIC_KEY');
        $stripeSecretKey = env('STRIPE_SECRATE_KEY');

        $key = [
            'STRIPE_PUBLIC_KEY'  => $stripePublicKey,
            'STRIPE_SECRATE_KEY' => $stripeSecretKey,
        ];
        return Helper::jsonResponse(true, "Stripe key fetched successfully", 200, $key);
    }
}
