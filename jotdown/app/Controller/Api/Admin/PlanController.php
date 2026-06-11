<?php

namespace App\Controller\Api\Admin;

use App\Controller\Controller;
use App\Models\Plan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class PlanController extends Controller
{
    public function index(): JsonResponse
    {
        $plans = Plan::query()
            ->select(['Id','name','price','max_notes','max_workspaces','max_attachment_size','can_export','status','CreatedTime','UpdatedTime'])
            ->orderBy('CreatedTime', 'desc')
            ->get();

        return response()->json($plans);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'max_notes' => 'nullable|integer|min:0',
            'max_workspaces' => 'nullable|integer|min:0',
            'max_attachment_size' => 'nullable|integer|min:0',
            'can_export' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ]);

        $admin = $request->user();

        $plan = new Plan();
        $plan->CreatedBy = $admin->email ?? 'system';
        $plan->UpdatedBy = $admin->email ?? 'system';
        $plan->name = $validated['name'];
        $plan->price = $validated['price'];
        $plan->max_notes = $validated['max_notes'] ?? null;
        $plan->max_workspaces = $validated['max_workspaces'] ?? null;
        $plan->max_attachment_size = $validated['max_attachment_size'] ?? null;
        $plan->can_export = $validated['can_export'] ?? false;
        $plan->status = $validated['status'] ?? true;
        $plan->save();

        ActivityLog::create([
            'user_id' => $admin->Id,
            'action' => 'admin_create_plan',
            'target_type' => 'plan',
            'target_id' => $plan->Id,
            'description' => "Created plan {$plan->name} by admin {$admin->email}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Cache::forget('admin.dashboard.stats');

        return response()->json($plan, 201);
    }

    public function show(string $id): JsonResponse
    {
        $plan = Plan::findOrFail($id);

        return response()->json($plan);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $plan = Plan::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'max_notes' => 'nullable|integer|min:0',
            'max_workspaces' => 'nullable|integer|min:0',
            'max_attachment_size' => 'nullable|integer|min:0',
            'can_export' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ]);

        $plan->fill($validated);
        $plan->save();

        $admin = $request->user();
        ActivityLog::create([
            'user_id' => $admin->Id,
            'action' => 'admin_update_plan',
            'target_type' => 'plan',
            'target_id' => $plan->Id,
            'description' => "Updated plan {$plan->name} by admin {$admin->email}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Cache::forget('admin.dashboard.stats');

        return response()->json($plan);
    }

    public function toggleStatus(Request $request, string $id): JsonResponse
    {
        $plan = Plan::findOrFail($id);
        $plan->status = ! (bool) $plan->status;
        $plan->save();

        $admin = $request->user();
        ActivityLog::create([
            'user_id' => $admin->Id,
            'action' => 'admin_toggle_plan',
            'target_type' => 'plan',
            'target_id' => $plan->Id,
            'description' => "Toggled plan {$plan->name} status to " . ($plan->status ? 'enabled' : 'disabled'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Cache::forget('admin.dashboard.stats');

        return response()->json($plan);
    }
}
