<div class="max-w-5xl mx-auto space-y-6">
    <!-- Profile Hero Card (FACEIT Player Passport) -->
    <div class="bg-[#121622] border border-[#1F273B] rounded-2xl p-6 sm:p-8 shadow-xl relative overflow-hidden">
        <!-- Subtle Top Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-[#FF5500] to-transparent"></div>

        <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start gap-6">
            <!-- Avatar with Large Level Badge -->
            <div class="relative shrink-0">
                <img src="{{ $user->avatar_url }}" class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl border border-white/10 bg-[#0A0D14] object-cover shadow-lg" alt="{{ $user->name }}">
                <div class="absolute -bottom-2 -right-2">
                    <x-faceit-level :level="$user->level" size="lg" />
                </div>
            </div>

            <!-- Profile Info & Tags -->
            <div class="flex-1 text-center sm:text-left space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                    <h1 class="text-2xl sm:text-3xl font-faceit font-extrabold text-white tracking-wide">
                        {{ $user->name }}
                    </h1>
                    @if($user->isAdmin())
                        <span class="inline-block text-[10px] uppercase font-mono font-bold bg-[#FF5500] text-white px-2 py-0.5 rounded w-max mx-auto sm:mx-0">ADMIN</span>
                    @endif
                    @if($user->is_bot)
                        <span class="inline-block text-[10px] uppercase font-mono font-semibold bg-neutral-800 text-neutral-400 border border-neutral-700 px-2 py-0.5 rounded w-max mx-auto sm:mx-0">AI BOT</span>
                    @endif
                </div>

                <!-- Steam ID Status Capsule -->
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                    @if($user->steam_id)
                        <span class="inline-flex items-center space-x-2 bg-[#0E121B] border border-[#1F273B] px-3 py-1 rounded-lg text-xs font-mono text-emerald-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>SteamID64: {{ $user->steam_id }}</span>
                        </span>
                        <a href="https://steamcommunity.com/profiles/{{ $user->steam_id }}" target="_blank" class="text-xs text-neutral-400 hover:text-white hover:underline font-mono flex items-center space-x-1">
                            <span>Профиль Steam</span>
                            <span class="text-xs">↗</span>
                        </a>
                    @else
                        <span class="inline-flex items-center space-x-1.5 bg-amber-500/10 border border-amber-500/30 px-3 py-1 rounded-lg text-xs text-amber-400 font-mono">
                            <span>Steam не привязан</span>
                        </span>
                        @if($isOwnProfile)
                            <a href="{{ route('auth.steam') }}" class="text-xs text-[#FF5500] hover:text-[#FF6E1A] hover:underline font-semibold font-mono">
                                Привязать через Steam OpenID ↗
                            </a>
                        @endif
                    @endif
                </div>

                <!-- Stats Summary Matrix -->
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-6 pt-4 text-sm border-t border-[#1F273B]">
                    <div>
                        <span class="text-neutral-400 text-xs font-mono uppercase tracking-wider block">Рейтинг ELO</span>
                        <span class="font-faceit font-bold text-2xl text-white">{{ $user->elo }} <span class="text-[#FF5500] text-xs font-mono">ELO</span></span>
                    </div>
                    <div class="border-l border-[#1F273B] pl-6">
                        <span class="text-neutral-400 text-xs font-mono uppercase tracking-wider block">Винрейт</span>
                        <span class="font-faceit font-bold text-2xl {{ $user->winrate >= 50 ? 'text-emerald-400' : 'text-white' }}">{{ $user->winrate }}%</span>
                    </div>
                    <div class="border-l border-[#1F273B] pl-6">
                        <span class="text-neutral-400 text-xs font-mono uppercase tracking-wider block">Матчей</span>
                        <span class="font-faceit font-bold text-2xl text-white">{{ $user->matches_count }}</span>
                    </div>
                    <div class="border-l border-[#1F273B] pl-6">
                        <span class="text-neutral-400 text-xs font-mono uppercase tracking-wider block">Победы / Поражения</span>
                        <span class="font-faceit font-bold text-xl text-emerald-400">{{ $user->wins_count }} <span class="text-neutral-500 text-sm">/</span> <span class="text-red-400">{{ $user->losses_count }}</span></span>
                    </div>
                </div>

                <!-- Admin Moderation Controls -->
                @if(auth()->check() && auth()->user()->isAdmin())
                    <div class="mt-4 pt-4 border-t border-[#1F273B] flex flex-wrap items-center justify-between gap-3 bg-[#0E121B] p-3.5 rounded-xl border border-[#1F273B]">
                        <div class="flex items-center space-x-2 text-xs font-mono">
                            <span class="text-[#FF5500] font-bold">МОДЕРАЦИЯ:</span>
                            @if($user->isBanned())
                                <span class="text-red-400 font-bold bg-red-500/10 px-2.5 py-0.5 rounded border border-red-500/30">
                                    ЗАБАНЕН до {{ $user->banned_until->format('H:i d.m') }} ({{ $user->ban_reason }})
                                </span>
                            @else
                                <span class="text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/30">
                                    АКТИВЕН
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center space-x-2">
                            @if($user->isBanned())
                                <button 
                                    wire:click="unban" 
                                    class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-faceit font-bold text-xs uppercase tracking-wider rounded-lg transition cursor-pointer flex items-center space-x-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>СНЯТЬ БАН</span>
                                </button>
                            @else
                                <button 
                                    wire:click="adminBan(15)" 
                                    class="px-2.5 py-1 bg-neutral-800 hover:bg-red-900/60 text-neutral-300 hover:text-red-200 font-mono text-xs rounded transition cursor-pointer">
                                    Бан 15м
                                </button>
                                <button 
                                    wire:click="adminBan(60)" 
                                    class="px-2.5 py-1 bg-neutral-800 hover:bg-red-900/60 text-neutral-300 hover:text-red-200 font-mono text-xs rounded transition cursor-pointer">
                                    Бан 1ч
                                </button>
                                <button 
                                    wire:click="adminBan(1440)" 
                                    class="px-2.5 py-1 bg-neutral-800 hover:bg-red-900/60 text-neutral-300 hover:text-red-200 font-mono text-xs rounded transition cursor-pointer">
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
        <div class="p-3.5 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-400 text-sm font-semibold flex items-center space-x-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <!-- Profile Edit Form (For Account Owner) -->
    @if($isOwnProfile)
    <div class="bg-[#121622] border border-[#1F273B] rounded-2xl p-6 sm:p-8 shadow-xl">
        <h2 class="text-lg font-faceit font-bold text-white uppercase tracking-wider mb-4 pb-3 border-b border-[#1F273B]">
            Настройки профиля
        </h2>

        <form wire:submit.prevent="updateProfile" class="space-y-4 max-w-xl">
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-neutral-400 mb-1.5">
                    Игровой никнейм
                </label>
                <input 
                    type="text" 
                    wire:model="name" 
                    class="w-full bg-[#0A0D14] border border-[#1F273B] text-white text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#FF5500]">
                @error('name') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="text-xs font-mono uppercase tracking-wider text-neutral-400">
                        SteamID64 (или ссылка на аккаунт)
                    </label>
                    <a href="{{ route('auth.steam') }}" class="text-[11px] text-[#FF5500] hover:underline font-mono">
                        Привязать через Steam OpenID ↗
                    </a>
                </div>
                <input 
                    type="text" 
                    wire:model="steamIdInput" 
                    placeholder="76561198000000001"
                    class="w-full bg-[#0A0D14] border border-[#1F273B] text-white font-mono text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#FF5500]">
                @error('steamIdInput') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                <p class="text-[11px] text-neutral-500 mt-1.5 font-mono">
                    Для корректного подключения к серверу CS2 ваш Steam ID должен совпадать с аккаунтом, с которого вы играете.
                </p>
            </div>

            <button 
                type="submit" 
                class="px-6 py-2.5 bg-[#FF5500] hover:bg-[#FF6E1A] text-white font-faceit font-bold text-sm uppercase tracking-wider rounded-xl transition cursor-pointer shadow-[0_0_15px_rgba(255,85,0,0.3)]">
                Сохранить изменения
            </button>
        </form>
    </div>
    @endif

    <!-- Match History -->
    <div class="bg-[#121622] border border-[#1F273B] rounded-2xl p-6 sm:p-8 shadow-xl">
        <h2 class="text-lg font-faceit font-bold text-white uppercase tracking-wider mb-4 pb-3 border-b border-[#1F273B]">
            История сыгранных матчей
        </h2>

        @if($matches->isEmpty())
            <div class="text-center py-12 text-neutral-500 text-sm font-mono">
                Матчей пока не найдено.
            </div>
        @else
            <div class="space-y-2.5">
                @foreach($matches as $m)
                    @php
                        $isP1 = ($m->player1_id === $user->id);
                        $isWin = ($m->winner_id === $user->id);
                        $opp = $isP1 ? $m->player2 : $m->player1;
                        $myScore = $isP1 ? $m->score_player1 : $m->score_player2;
                        $oppScore = $isP1 ? $m->score_player2 : $m->score_player1;
                        $eloDelta = $isP1 ? $m->elo_change_p1 : $m->elo_change_p2;
                    @endphp
                    <div class="p-3.5 bg-[#0A0D14] border border-[#1F273B] hover:border-white/20 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 transition">
                        <div class="flex items-center space-x-3.5">
                            <span class="w-24 text-center py-1.5 rounded-lg text-xs font-mono font-bold uppercase tracking-wider {{ $isWin ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-red-500/10 text-red-400 border border-red-500/30' }}">
                                {{ $isWin ? 'ПОБЕДА' : 'ПОРАЖЕНИЕ' }}
                            </span>

                            <div>
                                <p class="text-sm font-faceit font-bold text-white">
                                    {{ $m->map ? $m->map->name : 'CS2 Map' }}
                                </p>
                                <p class="text-[11px] text-neutral-400 font-mono">
                                    {{ $m->finished_at ? $m->finished_at->diffForHumans() : $m->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-5">
                            <div class="text-center bg-[#121622] px-3 py-1 rounded-lg border border-[#1F273B]">
                                <span class="font-mono text-sm font-bold text-white">{{ $myScore }} : {{ $oppScore }}</span>
                            </div>

                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-mono text-neutral-500">vs</span>
                                <span class="text-sm font-medium text-neutral-200">{{ $opp ? $opp->name : 'Оппонент' }}</span>
                                @if($opp)
                                    <x-faceit-level :level="$opp->level" size="xs" />
                                @endif
                            </div>

                            <div class="w-16 text-right">
                                @if($eloDelta !== null)
                                    <span class="font-mono font-bold text-sm {{ $eloDelta >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                        {{ $eloDelta >= 0 ? "+{$eloDelta}" : $eloDelta }}
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('match.lobby', ['uuid' => $m->uuid]) }}" class="px-3.5 py-1.5 bg-[#171C2B] hover:bg-[#FF5500] text-neutral-300 hover:text-white rounded-lg text-xs font-faceit font-bold uppercase tracking-wider border border-[#1F273B] hover:border-[#FF5500] transition">
                                Комната
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $matches->links() }}
            </div>
        @endif
    </div>
</div>
