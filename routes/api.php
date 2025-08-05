<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\SocialLoginController;
use App\Http\Controllers\Api\CollectionController;
use App\Http\Controllers\Api\ContributionController;
use App\Http\Controllers\Api\FirebaseTokenController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\StripeController;
use Illuminate\Support\Facades\Route;

//! Auth routes
Route::post('/register', [RegisterController::class, 'Register']);
Route::post('/verify-email', [RegisterController::class, 'VerifyEmail']);
Route::post('/resend-otp', [RegisterController::class, 'ResendOtp']);
Route::post('/login', [LoginController::class, 'Login']);
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('/logout', [LogoutController::class, 'Logout']);
});
Route::post('/send-otp', [ResetPasswordController::class, 'SendOTP']);
Route::post('/verify-otp', [ResetPasswordController::class, 'VerifyOTP']);
Route::post('/reset-password', [ResetPasswordController::class, 'ResetPassword']);

//! Route For Socialite Login.
Route::post('/social-login', [SocialLoginController::class, 'SocialLogin']);

//! Profile routes
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'ShowProfile']);
    Route::post('/profile/update', [ProfileController::class, 'EditProfile']);
    Route::delete('/account/delete', [ProfileController::class, 'DeleteAccount']);
});

//! For single collection details no need authenticated.
Route::get('/collection-details/{id}', [CollectionController::class, 'SingleCollectionDetails'])->name('collection-details');
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    //! Collections routes
    Route::post('/collection/create', [CollectionController::class, 'Create']);
    Route::post('/collection/update/{id}', [CollectionController::class, 'Update']);
    Route::post('/collection/destroy/{id}', [CollectionController::class, 'Destroy']);
    Route::get('/my-collections', [CollectionController::class, 'MyCollections']);
    Route::get('/search-collections', [CollectionController::class, 'SearchCollections']);
    Route::post('/generate-link/{id}', [CollectionController::class, 'GenerateLink']);
    Route::get('/collection/balance/{id}', [CollectionController::class, 'CollectionBalance']);
    Route::post('/collection/withdraw/{id}', [CollectionController::class, 'WithdrawBalance']);

    //! Contributions routes
    Route::get('/my-contributions', [ContributionController::class, 'MyContribution']);
    Route::get('/contribution-details/{id}', [ContributionController::class, 'ContributionDetails']);
    Route::get('/search-contributions', [ContributionController::class, 'SearchContributions']);

    //! Notification Routes
    Route::get('/notifications', [NotificationController::class, 'getNotifications']);
    Route::post('/notifications/read', [NotificationController::class, 'markAsRead']);

    //! Firebase Token Routes
    Route::post("/firebase/token/add", [FirebaseTokenController::class, "StoreFirebaseToken"]);
    Route::post("/firebase/token/get", [FirebaseTokenController::class, "GetFirebaseToken"]);
    // Route::post("/firebase/token/delete", [FirebaseTokenController::class, "DeleteFirebaseToken"]);
});

Route::post("/firebase/token/delete", [FirebaseTokenController::class, "DeleteFirebaseToken"]);
Route::post('/payment/store', [PaymentController::class, 'Store']);

//! Stripe Credientials routes
Route::get('/stripe-keys', [StripeController::class, 'GetStripeKeys']);
