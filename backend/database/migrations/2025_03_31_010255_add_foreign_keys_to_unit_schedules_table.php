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
        Schema::table('unit_schedules', function (Blueprint $table) {
            $table->foreign(['id_Units'], 'unit_schedules_ibfk_1')->references(['id'])->on('units')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['id_Schedules'], 'unit_schedules_ibfk_2')->references(['id'])->on('schedules')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unit_schedules', function (Blueprint $table) {
            $table->dropForeign('unit_schedules_ibfk_1');
            $table->dropForeign('unit_schedules_ibfk_2');
        });
    }
};
