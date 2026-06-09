<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->index(['user_id', 'DeleteFlag', 'archived_at'], 'notes_user_delete_archived_index');
            $table->index(['user_id', 'DeleteFlag', 'is_pinned', 'pinned_at'], 'notes_user_delete_pinned_index');
            $table->index(['user_id', 'DeleteFlag', 'is_favorite'], 'notes_user_delete_favorite_index');
            $table->index(['workspace_id', 'DeleteFlag'], 'notes_workspace_delete_index');
            $table->index(['folder_id', 'DeleteFlag'], 'notes_folder_delete_index');
        });

        Schema::table('note_labels', function (Blueprint $table) {
            $table->index('label_id', 'note_labels_label_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('note_labels', function (Blueprint $table) {
            $table->dropIndex('note_labels_label_id_index');
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->dropIndex('notes_user_delete_archived_index');
            $table->dropIndex('notes_user_delete_pinned_index');
            $table->dropIndex('notes_user_delete_favorite_index');
            $table->dropIndex('notes_workspace_delete_index');
            $table->dropIndex('notes_folder_delete_index');
        });
    }
};
