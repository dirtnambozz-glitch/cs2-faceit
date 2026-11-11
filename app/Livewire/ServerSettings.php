<?php

namespace App\Livewire;

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

    public function mount(): void
    {
        $this->serverIp = config('services.cs2.server_ip', '89.248.236.141');
        $this->serverPort = (int) config('services.cs2.server_port', 27574);
        $this->rconPassword = config('services.cs2.rcon_password', '');
    }

    public function testConnection(): void
    {
        $this->serverConsoleOutput = null;
        $this->connectionStatus = 'Подключение к серверу CS2...';

        $rcon = new RconService($this->serverIp, $this->serverPort, $this->rconPassword, 3.0);

        if ($rcon->connect()) {
            $this->isConnected = true;
            $this->connectionStatus = 'Успешно! RCON подключен к серверу.';
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
        // Update in-memory / session config
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
        return view('livewire.server-settings');
    }
}
