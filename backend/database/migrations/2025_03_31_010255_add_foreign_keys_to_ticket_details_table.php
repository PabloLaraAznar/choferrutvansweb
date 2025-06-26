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
        Schema::table('ticket_details', function (Blueprint $table) {
            $table->foreign(['fare_type_id'], 'fk_ticket_details_fare_types')->references(['id'])->on('fare_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['ticket_id'], 'fk_ticket_details_tickets')->references(['id'])->on('tickets')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_details', function (Blueprint $table) {
            $table->dropForeign('fk_ticket_details_fare_types');
            $table->dropForeign('fk_ticket_details_tickets');
        });
    }
};
