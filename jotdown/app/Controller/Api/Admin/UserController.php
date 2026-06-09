<?php

namespace App\Controller\Api\Admin;

use App\Controller\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = $request->query('q');
        $perPage = min(max(intval($request->query('per_page', 15)), 1), 50);

        $query = User::with('plan')->select(['Id','display_name','email','role','status','plan_id','CreatedTime']);

        if ($q) {
            $query->where(function ($qb) use ($q) {
                $qb->where('display_name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $users = $query->orderBy('CreatedTime', 'desc')->paginate($perPage);

        return response()->json($users);
    }

    public function show(string $id): JsonResponse
    {
        $user = User::with(['plan','payments'])->findOrFail($id);

        // Ensure private fields are not exposed
        $data = $user->toArray();
        unset($data['password']);

        return response()->json($data);
    }

    public function lock(Request $request, string $id): JsonResponse
    {
        $action = $request->input('action', 'lock'); // lock|unlock

        $user = User::findOrFail($id);

        $admin = $request->user();

        if ($action === 'lock') {
            $user->status = 'locked';
            $user->save();

            ActivityLog::create([
                'user_id' => $admin->Id,
                'action' => 'admin_lock_user',
                'target_type' => 'user',
                'target_id' => $user->Id,
                'description' => "Locked user {$user->email} by admin {$admin->email}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json(['message' => 'User locked.']);
        }

        if ($action === 'unlock') {
            $user->status = 'active';
            $user->save();

            ActivityLog::create([
                'user_id' => $admin->Id,
                'action' => 'admin_unlock_user',
                'target_type' => 'user',
                'target_id' => $user->Id,
                'description' => "Unlocked user {$user->email} by admin {$admin->email}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json(['message' => 'User unlocked.']);
        }

        return response()->json(['message' => 'Invalid action.'], 400);
    }
}
