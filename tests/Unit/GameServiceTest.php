<?php

use App\Services\GameService;

beforeEach(function () {
    $this->gameService = new GameService;
});

it('resolves win or lose and amount for known numbers', function (int $number, bool $isWin, float $amount) {
    $result = $this->gameService->resolve($number);

    expect($result['number'])->toBe($number)
        ->and($result['is_win'])->toBe($isWin)
        ->and($result['amount'])->toBe($amount);
})->with([
    '1000_win_70_percent' => [1000, true, 700.0],
    '902_win_70_percent' => [902, true, 631.4],
    '602_win_50_percent' => [602, true, 301.0],
    '600_win_30_percent_boundary' => [600, true, 180.0],
    '302_win_30_percent' => [302, true, 90.6],
    '300_win_10_percent_boundary' => [300, true, 30.0],
    '301_lose_ignores_threshold' => [301, false, 0.0],
    '299_lose_ignores_threshold' => [299, false, 0.0],
]);
