<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create game_results keyed by player_id so history survives link regeneration.
     */
    public function up(): void
    {
        Schema::create('game_results', function (Blueprint $table) {
            $table->id();
            // Intentionally player_id, not access_link_id
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('number');
            $table->boolean('is_win');
            // Store payout precisely (percent of number may be fractional)
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Drop game_results table.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_results');
    }
};
