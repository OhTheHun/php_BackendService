<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('CreatedTime', 'users_createdtime_idx');
            $table->index(['plan_id', 'CreatedTime'], 'users_plan_createdtime_idx');
            $table->index(['status', 'CreatedTime'], 'users_status_createdtime_idx');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->index(['price', 'status'], 'plans_price_status_idx');
            $table->index('CreatedTime', 'plans_createdtime_idx');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index(['status', 'CreatedTime'], 'payments_status_createdtime_idx');
            $table->index(['user_id', 'CreatedTime'], 'payments_user_createdtime_idx');
            $table->index(['plan_id', 'CreatedTime'], 'payments_plan_createdtime_idx');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->index(['status', 'CreatedTime'], 'reports_status_createdtime_idx');
            $table->index(['reporter_id', 'CreatedTime'], 'reports_reporter_createdtime_idx');
            $table->index(['admin_id', 'CreatedTime'], 'reports_admin_createdtime_idx');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('created_at', 'activity_logs_created_at_idx');
            $table->index(['user_id', 'created_at'], 'activity_logs_user_created_at_idx');
            $table->index(['target_type', 'target_id'], 'activity_logs_target_idx');
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->index('CreatedTime', 'notes_createdtime_idx');
            $table->index(['visibility', 'DeleteFlag'], 'notes_visibility_deleteflag_idx');
        });

        Schema::table('workspaces', function (Blueprint $table) {
            $table->index('CreatedTime', 'workspaces_createdtime_idx');
        });
    }

    public function down(): void
    {
        Schema::table('workspaces', function (Blueprint $table) {
            $table->dropIndex('workspaces_createdtime_idx');
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->dropIndex('notes_createdtime_idx');
            $table->dropIndex('notes_visibility_deleteflag_idx');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('activity_logs_created_at_idx');
            $table->dropIndex('activity_logs_user_created_at_idx');
            $table->dropIndex('activity_logs_target_idx');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropIndex('reports_status_createdtime_idx');
            $table->dropIndex('reports_reporter_createdtime_idx');
            $table->dropIndex('reports_admin_createdtime_idx');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('payments_status_createdtime_idx');
            $table->dropIndex('payments_user_createdtime_idx');
            $table->dropIndex('payments_plan_createdtime_idx');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->dropIndex('plans_price_status_idx');
            $table->dropIndex('plans_createdtime_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_createdtime_idx');
            $table->dropIndex('users_plan_createdtime_idx');
            $table->dropIndex('users_status_createdtime_idx');
        });
    }
};
