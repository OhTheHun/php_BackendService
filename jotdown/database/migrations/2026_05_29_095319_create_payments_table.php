<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('Id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('CreatedBy');
            $table->timestamp('CreatedTime')->useCurrent();
            $table->string('UpdatedBy')->nullable();
            $table->timestamp('UpdatedTime')->nullable()->useCurrent();
            $table->boolean('DeleteFlag')->default(false);
            $table->uuid('user_id');
            $table->uuid('plan_id');
            $table->decimal('amount', 10, 2);
            $table->string('status', 50)->default('pending');
            $table->string('payment_method', 50)->nullable();
            $table->string('transaction_code', 100)->nullable();
            $table->timestamp('paid_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
