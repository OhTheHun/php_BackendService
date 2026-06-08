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