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
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('passenger_name');
            $table->decimal('total', 10);
            $table->unsignedBigInteger('route_unit_id')->index('fk_tickets_route_units');
            $table->unsignedBigInteger('schedule_id')->index('fk_tickets_schedules');
            $table->unsignedBigInteger('route_id')->index('fk_tickets_routes');
            $table->unsignedBigInteger('intermediate_destination_id')->index('fk_tickets_intermediate_destinations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
