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
        Schema::table('drivers', function (Blueprint $table) {
            $table->foreign(['id_RouteUnit'], 'drivers_ibfk_1')->references(['id'])->on('route_unit')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['id_Schedules'], 'drivers_ibfk_2')->references(['id'])->on('schedules')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropForeign('drivers_ibfk_1');
            $table->dropForeign('drivers_ibfk_2');
        });
    }
};
