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
            $table->id();
            $table->string('folio', 50);
            $table->unsignedBigInteger('id_route_unit');
            $table->unsignedBigInteger('id_service');
            $table->string('sender_name', 100);
            $table->string('receiver_name', 100);
            $table->text('package_description')->nullable();
            $table->string('package_image', 255)->nullable();
            $table->string('status', 50)->default('Pendiente');
            $table->decimal('amount', 10, 2);
            $table->timestamps();
            
            $table->foreign('id_route_unit')->references('id')->on('route_unit')->onDelete('cascade');
            $table->foreign('id_service')->references('id')->on('services')->onDelete('cascade');
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
