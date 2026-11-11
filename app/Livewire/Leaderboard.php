<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Leaderboard extends Component
{
    use WithPagination;

    public string $search = '';
    public string $levelFilter = 'all';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingLevelFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::query()
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->levelFilter !== 'all', function ($q) {
                $q->where('level', (int) $this->levelFilter);
            })
            ->orderByDesc('elo')
            ->orderByDesc('wins_count');

        $users = $query->paginate(15);

        return view('livewire.leaderboard', [
            'users' => $users,
        ]);
    }
}
