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
        'player1_accepted',
        'player2_accepted',
        'accept_deadline',
        'connect_deadline',
        'player1_connected',
        'player2_connected',
        'forfeited_user_id',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'accept_deadline' => 'datetime',
        'connect_deadline' => 'datetime',
        'player1_accepted' => 'boolean',
        'player2_accepted' => 'boolean',
        'player1_connected' => 'boolean',
        'player2_connected' => 'boolean',
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

    public function forfeitedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'forfeited_user_id');
    }

    public function isAcceptedBy(int $userId): bool
    {
        if ($this->player1_id === $userId) {
            return (bool) $this->player1_accepted;
        }
        if ($this->player2_id === $userId) {
            return (bool) $this->player2_accepted;
        }
        return false;
    }

    public function isBothAccepted(): bool
    {
        return (bool) ($this->player1_accepted && $this->player2_accepted);
    }

    public function acceptRemainingSeconds(): int
    {
        if (! $this->accept_deadline) {
            return 0;
        }
        return max(0, (int) now()->diffInSeconds($this->accept_deadline, false));
    }

    public function connectRemainingSeconds(): int
    {
        if (! $this->connect_deadline) {
            return 0;
        }
        return max(0, (int) now()->diffInSeconds($this->connect_deadline, false));
    }

    /**
     * Forfeit match against a leaver / no-show player, deduct Elo, apply ban.
     */
    public function forfeitPlayer(User $leaver, string $reason = 'Не зашел на сервер в течение отведенного времени'): void
    {
        if ($this->status === 'finished') {
            return;
        }

        $isP1 = ($this->player1_id === $leaver->id);
        $winner = $isP1 ? $this->player2 : $this->player1;

        // Elo penalty: -25 for leaver, +25 for winner
        $eloPenalty = 25;
        $leaver->elo = max(100, $leaver->elo - $eloPenalty);
        $leaver->losses_count++;
        $leaver->matches_count++;
        $leaver->updateLevelFromElo();

        // Ban penalty scaling: 15 min for 1st warning, 30 min for 2nd, 60 min for 3rd+
        $banMinutes = match ($leaver->warnings_count) {
            0 => 15,
            1 => 30,
            default => 60,
        };
        $leaver->applyBan($banMinutes, $reason);

        if ($winner) {
            $winner->elo = $winner->elo + $eloPenalty;
            $winner->wins_count++;
            $winner->matches_count++;
            $winner->updateLevelFromElo();
        }

        $this->update([
            'status' => 'finished',
            'winner_id' => $winner ? $winner->id : null,
            'forfeited_user_id' => $leaver->id,
            'score_player1' => $isP1 ? 0 : 13,
            'score_player2' => $isP1 ? 13 : 0,
            'elo_change_p1' => $isP1 ? -$eloPenalty : $eloPenalty,
            'elo_change_p2' => $isP1 ? $eloPenalty : -$eloPenalty,
            'finished_at' => now(),
        ]);
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
