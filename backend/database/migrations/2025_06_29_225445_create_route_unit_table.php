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
        Schema::create('route_unit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_route');
            $table->unsignedBigInteger('id_driver_unit');
            $table->unsignedBigInteger('intermediate_location_id')->nullable();
            $table->decimal('price', 10, 2);
            $table->timestamps();
            
            $table->foreign('id_route')->references('id')->on('routes')->onDelete('cascade');
            $table->foreign('id_driver_unit')->references('id')->on('driver_unit')->onDelete('cascade');
            $table->foreign('intermediate_location_id')->references('id')->on('localities')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_unit');
    }
};
