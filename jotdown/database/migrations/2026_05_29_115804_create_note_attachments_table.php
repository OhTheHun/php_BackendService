<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('note_attachments', function (Blueprint $table) {
            $table->uuid('Id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('CreatedBy');
            $table->timestamp('CreatedTime')->useCurrent();
            $table->string('UpdatedBy')->nullable();
            $table->timestamp('UpdatedTime')->nullable()->useCurrent();
            $table->boolean('DeleteFlag')->default(false);
            $table->uuid('note_id');
            $table->string('file_name');
            $table->string('file_path', 500);
            $table->integer('file_size');
            $table->string('file_type', 100);
            $table->string('attachment_kind', 50)->nullable()->default('file');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('note_attachments');
    }
};
