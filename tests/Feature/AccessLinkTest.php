<?php

use App\Models\AccessLink;
use App\Models\GameResult;
use App\Models\Player;
use Illuminate\Support\Carbon;

it('shows page A for a valid link', function () {
    $link = AccessLink::factory()->create();

    $this->get(route('page.show', $link))
        ->assertOk()
        ->assertSee('ImFeelingLucky')
        ->assertSee('History')
        ->assertSee($link->player->username);
});

it('returns 410 for an expired link', function () {
    $link = AccessLink::factory()->expired()->create();

    $this->get(route('page.show', $link))
        ->assertStatus(410)
        ->assertSee('Access link is no longer valid');
});

it('returns 410 for a deactivated link', function () {
    $link = AccessLink::factory()->deactivated()->create();

    $this->get(route('page.show', $link))
        ->assertStatus(410)
        ->assertSee('Access link is no longer valid');
});

it('regenerates a link, deactivates the old one, and sets expiry from now', function () {
    Carbon::setTestNow('2026-01-01 12:00:00');

    $link = AccessLink::factory()->create([
        'expires_at' => now()->addDays(3),
    ]);

    Carbon::setTestNow('2026-01-03 12:00:00');

    $response = $this->post(route('page.regenerate', $link));

    $link->refresh();
    expect($link->is_active)->toBeFalse();

    $newLink = AccessLink::query()
        ->where('player_id', $link->player_id)
        ->where('is_active', true)
        ->first();

    expect($newLink)->not->toBeNull()
        ->and($newLink->token)->not->toBe($link->token)
        ->and($newLink->expires_at->equalTo(now()->addDays(7)))->toBeTrue();

    $response->assertRedirect(route('page.show', $newLink));

    Carbon::setTestNow();
});

it('makes the old access link unavailable after regenerate while the new one works', function () {
    $oldLink = AccessLink::factory()->create();

    $this->post(route('page.regenerate', $oldLink))
        ->assertRedirect();

    $newLink = AccessLink::query()
        ->where('player_id', $oldLink->player_id)
        ->where('is_active', true)
        ->first();

    expect($newLink)->not->toBeNull()
        ->and($newLink->id)->not->toBe($oldLink->id);

    $this->get(route('page.show', $oldLink))->assertStatus(410);
    $this->get(route('page.show', $newLink))->assertOk();
});

it('deactivates a link and makes it unavailable', function () {
    $link = AccessLink::factory()->create();

    $this->post(route('page.deactivate', $link))
        ->assertOk()
        ->assertSee('deactivated');

    expect($link->fresh()->is_active)->toBeFalse();
    $this->get(route('page.show', $link))->assertStatus(410);
});

it('creates a game result when playing lucky', function () {
    $link = AccessLink::factory()->create();

    $this->post(route('page.lucky', $link))
        ->assertOk()
        ->assertSee('Lucky result');

    $this->assertDatabaseCount('game_results', 1);
    $this->assertDatabaseHas('game_results', [
        'player_id' => $link->player_id,
    ]);
});

it('shows only the last three results for the current player', function () {
    $link = AccessLink::factory()->create();
    $otherPlayer = Player::factory()->create();

    GameResult::factory()->create([
        'player_id' => $otherPlayer->id,
        'number' => 998,
        'is_win' => true,
        'amount' => 698.6,
        'created_at' => now()->subMinutes(1),
    ]);

    foreach ([10, 20, 30, 40] as $index => $number) {
        GameResult::factory()->create([
            'player_id' => $link->player_id,
            'number' => $number,
            'is_win' => true,
            'amount' => 1,
            'created_at' => now()->subMinutes(10 - $index),
        ]);
    }

    $this->get(route('page.history', $link))
        ->assertOk()
        ->assertSee('History (last 3)')
        ->assertSee('Number: 40')
        ->assertSee('Number: 30')
        ->assertSee('Number: 20')
        ->assertDontSee('Number: 10')
        ->assertDontSee('Number: 998');
});
