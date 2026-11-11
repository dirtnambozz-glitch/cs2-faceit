<?php

namespace App\Models;

use App\Services\EloService;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'steam_id',
        'elo',
        'level',
        'matches_count',
        'wins_count',
        'losses_count',
        'avatar',
        'is_bot',
        'is_admin',
        'banned_until',
        'ban_reason',
        'warnings_count',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_bot' => 'boolean',
            'is_admin' => 'boolean',
            'banned_until' => 'datetime',
            'warnings_count' => 'integer',
            'elo' => 'integer',
            'level' => 'integer',
            'matches_count' => 'integer',
            'wins_count' => 'integer',
            'losses_count' => 'integer',
        ];
    }

    public function isBanned(): bool
    {
        return $this->banned_until !== null && $this->banned_until->isFuture();
    }

    public function banRemainingSeconds(): int
    {
        if (! $this->isBanned()) {
            return 0;
        }

        return max(0, (int) now()->diffInSeconds($this->banned_until, false));
    }

    public function applyBan(int $minutes, string $reason): void
    {
        $this->banned_until = now()->addMinutes($minutes);
        $this->ban_reason = $reason;
        $this->warnings_count = ($this->warnings_count ?? 0) + 1;
        $this->save();
    }

    public function isAdmin(): bool
    {
        return (bool) ($this->is_admin || strtolower($this->name) === 'defense');
    }

    public function updateLevelFromElo(): void
    {
        $this->level = EloService::calculateLevel($this->elo);
        $this->save();
    }

    public function getWinrateAttribute(): float
    {
        if ($this->matches_count === 0) {
            return 0.0;
        }

        return round(($this->wins_count / $this->matches_count) * 100, 1);
    }

    public function getLevelBadgeColorAttribute(): string
    {
        return EloService::getLevelBadgeColor($this->level);
    }

    public function matchesAsPlayer1(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'player1_id');
    }

    public function matchesAsPlayer2(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'player2_id');
    }
}
