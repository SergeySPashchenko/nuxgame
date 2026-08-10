<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create players table (assignment "registration" without Auth users).
     */
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('phone');
            $table->timestamps();
        });
    }

    /**
     * Drop players table.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
