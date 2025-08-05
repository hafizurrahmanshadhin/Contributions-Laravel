<?php

use App\Http\Controllers\Web\Backend\Settings\DynamicPageController;
use App\Http\Controllers\Web\Backend\Settings\FacebookSettingsController;
use App\Http\Controllers\Web\Backend\Settings\GoogleSettingsController;
use App\Http\Controllers\Web\Backend\Settings\MailSettingController;
use App\Http\Controllers\Web\Backend\Settings\ProfileController;
use App\Http\Controllers\Web\Backend\Settings\StripeSettingController;
use App\Http\Controllers\Web\Backend\Settings\SystemSettingController;
use Illuminate\Support\Facades\Route;

//! Route for Profile
Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
Route::post('/update-profile-picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.picture.update');
Route::post('/update-profile', [ProfileController::class, 'UpdateProfile'])->name('profile.update');
Route::post('/update-password', [ProfileController::class, 'UpdatePassword'])->name('profile.password.update');

Route::get('/system-setting', [SystemSettingController::class, 'index'])->name('system.index');
Route::post('/system-setting', [SystemSettingController::class, 'update'])->name('system.update');

//! Route for MailSetting
Route::get('/mail-setting', [MailSettingController::class, 'index'])->name('mail.index');
Route::post('/mail-setting', [MailSettingController::class, 'update'])->name('mail.update');

//! Route for DynamicpageController
Route::controller(DynamicPageController::class)->group(function () {
    Route::get('/dynamic-page', 'index')->name('dynamic_page.index');
    Route::get('/dynamic-page/create', 'create')->name('dynamic_page.create');
    Route::post('/dynamic-page/store', 'store')->name('dynamic_page.store');
    Route::get('/dynamic-page/edit/{id}', 'edit')->name('dynamic_page.edit');
    Route::post('/dynamic-page/update/{id}', 'update')->name('dynamic_page.update');
    Route::get('/dynamic-page/status/{id}', 'status')->name('dynamic_page.status');
    Route::delete('/dynamic-page/destroy/{id}', 'destroy')->name('dynamic_page.destroy');
});

//! Route for StripeSetting
Route::get('/stripe-setting', [StripeSettingController::class, 'index'])->name('stripe.index');
Route::post('/stripe-setting', [StripeSettingController::class, 'update'])->name('stripe.update');

//! Route for GoogleSeetings
// Route::get('/google-setting', [GoogleSettingsController::class, 'index'])->name('google.index');
// Route::post('/google-setting', [GoogleSettingsController::class, 'update'])->name('google.update');

//! Route for FacebookSettings
// Route::get('/facebook-setting', [FacebookSettingsController::class, 'index'])->name('facebook.index');
// Route::post('/facebook-setting', [FacebookSettingsController::class, 'update'])->name('facebook.update');
