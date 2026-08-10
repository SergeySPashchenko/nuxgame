<?php

namespace App\Models;

use Database\Factories\AccessLinkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Time-limited unique URL token that grants access to Page A for a player.
 *
 * @property int $player_id
 * @property string $token
 * @property Carbon $expires_at
 * @property bool $is_active
 */
class AccessLink extends Model
{
    /** @use HasFactory<AccessLinkFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'player_id',
        'token',
        'expires_at',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Player, $this>
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * Whether this link may be used: still active and not past expires_at.
     *
     * Used by EnsureValidAccessLink middleware — do not duplicate this logic elsewhere.
     */
    public function isValid(): bool
    {
        return $this->is_active && $this->expires_at->isFuture();
    }

    /**
     * Bind routes on the random token string, not the numeric id.
     */
    public function getRouteKeyName(): string
    {
        return 'token';
    }
}
