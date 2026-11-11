<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EloService;
use App\Services\SteamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SteamAuthController extends Controller
{
    /**
     * Redirect to Steam OpenID authentication.
     */
    public function redirect(Request $request): RedirectResponse
    {
        // Build return URL dynamically using the current request host and port
        $returnUrl = $request->schemeAndHttpHost() . '/auth/steam/callback';
        $steamUrl = SteamService::buildLoginUrl($returnUrl);

        return redirect()->away($steamUrl);
    }

    /**
     * Handle the callback from Steam OpenID.
     */
    public function callback(Request $request): RedirectResponse
    {
        $steamId = SteamService::validateCallback($request->all());

        if (! $steamId) {
            return redirect()->route('login')->withErrors([
                'steam' => 'Не удалось подтвердить вход через Steam. Попробуйте войти по Steam ID напрямую.'
            ]);
        }

        return $this->loginWithSteamId($steamId);
    }

    /**
     * Direct login by SteamID64 or profile link (fallback / fast demo).
     */
    public function directLogin(Request $request): RedirectResponse
    {
        $request->validate([
            'steam_input' => ['required', 'string'],
        ], [
            'steam_input.required' => 'Укажите SteamID64 или ссылку на профиль Steam.',
        ]);

        $steamId = SteamService::normalizeSteamId($request->input('steam_input'));

        if (! $steamId) {
            return back()->withErrors([
                'steam_input' => 'Неверный формат. Введите 17-значный SteamID64 (напр. 76561198034202275) или ссылку на профиль.'
            ])->withInput();
        }

        return $this->loginWithSteamId($steamId);
    }

    /**
     * Common login or profile-binding logic for a verified or entered SteamID64.
     */
    private function loginWithSteamId(string $steamId): RedirectResponse
    {
        $steamProfile = SteamService::fetchSteamProfile($steamId);

        // If user is already logged in, bind Steam ID to their profile
        if (Auth::check()) {
            $user = Auth::user();
            $user->update([
                'steam_id' => $steamId,
                'avatar' => $steamProfile['avatar'] ?? $user->avatar,
            ]);

            return redirect()->route('profile')->with('status', 'Steam аккаунт (' . $steamProfile['name'] . ') успешно привязан!');
        }

        // Find existing user by steam_id
        $user = User::where('steam_id', $steamId)->first();

        if (! $user) {
            // Create a new user with Steam profile
            $randomPassword = Str::random(24);
            $user = User::create([
                'name' => $steamProfile['name'] ?? ('Player_' . substr($steamId, -4)),
                'email' => $steamId . '@steam.faceit.local',
                'password' => bcrypt($randomPassword),
                'steam_id' => $steamId,
                'elo' => 1000,
                'level' => EloService::calculateLevel(1000),
                'avatar' => $steamProfile['avatar'] ?? ('https://api.dicebear.com/7.x/bottts/svg?seed=' . $steamId),
            ]);
        } else {
            // Update avatar if currently default
            if ($steamProfile['avatar'] && str_contains($user->avatar ?? '', 'dicebear')) {
                $user->update(['avatar' => $steamProfile['avatar']]);
            }
        }

        Auth::login($user, true);

        return redirect()->route('home')->with('status', 'Добро пожаловать, ' . $user->name . '!');
    }
}
