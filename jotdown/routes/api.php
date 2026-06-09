<?php

use App\Controller\Api\AuthController;
use App\Controller\Api\NoteShareController;
use App\Controller\Api\UserController;
use App\Http\Controllers\Api\AIAssistantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/ai/summarize', [AIAssistantController::class, 'summarize']);
Route::post('/ai/fix-grammar', [AIAssistantController::class, 'fixGrammar']);
Route::post('/ai/test-moderation', [AIAssistantController::class, 'testModeration']);

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/password/forgot', 'sendResetPasswordOtp');
    Route::post('/password/verify-otp', 'verifyResetPasswordOtp');
});

Route::prefix('notes')->controller(NoteShareController::class)->group(function () {
    Route::get('/shared', 'getSharedNotes');
});

Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::get('/{id}/profile', 'getProfile');
    Route::get('/{id}/avatar/signature', 'getAvatarUploadSignature');
    Route::patch('/{id}/avatar', 'updateAvatar');
    Route::patch('/{id}/display-name', 'updateDisplayName');
    Route::patch('/{id}/settings/appearance', 'updateAppearance');
    Route::patch('/{id}/password', 'changePassword');
});
