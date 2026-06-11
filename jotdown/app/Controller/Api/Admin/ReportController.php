<?php

namespace App\Controller\Api\Admin;

use App\Controller\Controller;
use App\Models\Report;
use App\Models\Note;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class ReportController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $status = $request->query('status'); // pending, resolved, rejected

        $query = Report::with([
            'note:Id,title,user_id,visibility,DeleteFlag,CreatedTime',
            'reporter:Id,display_name,email,status',
            'admin:Id,display_name,email',
        ])->orderBy('CreatedTime', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        $reports = $query->paginate(20);

        return response()->json($reports);
    }

    public function show(string $id): JsonResponse
    {
        $report = Report::with([
            'note:Id,title,content,user_id,visibility,DeleteFlag,CreatedTime',
            'reporter:Id,display_name,email,status',
            'admin:Id,display_name,email',
        ])->findOrFail($id);

        return response()->json($report);
    }

    public function action(Request $request, string $id): JsonResponse
    {
        $action = $request->input('action');
        $adminNotes = $request->input('admin_notes');

        $report = Report::findOrFail($id);
        $admin = $request->user();

        if ($action === 'mark_processed') {
            $report->status = 'resolved';
            $report->admin_id = $admin->Id;
            $report->admin_notes = $adminNotes;
            $report->resolved_at = now();
            $report->save();

            ActivityLog::create([
                'user_id' => $admin->Id,
                'action' => 'admin_process_report',
                'target_type' => 'report',
                'target_id' => $report->Id,
                'description' => "Processed report {$report->Id}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            Cache::forget('admin.dashboard.stats');

            return response()->json(['message' => 'Report marked as processed.']);
        }

        if ($action === 'reject') {
            $report->status = 'rejected';
            $report->admin_id = $admin->Id;
            $report->admin_notes = $adminNotes;
            $report->resolved_at = now();
            $report->save();

            ActivityLog::create([
                'user_id' => $admin->Id,
                'action' => 'admin_reject_report',
                'target_type' => 'report',
                'target_id' => $report->Id,
                'description' => "Rejected report {$report->Id}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            Cache::forget('admin.dashboard.stats');

            return response()->json(['message' => 'Report rejected.']);
        }

        // Actions that affect note or user
        $note = Note::find($report->note_id);

        if ($action === 'hide_note' && $note) {
            $note->visibility = 'private';
            $note->save();

            $report->status = 'resolved';
            $report->admin_id = $admin->Id;
            $report->admin_notes = $adminNotes;
            $report->resolved_at = now();
            $report->save();

            ActivityLog::create([
                'user_id' => $admin->Id,
                'action' => 'admin_hide_note',
                'target_type' => 'note',
                'target_id' => $note->Id,
                'description' => "Hid shared note {$note->Id} due to report {$report->Id}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            Cache::forget('admin.dashboard.stats');

            return response()->json(['message' => 'Note hidden.']);
        }

        if ($action === 'soft_delete_note' && $note) {
            $note->DeleteFlag = true;
            $note->save();

            $report->status = 'resolved';
            $report->admin_id = $admin->Id;
            $report->admin_notes = $adminNotes;
            $report->resolved_at = now();
            $report->save();

            ActivityLog::create([
                'user_id' => $admin->Id,
                'action' => 'admin_soft_delete_note',
                'target_type' => 'note',
                'target_id' => $note->Id,
                'description' => "Soft deleted note {$note->Id} due to report {$report->Id}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            Cache::forget('admin.dashboard.stats');

            return response()->json(['message' => 'Note soft-deleted.']);
        }

        if ($action === 'restore_note' && $note) {
            $note->DeleteFlag = false;
            $note->save();

            ActivityLog::create([
                'user_id' => $admin->Id,
                'action' => 'admin_restore_note',
                'target_type' => 'note',
                'target_id' => $note->Id,
                'description' => "Restored note {$note->Id}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            Cache::forget('admin.dashboard.stats');

            return response()->json(['message' => 'Note restored.']);
        }

        if ($action === 'lock_user') {
            $offending = User::find($request->input('user_id') ?? $report->reporter_id);
            if ($offending) {
                $offending->status = 'locked';
                $offending->save();

                ActivityLog::create([
                    'user_id' => $admin->Id,
                    'action' => 'admin_lock_user_from_report',
                    'target_type' => 'user',
                    'target_id' => $offending->Id,
                    'description' => "Locked user {$offending->email} due to report {$report->Id}",
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                Cache::forget('admin.dashboard.stats');

                $report->status = 'resolved';
                $report->admin_id = $admin->Id;
                $report->admin_notes = $adminNotes;
                $report->resolved_at = now();
                $report->save();

                return response()->json(['message' => 'User locked.']);
            }

            return response()->json(['message' => 'User not found.'], 404);
        }

        return response()->json(['message' => 'Invalid action.'], 400);
    }
}
