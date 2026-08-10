<?php

namespace App\Models;

use Database\Factories\PlayerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Registered participant (username + phone). Not a Laravel Auth user.
 *
 * @property string $username
 * @property string $phone
 */
class Player extends Model
{
    /** @use HasFactory<PlayerFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'phone',
    ];

    /**
     * @return HasMany<AccessLink, $this>
     */
    public function accessLinks(): HasMany
    {
        return $this->hasMany(AccessLink::class);
    }

    /**
     * Game history lives on the player so it survives access-link regeneration.
     *
     * @return HasMany<GameResult, $this>
     */
    public function gameResults(): HasMany
    {
        return $this->hasMany(GameResult::class);
    }
}
