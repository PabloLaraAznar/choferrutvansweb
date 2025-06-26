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
        Schema::table('intermediate_destinations', function (Blueprint $table) {
            $table->foreign(['route_id'], 'fk_intermediate_destinations_routes')->references(['id'])->on('routes')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('intermediate_destinations', function (Blueprint $table) {
            $table->dropForeign('fk_intermediate_destinations_routes');
        });
    }
};
