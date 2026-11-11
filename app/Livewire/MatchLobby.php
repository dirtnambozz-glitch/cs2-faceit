<?php

namespace App\Livewire;

use App\Models\GameMap;
use App\Models\GameMatch;
use App\Models\MatchVeto;
use App\Models\User;
use App\Services\Cs2\RconService;
use App\Services\EloService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class MatchLobby extends Component
{
    public string $uuid;
    public ?GameMatch $match = null;

    public string $rconStatusMessage = '';
    public bool $rconSuccess = false;

    public function mount(string $uuid): void
    {
        $this->uuid = $uuid;
        $this->loadMatch();
    }

    public function loadMatch(): void
    {
        $this->match = GameMatch::with(['player1', 'player2', 'map', 'vetoes.map', 'forfeitedUser', 'winner'])
            ->where('uuid', $this->uuid)
            ->firstOrFail();

        // Check connect timeout if match is ready or in progress
        if (in_array($this->match->status, ['ready', 'in_progress']) && $this->match->connect_deadline) {
            $this->checkConnectTimeout();
        }

        // If current turn is bot and in voting status, perform bot turn
        if ($this->match->status === 'voting' && $this->match->currentTurnPlayer && $this->match->currentTurnPlayer->is_bot) {
            $this->performBotBan();
        }
    }

    private function checkConnectTimeout(): void
    {
        if ($this->match->status === 'finished') {
            return;
        }

        // Check if deadline has passed
        if ($this->match->connectRemainingSeconds() <= 0) {
            $p1 = $this->match->player1;
            $p2 = $this->match->player2;

            $p1Conn = (bool) $this->match->player1_connected;
            $p2Conn = (bool) $this->match->player2_connected;

            // If player 2 is a bot, treat bot as always connected
            if ($p2 && $p2->is_bot) {
                $p2Conn = true;
            }

            if ($p1Conn && ! $p2Conn && $p2) {
                $this->match->forfeitPlayer($p2, 'Не зашел на CS2 сервер в течение отведенного времени (5 мин)');
                $this->match->refresh();
                return;
            }

            if ($p2Conn && ! $p1Conn && $p1) {
                $this->match->forfeitPlayer($p1, 'Не зашел на CS2 сервер в течение отведенного времени (5 мин)');
                $this->match->refresh();
                return;
            }

            if (! $p1Conn && ! $p2Conn) {
                // Neither connected: cancel match
                $this->match->update([
                    'status' => 'finished',
                    'finished_at' => now(),
                ]);
                $this->match->refresh();
                return;
            }
        }
    }

    public function banMap(int $mapId): void
    {
        $user = Auth::user();
        if (! $user) {
            return;
        }

        $this->loadMatch();

        if ($this->match->status !== 'voting') {
            return;
        }

        // Check if it's user's turn
        if ($this->match->current_turn_player_id !== $user->id) {
            return;
        }

        // Check if map is already banned
        $bannedMapIds = $this->match->vetoes()->pluck('map_id')->toArray();
        if (in_array($mapId, $bannedMapIds)) {
            return;
        }

        $step = $this->match->vetoes()->count() + 1;

        MatchVeto::create([
            'match_id' => $this->match->id,
            'user_id' => $user->id,
            'map_id' => $mapId,
            'action' => 'ban',
            'step' => $step,
        ]);

        $this->advanceVeto();
    }

    private function performBotBan(): void
    {
        $bannedMapIds = $this->match->vetoes()->pluck('map_id')->toArray();
        $availableMaps = GameMap::where('is_active', true)
            ->whereNotIn('id', $bannedMapIds)
            ->get();

        if ($availableMaps->count() <= 1) {
            $this->finalizeVeto();
            return;
        }

        $randomMap = $availableMaps->random();
        $step = $this->match->vetoes()->count() + 1;

        MatchVeto::create([
            'match_id' => $this->match->id,
            'user_id' => $this->match->current_turn_player_id,
            'map_id' => $randomMap->id,
            'action' => 'ban',
            'step' => $step,
        ]);

        $this->advanceVeto();
    }

    private function advanceVeto(): void
    {
        $bannedMapIds = $this->match->vetoes()->pluck('map_id')->toArray();
        $allActiveMaps = GameMap::where('is_active', true)->get();
        $remainingCount = $allActiveMaps->count() - count($bannedMapIds);

        if ($remainingCount <= 1) {
            $this->finalizeVeto();
            return;
        }

        // Switch turn to the other player
        $nextPlayerId = ($this->match->current_turn_player_id === $this->match->player1_id)
            ? $this->match->player2_id
            : $this->match->player1_id;

        $this->match->update(['current_turn_player_id' => $nextPlayerId]);
        $this->loadMatch();
    }

    private function finalizeVeto(): void
    {
        $bannedMapIds = $this->match->vetoes()->pluck('map_id')->toArray();
        $finalMap = GameMap::where('is_active', true)
            ->whereNotIn('id', $bannedMapIds)
            ->first();

        if (! $finalMap) {
            $finalMap = GameMap::where('code', 'de_mirage')->first();
        }

        // Create the Pick entry for the final decider map
        MatchVeto::create([
            'match_id' => $this->match->id,
            'user_id' => null,
            'map_id' => $finalMap->id,
            'action' => 'pick',
            'step' => $this->match->vetoes()->count() + 1,
        ]);

        $this->match->update([
            'map_id' => $finalMap->id,
            'status' => 'ready',
            'connect_deadline' => now()->addMinutes(5),
            'player1_connected' => false,
            'player2_connected' => false,
        ]);

        $this->sendRconSetup($finalMap->code, $this->match->server_password);
        $this->loadMatch();
    }

    public function sendRconSetup(string $mapCode, string $password): void
    {
        $rcon = new RconService();
        if ($rcon->connect()) {
            $rcon->execute("sv_password \"{$password}\"");
            $rcon->execute("matchzy_remote_log_url \"http://45.140.19.240/api/matchzy/events\"");
            $rcon->execute("changelevel {$mapCode}");
            $this->rconSuccess = true;
            $this->rconStatusMessage = "Карта на CS2 сервере успешно изменена на {$mapCode}!";
        } else {
            $this->rconSuccess = false;
            $this->rconStatusMessage = "RCON не подключен. Укажите RCON-пароль сервера в разделе «Сервер» в меню.";
        }
    }

    public function retryRcon(): void
    {
        $this->loadMatch();
        if ($this->match && $this->match->map) {
            $this->sendRconSetup($this->match->map->code, $this->match->server_password);
        }
    }

    public function render()
    {
        $this->loadMatch();

        $allMaps = GameMap::where('is_active', true)->get();
        $bannedMapIds = $this->match->vetoes()->where('action', 'ban')->pluck('map_id')->toArray();
        $pickedVeto = $this->match->vetoes()->where('action', 'pick')->first();

        return view('livewire.match-lobby', [
            'allMaps' => $allMaps,
            'bannedMapIds' => $bannedMapIds,
            'pickedVeto' => $pickedVeto,
        ]);
    }
}
