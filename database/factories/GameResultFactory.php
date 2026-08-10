<?php

namespace Database\Factories;

use App\Models\GameResult;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GameResult>
 */
class GameResultFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $number = fake()->numberBetween(1, 1000);
        $isWin = $number % 2 === 0;

        return [
            'player_id' => Player::factory(),
            'number' => $number,
            'is_win' => $isWin,
            'amount' => $isWin ? round($number * 0.1, 2) : 0,
        ];
    }
}
