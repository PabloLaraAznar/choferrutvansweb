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
            // Agregar company_id como clave foránea
            $table->unsignedBigInteger('company_id')->nullable()->after('id');
            
            // Agregar campos adicionales para identificar mejor las rutas
            $table->string('route_name')->nullable()->after('name'); // Ej: "Maxcanú-Mérida"
            $table->string('origin_locality')->nullable()->after('route_name'); // Localidad origen
            $table->string('destination_locality')->nullable()->after('origin_locality'); // Localidad destino
            
            // Índices
            $table->index('company_id');
            
            // Relación con companies
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            // Eliminar la clave foránea y los campos agregados
            $table->dropForeign(['company_id']);
            $table->dropColumn(['company_id', 'route_name', 'origin_locality', 'destination_locality']);
        });
    }
};
