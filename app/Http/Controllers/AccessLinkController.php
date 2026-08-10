<?php

namespace App\Http\Controllers;

use App\Models\AccessLink;
use App\Models\GameResult;
use App\Services\GameService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Page A actions for a valid access link (middleware already enforced validity).
 */
class AccessLinkController extends Controller
{
    /**
     * Show Page A controls for the current access link.
     */
    public function show(AccessLink $accessLink): View
    {
        return view('page-a', [
            'accessLink' => $accessLink,
            'player' => $accessLink->player,
            'result' => null,
            'history' => null,
        ]);
    }

    /**
     * Invalidate the current link and issue a new one (7 days from now).
     *
     * Runs in a transaction so we never leave the player without an active link
     * if creation of the new token fails after deactivating the old one.
     */
    public function regenerate(AccessLink $accessLink): RedirectResponse
    {
        $newLink = DB::transaction(function () use ($accessLink): AccessLink {
            $accessLink->update(['is_active' => false]);

            return AccessLink::query()->create([
                'player_id' => $accessLink->player_id,
                'token' => Str::random(64),
                // Fresh 7-day window from regenerate time, not from original registration
                'expires_at' => now()->addDays(7),
                'is_active' => true,
            ]);
        });

        return redirect()->route('page.show', $newLink);
    }

    /**
     * Permanently deactivate this access link (further requests return 410).
     */
    public function deactivate(AccessLink $accessLink): View
    {
        $accessLink->update(['is_active' => false]);

        return view('link-deactivated');
    }

    /**
     * Play ImFeelingLucky and persist the result against the player (not the link).
     *
     * History is keyed by player_id so regenerating the link does not wipe past games.
     */
    public function lucky(AccessLink $accessLink, GameService $gameService): View
    {
        $outcome = $gameService->play();

        $result = GameResult::query()->create([
            'player_id' => $accessLink->player_id,
            'number' => $outcome['number'],
            'is_win' => $outcome['is_win'],
            'amount' => $outcome['amount'],
        ]);

        return view('page-a', [
            'accessLink' => $accessLink,
            'player' => $accessLink->player,
            'result' => $result,
            'history' => null,
        ]);
    }

    /**
     * Show the last 3 ImFeelingLucky results for this player.
     */
    public function history(AccessLink $accessLink): View
    {
        $history = GameResult::query()
            ->where('player_id', $accessLink->player_id)
            ->latest()
            ->limit(3)
            ->get();

        return view('page-a', [
            'accessLink' => $accessLink,
            'player' => $accessLink->player,
            'result' => null,
            'history' => $history,
        ]);
    }
}
