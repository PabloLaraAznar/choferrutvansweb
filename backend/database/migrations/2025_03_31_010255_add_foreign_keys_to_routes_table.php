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
        Schema::table('routes', function (Blueprint $table) {
            $table->foreign(['destination_locality_id'], 'fk_routes_destination_localities')->references(['id'])->on('localities')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['origin_locality_id'], 'fk_routes_origin_localities')->references(['id'])->on('localities')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->dropForeign('fk_routes_destination_localities');
            $table->dropForeign('fk_routes_origin_localities');
        });
    }
};
