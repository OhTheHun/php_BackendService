<?php

namespace App\Controller\Api\Admin;

use App\Controller\Controller;
use App\Models\User;
use App\Models\Note;
use App\Models\Workspace;
use App\Models\Payment;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $stats = Cache::remember('admin.dashboard.stats', now()->addSeconds(30), function () {
            $userStats = User::query()
                ->leftJoin('plans', 'users.plan_id', '=', 'plans.Id')
                ->selectRaw('COUNT(*) as total_users')
                ->selectRaw('SUM(CASE WHEN plans.price > 0 THEN 1 ELSE 0 END) as premium_users')
                ->first();

            $paymentStats = Payment::query()
                ->selectRaw('COUNT(*) as payments')
                ->selectRaw("COALESCE(SUM(CASE WHEN status = 'confirmed' THEN amount ELSE 0 END), 0) as revenue")
                ->first();

            $totalUsers = (int) ($userStats->total_users ?? 0);
            $premiumUsers = (int) ($userStats->premium_users ?? 0);

            return [
                'total_users' => $totalUsers,
                'premium_users' => $premiumUsers,
                'free_users' => $totalUsers - $premiumUsers,
                'notes' => Note::count(),
                'workspaces' => Workspace::count(),
                'payments' => (int) ($paymentStats->payments ?? 0),
                'revenue' => (float) ($paymentStats->revenue ?? 0),
                'unresolved_reports' => Report::where('status', 'pending')->count(),
            ];
        });

        return response()->json($stats);
    }
}
