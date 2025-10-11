<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('avgsensors', function (Blueprint $table) {
            $table->id();
            $table->decimal('ph', 10, 2)->nullable();
            $table->decimal('temp_c', 10, 2)->nullable();
            $table->decimal('kekeruhan', 10, 2)->nullable();
            $table->decimal('kualitas', 10, 2)->nullable();
            $table->timestamp('last_ts')->nullable();
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avgsensors');
    }
};
