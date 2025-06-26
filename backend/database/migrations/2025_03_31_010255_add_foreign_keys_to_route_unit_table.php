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
        Schema::table('route_unit', function (Blueprint $table) {
            $table->foreign(['route_id'], 'fk_route_unit_routes')->references(['id'])->on('routes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['schedule_id'], 'fk_route_unit_schedules')->references(['id'])->on('schedules')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['unit_id'], 'fk_route_unit_units')->references(['id'])->on('units')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('route_unit', function (Blueprint $table) {
            $table->dropForeign('fk_route_unit_routes');
            $table->dropForeign('fk_route_unit_schedules');
            $table->dropForeign('fk_route_unit_units');
        });
    }
};
