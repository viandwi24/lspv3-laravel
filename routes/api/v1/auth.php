<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth',
    'as' => 'auth.'
], function () {
    // 
    Route::post('/login', [ AuthController::class, 'login' ])->name('login');
    Route::post('/logout', [ AuthController::class, 'logout' ])->name('logout');
    Route::post('/register', [ AuthController::class, 'register' ])->name('register');
    Route::post('/forgot-password', [ AuthController::class, 'forgot_password' ])->name('forgot_password');
    Route::get('/user', [ AuthController::class, 'user' ])->name('user');

    // update profile
    Route::put('/user', [ AuthController::class, 'profile_update' ])->name('user');
    Route::put('/change-password', [ AuthController::class, 'profile_change_password' ])->name('change_password');
});
