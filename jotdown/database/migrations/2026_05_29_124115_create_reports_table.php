<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid('Id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('CreatedBy');
            $table->timestamp('CreatedTime')->useCurrent();
            $table->string('UpdatedBy')->nullable();
            $table->timestamp('UpdatedTime')->nullable()->useCurrent();
            $table->boolean('DeleteFlag')->default(false);
            $table->uuid('note_id');
            $table->uuid('reporter_id');
            $table->text('reason');
            $table->string('status', 50)->default('pending');
            $table->uuid('admin_id')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
