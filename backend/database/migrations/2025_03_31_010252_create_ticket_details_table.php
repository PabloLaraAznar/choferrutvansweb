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
        Schema::create('ticket_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('seat_number');
            $table->decimal('seat_price', 10);
            $table->unsignedBigInteger('ticket_id')->index('fk_ticket_details_tickets');
            $table->unsignedBigInteger('fare_type_id')->index('fk_ticket_details_fare_types');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_details');
    }
};
