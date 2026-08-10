<?php

use App\Models\AccessLink;

it('shows the registration form on the home page', function () {
    $this->get(route('register'))
        ->assertOk()
        ->assertSee('Username')
        ->assertSee('Phonenumber')
        ->assertSee('Register');
});

it('creates a player and redirects to page A', function () {
    $response = $this->post(route('register.store'), [
        'username' => 'nuxgame',
        'phonenumber' => '+380501112233',
    ]);

    $this->assertDatabaseHas('players', [
        'username' => 'nuxgame',
        'phone' => '+380501112233',
    ]);

    $link = AccessLink::query()->first();

    expect($link)->not->toBeNull()
        ->and(strlen($link->token))->toBe(64)
        ->and($link->is_active)->toBeTrue()
        ->and($link->expires_at->greaterThan(now()->addDays(6)))->toBeTrue();

    $response->assertRedirect(route('page.show', $link));
});

it('requires username and phonenumber', function () {
    $this->from(route('register'))
        ->post(route('register.store'), [])
        ->assertRedirect(route('register'))
        ->assertSessionHasErrors(['username', 'phonenumber']);
});
