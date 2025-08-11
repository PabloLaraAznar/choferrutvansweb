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
        Schema::table('sites', function (Blueprint $table) {
            // Eliminar campos que no necesitamos para sitios/terminales
            $table->dropColumn(['origin_locality', 'destination_locality']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            // Restaurar campos si se revierte la migración
            $table->string('origin_locality')->nullable()->after('route_name');
            $table->string('destination_locality')->nullable()->after('origin_locality');
        });
    }
};
