<?php

namespace App\Models;

use Database\Factories\GameResultFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One ImFeelingLucky outcome. Belongs to player_id (not access_link_id).
 *
 * @property int $player_id
 * @property int $number
 * @property bool $is_win
 * @property string $amount
 */
class GameResult extends Model
{
    /** @use HasFactory<GameResultFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'player_id',
        'number',
        'is_win',
        'amount',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'number' => 'integer',
            'is_win' => 'boolean',
            'amount' => 'decimal:2',
        ];
    }

    /**
     * @return BelongsTo<Player, $this>
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
