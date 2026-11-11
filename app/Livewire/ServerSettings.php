<?php

namespace App\Livewire;

use App\Models\GameMap;
use App\Models\GameMatch;
use App\Models\MatchmakingQueue;
use App\Models\User;
use App\Services\Cs2\RconService;
use App\Services\EloService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ServerSettings extends Component
{
    public string $activeTab = 'overview'; // overview, players, matches, mappool, console, server

    // Server Config
    public string $serverIp = '';
    public int $serverPort = 27574;
    public string $rconPassword = '';
    public string $connectionStatus = '';
    public ?string $serverConsoleOutput = null;
    public bool $isConnected = false;

    // Player Management
    public string $searchPlayer = '';
    public string $playerFilter = 'all'; // all, banned, admin, bot
    public int $selectedUserId = 0;
    public int $editEloValue = 1000;
    public int $banMinutes = 15;
    public string $banReason = 'Нарушение правил';

    // RCON Console
    public string $rconInput = '';
    public array $consoleHistory = [];

    public function mount(): void
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            abort(403, 'Доступ к панели управления разрешен только администраторам.');
        }

        $this->serverIp = config('services.cs2.server_ip', '89.248.236.141');
        $this->serverPort = (int) config('services.cs2.server_port', 27574);
        $this->rconPassword = config('services.cs2.rcon_password', '');

        if (request()->query('tab')) {
            $this->activeTab = request()->query('tab');
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    /* -------------------------------------------------------------------------- */
    /* 1. PLAYER MANAGEMENT ACTIONS                                               */
    /* -------------------------------------------------------------------------- */

    public function unbanPlayer(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->banned_until = null;
        $user->ban_reason = null;
        $user->save();

        session()->flash('success', "Бан с игрока {$user->name} успешно снят!");
    }

    public function banPlayer(int $userId, int $minutes = 15, ?string $reason = null): void
    {
        $user = User::findOrFail($userId);
        $reason = $reason ?: $this->banReason;
        $user->applyBan($minutes, $reason);

        session()->flash('success', "Игрок {$user->name} заблокирован на {$minutes} мин. Причина: {$reason}");
    }

    public function resetWarnings(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->warnings_count = 0;
        $user->save();

        session()->flash('success', "Предупреждения игрока {$user->name} сброшены!");
    }

    public function toggleAdmin(int $userId): void
    {
        $user = User::findOrFail($userId);
        if (strtolower($user->name) === 'defense') {
            session()->flash('error', "Нельзя снять права создателя с defense!");
            return;
        }

        $user->is_admin = ! $user->is_admin;
        $user->save();

        $status = $user->is_admin ? 'назначен Администратором' : 'лишен прав Администратора';
        session()->flash('success', "Игрок {$user->name} {$status}.");
    }

    public function adjustElo(int $userId, int $delta): void
    {
        $user = User::findOrFail($userId);
        $user->elo = max(100, $user->elo + $delta);
        $user->updateLevelFromElo();

        session()->flash('success', "Рейтинг игрока {$user->name} изменен: {$delta} (Итог: {$user->elo} ELO, уровень {$user->level}).");
    }

    public function setExactElo(int $userId, int $elo): void
    {
        $user = User::findOrFail($userId);
        $user->elo = max(100, $elo);
        $user->updateLevelFromElo();

        session()->flash('success', "Рейтинг игрока {$user->name} установлен на {$user->elo} ELO (Уровень {$user->level}).");
    }

    public function resetPlayerStats(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->matches_count = 0;
        $user->wins_count = 0;
        $user->losses_count = 0;
        $user->elo = 1000;
        $user->updateLevelFromElo();

        session()->flash('success', "Статистика и ELO игрока {$user->name} сброшены до начальных значений (1000 ELO).");
    }

    /* -------------------------------------------------------------------------- */
    /* 2. MATCH MANAGEMENT ACTIONS                                                */
    /* -------------------------------------------------------------------------- */

    public function cancelMatch(int $matchId): void
    {
        $match = GameMatch::findOrFail($matchId);
        $match->status = 'cancelled';
        $match->save();

        MatchmakingQueue::whereIn('user_id', [$match->player1_id, $match->player2_id])->delete();

        session()->flash('success', "Матч #{$match->id} успешно отменен без изменения очков рейтинга.");
    }

    public function forceWinMatch(int $matchId, int $winnerId): void
    {
        $match = GameMatch::findOrFail($matchId);
        if ($match->status === 'finished') {
            session()->flash('error', "Матч #{$match->id} уже завершен.");
            return;
        }

        $p1 = $match->player1;
        $p2 = $match->player2;

        $isP1Winner = ($winnerId === $p1->id);
        $scoreNorm = $isP1Winner ? 1.0 : 0.0;

        [$elo1, $elo2] = EloService::calculateMatchElo($p1->elo, $p2->elo, $scoreNorm);

        $p1->elo = max(100, $p1->elo + $elo1);
        $p1->matches_count++;
        if ($isP1Winner) $p1->wins_count++; else $p1->losses_count++;
        $p1->updateLevelFromElo();

        $p2->elo = max(100, $p2->elo + $elo2);
        $p2->matches_count++;
        if (! $isP1Winner) $p2->wins_count++; else $p2->losses_count++;
        $p2->updateLevelFromElo();

        $match->update([
            'status' => 'finished',
            'winner_id' => $winnerId,
            'score_player1' => $isP1Winner ? 13 : 8,
            'score_player2' => $isP1Winner ? 8 : 13,
            'elo_change_p1' => $elo1,
            'elo_change_p2' => $elo2,
            'finished_at' => now(),
        ]);

        MatchmakingQueue::whereIn('user_id', [$p1->id, $p2->id])->delete();

        session()->flash('success', "Матч #{$match->id} принудительно завершен. Победа присуждена игроку ID: {$winnerId}. Elo пересчитано!");
    }

    public function extendMatchTimer(int $matchId, int $extraMinutes = 5): void
    {
        $match = GameMatch::findOrFail($matchId);
        $currentDeadline = $match->connect_deadline && $match->connect_deadline->isFuture()
            ? $match->connect_deadline
            : now();

        $match->connect_deadline = $currentDeadline->addMinutes($extraMinutes);
        $match->save();

        session()->flash('success', "Таймер захода для матча #{$match->id} продлен на {$extraMinutes} мин.");
    }

    /* -------------------------------------------------------------------------- */
    /* 3. MAP POOL MANAGEMENT ACTIONS                                             */
    /* -------------------------------------------------------------------------- */

    public function toggleMap(int $mapId): void
    {
        $map = GameMap::findOrFail($mapId);
        $map->is_active = ! $map->is_active;
        $map->save();

        $status = $map->is_active ? 'включена в соревновательный маппул' : 'отключена из маппула';
        session()->flash('success', "Карта {$map->name} {$status}.");
    }

    public function forceServerMap(string $mapCode): void
    {
        $rcon = new RconService($this->serverIp, $this->serverPort, $this->rconPassword, 3.0);
        if ($rcon->connect()) {
            $rcon->execute("changelevel {$mapCode}");
            $this->addConsoleOutput("changelevel {$mapCode}", "Карта на CS2 сервере успешно изменена на {$mapCode}!");
            session()->flash('success', "Карта на игровом сервере успешно изменена на {$mapCode}!");
        } else {
            session()->flash('error', "Не удалось отправить RCON команду на сервер. Проверьте пароль.");
        }
    }

    /* -------------------------------------------------------------------------- */
    /* 4. RCON CONSOLE ACTIONS                                                    */
    /* -------------------------------------------------------------------------- */

    public function executeRconCommand(?string $cmd = null): void
    {
        $command = trim($cmd ?: $this->rconInput);
        if (empty($command)) {
            return;
        }

        $rcon = new RconService($this->serverIp, $this->serverPort, $this->rconPassword, 4.0);
        if ($rcon->connect()) {
            $output = $rcon->execute($command);
            $this->addConsoleOutput($command, $output ?: '[OK] Команда успешно принята сервером.');
            $rcon->disconnect();
        } else {
            $this->addConsoleOutput($command, '[ERROR] Ошибка подключения к CS2 RCON серверу.');
        }

        $this->rconInput = '';
    }

    private function addConsoleOutput(string $cmd, string $response): void
    {
        array_unshift($this->consoleHistory, [
            'time' => now()->format('H:i:s'),
            'command' => $cmd,
            'response' => $response,
        ]);

        if (count($this->consoleHistory) > 30) {
            array_pop($this->consoleHistory);
        }
    }

    public function clearConsoleHistory(): void
    {
        $this->consoleHistory = [];
    }

    /* -------------------------------------------------------------------------- */
    /* 5. SERVER CONFIG ACTIONS                                                   */
    /* -------------------------------------------------------------------------- */

    public function testConnection(): void
    {
        $this->serverConsoleOutput = null;
        $this->connectionStatus = 'Подключение к серверу CS2...';

        $rcon = new RconService($this->serverIp, $this->serverPort, $this->rconPassword, 3.0);

        if ($rcon->connect()) {
            $this->isConnected = true;
            $this->connectionStatus = 'Успешно! RCON подключен к серверу (MatchZy webhook настроен).';
            $rcon->execute('matchzy_remote_log_url "http://45.140.19.240/api/matchzy/events"');
            $output = $rcon->execute('status');
            $this->serverConsoleOutput = $output ?: 'Сервер ответил (команда выполнена).';
            $this->addConsoleOutput('status', $output ?: 'OK');
            $rcon->disconnect();
        } else {
            $this->isConnected = false;
            $this->connectionStatus = 'Не удалось подключиться. Проверьте IP, порт и rcon_password в server.cfg.';
        }
    }

    public function saveSettings(): void
    {
        config([
            'services.cs2.server_ip' => $this->serverIp,
            'services.cs2.server_port' => $this->serverPort,
            'services.cs2.rcon_password' => $this->rconPassword,
        ]);

        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $content = file_get_contents($envPath);
            $this->updateOrAppendEnv($content, 'CS2_SERVER_IP', $this->serverIp);
            $this->updateOrAppendEnv($content, 'CS2_SERVER_PORT', (string) $this->serverPort);
            $this->updateOrAppendEnv($content, 'CS2_SERVER_RCON', $this->rconPassword);
            file_put_contents($envPath, $content);
        }

        session()->flash('success', 'Настройки сервера успешно сохранены!');
    }

    private function updateOrAppendEnv(string &$content, string $key, string $value): void
    {
        if (preg_match("/^{$key}=/m", $content)) {
            $content = preg_replace("/^{$key}=.*/m", "{$key}=\"{$value}\"", $content);
        } else {
            $content .= "\n{$key}=\"{$value}\"";
        }
    }

    /* -------------------------------------------------------------------------- */
    /* RENDER METHOD                                                              */
    /* -------------------------------------------------------------------------- */

    public function render()
    {
        // 1. Metrics for overview
        $totalUsers = User::count();
        $bannedUsersCount = User::whereNotNull('banned_until')->where('banned_until', '>', now())->count();
        $activeMatchesCount = GameMatch::whereIn('status', ['ready_check', 'voting', 'ready', 'in_progress'])->count();
        $finishedMatchesCount = GameMatch::where('status', 'finished')->count();

        // 2. Players query
        $playersQuery = User::query();
        if (! empty(trim($this->searchPlayer))) {
            $q = trim($this->searchPlayer);
            $playersQuery->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('steam_id', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }
        if ($this->playerFilter === 'banned') {
            $playersQuery->whereNotNull('banned_until')->where('banned_until', '>', now());
        } elseif ($this->playerFilter === 'admin') {
            $playersQuery->where('is_admin', true);
        } elseif ($this->playerFilter === 'bot') {
            $playersQuery->where('is_bot', true);
        }
        $players = $playersQuery->orderBy('elo', 'desc')->paginate(15);

        // 3. Active matches
        $activeMatches = GameMatch::with(['player1', 'player2', 'map'])
            ->whereIn('status', ['ready_check', 'voting', 'ready', 'in_progress'])
            ->latest()
            ->get();

        // 4. Recent finished matches
        $recentMatches = GameMatch::with(['player1', 'player2', 'map', 'winner', 'forfeitedUser'])
            ->where('status', 'finished')
            ->latest('finished_at')
            ->take(10)
            ->get();

        // 5. Maps
        $maps = GameMap::orderBy('id')->get();

        // 6. Currently banned users list
        $bannedUsers = User::whereNotNull('banned_until')
            ->where('banned_until', '>', now())
            ->orderBy('banned_until', 'desc')
            ->get();

        return view('livewire.server-settings', [
            'totalUsers' => $totalUsers,
            'bannedUsersCount' => $bannedUsersCount,
            'activeMatchesCount' => $activeMatchesCount,
            'finishedMatchesCount' => $finishedMatchesCount,
            'players' => $players,
            'activeMatches' => $activeMatches,
            'recentMatches' => $recentMatches,
            'maps' => $maps,
            'bannedUsers' => $bannedUsers,
        ]);
    }
}
