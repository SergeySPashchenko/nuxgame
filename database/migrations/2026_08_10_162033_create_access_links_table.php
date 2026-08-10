<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create access_links: unique VARCHAR(64) token, 7-day window, active flag.
     */
    public function up(): void
    {
        Schema::create('access_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            // Random token string (Str::random(64)) + UNIQUE index
            $table->string('token', 64)->unique();
            $table->timestamp('expires_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Drop access_links table.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_links');
    }
};
