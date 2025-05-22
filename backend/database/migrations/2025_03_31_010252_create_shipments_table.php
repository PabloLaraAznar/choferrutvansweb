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
        Schema::create('shipments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sender_name');
            $table->string('receiver_name');
            $table->decimal('total', 10);
            $table->string('photo');
            $table->text('description');
            $table->unsignedBigInteger('route_unit_id')->index('fk_shipments_route_units');
            $table->unsignedBigInteger('schedule_id')->index('fk_shipments_schedules');
            $table->unsignedBigInteger('route_id')->index('fk_shipments_routes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
