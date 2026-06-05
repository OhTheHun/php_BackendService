<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('Id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('CreatedBy');
            $table->timestamp('CreatedTime')->useCurrent();
            $table->string('UpdatedBy')->nullable();
            $table->timestamp('UpdatedTime')->nullable()->useCurrent();
            $table->boolean('DeleteFlag')->default(false);
            $table->string('display_name');
            $table->string('email');
            $table->string('password');
            $table->string('role', 50)->default('user');
            $table->string('status', 50)->default('active');
            $table->string('avatar_url', 500)->nullable();
            $table->string('theme', 20)->nullable()->default('light');
            $table->string('font_size', 20)->nullable()->default('medium');
            $table->string('default_note_color', 7)->nullable()->default('#ffffff');
            $table->uuid('plan_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
