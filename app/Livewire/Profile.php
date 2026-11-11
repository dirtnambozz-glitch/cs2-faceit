<?php

namespace App\Livewire;

use App\Models\GameMatch;
use App\Models\User;
use App\Services\SteamService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Profile extends Component
{
    public ?User $user = null;
    public string $name = '';
    public string $steamIdInput = '';
    public ?int $viewUserId = null;

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->user = User::findOrFail($id);
            $this->viewUserId = $id;
        } else {
            $this->user = Auth::user();
            if (! $this->user) {
                $this->redirectRoute('login');
                return;
            }
        }

        $this->name = $this->user->name;
        $this->steamIdInput = $this->user->steam_id ?? '';
    }

    public function updateProfile(): void
    {
        if (! Auth::check() || Auth::id() !== $this->user->id) {
            return;
        }

        $this->validate([
            'name' => 'required|min:3|max:30|unique:users,name,' . $this->user->id,
            'steamIdInput' => 'nullable|min:17',
        ]);

        $steamId = null;
        if (! empty($this->steamIdInput)) {
            $steamId = SteamService::normalizeSteamId($this->steamIdInput);
            if (! $steamId) {
                $this->addError('steamIdInput', 'Неверный формат SteamID64 (17 цифр).');
                return;
            }

            $alreadyLinked = User::where('steam_id', $steamId)
                ->where('id', '!=', $this->user->id)
                ->exists();

            if ($alreadyLinked) {
                $this->addError('steamIdInput', 'Этот Steam ID уже привязан к другому профилю.');
                return;
            }
        }

        $avatar = $this->user->avatar;
        if ($steamId) {
            $steamProfile = SteamService::fetchSteamProfile($steamId);
            if (!empty($steamProfile['avatar'])) {
                $avatar = $steamProfile['avatar'];
            }
        }

        $this->user->update([
            'name' => $this->name,
            'steam_id' => $steamId,
            'avatar' => $avatar,
        ]);

        session()->flash('status', 'Профиль успешно сохранен!');
    }

    public function render()
    {
        $matches = GameMatch::where(function ($q) {
            $q->where('player1_id', $this->user->id)
              ->orWhere('player2_id', $this->user->id);
        })
        ->where('status', 'finished')
        ->with(['player1', 'player2', 'map', 'winner'])
        ->latest('finished_at')
        ->paginate(10);

        return view('livewire.profile', [
            'matches' => $matches,
            'isOwnProfile' => Auth::id() === $this->user->id,
        ]);
    }
}
