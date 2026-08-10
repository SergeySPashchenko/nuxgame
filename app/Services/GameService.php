<?php

namespace App\Services;

/**
 * ImFeelingLucky game rules: random draw, win/lose, and payout amount.
 */
class GameService
{
    /**
     * Draw a random number and resolve the outcome.
     *
     * Uses cryptographically secure random_int() — not rand().
     *
     * @return array{number: int, is_win: bool, amount: float}
     */
    public function play(): array
    {
        return $this->resolve(random_int(1, 1000));
    }

    /**
     * Deterministic win/lose and amount for a known number (unit-tested).
     *
     * Even numbers win; odd numbers lose with amount 0 (thresholds are not applied on lose).
     *
     * @return array{number: int, is_win: bool, amount: float}
     */
    public function resolve(int $number): array
    {
        // Even → Win; odd → Lose
        if ($number % 2 === 0) {
            return [
                'number' => $number,
                'is_win' => true,
                'amount' => $this->winAmount($number),
            ];
        }

        return [
            'number' => $number,
            'is_win' => false,
            'amount' => 0.0,
        ];
    }

    /**
     * Payout as a percentage of the number. Thresholds use strict ">" (not ">=").
     *
     * >900 → 70%, >600 → 50%, >300 → 30%, otherwise 10%.
     */
    private function winAmount(int $number): float
    {
        return match (true) {
            $number > 900 => round($number * 0.7, 2),
            $number > 600 => round($number * 0.5, 2),
            $number > 300 => round($number * 0.3, 2),
            default => round($number * 0.1, 2),
        };
    }
}
