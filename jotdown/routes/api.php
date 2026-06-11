<?php

use App\Controller\Api\AuthController;
use App\Controller\Api\FolderController;
use App\Controller\Api\LabelController;
use App\Controller\Api\NoteController;
use App\Controller\Api\NoteShareController;
use App\Controller\Api\PlanController;
use App\Controller\Api\UserController;
use App\Controller\Api\WorkspaceController;
use App\Controller\Api\Admin\DashboardController;
use App\Controller\Api\Admin\UserController as AdminUserController;
use App\Controller\Api\Admin\ReportController;
use App\Controller\Api\Admin\PlanController;
use App\Controller\Api\Admin\PaymentController;
use App\Controller\Api\Admin\ActivityLogController;
use App\Http\Controllers\Api\AIAssistantController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(\App\Http\Middleware\JwtAuthenticate::class);

Route::post('/ai/summarize', [AIAssistantController::class, 'summarize']);
Route::post('/ai/fix-grammar', [AIAssistantController::class, 'fixGrammar']);
Route::post('/ai/test-moderation', [AIAssistantController::class, 'testModeration']);

Route::get('/plans', [PlanController::class, 'index']);

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/password/forgot', 'sendResetPasswordOtp');
    Route::post('/password/verify-otp', 'verifyResetPasswordOtp');
    Route::post('/password/reset', 'resetPassword');
});

Route::prefix('notes')->controller(NoteShareController::class)->group(function () {
    Route::get('/shared', 'getSharedNotes');
    Route::post('/{id}/shares', 'createShare');
});

Route::prefix('notes')->controller(NoteController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
    Route::patch('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
    Route::patch('/{id}/pin', 'updatePin');
    Route::patch('/{id}/favorite', 'updateFavorite');
    Route::patch('/{id}/archive', 'updateArchive');
    Route::patch('/{id}/protection', 'updateProtection');
    Route::patch('/{id}/share', 'updateShare');
});

Route::prefix('folders')->controller(FolderController::class)->group(function () {
    Route::get('/', 'index');
    Route::patch('/{folderId}', 'update');
    Route::delete('/{folderId}', 'destroy');
});

Route::prefix('workspaces')->controller(WorkspaceController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::patch('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

Route::prefix('workspaces/{workspaceId}/folders')->controller(FolderController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
});

Route::prefix('labels')->controller(LabelController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::patch('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::get('/{id}/profile', 'getProfile');
    Route::get('/{id}/avatar/signature', 'getAvatarUploadSignature');
    Route::patch('/{id}/avatar', 'updateAvatar');
    Route::patch('/{id}/display-name', 'updateDisplayName');
    Route::patch('/{id}/settings/appearance', 'updateAppearance');
    Route::patch('/{id}/password', 'changePassword');
});

Route::prefix('admin')->middleware([\App\Http\Middleware\JwtAuthenticate::class, EnsureAdmin::class])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'stats']);

    Route::prefix('users')->controller(AdminUserController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/{id}/lock', 'lock');
    });

    Route::prefix('reports')->controller(ReportController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/{id}/action', 'action');
    });

    Route::prefix('plans')->controller(PlanController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::patch('/{id}', 'update');
        Route::post('/{id}/toggle-status', 'toggleStatus');
    });

    Route::prefix('payments')->controller(PaymentController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/{id}/confirm', 'confirm');
    });

    Route::prefix('activity-logs')->controller(ActivityLogController::class)->group(function () {
        Route::get('/', 'index');
    });
});
