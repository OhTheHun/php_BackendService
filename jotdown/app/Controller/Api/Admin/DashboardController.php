<?php

namespace App\Controller\Api\Admin;

use App\Controller\Controller;
use App\Models\User;
use App\Models\Note;
use App\Models\Workspace;
use App\Models\Payment;
use App\Models\Report;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $totalUsers = User::count();
        $premiumUsers = User::whereHas('plan', function ($q) {
            $q->where('price', '>', 0);
        })->count();
        $freeUsers = $totalUsers - $premiumUsers;

        $notes = Note::count();
        $workspaces = Workspace::count();
        $payments = Payment::count();
        $revenue = Payment::where('status', 'confirmed')->sum('amount');
        $unresolvedReports = Report::where('status', 'pending')->count();

        return response()->json([
            'total_users' => $totalUsers,
            'premium_users' => $premiumUsers,
            'free_users' => $freeUsers,
            'notes' => $notes,
            'workspaces' => $workspaces,
            'payments' => $payments,
            'revenue' => (float) $revenue,
            'unresolved_reports' => $unresolvedReports,
        ]);
    }
}
