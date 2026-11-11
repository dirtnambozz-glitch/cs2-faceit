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

    public int $scoreP1 = 13;
    public int $scoreP2 = 9;
    public string $rconStatusMessage = '';
    public bool $rconSuccess = false;

    public function mount(string $uuid): void
    {
        $this->uuid = $uuid;
        $this->loadMatch();
    }

    public function loadMatch(): void
    {
        $this->match = GameMatch::with(['player1', 'player2', 'map', 'vetoes.map'])
            ->where('uuid', $this->uuid)
            ->firstOrFail();

        // If current turn is bot and in voting status, perform bot turn
        if ($this->match->status === 'voting' && $this->match->currentTurnPlayer && $this->match->currentTurnPlayer->is_bot) {
            $this->performBotBan();
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

    public function submitScore(): void
    {
        $this->loadMatch();
        if ($this->match->status === 'finished') {
            return;
        }

        $winnerId = ($this->scoreP1 > $this->scoreP2)
            ? $this->match->player1_id
            : $this->match->player2_id;

        $scoreP1Normalized = ($this->scoreP1 > $this->scoreP2) ? 1.0 : (($this->scoreP1 === $this->scoreP2) ? 0.5 : 0.0);

        $p1 = $this->match->player1;
        $p2 = $this->match->player2;

        [$eloChangeP1, $eloChangeP2] = EloService::calculateMatchElo(
            $p1->elo,
            $p2->elo,
            $scoreP1Normalized
        );

        // Update player 1
        $p1->elo = max(100, $p1->elo + $eloChangeP1);
        $p1->matches_count++;
        if ($scoreP1Normalized == 1.0) {
            $p1->wins_count++;
        } else {
            $p1->losses_count++;
        }
        $p1->updateLevelFromElo();

        // Update player 2
        $p2->elo = max(100, $p2->elo + $eloChangeP2);
        $p2->matches_count++;
        if ($scoreP1Normalized == 0.0) {
            $p2->wins_count++;
        } else {
            $p2->losses_count++;
        }
        $p2->updateLevelFromElo();

        $this->match->update([
            'status' => 'finished',
            'score_player1' => $this->scoreP1,
            'score_player2' => $this->scoreP2,
            'winner_id' => $winnerId,
            'elo_change_p1' => $eloChangeP1,
            'elo_change_p2' => $eloChangeP2,
            'finished_at' => now(),
        ]);

        $this->loadMatch();
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
