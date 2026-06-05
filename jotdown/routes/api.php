<?php

use App\Controller\Api\AuthController;
use App\Controller\Api\NoteShareController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/password/forgot', 'sendResetPasswordOtp');
    Route::post('/password/verify-otp', 'verifyResetPasswordOtp');
});

Route::prefix('notes')->controller(NoteShareController::class)->group(function () {
    Route::get('/shared', 'getSharedNotes');
});
