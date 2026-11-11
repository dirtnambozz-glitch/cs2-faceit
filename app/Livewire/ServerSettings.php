<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\Cs2\RconService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ServerSettings extends Component
{
    public string $serverIp = '';
    public int $serverPort = 27015;
    public string $rconPassword = '';
    public string $connectionStatus = '';
    public ?string $serverConsoleOutput = null;
    public bool $isConnected = false;

    // Admin Player & Ban Management
    public string $searchQuery = '';
    public int $manualBanMinutes = 15;
    public string $manualBanReason = 'Нарушение правил администратором';

    public function mount(): void
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            abort(403, 'Доступ к разделу разрешен только создателю и администраторам.');
        }

        $this->serverIp = config('services.cs2.server_ip', '89.248.236.141');
        $this->serverPort = (int) config('services.cs2.server_port', 27574);
        $this->rconPassword = config('services.cs2.rcon_password', '');
    }

    public function unbanUser(int $userId): void
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            return;
        }

        $user = User::findOrFail($userId);
        $user->banned_until = null;
        $user->ban_reason = null;
        $user->save();

        session()->flash('message', "Бан с игрока {$user->name} успешно снят!");
    }

    public function resetWarnings(int $userId): void
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            return;
        }

        $user = User::findOrFail($userId);
        $user->warnings_count = 0;
        $user->save();

        session()->flash('message', "Предупреждения игрока {$user->name} сброшены на 0!");
    }

    public function manualBanUser(int $userId): void
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            return;
        }

        $user = User::findOrFail($userId);
        $reason = trim($this->manualBanReason) ?: 'Блокировка администратором';
        $user->applyBan($this->manualBanMinutes, $reason);

        session()->flash('message', "Игрок {$user->name} заблокирован на {$this->manualBanMinutes} мин. Причина: {$reason}");
    }

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

        session()->flash('message', 'Настройки сервера успешно сохранены!');
    }

    private function updateOrAppendEnv(string &$content, string $key, string $value): void
    {
        if (preg_match("/^{$key}=/m", $content)) {
            $content = preg_replace("/^{$key}=.*/m", "{$key}=\"{$value}\"", $content);
        } else {
            $content .= "\n{$key}=\"{$value}\"";
        }
    }

    public function render()
    {
        $bannedUsers = User::whereNotNull('banned_until')
            ->where('banned_until', '>', now())
            ->orderBy('banned_until', 'desc')
            ->get();

        $searchResults = collect();
        if (strlen(trim($this->searchQuery)) >= 2) {
            $q = trim($this->searchQuery);
            $searchResults = User::where('name', 'like', "%{$q}%")
                ->orWhere('steam_id', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->take(10)
                ->get();
        }

        return view('livewire.server-settings', [
            'bannedUsers' => $bannedUsers,
            'searchResults' => $searchResults,
        ]);
    }
}
