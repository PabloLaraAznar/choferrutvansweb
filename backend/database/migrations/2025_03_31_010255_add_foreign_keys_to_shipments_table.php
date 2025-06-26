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
        Schema::table('shipments', function (Blueprint $table) {
            $table->foreign(['route_id'], 'fk_shipments_routes')->references(['id'])->on('routes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['route_unit_id'], 'fk_shipments_route_units')->references(['id'])->on('route_unit')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['schedule_id'], 'fk_shipments_schedules')->references(['id'])->on('schedules')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropForeign('fk_shipments_routes');
            $table->dropForeign('fk_shipments_route_units');
            $table->dropForeign('fk_shipments_schedules');
        });
    }
};
