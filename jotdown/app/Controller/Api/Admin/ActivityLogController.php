<?php

namespace App\Controller\Api\Admin;

use App\Controller\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ActivityLogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = $request->query('q');

        $query = ActivityLog::with('user:Id,display_name,email')
            ->orderBy('created_at', 'desc');

        if ($q) {
            $query->where('action', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%");
        }

        $logs = $query->paginate(50);

        return response()->json($logs);
    }
}
