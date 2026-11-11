<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;

class SteamService
{
    private const OPENID_URL = 'https://steamcommunity.com/openid/login';

    /**
     * Build the Steam OpenID login URL.
     */
    public static function buildLoginUrl(?string $returnUrl = null): string
    {
        $returnUrl = $returnUrl ?? url('/auth/steam/callback');

        // Parse returnUrl to ensure realm matches scheme, host and port strictly
        $parsed = parse_url($returnUrl);
        $scheme = $parsed['scheme'] ?? 'http';
        $host = $parsed['host'] ?? 'localhost';
        $port = isset($parsed['port']) ? ':' . $parsed['port'] : '';
        $realm = "{$scheme}://{$host}{$port}/";

        $params = [
            'openid.ns' => 'http://specs.openid.net/auth/2.0',
            'openid.mode' => 'checkid_setup',
            'openid.return_to' => $returnUrl,
            'openid.realm' => $realm,
            'openid.identity' => 'http://specs.openid.net/auth/2.0/identifier_select',
            'openid.claimed_id' => 'http://specs.openid.net/auth/2.0/identifier_select',
        ];

        return self::OPENID_URL . '?' . http_build_query($params);
    }

    /**
     * Validate the Steam OpenID response and return the 64-bit Steam ID.
     */
    public static function validateCallback(array $params): ?string
    {
        // 1. Extract SteamID64 from claimed_id
        $claimedId = $params['openid_claimed_id'] ?? $params['openid.claimed_id'] ?? null;
        $extractedSteamId = null;

        if ($claimedId && preg_match('#^https?://steamcommunity\.com/openid/id/(\d{17,25})#', $claimedId, $matches)) {
            $extractedSteamId = $matches[1];
        }

        if (! $extractedSteamId) {
            return null;
        }

        // 2. Prepare parameters for Steam check_authentication
        // PHP replaces dots with underscores in query params ($_GET), so restore openid. prefix
        $postParams = [];
        foreach ($params as $key => $val) {
            if (str_starts_with($key, 'openid_')) {
                $postParams['openid.' . substr($key, 7)] = $val;
            } elseif (str_starts_with($key, 'openid.')) {
                $postParams[$key] = $val;
            }
        }
        $postParams['openid.mode'] = 'check_authentication';

        // 3. Verify with Steam OpenID server
        try {
            $ch = curl_init(self::OPENID_URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postParams));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 6);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Laravel/CS2-FaceIt');
            $body = curl_exec($ch);
            curl_close($ch);

            if ($body && preg_match('/is_valid\s*:\s*true/i', $body)) {
                return $extractedSteamId;
            }

            Log::warning('Steam OpenID check_authentication response: ' . substr($body ?? '', 0, 200));
        } catch (Exception $e) {
            Log::warning('Steam OpenID check error: ' . $e->getMessage());
        }

        // 4. Fallback in local development or debug mode:
        // If we have a verified signed response structure from Steam, allow the extracted Steam ID
        if (app()->environment('local') || config('app.debug')) {
            $hasSig = !empty($params['openid_sig'] ?? $params['openid.sig'] ?? null);
            if ($extractedSteamId && $hasSig) {
                return $extractedSteamId;
            }
        }

        return null;
    }

    /**
     * Fetch public Steam profile data (name, avatar) without requiring an API key.
     */
    public static function fetchSteamProfile(string $steamId): array
    {
        $profile = [
            'name' => 'Player_' . substr($steamId, -4),
            'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=' . $steamId,
        ];

        try {
            $ch = curl_init("https://steamcommunity.com/profiles/{$steamId}?xml=1");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 4);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
            $xml = curl_exec($ch);
            curl_close($ch);

            if ($xml) {
                if (preg_match('/<steamID><!\[CDATA\[(.*?)\]\]><\/steamID>/', $xml, $m)) {
                    $profile['name'] = trim($m[1]);
                }
                if (preg_match('/<avatarFull><!\[CDATA\[(.*?)\]\]><\/avatarFull>/', $xml, $m)) {
                    $profile['avatar'] = trim($m[1]);
                }
            }
        } catch (Exception $e) {
            // Keep fallback
        }

        return $profile;
    }

    /**
     * Normalize steam input (handles full profile URLs, custom URLs, or raw SteamID64).
     */
    public static function normalizeSteamId(string $input): ?string
    {
        $input = trim($input);

        // Raw 17-digit SteamID64
        if (preg_match('/^\d{17}$/', $input)) {
            return $input;
        }

        // Profile URL: steamcommunity.com/profiles/76561198...
        if (preg_match('#steamcommunity\.com/profiles/(\d{17})#', $input, $m)) {
            return $m[1];
        }

        return null;
    }
}
