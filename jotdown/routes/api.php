<?php

use App\Controller\Api\AuthController;
use App\Controller\Api\NoteShareController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\JwtTokenService;


Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/password/forgot', 'sendResetPasswordOtp');
    Route::post('/password/verify-otp', 'verifyResetPasswordOtp');
});

Route::prefix('notes')->controller(NoteShareController::class)->group(function () {
    Route::get('/shared', 'getSharedNotes');
});

// Temporary debug route to generate an admin JWT for local testing.
Route::get('/debug/admin-token', function (Request $request) {
    $secret = $request->query('secret');
    if ($secret !== env('ADMIN_TOKEN_SECRET', 'dev')) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $user = User::where('email', env('ADMIN_SEED_EMAIL', 'admin@example.com'))->first();
    if (!$user) {
        return response()->json(['message' => 'Admin user not found'], 404);
    }

    $token = app(JwtTokenService::class)->generate($user);
    return response()->json(['token' => $token]);
});

use App\Http\Middleware\JwtAuthenticate;
use App\Http\Middleware\EnsureAdmin;
use App\Controller\Api\Admin\UserController;
use App\Controller\Api\Admin\PlanController;
use App\Controller\Api\Admin\PaymentController;
use App\Controller\Api\Admin\ReportController;
use App\Controller\Api\Admin\DashboardController;
use App\Controller\Api\Admin\ActivityLogController;

Route::prefix('admin')
    ->middleware([JwtAuthenticate::class, EnsureAdmin::class])
    ->group(function () {
        // Users
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::post('/users/{id}/lock', [UserController::class, 'lock']);

        // Plans
        Route::get('/plans', [PlanController::class, 'index']);
        Route::post('/plans', [PlanController::class, 'store']);
        Route::get('/plans/{id}', [PlanController::class, 'show']);
        Route::put('/plans/{id}', [PlanController::class, 'update']);
        Route::patch('/plans/{id}/toggle', [PlanController::class, 'toggleStatus']);

        // Payments
        Route::get('/payments', [PaymentController::class, 'index']);
        Route::get('/payments/{id}', [PaymentController::class, 'show']);
        Route::post('/payments/{id}/confirm', [PaymentController::class, 'confirm']);

        // Reports / Moderation
        Route::get('/reports', [ReportController::class, 'index']);
        Route::get('/reports/{id}', [ReportController::class, 'show']);
        Route::post('/reports/{id}/action', [ReportController::class, 'action']);

        // Dashboard / Stats
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

        // Activity logs
        Route::get('/activity-logs', [ActivityLogController::class, 'index']);
    });
