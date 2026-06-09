<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('note_attachments', function (Blueprint $table) {
            $table->index(['note_id', 'DeleteFlag'], 'note_attachments_note_delete_idx');
        });

        Schema::table('labels', function (Blueprint $table) {
            $table->index(['user_id', 'DeleteFlag'], 'labels_user_delete_idx');
        });
    }

    public function down(): void
    {
        Schema::table('labels', function (Blueprint $table) {
            $table->dropIndex('labels_user_delete_idx');
        });

        Schema::table('note_attachments', function (Blueprint $table) {
            $table->dropIndex('note_attachments_note_delete_idx');
        });
    }
};
