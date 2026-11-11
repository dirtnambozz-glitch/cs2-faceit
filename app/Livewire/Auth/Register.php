<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Services\EloService;
use App\Services\SteamService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $steamIdInput = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|min:3|max:30|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'steamIdInput' => 'required|min:17',
            'password' => 'required|min:6|confirmed',
        ];
    }

    public function register(): void
    {
        $this->validate();

        $steamId = SteamService::normalizeSteamId($this->steamIdInput);

        if (! $steamId) {
            $this->addError('steamIdInput', 'Укажите корректный 17-значный SteamID64 (например, 76561198000000001) или ссылку на профиль.');
            return;
        }

        if (User::where('steam_id', $steamId)->exists()) {
            $this->addError('steamIdInput', 'Этот Steam ID уже привязан к другому аккаунту.');
            return;
        }

        $steamProfile = SteamService::fetchSteamProfile($steamId);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'steam_id' => $steamId,
            'password' => Hash::make($this->password),
            'elo' => 1000,
            'level' => EloService::calculateLevel(1000),
            'avatar' => $steamProfile['avatar'] ?? ('https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($this->name)),
        ]);

        Auth::login($user, true);

        $this->redirectRoute('home');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
