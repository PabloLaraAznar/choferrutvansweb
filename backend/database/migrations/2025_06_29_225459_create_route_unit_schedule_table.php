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
        Schema::create('route_unit_schedule', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_route_unit');
            $table->date('schedule_date');
            $table->time('schedule_time');
            $table->string('status', 50)->default('Activo');
            $table->timestamps();
            
            $table->foreign('id_route_unit')->references('id')->on('route_unit')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_unit_schedule');
    }
};
