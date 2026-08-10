<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterPlayerRequest;
use App\Models\AccessLink;
use App\Models\Player;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Home-page registration: creates a player and a 7-day unique access link.
 */
class RegistrationController extends Controller
{
    /**
     * Display the registration form (Username, Phonenumber, Register).
     */
    public function create(): View
    {
        return view('register');
    }

    /**
     * Persist player + access link, then redirect to Page A.
     *
     * Wrapped in a transaction to avoid an orphan player if link creation fails
     * (e.g. rare unique token collision).
     */
    public function store(RegisterPlayerRequest $request): RedirectResponse
    {
        $accessLink = DB::transaction(function () use ($request): AccessLink {
            $player = Player::query()->create([
                'username' => $request->validated('username'),
                'phone' => $request->validated('phonenumber'),
            ]);

            return AccessLink::query()->create([
                'player_id' => $player->id,
                'token' => Str::random(64),
                'expires_at' => now()->addDays(7),
                'is_active' => true,
            ]);
        });

        return redirect()->route('page.show', $accessLink);
    }
}
