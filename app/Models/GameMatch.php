<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'player1_id',
        'player2_id',
        'map_id',
        'status',
        'current_turn_player_id',
        'winner_id',
        'score_player1',
        'score_player2',
        'elo_change_p1',
        'elo_change_p2',
        'server_ip',
        'server_port',
        'server_password',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'score_player1' => 'integer',
        'score_player2' => 'integer',
        'elo_change_p1' => 'integer',
        'elo_change_p2' => 'integer',
        'server_port' => 'integer',
    ];

    public function player1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player1_id');
    }

    public function player2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player2_id');
    }

    public function currentTurnPlayer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_turn_player_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function map(): BelongsTo
    {
        return $this->belongsTo(GameMap::class, 'map_id');
    }

    public function vetoes(): HasMany
    {
        return $this->hasMany(MatchVeto::class, 'match_id')->orderBy('step');
    }
}
