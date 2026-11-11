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
    public int $acceptCountdown = 15;

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
                    $this->matchFound = true;
                    $this->matchUuid = $match->uuid;
                    $this->accepted = ($entry->status === 'accepted');
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
    }

    public function pollQueue(): void
    {
        if (! $this->inQueue) {
            return;
        }

        $this->queueSeconds++;

        $user = Auth::user();
        if (! $user) {
            return;
        }

        $entry = MatchmakingQueue::where('user_id', $user->id)->first();
        if ($entry && $entry->status === 'matched' && $entry->match_id) {
            $match = GameMatch::find($entry->match_id);
            if ($match) {
                $this->matchFound = true;
                $this->matchUuid = $match->uuid;
                return;
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

    private function createMatchBetween(User $p1, User $p2, bool $instantAccept = false): void
    {
        $serverIp = config('services.cs2.server_ip') ?: env('CS2_SERVER_IP', '89.248.236.141');
        $serverPort = config('services.cs2.server_port') ?: env('CS2_SERVER_PORT', 27574);
        $serverPass = 'match_' . rand(1000, 9999);

        $match = GameMatch::create([
            'uuid' => (string) Str::uuid(),
            'player1_id' => $p1->id,
            'player2_id' => $p2->id,
            'status' => 'voting',
            'current_turn_player_id' => $p1->id,
            'server_ip' => $serverIp,
            'server_port' => (int) $serverPort,
            'server_password' => $serverPass,
            'started_at' => now(),
        ]);

        MatchmakingQueue::whereIn('user_id', [$p1->id, $p2->id])->delete();

        // If configured and live server is enabled, we could ping RCON
        $this->redirectRoute('match.lobby', ['uuid' => $match->uuid]);
    }

    public function acceptMatch(): void
    {
        $this->accepted = true;

        if ($this->matchUuid) {
            $this->redirectRoute('match.lobby', ['uuid' => $this->matchUuid]);
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
