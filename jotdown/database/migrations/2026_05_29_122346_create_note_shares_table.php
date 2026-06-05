<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('note_shares', function (Blueprint $table) {
            $table->uuid('Id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('CreatedBy');
            $table->timestamp('CreatedTime')->useCurrent();
            $table->string('UpdatedBy')->nullable();
            $table->timestamp('UpdatedTime')->nullable()->useCurrent();
            $table->boolean('DeleteFlag')->default(false);
            $table->uuid('note_id');
            $table->uuid('shared_by_user_id');
            $table->string('shared_with_email');
            $table->string('permission', 50)->default('view');
            $table->string('share_token')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('note_shares');
    }
};
