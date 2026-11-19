<div class="max-w-5xl mx-auto space-y-8 animate-fadeIn">
    <!-- Profile Hero Card (Cyber HUD Style) -->
    <div class="bg-gradient-to-b from-[#14081E] via-[#0E0515] to-[#08030D] border border-white/[0.08] hover:border-[#FF0055]/30 rounded-3xl p-8 sm:p-10 shadow-[0_8px_32px_rgba(0,0,0,0.6)] relative overflow-hidden transition-all">
        <!-- Ambient Glow -->
        <div class="absolute -right-16 -top-16 w-56 h-56 bg-[#FF0055]/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start gap-6">
            <!-- Avatar with Cyber Level Badge -->
            <div class="relative shrink-0">
                <img src="{{ $user->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . $user->name }}" class="w-28 h-28 sm:w-32 sm:h-32 rounded-3xl border-2 border-[#FF0055] bg-neutral-950 shadow-[0_0_25px_rgba(255,0,85,0.4)] object-cover" alt="Avatar">
                <div class="absolute -bottom-2 -right-2 w-10 h-10 rounded-xl flex items-center justify-center font-cyber font-black text-lg bg-gradient-to-br from-[#FF0055] to-[#990033] text-white shadow-[0_0_15px_rgba(255,0,85,0.5)] border border-[#FF0055]">
                    {{ $user->level }}
                </div>
            </div>

            <!-- Profile Info -->
            <div class="flex-1 text-center sm:text-left space-y-3 w-full">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                    <h1 class="text-3xl sm:text-4xl font-cyber font-black text-white tracking-wide">
                        {{ $user->name }}
                    </h1>
                    @if($user->isAdmin())
                        <span class="inline-block text-[10px] uppercase font-mono font-bold bg-[#FF0055] text-white px-2.5 py-0.5 rounded-md shadow-[0_0_10px_rgba(255,0,85,0.5)] w-max mx-auto sm:mx-0">CREATOR / ADMIN</span>
                    @endif
                    @if($user->is_bot)
                        <span class="inline-block text-[10px] uppercase font-mono font-bold bg-[#FF0055]/20 text-[#FF0055] border border-[#FF0055]/40 px-2 py-0.5 rounded w-max mx-auto sm:mx-0">AI BOT</span>
                    @endif
                </div>

                <!-- Steam ID Pill -->
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                    @if($user->steam_id)
                        <span class="inline-flex items-center space-x-2 bg-[#08040A] border border-white/[0.1] px-3.5 py-1.5 rounded-xl text-xs font-mono text-emerald-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>SteamID64: {{ $user->steam_id }}</span>
                        </span>
                        <a href="https://steamcommunity.com/profiles/{{ $user->steam_id }}" target="_blank" class="text-xs text-[#FF2A6D] hover:underline font-mono">
                            Профиль Steam ↗
                        </a>
                    @else
                        <span class="inline-flex items-center space-x-1.5 bg-[#08040A] border border-[#FF0055]/40 px-3.5 py-1.5 rounded-xl text-xs text-[#FF2A6D] font-mono">
                            <span>Steam не привязан</span>
                        </span>
                        @if($isOwnProfile)
                            <a href="{{ route('auth.steam') }}" class="text-xs text-[#FF0055] hover:underline font-semibold font-mono">
                                Привязать в 1 клик через Steam OpenID ↗
                            </a>
                        @endif
                    @endif
                </div>

                <!-- Stats summary HUD -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 text-sm border-t border-white/[0.08]">
                    <div class="bg-[#08040A]/60 p-3 rounded-2xl border border-white/[0.04]">
                        <span class="text-[#FF0055] text-[11px] font-mono uppercase tracking-wider block font-bold">Рейтинг Elo</span>
                        <span class="font-cyber font-black text-2xl text-white tabular-nums">{{ $user->elo }} <span class="text-[#FF0055] text-xs font-mono">ELO</span></span>
                    </div>
                    <div class="bg-[#08040A]/60 p-3 rounded-2xl border border-white/[0.04]">
                        <span class="text-[#FF0055] text-[11px] font-mono uppercase tracking-wider block font-bold">Винрейт</span>
                        <span class="font-cyber font-black text-2xl text-white tabular-nums">{{ $user->winrate }}%</span>
                    </div>
                    <div class="bg-[#08040A]/60 p-3 rounded-2xl border border-white/[0.04]">
                        <span class="text-[#FF0055] text-[11px] font-mono uppercase tracking-wider block font-bold">Матчи</span>
                        <span class="font-cyber font-black text-2xl text-white tabular-nums">{{ $user->matches_count }}</span>
                    </div>
                    <div class="bg-[#08040A]/60 p-3 rounded-2xl border border-white/[0.04]">
                        <span class="text-[#FF0055] text-[11px] font-mono uppercase tracking-wider block font-bold">W / L</span>
                        <span class="font-cyber font-black text-xl text-emerald-400 tabular-nums">{{ $user->wins_count }} <span class="text-neutral-500 text-sm">/</span> <span class="text-red-400">{{ $user->losses_count }}</span></span>
                    </div>
                </div>

                <!-- Admin Ban Control Bar (For Admins) -->
                @if(auth()->check() && auth()->user()->isAdmin())
                    <div class="mt-4 pt-4 border-t border-white/[0.08] flex flex-wrap items-center justify-between gap-3 bg-[#08040A] p-4 rounded-2xl border border-white/10">
                        <div class="flex items-center space-x-2 text-xs font-mono">
                            <span class="text-[#FF0055] font-bold">АДМИН-ПАНЕЛЬ:</span>
                            @if($user->isBanned())
                                <span class="text-red-400 font-bold bg-red-500/20 px-2.5 py-1 rounded-lg border border-red-500/40">
                                    ЗАБАНЕН до {{ $user->banned_until->format('H:i d.m') }} ({{ $user->ban_reason }})
                                </span>
                            @else
                                <span class="text-emerald-400 bg-emerald-500/20 px-2 py-0.5 rounded-lg border border-emerald-500/40">
                                    АКТИВЕН (НЕТ БАНА)
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center space-x-2">
                            @if($user->isBanned())
                                <button 
                                    wire:click="unban" 
                                    class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:brightness-110 text-white font-cyber font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-[0_0_15px_rgba(16,185,129,0.4)] transition cursor-pointer flex items-center space-x-1.5 border border-emerald-300/40">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>СНЯТЬ БАН</span>
                                </button>
                            @else
                                <button 
                                    wire:click="adminBan(15)" 
                                    class="px-3 py-1.5 bg-red-600/80 hover:bg-red-600 text-white font-cyber font-bold text-xs uppercase tracking-wider rounded-xl transition cursor-pointer">
                                    Бан 15м
                                </button>
                                <button 
                                    wire:click="adminBan(60)" 
                                    class="px-3 py-1.5 bg-red-700/80 hover:bg-red-700 text-white font-cyber font-bold text-xs uppercase tracking-wider rounded-xl transition cursor-pointer">
                                    Бан 1ч
                                </button>
                                <button 
                                    wire:click="adminBan(1440)" 
                                    class="px-3 py-1.5 bg-red-900/80 hover:bg-red-800 text-white font-cyber font-bold text-xs uppercase tracking-wider rounded-xl transition cursor-pointer">
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
        <div class="p-4 bg-emerald-500/15 border border-emerald-500/40 rounded-2xl text-emerald-400 text-sm font-semibold flex items-center space-x-2 shadow-[0_0_20px_rgba(16,185,129,0.2)]">
            <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <!-- Profile Edit Form (For Account Owner) -->
    @if($isOwnProfile)
    <div class="bg-gradient-to-b from-[#120817] to-[#0A040E] border border-white/[0.08] hover:border-[#FF0055]/30 rounded-3xl p-6 sm:p-8 shadow-[0_8px_32px_rgba(0,0,0,0.6)] transition-all">
        <h2 class="text-xl font-cyber font-black text-white uppercase tracking-wider mb-4 pb-3 border-b border-white/[0.08]">
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
                    class="w-full bg-[#08040A] border border-white/[0.1] focus:border-[#FF0055] text-white text-sm px-4 py-3 rounded-xl focus:outline-none transition-colors">
                @error('name') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="text-xs font-mono uppercase tracking-wider text-neutral-400">
                        SteamID64 (или ссылка на аккаунт)
                    </label>
                    <a href="{{ route('auth.steam') }}" class="text-[11px] text-[#FF2A6D] hover:underline font-mono">
                        Привязать через Steam OpenID ↗
                    </a>
                </div>
                <input 
                    type="text" 
                    wire:model="steamIdInput" 
                    placeholder="76561198000000001"
                    class="w-full bg-[#08040A] border border-white/[0.1] focus:border-[#FF0055] text-white font-mono text-sm px-4 py-3 rounded-xl focus:outline-none transition-colors">
                @error('steamIdInput') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                <p class="text-[11px] text-neutral-500 mt-1.5 font-mono">
                    Для подключения к CS2 серверу ваш Steam ID должен совпадать с тем, с которого вы заходите в игру.
                </p>
            </div>

            <button 
                type="submit" 
                class="px-8 py-3.5 bg-gradient-to-r from-[#FF0055] to-[#E1066A] hover:brightness-110 text-white font-cyber font-black text-sm uppercase tracking-wider rounded-xl shadow-[0_0_25px_rgba(255,0,85,0.45)] transition cursor-pointer border border-white/20">
                Сохранить изменения
            </button>
        </form>
    </div>
    @endif

    <!-- Match History -->
    <div class="bg-gradient-to-b from-[#120817] to-[#0A040E] border border-white/[0.08] hover:border-[#FF0055]/30 rounded-3xl p-6 sm:p-8 shadow-[0_8px_32px_rgba(0,0,0,0.6)] transition-all">
        <h2 class="text-xl font-cyber font-black text-white uppercase tracking-wider mb-6 pb-3 border-b border-white/[0.08]">
            История сыгранных матчей
        </h2>

        @if($matches->isEmpty())
            <div class="text-center py-12 text-neutral-500 text-sm font-mono">
                Матчей пока не найдено.
            </div>
        @else
            <div class="space-y-3">
                @foreach($matches as $m)
                    @php
                        $isP1 = ($m->player1_id === $user->id);
                        $isWin = ($m->winner_id === $user->id);
                        $opp = $isP1 ? $m->player2 : $m->player1;
                        $myScore = $isP1 ? $m->score_player1 : $m->score_player2;
                        $oppScore = $isP1 ? $m->score_player2 : $m->score_player1;
                        $eloDelta = $isP1 ? $m->elo_change_p1 : $m->elo_change_p2;
                    @endphp
                    <div class="p-4 bg-[#08040C] border border-white/[0.06] hover:border-[#FF0055]/50 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all duration-200 group hover:-translate-y-0.5">
                        <div class="flex items-center space-x-4">
                            <span class="w-28 text-center py-1.5 rounded-xl text-xs font-cyber font-black uppercase tracking-wider {{ $isWin ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/40 shadow-[0_0_12px_rgba(16,185,129,0.25)]' : 'bg-red-500/15 text-red-400 border border-red-500/40' }}">
                                {{ $isWin ? 'ПОБЕДА' : 'ПОРАЖЕНИЕ' }}
                            </span>

                            <div>
                                <p class="text-sm font-cyber font-bold text-white group-hover:text-[#FF2A6D] transition-colors">
                                    {{ $m->map ? $m->map->name : 'CS2 Map' }}
                                </p>
                                <p class="text-xs text-neutral-400 font-mono">
                                    {{ $m->finished_at ? $m->finished_at->diffForHumans() : $m->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4 sm:space-x-6">
                            <div class="text-center bg-[#120817] px-3.5 py-1 rounded-xl border border-white/[0.08]">
                                <span class="font-cyber text-base font-black text-white tabular-nums">{{ $myScore }} : {{ $oppScore }}</span>
                            </div>

                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-mono text-[#FF0055]">vs</span>
                                <span class="text-sm font-medium text-neutral-200">{{ $opp ? $opp->name : 'Оппонент' }}</span>
                            </div>

                            <div class="w-16 text-right">
                                @if($eloDelta !== null)
                                    <span class="font-cyber font-bold text-sm tabular-nums {{ $eloDelta >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                        {{ $eloDelta >= 0 ? "+{$eloDelta}" : $eloDelta }}
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('match.lobby', ['uuid' => $m->uuid]) }}" class="px-3.5 py-1.5 bg-[#FF0055]/15 hover:bg-[#FF0055] text-neutral-200 hover:text-white rounded-xl text-xs font-faceit font-bold uppercase tracking-wider border border-[#FF0055]/30 transition whitespace-nowrap">
                                Комната
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $matches->links() }}
            </div>
        @endif
    </div>
</div>
