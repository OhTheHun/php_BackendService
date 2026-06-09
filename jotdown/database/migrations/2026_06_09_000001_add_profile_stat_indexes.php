<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->index(['user_id', 'DeleteFlag'], 'notes_user_id_deleteflag_index');
        });

        Schema::table('workspaces', function (Blueprint $table) {
            $table->index(['user_id', 'DeleteFlag'], 'workspaces_user_id_deleteflag_index');
        });

        Schema::table('labels', function (Blueprint $table) {
            $table->index(['user_id', 'DeleteFlag'], 'labels_user_id_deleteflag_index');
        });

        Schema::table('note_shares', function (Blueprint $table) {
            $table->index(['shared_by_user_id', 'DeleteFlag', 'revoked_at'], 'note_shares_shared_by_deleteflag_revoked_index');
        });
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropIndex('notes_user_id_deleteflag_index');
        });

        Schema::table('workspaces', function (Blueprint $table) {
            $table->dropIndex('workspaces_user_id_deleteflag_index');
        });

        Schema::table('labels', function (Blueprint $table) {
            $table->dropIndex('labels_user_id_deleteflag_index');
        });

        Schema::table('note_shares', function (Blueprint $table) {
            $table->dropIndex('note_shares_shared_by_deleteflag_revoked_index');
        });
    }
};
