<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('note_labels', function (Blueprint $table) {
            $table->uuid('note_id');
            $table->uuid('label_id');

            $table->primary(['note_id', 'label_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('note_labels');
    }
};
