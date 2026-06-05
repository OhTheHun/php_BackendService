<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->uuid('Id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('CreatedBy');
            $table->timestamp('CreatedTime')->useCurrent();
            $table->string('UpdatedBy')->nullable();
            $table->timestamp('UpdatedTime')->nullable()->useCurrent();
            $table->boolean('DeleteFlag')->default(false);
            $table->uuid('user_id');
            $table->uuid('workspace_id')->nullable();
            $table->uuid('folder_id')->nullable();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('color', 7)->nullable()->default('#ffffff');
            $table->boolean('is_pinned')->default(false);
            $table->timestamp('pinned_at')->nullable();
            $table->boolean('is_favorite')->default(false);
            $table->string('visibility', 50)->default('private');
            $table->boolean('is_protected')->default(false);
            $table->string('note_password')->nullable();
            $table->timestamp('archived_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
