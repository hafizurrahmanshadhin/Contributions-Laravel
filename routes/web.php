<?php

use App\Http\Controllers\Api\Auth\SocialLoginController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\Web\Frontend\DonateCollectionController;
use App\Http\Controllers\Web\Frontend\DynamicPageController;
use App\Http\Controllers\Web\Frontend\StripePaymentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('home');

Auth::routes();

//! Reset Database & Optimize
// Route::get('/reset', [ResetController::class, 'RunMigrations'])->name('reset');

//! Social Login
Route::get('/auth/{provider}', [SocialLoginController::class, 'RedirectToProvider']);
Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'HandleProviderCallback']);

//! Membsrship
Route::controller(StripePaymentController::class)->group(function () {
    Route::post('/checkout', 'confirmed')->name('checkout');
    Route::get('/success-payment', 'success')->name('membership.payment.success');
    Route::get('/cancled-payment', 'cancel')->name('membership.payment.cancled');
});

Route::get('/collection/donate/{collection_id}', [DonateCollectionController::class, 'donate'])->name('collection.donate');

Route::get('/{page_title}', [DynamicPageController::class, 'showDynamicPage'])->name('dynamic_page.show');
