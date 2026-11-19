<div class="max-w-5xl mx-auto space-y-6">
    <!-- Profile Header Card (Esports Player Profile) -->
    <div class="bg-[#0B0E14] border border-white/[0.07] rounded-3xl p-6 sm:p-8 shadow-sm">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
            <!-- Avatar with Level Shield -->
            <div class="relative shrink-0">
                <img src="{{ $user->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . $user->name }}" class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl border border-white/[0.1] bg-neutral-900 object-cover" alt="Avatar">
                <div class="absolute -bottom-2 -right-2 w-9 h-9 rounded-xl flex items-center justify-center font-heading font-black text-sm bg-gradient-to-br from-[#FF5500] to-[#D44400] text-white shadow-sm border border-white/[0.1]">
                    {{ $user->level }}
                </div>
            </div>

            <!-- Profile Info & Metrics -->
            <div class="flex-1 text-center sm:text-left space-y-3 w-full">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                    <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-white tracking-tight">
                        {{ $user->name }}
                    </h1>
                    @if($user->isAdmin())
                        <span class="inline-block text-[10px] uppercase font-mono font-bold bg-white/[0.08] text-white px-2 py-0.5 rounded w-max mx-auto sm:mx-0">ADMIN</span>
                    @endif
                    @if($user->is_bot)
                        <span class="inline-block text-[10px] uppercase font-mono font-semibold bg-white/[0.04] text-neutral-400 border border-white/[0.08] px-2 py-0.5 rounded w-max mx-auto sm:mx-0">AI BOT</span>
                    @endif
                </div>

                <!-- Steam ID Link -->
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                    @if($user->steam_id)
                        <span class="inline-flex items-center space-x-1.5 bg-emerald-500/10 border border-emerald-500/25 px-2.5 py-1 rounded-lg text-xs font-mono text-emerald-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            <span>SteamID64: {{ $user->steam_id }}</span>
                        </span>
                        <a href="https://steamcommunity.com/profiles/{{ $user->steam_id }}" target="_blank" class="text-xs text-neutral-400 hover:text-white font-mono hover:underline">
                            Открыть профиль Steam ↗
                        </a>
                    @else
                        <span class="inline-flex items-center space-x-1.5 bg-white/[0.04] border border-white/[0.08] px-2.5 py-1 rounded-lg text-xs text-neutral-400 font-mono">
                            <span>Steam не привязан</span>
                        </span>
                        @if($isOwnProfile)
                            <a href="{{ route('auth.steam') }}" class="text-xs text-[#FF5500] hover:underline font-semibold font-mono">
                                Привязать в 1 клик через Steam OpenID ↗
                            </a>
                        @endif
                    @endif
                </div>

                <!-- Key Metrics Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 border-t border-white/[0.06]">
                    <div class="bg-[#0E131C] p-3 rounded-xl border border-white/[0.05]">
                        <span class="text-neutral-400 text-[11px] font-mono uppercase tracking-wider block">Рейтинг</span>
                        <span class="font-mono font-bold text-xl text-white tabular-nums">{{ $user->elo }} <span class="text-neutral-500 text-xs font-normal">ELO</span></span>
                    </div>
                    <div class="bg-[#0E131C] p-3 rounded-xl border border-white/[0.05]">
                        <span class="text-neutral-400 text-[11px] font-mono uppercase tracking-wider block">Винрейт</span>
                        <span class="font-mono font-bold text-xl text-white tabular-nums">{{ $user->winrate }}%</span>
                    </div>
                    <div class="bg-[#0E131C] p-3 rounded-xl border border-white/[0.05]">
                        <span class="text-neutral-400 text-[11px] font-mono uppercase tracking-wider block">Матчей</span>
                        <span class="font-mono font-bold text-xl text-white tabular-nums">{{ $user->matches_count }}</span>
                    </div>
                    <div class="bg-[#0E131C] p-3 rounded-xl border border-white/[0.05]">
                        <span class="text-neutral-400 text-[11px] font-mono uppercase tracking-wider block">Побед / Поражений</span>
                        <span class="font-mono font-semibold text-lg text-emerald-400 tabular-nums">{{ $user->wins_count }} <span class="text-neutral-600 text-sm">/</span> <span class="text-neutral-400">{{ $user->losses_count }}</span></span>
                    </div>
                </div>

                <!-- Admin Ban Control Bar -->
                @if(auth()->check() && auth()->user()->isAdmin())
                    <div class="mt-4 pt-3 border-t border-white/[0.06] flex flex-wrap items-center justify-between gap-3 bg-[#0E131C] p-3.5 rounded-xl border border-white/[0.06]">
                        <div class="flex items-center space-x-2 text-xs font-mono">
                            <span class="text-neutral-400 font-semibold">Администрирование:</span>
                            @if($user->isBanned())
                                <span class="text-red-400 font-semibold bg-red-500/10 px-2 py-0.5 rounded border border-red-500/25">
                                    БАН до {{ $user->banned_until->format('H:i d.m') }} ({{ $user->ban_reason }})
                                </span>
                            @else
                                <span class="text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/25">
                                    АКТИВЕН
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center space-x-1.5">
                            @if($user->isBanned())
                                <button 
                                    wire:click="unban" 
                                    class="px-3 py-1 bg-emerald-500 hover:bg-emerald-400 text-white font-medium text-xs rounded-lg transition cursor-pointer">
                                    Снять бан
                                </button>
                            @else
                                <button 
                                    wire:click="adminBan(15)" 
                                    class="px-2.5 py-1 bg-red-600/80 hover:bg-red-600 text-white font-mono text-xs rounded-lg transition cursor-pointer">
                                    Бан 15м
                                </button>
                                <button 
                                    wire:click="adminBan(60)" 
                                    class="px-2.5 py-1 bg-red-700/80 hover:bg-red-700 text-white font-mono text-xs rounded-lg transition cursor-pointer">
                                    Бан 1ч
                                </button>
                                <button 
                                    wire:click="adminBan(1440)" 
                                    class="px-2.5 py-1 bg-red-900/80 hover:bg-red-800 text-white font-mono text-xs rounded-lg transition cursor-pointer">
                                    Бан 24ч
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Status message -->
    @if(session()->has('status'))
        <div class="p-3.5 bg-emerald-500/10 border border-emerald-500/25 rounded-xl text-emerald-400 text-xs font-medium flex items-center space-x-2">
            <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <!-- Profile Edit Form (For Account Owner) -->
    @if($isOwnProfile)
    <div class="bg-[#0B0E14] border border-white/[0.07] rounded-2xl p-6 shadow-sm">
        <h2 class="text-base font-heading font-bold text-white tracking-tight mb-4 pb-3 border-b border-white/[0.06]">
            Настройки аккаунта
        </h2>

        <form wire:submit.prevent="updateProfile" class="space-y-4 max-w-lg">
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-neutral-400 mb-1">
                    Игровой никнейм
                </label>
                <input 
                    type="text" 
                    wire:model="name" 
                    class="w-full bg-[#0E131C] border border-white/[0.08] focus:border-[#FF5500] text-white text-sm px-3.5 py-2.5 rounded-xl focus:outline-none transition-colors">
                @error('name') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="text-xs font-mono uppercase tracking-wider text-neutral-400">
                        SteamID64 (или ссылка на профиль)
                    </label>
                    <a href="{{ route('auth.steam') }}" class="text-[11px] text-[#FF5500] hover:underline font-mono">
                        Привязать через Steam OpenID ↗
                    </a>
                </div>
                <input 
                    type="text" 
                    wire:model="steamIdInput" 
                    placeholder="76561198000000001"
                    class="w-full bg-[#0E131C] border border-white/[0.08] focus:border-[#FF5500] text-white font-mono text-sm px-3.5 py-2.5 rounded-xl focus:outline-none transition-colors">
                @error('steamIdInput') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                <p class="text-[11px] text-neutral-500 mt-1 font-mono">
                    Ваш Steam ID используется сервером Incloudgame для распознавания вашего игрового профиля.
                </p>
            </div>

            <button 
                type="submit" 
                class="px-6 py-2.5 bg-[#FF5500] hover:bg-[#FF6E26] text-white font-medium text-xs uppercase tracking-wider rounded-xl transition cursor-pointer">
                Сохранить настройки
            </button>
        </form>
    </div>
    @endif

    <!-- Match History -->
    <div class="bg-[#0B0E14] border border-white/[0.07] rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-white/[0.06] flex items-center justify-between">
            <h2 class="text-base font-heading font-bold text-white tracking-tight">
                История матчей игрока
            </h2>
            <span class="text-xs font-mono text-neutral-500">ВСЕ ДУЭЛИ</span>
        </div>

        @if($matches->isEmpty())
            <div class="text-center py-12 text-neutral-500 text-sm font-mono">
                Сыгранных матчей пока нет.
            </div>
        @else
            <div class="divide-y divide-white/[0.04]">
                @foreach($matches as $m)
                    @php
                        $isP1 = ($m->player1_id === $user->id);
                        $isWin = ($m->winner_id === $user->id);
                        $opp = $isP1 ? $m->player2 : $m->player1;
                        $myScore = $isP1 ? $m->score_player1 : $m->score_player2;
                        $oppScore = $isP1 ? $m->score_player2 : $m->score_player1;
                        $eloDelta = $isP1 ? $m->elo_change_p1 : $m->elo_change_p2;
                    @endphp
                    <div class="p-4 hover:bg-white/[0.02] flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-colors">
                        <div class="flex items-center space-x-3.5">
                            <span class="w-24 text-center py-1 rounded-md text-xs font-mono font-bold uppercase tracking-wider {{ $isWin ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/25' : 'bg-red-500/10 text-red-400 border border-red-500/25' }}">
                                {{ $isWin ? 'ПОБЕДА' : 'ПОРАЖЕНИЕ' }}
                            </span>

                            <div>
                                <div class="text-sm font-semibold text-white">
                                    {{ $m->map ? $m->map->name : 'CS2 Map' }}
                                </div>
                                <div class="text-xs font-mono text-neutral-500">
                                    {{ $m->finished_at ? $m->finished_at->diffForHumans() : $m->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4 sm:space-x-6 justify-between sm:justify-end">
                            <div class="bg-white/[0.04] px-3 py-1 rounded-lg border border-white/[0.06] font-mono font-bold text-sm text-white tabular-nums">
                                {{ $myScore }} : {{ $oppScore }}
                            </div>

                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-mono text-neutral-500">vs</span>
                                <span class="text-sm font-medium text-neutral-300">{{ $opp ? $opp->name : 'Оппонент' }}</span>
                            </div>

                            <div class="w-14 text-right font-mono font-bold text-sm tabular-nums">
                                @if($eloDelta !== null)
                                    <span class="{{ $eloDelta >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                        {{ $eloDelta >= 0 ? "+{$eloDelta}" : $eloDelta }}
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('match.lobby', ['uuid' => $m->uuid]) }}" class="px-3 py-1.5 bg-white/[0.04] hover:bg-white/[0.08] text-neutral-300 hover:text-white rounded-lg text-xs font-medium border border-white/[0.08] transition">
                                Комната
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-4 border-t border-white/[0.06] bg-[#07090D]">
                {{ $matches->links() }}
            </div>
        @endif
    </div>
</div>
