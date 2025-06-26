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
        Schema::create('sale_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('passenger_name');
            $table->decimal('amount', 10);
            $table->decimal('discount', 10);
            $table->decimal('total_tax', 10);
            $table->unsignedBigInteger('sale_id')->index('fk_sale_details_sales');
            $table->unsignedBigInteger('ticket_id')->index('fk_sale_details_tickets');
            $table->unsignedBigInteger('service_type_id');
            $table->unsignedBigInteger('shipment_id')->index('fk_sale_details_shipments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
};
