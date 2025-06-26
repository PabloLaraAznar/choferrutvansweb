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
        Schema::create('localities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('longitude'); // Longitud geográfica
            $table->string('latitude');  // Latitud geográfica
            $table->string('locality');  // Sustituye a 'nombre'
            $table->string('street');    // Calle
            $table->string('postal_code'); // Código postal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('localities');
    }
};
