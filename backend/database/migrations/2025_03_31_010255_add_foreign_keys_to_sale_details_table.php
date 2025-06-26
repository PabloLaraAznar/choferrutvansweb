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
        Schema::table('sale_details', function (Blueprint $table) {
            $table->foreign(['sale_id'], 'fk_sale_details_sales')->references(['id'])->on('sales')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['shipment_id'], 'fk_sale_details_shipments')->references(['id'])->on('shipments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['ticket_id'], 'fk_sale_details_tickets')->references(['id'])->on('tickets')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_details', function (Blueprint $table) {
            $table->dropForeign('fk_sale_details_sales');
            $table->dropForeign('fk_sale_details_shipments');
            $table->dropForeign('fk_sale_details_tickets');
        });
    }
};
