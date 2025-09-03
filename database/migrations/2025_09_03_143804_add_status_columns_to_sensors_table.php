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
        Schema::table('sensors', function (Blueprint $table) {
            $table->string('ph_status', 10)->nullable()->after('ph');
            $table->string('suhu_status', 10)->nullable()->after('suhu');
            $table->string('kekeruhan_status', 10)->nullable()->after('kekeruhan');
            $table->string('overall_status', 10)->nullable()->after('kualitas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sensors', function (Blueprint $table) {
            $table->dropColumn(['ph_status', 'suhu_status', 'kekeruhan_status', 'overall_status']);
        });
    }
};
