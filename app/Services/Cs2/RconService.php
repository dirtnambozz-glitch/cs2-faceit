<?php

namespace App\Services\Cs2;

use Exception;

class RconService
{
    private const SERVERDATA_AUTH = 3;
    private const SERVERDATA_AUTH_RESPONSE = 2;
    private const SERVERDATA_EXECCOMMAND = 2;
    private const SERVERDATA_RESPONSE_VALUE = 0;

    /** @var resource|null */
    private $socket = null;
    private bool $authenticated = false;
    private int $packetId = 1;

    public function __construct(
        private ?string $host = null,
        private ?int $port = null,
        private ?string $password = null,
        private float $timeout = 2.5
    ) {
        if ($this->host === null) {
            $this->host = function_exists('config') ? config('services.cs2.server_ip') : env('CS2_SERVER_IP');
        }
        if ($this->port === null) {
            $this->port = (int) (function_exists('config') ? config('services.cs2.server_port', 27015) : env('CS2_SERVER_PORT', 27574));
        }
        if ($this->password === null) {
            $this->password = function_exists('config') ? config('services.cs2.rcon_password') : env('CS2_SERVER_RCON');
        }
    }

    public function connect(): bool
    {
        if (empty($this->host) || empty($this->password)) {
            return false;
        }

        $errorNum = 0;
        $errorStr = '';
        $this->socket = @fsockopen($this->host, $this->port, $errorNum, $errorStr, $this->timeout);

        if (! $this->socket) {
            return false;
        }

        stream_set_timeout($this->socket, (int) $this->timeout);

        // Authenticate
        $this->authPacketId = ++$this->packetId;
        $authPacket = $this->createPacketWithId($this->authPacketId, self::SERVERDATA_AUTH, $this->password);
        fwrite($this->socket, $authPacket);

        while (! feof($this->socket)) {
            $response = $this->readPacket();
            if (! $response) {
                break;
            }
            if ($response['type'] === self::SERVERDATA_AUTH_RESPONSE) {
                if ($response['id'] === $this->authPacketId) {
                    $this->authenticated = true;
                    return true;
                } else {
                    $this->disconnect();
                    return false;
                }
            }
        }

        $this->disconnect();
        return false;
    }

    public function execute(string $command): ?string
    {
        if (! $this->socket && ! $this->connect()) {
            return null;
        }

        $packet = $this->createPacket(self::SERVERDATA_EXECCOMMAND, $command);
        fwrite($this->socket, $packet);

        $response = $this->readPacket();
        return $response ? $response['body'] : null;
    }

    public function disconnect(): void
    {
        if ($this->socket) {
            @fclose($this->socket);
            $this->socket = null;
            $this->authenticated = false;
        }
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    private function createPacket(int $type, string $body): string
    {
        return $this->createPacketWithId(++$this->packetId, $type, $body);
    }

    private function createPacketWithId(int $id, int $type, string $body): string
    {
        $size = strlen($body) + 10; // 4 (id) + 4 (type) + body + 2 (null bytes)

        return pack('VVVa*xx', $size, $id, $type, $body);
    }

    private function readPacket(): ?array
    {
        if (! $this->socket) {
            return null;
        }

        $header = fread($this->socket, 4);
        if (strlen($header) < 4) {
            return null;
        }

        $unpacked = unpack('Vsize', $header);
        $size = $unpacked['size'] ?? 0;

        if ($size < 10) {
            return null;
        }

        $data = '';
        $remaining = $size;
        while ($remaining > 0 && ! feof($this->socket)) {
            $chunk = fread($this->socket, min($remaining, 4096));
            if ($chunk === false || $chunk === '') {
                break;
            }
            $data .= $chunk;
            $remaining -= strlen($chunk);
        }

        if (strlen($data) < 8) {
            return null;
        }

        $headerData = unpack('Vid/Vtype', substr($data, 0, 8));
        $body = substr($data, 8, -2); // strip terminating null bytes

        return [
            'id' => $headerData['id'],
            'type' => $headerData['type'],
            'body' => $body,
        ];
    }
}
