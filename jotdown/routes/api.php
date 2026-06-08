<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AIAssistantController;

// Route mặc định của Laravel (để test xem API có hoạt động không)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route AI của bạn
Route::post('/ai/summarize', [AIAssistantController::class, 'summarize']);
Route::post('/ai/fix-grammar', [AIAssistantController::class, 'fixGrammar']);
Route::post('/ai/test-moderation', [AIAssistantController::class, 'testModeration']);
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
