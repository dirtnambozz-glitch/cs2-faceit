<?php

namespace App\Livewire;

use App\Models\GameMap;
use App\Models\GameMatch;
use App\Models\MatchmakingQueue;
use App\Models\User;
use App\Services\Cs2\RconService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Matchmaking extends Component
{
    public bool $inQueue = false;
    public int $queueSeconds = 0;
    public bool $matchFound = false;
    public ?string $matchUuid = null;
    public bool $accepted = false;
    public int $acceptedCount = 0;
    public int $acceptRemainingSeconds = 20;

    public function mount(): void
    {
        if (! Auth::check()) {
            $this->redirectRoute('login');
            return;
        }
        $this->syncQueueStatus();
    }

    public function syncQueueStatus(): void
    {
        $user = Auth::user();
        if (! $user) {
            return;
        }

        $entry = MatchmakingQueue::where('user_id', $user->id)->first();
        if ($entry) {
            $this->inQueue = true;
            if ($entry->status === 'matched' && $entry->match_id) {
                $match = GameMatch::find($entry->match_id);
                if ($match) {
                    if ($match->status === 'cancelled') {
                        $entry->delete();
                        $this->inQueue = false;
                        $this->matchFound = false;
                        $this->matchUuid = null;
                        return;
                    }

                    if ($match->status === 'voting' || $match->status === 'ready' || $match->status === 'in_progress') {
                        $entry->delete();
                        $this->redirectRoute('match.lobby', ['uuid' => $match->uuid]);
                        return;
                    }

                    if ($match->status === 'ready_check') {
                        $this->matchFound = true;
                        $this->matchUuid = $match->uuid;
                        $this->accepted = $match->isAcceptedBy($user->id);
                        $this->acceptedCount = ($match->player1_accepted ? 1 : 0) + ($match->player2_accepted ? 1 : 0);
                        $this->acceptRemainingSeconds = $match->acceptRemainingSeconds();
                    }
                }
            }
        }
    }

    public function startQueue(): void
    {
        $user = Auth::user();
        if (! $user) {
            $this->redirectRoute('login');
            return;
        }

        if ($user->isBanned()) {
            $until = $user->banned_until->format('H:i:s d.m.Y');
            session()->flash('warning', "Вы временно заблокированы в матчмейкинге до {$until}. Причина: {$user->ban_reason}");
            return;
        }

        if (! $user->steam_id) {
            session()->flash('warning', 'Для поиска матча CS2 необходимо привязать Steam ID в профиле!');
            $this->redirectRoute('profile');
            return;
        }

        MatchmakingQueue::updateOrCreate(
            ['user_id' => $user->id],
            [
                'elo' => $user->elo,
                'status' => 'searching',
                'match_id' => null,
                'created_at' => now(),
            ]
        );

        $this->inQueue = true;
        $this->queueSeconds = 0;
        $this->matchFound = false;
        $this->accepted = false;
        $this->acceptedCount = 0;
        $this->acceptRemainingSeconds = 20;
    }

    public function cancelQueue(): void
    {
        $user = Auth::user();
        if ($user) {
            MatchmakingQueue::where('user_id', $user->id)->delete();
        }

        $this->inQueue = false;
        $this->queueSeconds = 0;
        $this->matchFound = false;
        $this->accepted = false;
        $this->acceptedCount = 0;
    }

    public function pollQueue(): void
    {
        $user = Auth::user();
        if (! $user) {
            return;
        }

        if (! $this->inQueue && ! $this->matchFound) {
            return;
        }

        $this->queueSeconds++;

        $entry = MatchmakingQueue::where('user_id', $user->id)->first();
        if ($entry && $entry->status === 'matched' && $entry->match_id) {
            $match = GameMatch::find($entry->match_id);
            if ($match) {
                // If match is already in voting/ready, redirect
                if (in_array($match->status, ['voting', 'ready', 'in_progress'])) {
                    $entry->delete();
                    $this->redirectRoute('match.lobby', ['uuid' => $match->uuid]);
                    return;
                }

                if ($match->status === 'ready_check') {
                    $this->matchFound = true;
                    $this->matchUuid = $match->uuid;
                    $this->accepted = $match->isAcceptedBy($user->id);
                    $this->acceptedCount = ($match->player1_accepted ? 1 : 0) + ($match->player2_accepted ? 1 : 0);
                    $this->acceptRemainingSeconds = $match->acceptRemainingSeconds();

                    // Check timeout
                    if ($match->acceptRemainingSeconds() <= 0) {
                        // Someone failed to accept
                        $leaverName = '';
                        if (! $match->player1_accepted && $match->player1) {
                            $match->player1->applyBan(5, 'Не подтвердил готовность к матчу (Ready Check)');
                            $leaverName = $match->player1->name;
                        }
                        if (! $match->player2_accepted && $match->player2 && ! $match->player2->is_bot) {
                            $match->player2->applyBan(5, 'Не подтвердил готовность к матчу (Ready Check)');
                            $leaverName = $match->player2->name;
                        }

                        $match->update(['status' => 'cancelled']);
                        MatchmakingQueue::whereIn('user_id', [$match->player1_id, $match->player2_id])->delete();

                        $this->inQueue = false;
                        $this->matchFound = false;
                        $this->accepted = false;
                        $this->matchUuid = null;

                        $user->refresh();
                        if ($user->isBanned()) {
                            session()->flash('warning', "Вы не подтвердили матч вовремя и получили блокировку поиска на 5 минут.");
                        } else {
                            session()->flash('warning', "Матч отменен: игрок {$leaverName} не подтвердил готовность.");
                        }
                        return;
                    }

                    // Check if both have accepted
                    if ($match->isBothAccepted()) {
                        $match->update(['status' => 'voting']);
                        MatchmakingQueue::whereIn('user_id', [$match->player1_id, $match->player2_id])->delete();
                        $this->redirectRoute('match.lobby', ['uuid' => $match->uuid]);
                        return;
                    }

                    return;
                }

                if ($match->status === 'cancelled') {
                    $entry->delete();
                    $this->inQueue = false;
                    $this->matchFound = false;
                    $this->accepted = false;
                    $this->matchUuid = null;
                    return;
                }
            }
        }

        // Try to match with another player in queue
        $opponentQueue = MatchmakingQueue::where('status', 'searching')
            ->where('user_id', '!=', $user->id)
            ->first();

        if ($opponentQueue) {
            $this->createMatchBetween($user, $opponentQueue->user);
        }
    }

    public function startInstantBotMatch(): void
    {
        $user = Auth::user();
        if (! $user) {
            $this->redirectRoute('login');
            return;
        }

        if ($user->isBanned()) {
            $until = $user->banned_until->format('H:i:s d.m.Y');
            session()->flash('warning', "Вы временно заблокированы в матчмейкинге до {$until}. Причина: {$user->ban_reason}");
            return;
        }

        if (! $user->steam_id) {
            session()->flash('warning', 'Для игры на сервере необходимо привязать Steam ID в профиле!');
            $this->redirectRoute('profile');
            return;
        }

        $bot = User::where('is_bot', true)->where('email', 'bot@faceit.test')->first();
        if (! $bot) {
            $bot = User::firstOrCreate(
                ['email' => 'bot@faceit.test'],
                [
                    'name' => 'TestOpponentBot',
                    'password' => bcrypt('password'),
                    'elo' => 1020,
                    'level' => 3,
                    'is_bot' => true,
                ]
            );
        }

        $this->createMatchBetween($user, $bot, true);
    }

    private function createMatchBetween(User $p1, User $p2, bool $isBotMatch = false): void
    {
        $serverIp = config('services.cs2.server_ip') ?: env('CS2_SERVER_IP', '89.248.236.141');
        $serverPort = config('services.cs2.server_port') ?: env('CS2_SERVER_PORT', 27574);
        $serverPass = 'match_' . rand(1000, 9999);

        $match = GameMatch::create([
            'uuid' => (string) Str::uuid(),
            'player1_id' => $p1->id,
            'player2_id' => $p2->id,
            'status' => 'ready_check',
            'current_turn_player_id' => $p1->id,
            'server_ip' => $serverIp,
            'server_port' => (int) $serverPort,
            'server_password' => $serverPass,
            'player1_accepted' => false,
            'player2_accepted' => $isBotMatch, // Bot automatically accepts
            'accept_deadline' => now()->addSeconds(20),
            'started_at' => now(),
        ]);

        MatchmakingQueue::updateOrCreate(
            ['user_id' => $p1->id],
            ['status' => 'matched', 'match_id' => $match->id]
        );

        if (! $isBotMatch) {
            MatchmakingQueue::updateOrCreate(
                ['user_id' => $p2->id],
                ['status' => 'matched', 'match_id' => $match->id]
            );
        }

        $this->inQueue = true;
        $this->matchFound = true;
        $this->matchUuid = $match->uuid;
        $this->accepted = false;
        $this->acceptedCount = $isBotMatch ? 1 : 0;
        $this->acceptRemainingSeconds = 20;
    }

    public function acceptMatch(): void
    {
        $user = Auth::user();
        if (! $user || ! $this->matchUuid) {
            return;
        }

        $match = GameMatch::where('uuid', $this->matchUuid)->first();
        if (! $match || $match->status !== 'ready_check') {
            return;
        }

        if ($match->player1_id === $user->id) {
            $match->player1_accepted = true;
        } elseif ($match->player2_id === $user->id) {
            $match->player2_accepted = true;
        }
        $match->save();

        $this->accepted = true;
        $this->acceptedCount = ($match->player1_accepted ? 1 : 0) + ($match->player2_accepted ? 1 : 0);

        if ($match->isBothAccepted()) {
            $match->update(['status' => 'voting']);
            MatchmakingQueue::whereIn('user_id', [$match->player1_id, $match->player2_id])->delete();
            $this->redirectRoute('match.lobby', ['uuid' => $match->uuid]);
        }
    }

    public function render()
    {
        $user = Auth::user();
        $recentMatches = collect();

        if ($user) {
            $recentMatches = GameMatch::where(function ($q) use ($user) {
                $q->where('player1_id', $user->id)
                  ->orWhere('player2_id', $user->id);
            })
            ->with(['player1', 'player2', 'map', 'winner'])
            ->latest()
            ->take(5)
            ->get();
        }

        return view('livewire.matchmaking', [
            'user' => $user,
            'recentMatches' => $recentMatches,
        ]);
    }
}
