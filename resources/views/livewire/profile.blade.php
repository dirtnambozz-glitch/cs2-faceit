<div class="max-w-5xl mx-auto space-y-8">
    <!-- Profile Hero Card -->
    <div class="bg-gradient-to-r from-neutral-900 via-faceit-card to-neutral-900 border border-faceit-border rounded-2xl p-8 shadow-2xl relative overflow-hidden">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
            <!-- Avatar with Level Ring -->
            <div class="relative">
                <img src="{{ $user->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . $user->name }}" class="w-28 h-28 rounded-2xl border-2 border-faceit-border bg-neutral-900 shadow-xl" alt="Avatar">
                <div class="absolute -bottom-2 -right-2 w-9 h-9 rounded-full flex items-center justify-center font-faceit font-bold text-base shadow-lg {{ $user->level_badge_color }}">
                    {{ $user->level }}
                </div>
            </div>

            <!-- Profile Info -->
            <div class="flex-1 text-center sm:text-left space-y-2">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                    <h1 class="text-3xl font-faceit font-bold text-white tracking-wide">
                        {{ $user->name }}
                    </h1>
                    @if($user->is_bot)
                        <span class="inline-block text-[10px] uppercase font-bold bg-neutral-800 text-neutral-400 px-2 py-0.5 rounded w-max mx-auto sm:mx-0">BOT</span>
                    @endif
                </div>

                <!-- Steam ID Pill -->
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                    @if($user->steam_id)
                        <span class="inline-flex items-center space-x-1.5 bg-neutral-900 border border-neutral-700 px-3 py-1 rounded-full text-xs font-mono text-emerald-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>SteamID64: {{ $user->steam_id }}</span>
                        </span>
                        <a href="https://steamcommunity.com/profiles/{{ $user->steam_id }}" target="_blank" class="text-xs text-faceit-orange hover:underline">
                            Открыть в Steam ↗
                        </a>
                    @else
                        <span class="inline-flex items-center space-x-1.5 bg-neutral-900 border border-amber-600/40 px-3 py-1 rounded-full text-xs text-amber-400">
                            <span>Steam не привязан</span>
                        </span>
                        @if($isOwnProfile)
                            <a href="{{ route('auth.steam') }}" class="text-xs text-faceit-orange hover:underline font-semibold">
                                Привязать в 1 клик через Steam ↗
                            </a>
                        @endif
                    @endif
                </div>

                <!-- Stats summary -->
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-6 pt-3 text-sm">
                    <div>
                        <span class="text-neutral-400 text-xs uppercase tracking-wider block">Рейтинг</span>
                        <span class="font-faceit font-bold text-2xl text-white">{{ $user->elo }} <span class="text-faceit-orange text-sm">ELO</span></span>
                    </div>
                    <div class="border-l border-faceit-border pl-6">
                        <span class="text-neutral-400 text-xs uppercase tracking-wider block">Винрейт</span>
                        <span class="font-faceit font-bold text-2xl text-white">{{ $user->winrate }}%</span>
                    </div>
                    <div class="border-l border-faceit-border pl-6">
                        <span class="text-neutral-400 text-xs uppercase tracking-wider block">Матчи</span>
                        <span class="font-faceit font-bold text-2xl text-white">{{ $user->matches_count }}</span>
                    </div>
                    <div class="border-l border-faceit-border pl-6">
                        <span class="text-neutral-400 text-xs uppercase tracking-wider block">Победы / Поражения</span>
                        <span class="font-faceit font-bold text-xl text-emerald-400">{{ $user->wins_count }} <span class="text-neutral-500 text-sm">/</span> <span class="text-red-400">{{ $user->losses_count }}</span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status message -->
    @if(session()->has('status'))
        <div class="p-4 bg-emerald-500/20 border border-emerald-500/40 rounded-xl text-emerald-400 text-sm font-semibold flex items-center space-x-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <!-- Profile Edit Form (For Account Owner) -->
    @if($isOwnProfile)
    <div class="bg-faceit-card border border-faceit-border rounded-2xl p-6 shadow-xl">
        <h2 class="text-xl font-faceit font-bold text-white uppercase tracking-wider mb-4">
            Настройки профиля
        </h2>

        <form wire:submit.prevent="updateProfile" class="space-y-4 max-w-xl">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-neutral-400 mb-1">
                    Игровой никнейм
                </label>
                <input 
                    type="text" 
                    wire:model="name" 
                    class="w-full bg-neutral-900 border border-neutral-700 text-white text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-faceit-orange">
                @error('name') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="text-xs font-semibold uppercase tracking-wider text-neutral-400">
                        SteamID64 (или ссылка на аккаунт)
                    </label>
                    <a href="{{ route('auth.steam') }}" class="text-[11px] text-faceit-orange hover:underline font-semibold">
                        Привязать через Steam OpenID ↗
                    </a>
                </div>
                <input 
                    type="text" 
                    wire:model="steamIdInput" 
                    placeholder="76561198000000001"
                    class="w-full bg-neutral-900 border border-neutral-700 text-white font-mono text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-faceit-orange">
                @error('steamIdInput') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                <p class="text-[11px] text-neutral-500 mt-1">
                    Для подключения к CS2 серверу Incloudgame ваш Steam ID должен совпадать с тем, с которого вы заходите в игру.
                </p>
            </div>

            <button 
                type="submit" 
                class="px-6 py-2.5 bg-faceit-orange hover:bg-faceit-orangeHover text-white font-faceit font-bold text-sm uppercase tracking-wider rounded-xl shadow-lg shadow-faceit-orange/20 transition cursor-pointer">
                Сохранить изменения
            </button>
        </form>
    </div>
    @endif

    <!-- Match History -->
    <div class="bg-faceit-card border border-faceit-border rounded-2xl p-6 shadow-xl">
        <h2 class="text-xl font-faceit font-bold text-white uppercase tracking-wider mb-6">
            История сыгранных матчей
        </h2>

        @if($matches->isEmpty())
            <div class="text-center py-12 text-neutral-500 text-sm">
                Матчей пока не найдено.
            </div>
        @else
            <div class="divide-y divide-faceit-border">
                @foreach($matches as $m)
                    @php
                        $isP1 = ($m->player1_id === $user->id);
                        $isWin = ($m->winner_id === $user->id);
                        $opp = $isP1 ? $m->player2 : $m->player1;
                        $myScore = $isP1 ? $m->score_player1 : $m->score_player2;
                        $oppScore = $isP1 ? $m->score_player2 : $m->score_player1;
                        $eloDelta = $isP1 ? $m->elo_change_p1 : $m->elo_change_p2;
                    @endphp
                    <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-neutral-800/40 px-3 rounded-lg transition">
                        <div class="flex items-center space-x-4">
                            <span class="w-20 text-center py-1 rounded text-xs font-bold uppercase tracking-wider {{ $isWin ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' }}">
                                {{ $isWin ? 'Победа' : 'Поражение' }}
                            </span>

                            <div>
                                <p class="text-sm font-semibold text-white">
                                    {{ $m->map ? $m->map->name : 'CS2 Map' }}
                                </p>
                                <p class="text-xs text-neutral-400">
                                    {{ $m->finished_at ? $m->finished_at->diffForHumans() : $m->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-6">
                            <span class="font-mono text-lg font-bold text-white">
                                {{ $myScore }} : {{ $oppScore }}
                            </span>

                            <div class="flex items-center space-x-2">
                                <span class="text-xs text-neutral-400">vs</span>
                                <span class="text-sm font-medium text-neutral-200">{{ $opp ? $opp->name : 'Оппонент' }}</span>
                                @if($opp)
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center font-faceit text-xs font-bold {{ $opp->level_badge_color }}">
                                        {{ $opp->level }}
                                    </span>
                                @endif
                            </div>

                            <div class="w-16 text-right">
                                @if($eloDelta !== null)
                                    <span class="font-mono font-bold text-sm {{ $eloDelta >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                        {{ $eloDelta >= 0 ? "+{$eloDelta}" : $eloDelta }}
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('match.lobby', ['uuid' => $m->uuid]) }}" class="px-3 py-1.5 bg-neutral-800 hover:bg-neutral-700 text-neutral-300 hover:text-white rounded text-xs font-semibold transition">
                                Матч
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($matches->hasPages())
                <div class="pt-4 border-t border-faceit-border">
                    {{ $matches->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
