<?php

use App\Http\Controllers\Web\Backend\CollectionController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\UserController;
use App\Http\Controllers\Web\Backend\WithdrawRequestController;
use Illuminate\Support\Facades\Route;

//! Route for Dashboard
Route::get("/dashboard", [DashboardController::class, "index"])->name("dashboard");

//! Route for Collections
Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
Route::get('/collections/status/{id}', [CollectionController::class, 'status'])->name('collections.status');
Route::get('/collections/view/{id}', [CollectionController::class, 'view'])->name('collections.view');

//! Route for Users
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users/status/{id}', [UserController::class, 'status'])->name('users.status');

//! Route for Withdraw Request
Route::get('/withdraw-request', [WithdrawRequestController::class, 'index'])->name('withdraw.request.index');
Route::post('/withdraw-request/{id}', [WithdrawRequestController::class, 'status'])->name('withdraw.request.status');
