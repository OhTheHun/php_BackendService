<?php

namespace App\Controller\Api\Admin;

use App\Controller\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = $request->query('q');
        $perPage = min(max(intval($request->query('per_page', 20)), 1), 50);

        $query = Payment::with([
            'user:Id,display_name,email,status',
            'plan:Id,name,price',
        ])->orderBy('CreatedTime', 'desc');

        if ($q) {
            $query->whereHas('user', function ($qb) use ($q) {
                $qb->where('display_name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $payments = $query->paginate($perPage);

        return response()->json($payments);
    }

    public function show(string $id): JsonResponse
    {
        $payment = Payment::with([
            'user:Id,display_name,email,status,plan_id',
            'plan:Id,name,price,max_notes,max_workspaces,max_attachment_size,can_export',
        ])->findOrFail($id);

        return response()->json($payment);
    }

    public function confirm(Request $request, string $id): JsonResponse
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status === 'confirmed') {
            return response()->json(['message' => 'Payment already confirmed.'], 400);
        }

        DB::transaction(function () use ($payment, $request) {
            $payment->status = 'confirmed';
            $payment->paid_at = now();
            $payment->save();

            // update user's plan
            $user = User::find($payment->user_id);
            if ($user) {
                $user->plan_id = $payment->plan_id;
                $user->save();
            }

            $admin = $request->user();
            ActivityLog::create([
                'user_id' => $admin->Id,
                'action' => 'admin_confirm_payment',
                'target_type' => 'payment',
                'target_id' => $payment->Id,
                'description' => "Confirmed payment {$payment->transaction_code} for user {$user?->email}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            Cache::forget('admin.dashboard.stats');
        });

        return response()->json(['message' => 'Payment confirmed.']);
    }
}
