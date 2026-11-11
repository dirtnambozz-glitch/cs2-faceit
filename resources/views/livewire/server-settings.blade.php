<div class="max-w-7xl mx-auto space-y-8 animate-fadeIn">
    <!-- Top Cyber Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-[#FF0055]/30 gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-mono uppercase tracking-widest text-[#FF0055] mb-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 shadow-[0_0_8px_#34d399] animate-pulse"></span>
                <span>ADMIN PORTAL // SECURITY CLEARANCE LEVEL 10</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-cyber font-black text-white uppercase tracking-wider">
                ПАНЕЛЬ АДМИНИСТРАТОРА <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FF0055] via-[#FF2A6D] to-white">RIVALIS</span>
            </h1>
            <p class="text-neutral-400 text-xs sm:text-sm mt-1 font-mono">
                Центр управления игроками, банами, активными матчами CS2, маппулом и сервером Incloudgame
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <div class="px-4 py-2 bg-[#120817] border border-[#FF0055]/40 rounded-2xl flex items-center space-x-2.5 shadow-[0_0_15px_rgba(255,0,85,0.2)]">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span class="font-cyber font-bold text-xs text-white uppercase">{{ auth()->user()->name }} (ADMIN)</span>
            </div>
        </div>
    </div>

    <!-- Flash Notifications -->
    @if(session()->has('success'))
        <div class="p-4 bg-emerald-500/15 border-2 border-emerald-500/50 rounded-2xl text-emerald-300 text-sm font-semibold flex items-center justify-between shadow-[0_0_25px_rgba(16,185,129,0.25)]">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="p-4 bg-red-500/15 border-2 border-red-500/50 rounded-2xl text-red-300 text-sm font-semibold flex items-center space-x-3 shadow-[0_0_25px_rgba(239,68,68,0.25)]">
            <svg class="w-6 h-6 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Navigation Tabs Bar (Cyber Style) -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-2 scrollbar-none border-b border-white/10">
        <button 
            wire:click="setTab('overview')" 
            class="px-5 py-3 rounded-2xl font-cyber font-bold text-xs uppercase tracking-wider transition whitespace-nowrap flex items-center space-x-2 cursor-pointer {{ $activeTab === 'overview' ? 'bg-gradient-to-r from-[#FF0055] to-[#E1066A] text-white shadow-[0_0_20px_rgba(255,0,85,0.5)] border border-white/20' : 'bg-[#120817] text-neutral-400 hover:text-white border border-white/10 hover:border-[#FF0055]/50' }}">
            <span>📊</span>
            <span>ОБЗОР И СТАТИСТИКА</span>
        </button>

        <button 
            wire:click="setTab('players')" 
            class="px-5 py-3 rounded-2xl font-cyber font-bold text-xs uppercase tracking-wider transition whitespace-nowrap flex items-center space-x-2 cursor-pointer {{ $activeTab === 'players' ? 'bg-gradient-to-r from-[#FF0055] to-[#E1066A] text-white shadow-[0_0_20px_rgba(255,0,85,0.5)] border border-white/20' : 'bg-[#120817] text-neutral-400 hover:text-white border border-white/10 hover:border-[#FF0055]/50' }}">
            <span>👥</span>
            <span>ИГРОКИ И БАНЫ</span>
            @if($bannedUsersCount > 0)
                <span class="px-2 py-0.5 rounded-full bg-red-600 text-white text-[10px] font-mono">{{ $bannedUsersCount }}</span>
            @endif
        </button>

        <button 
            wire:click="setTab('matches')" 
            class="px-5 py-3 rounded-2xl font-cyber font-bold text-xs uppercase tracking-wider transition whitespace-nowrap flex items-center space-x-2 cursor-pointer {{ $activeTab === 'matches' ? 'bg-gradient-to-r from-[#FF0055] to-[#E1066A] text-white shadow-[0_0_20px_rgba(255,0,85,0.5)] border border-white/20' : 'bg-[#120817] text-neutral-400 hover:text-white border border-white/10 hover:border-[#FF0055]/50' }}">
            <span>⚔️</span>
            <span>МАТЧИ (LIVE & ИСТОРИЯ)</span>
            @if($activeMatchesCount > 0)
                <span class="px-2 py-0.5 rounded-full bg-emerald-500 text-white text-[10px] font-mono animate-pulse">{{ $activeMatchesCount }} LIVE</span>
            @endif
        </button>

        <button 
            wire:click="setTab('mappool')" 
            class="px-5 py-3 rounded-2xl font-cyber font-bold text-xs uppercase tracking-wider transition whitespace-nowrap flex items-center space-x-2 cursor-pointer {{ $activeTab === 'mappool' ? 'bg-gradient-to-r from-[#FF0055] to-[#E1066A] text-white shadow-[0_0_20px_rgba(255,0,85,0.5)] border border-white/20' : 'bg-[#120817] text-neutral-400 hover:text-white border border-white/10 hover:border-[#FF0055]/50' }}">
            <span>🗺️</span>
            <span>МАППУЛ CS2</span>
        </button>

        <button 
            wire:click="setTab('console')" 
            class="px-5 py-3 rounded-2xl font-cyber font-bold text-xs uppercase tracking-wider transition whitespace-nowrap flex items-center space-x-2 cursor-pointer {{ $activeTab === 'console' ? 'bg-gradient-to-r from-[#FF0055] to-[#E1066A] text-white shadow-[0_0_20px_rgba(255,0,85,0.5)] border border-white/20' : 'bg-[#120817] text-neutral-400 hover:text-white border border-white/10 hover:border-[#FF0055]/50' }}">
            <span>💻</span>
            <span>RCON КОНСОЛЬ</span>
        </button>

        <button 
            wire:click="setTab('server')" 
            class="px-5 py-3 rounded-2xl font-cyber font-bold text-xs uppercase tracking-wider transition whitespace-nowrap flex items-center space-x-2 cursor-pointer {{ $activeTab === 'server' ? 'bg-gradient-to-r from-[#FF0055] to-[#E1066A] text-white shadow-[0_0_20px_rgba(255,0,85,0.5)] border border-white/20' : 'bg-[#120817] text-neutral-400 hover:text-white border border-white/10 hover:border-[#FF0055]/50' }}">
            <span>⚙️</span>
            <span>СЕРВЕР И ВЕБХУК</span>
        </button>
    </div>

    <!-- ====================================================================== -->
    <!-- TAB 1: OVERVIEW & DASHBOARD                                            -->
    <!-- ====================================================================== -->
    @if($activeTab === 'overview')
        <div class="space-y-8">
            <!-- 4 Metrics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-[#120817] border border-[#FF0055]/30 rounded-3xl p-6 shadow-[0_0_25px_rgba(255,0,85,0.15)] relative overflow-hidden group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono uppercase tracking-wider text-neutral-400">Всего игроков</span>
                        <div class="w-10 h-10 rounded-2xl bg-[#FF0055]/20 text-[#FF0055] flex items-center justify-center font-bold">👥</div>
                    </div>
                    <p class="text-4xl font-cyber font-black text-white mt-3 group-hover:text-[#FF2A6D] transition">{{ $totalUsers }}</p>
                    <span class="text-[11px] font-mono text-neutral-500 mt-1 block">Зарегистрированные аккаунты</span>
                </div>

                <div class="bg-[#120817] border border-emerald-500/30 rounded-3xl p-6 shadow-[0_0_25px_rgba(16,185,129,0.15)] relative overflow-hidden group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono uppercase tracking-wider text-emerald-400">Матчей Live</span>
                        <div class="w-10 h-10 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold">⚔️</div>
                    </div>
                    <p class="text-4xl font-cyber font-black text-emerald-300 mt-3">{{ $activeMatchesCount }}</p>
                    <span class="text-[11px] font-mono text-neutral-500 mt-1 block">В поиске / игре прямо сейчас</span>
                </div>

                <div class="bg-[#120817] border border-[#FF0055]/30 rounded-3xl p-6 shadow-[0_0_25px_rgba(255,0,85,0.15)] relative overflow-hidden group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono uppercase tracking-wider text-neutral-400">Сыграно матчей</span>
                        <div class="w-10 h-10 rounded-2xl bg-[#FF0055]/20 text-[#FF0055] flex items-center justify-center font-bold">🏆</div>
                    </div>
                    <p class="text-4xl font-cyber font-black text-white mt-3 group-hover:text-[#FF2A6D] transition">{{ $finishedMatchesCount }}</p>
                    <span class="text-[11px] font-mono text-neutral-500 mt-1 block">С зафиксированным ELO</span>
                </div>

                <div class="bg-[#120817] border border-red-500/30 rounded-3xl p-6 shadow-[0_0_25px_rgba(239,68,68,0.15)] relative overflow-hidden group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono uppercase tracking-wider text-red-400">Активных банов</span>
                        <div class="w-10 h-10 rounded-2xl bg-red-500/20 text-red-400 flex items-center justify-center font-bold">⛔</div>
                    </div>
                    <p class="text-4xl font-cyber font-black text-red-400 mt-3">{{ $bannedUsersCount }}</p>
                    <span class="text-[11px] font-mono text-neutral-500 mt-1 block">Блокировок очереди</span>
                </div>
            </div>

            <!-- Server Quick Status & Presets -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- CS2 Dedicated Server Card -->
                <div class="lg:col-span-2 bg-[#120817] border border-[#FF0055]/30 rounded-3xl p-6 sm:p-8 shadow-[0_0_25px_rgba(255,0,85,0.2)]">
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-[#FF0055]/20">
                        <div class="flex items-center space-x-3">
                            <span class="w-3.5 h-3.5 rounded-full bg-emerald-400 shadow-[0_0_12px_#34d399] animate-pulse"></span>
                            <div>
                                <h3 class="font-cyber font-bold text-lg text-white uppercase">Игровой сервер CS2 (UP FaceIt)</h3>
                                <p class="text-xs font-mono text-neutral-400">89.248.236.141:27574 • Incloudgame CS2</p>
                            </div>
                        </div>
                        <button 
                            wire:click="testConnection" 
                            class="px-4 py-2 bg-[#08040A] hover:bg-[#FF0055]/20 text-neutral-200 hover:text-white rounded-xl text-xs font-cyber font-bold uppercase tracking-wider border border-[#FF0055]/30 transition cursor-pointer">
                            Проверить связь
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6 font-mono text-xs">
                        <div class="p-3 bg-[#08040A] rounded-xl border border-white/10">
                            <span class="text-neutral-500 block text-[10px]">ПЛАГИН СОРЕВНОВАНИЙ</span>
                            <span class="text-emerald-400 font-bold">MatchZy 0.9.2</span>
                        </div>
                        <div class="p-3 bg-[#08040A] rounded-xl border border-white/10">
                            <span class="text-neutral-500 block text-[10px]">WEBHOOK ПРИЁМНИК</span>
                            <span class="text-emerald-400 font-bold">ACTIVE (45.140.19.240)</span>
                        </div>
                        <div class="p-3 bg-[#08040A] rounded-xl border border-white/10">
                            <span class="text-neutral-500 block text-[10px]">РЕЖИМ МАТЧЕЙ</span>
                            <span class="text-white font-bold">1v1 FACEIT AUTO</span>
                        </div>
                    </div>

                    <!-- Quick RCON Preset Buttons -->
                    <h4 class="text-xs font-mono uppercase tracking-wider text-[#FF0055] mb-2.5">Быстрые команды на сервер:</h4>
                    <div class="flex flex-wrap gap-2.5">
                        <button 
                            wire:click="executeRconCommand('mp_restartgame 1')" 
                            class="px-3.5 py-2 bg-[#08040A] hover:bg-neutral-800 text-neutral-200 text-xs font-mono rounded-xl border border-white/15 transition cursor-pointer">
                            🔄 Рестарт игры (restart 1)
                        </button>
                        <button 
                            wire:click="executeRconCommand('bot_kick')" 
                            class="px-3.5 py-2 bg-[#08040A] hover:bg-neutral-800 text-neutral-200 text-xs font-mono rounded-xl border border-white/15 transition cursor-pointer">
                            🤖 Кикнуть ботов (bot_kick)
                        </button>
                        <button 
                            wire:click="forceServerMap('de_mirage')" 
                            class="px-3.5 py-2 bg-[#08040A] hover:bg-[#FF0055]/30 text-neutral-200 hover:text-white text-xs font-mono rounded-xl border border-[#FF0055]/30 transition cursor-pointer">
                            📍 Сменить на Mirage
                        </button>
                        <button 
                            wire:click="forceServerMap('de_dust2')" 
                            class="px-3.5 py-2 bg-[#08040A] hover:bg-[#FF0055]/30 text-neutral-200 hover:text-white text-xs font-mono rounded-xl border border-[#FF0055]/30 transition cursor-pointer">
                            📍 Сменить на Dust 2
                        </button>
                    </div>
                </div>

                <!-- Active Bans Quick Bar -->
                <div class="bg-[#120817] border border-red-500/30 rounded-3xl p-6 shadow-[0_0_25px_rgba(239,68,68,0.15)] flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-3 mb-3 border-b border-red-500/20">
                            <h3 class="font-cyber font-bold text-sm text-red-200 uppercase">Снятие банов в 1 клик</h3>
                            <span class="text-xs font-mono text-red-400 font-bold">{{ $bannedUsers->count() }}</span>
                        </div>

                        @if($bannedUsers->isEmpty())
                            <div class="text-center py-8 text-neutral-500 text-xs font-mono">
                                Забаненных игроков сейчас нет.
                            </div>
                        @else
                            <div class="space-y-2.5 max-h-56 overflow-y-auto pr-1">
                                @foreach($bannedUsers->take(4) as $bU)
                                    <div class="p-2.5 bg-[#08040A] border border-red-500/30 rounded-xl flex items-center justify-between gap-2">
                                        <div class="overflow-hidden">
                                            <p class="font-cyber font-bold text-xs text-white truncate">{{ $bU->name }}</p>
                                            <p class="text-[10px] font-mono text-neutral-400 truncate">{{ $bU->ban_reason ?? 'Бан' }}</p>
                                        </div>
                                        <button 
                                            wire:click="unbanPlayer({{ $bU->id }})" 
                                            class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-500 text-white font-cyber text-[10px] font-bold uppercase rounded-lg transition shrink-0 cursor-pointer">
                                            Снять
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <button 
                        wire:click="setTab('players')" 
                        class="w-full mt-4 py-2.5 bg-red-950/60 hover:bg-red-900 text-red-200 text-xs font-mono uppercase tracking-wider rounded-xl border border-red-500/40 transition cursor-pointer">
                        Все игроки и баны →
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ====================================================================== -->
    <!-- TAB 2: PLAYERS & BANS MANAGEMENT                                       -->
    <!-- ====================================================================== -->
    @if($activeTab === 'players')
        <div class="bg-[#120817] border border-[#FF0055]/30 rounded-3xl p-6 sm:p-8 shadow-[0_0_35px_rgba(255,0,85,0.2)] space-y-6">
            <!-- Header & Search Controls -->
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 pb-6 border-b border-[#FF0055]/20">
                <div>
                    <h2 class="text-2xl font-cyber font-black text-white uppercase tracking-wider">
                        УПРАВЛЕНИЕ ИГРОКАМИ И РЕЙТИНГОМ
                    </h2>
                    <p class="text-xs font-mono text-neutral-400 mt-0.5">
                        Мгновенное снятие и выдача банов, корректировка очков ELO, сброс варнов и выдача админок
                    </p>
                </div>

                <!-- Filter Buttons -->
                <div class="flex items-center space-x-1.5 bg-[#08040A] p-1.5 rounded-2xl border border-white/10">
                    <button wire:click="$set('playerFilter', 'all')" class="px-3 py-1.5 rounded-xl text-xs font-mono font-bold transition cursor-pointer {{ $playerFilter === 'all' ? 'bg-[#FF0055] text-white shadow' : 'text-neutral-400 hover:text-white' }}">Все</button>
                    <button wire:click="$set('playerFilter', 'banned')" class="px-3 py-1.5 rounded-xl text-xs font-mono font-bold transition cursor-pointer {{ $playerFilter === 'banned' ? 'bg-red-600 text-white shadow' : 'text-neutral-400 hover:text-white' }}">Забаненные ({{ $bannedUsersCount }})</button>
                    <button wire:click="$set('playerFilter', 'admin')" class="px-3 py-1.5 rounded-xl text-xs font-mono font-bold transition cursor-pointer {{ $playerFilter === 'admin' ? 'bg-[#FF0055] text-white shadow' : 'text-neutral-400 hover:text-white' }}">Админы</button>
                    <button wire:click="$set('playerFilter', 'bot')" class="px-3 py-1.5 rounded-xl text-xs font-mono font-bold transition cursor-pointer {{ $playerFilter === 'bot' ? 'bg-[#FF0055] text-white shadow' : 'text-neutral-400 hover:text-white' }}">Боты</button>
                </div>
            </div>

            <!-- Search input bar -->
            <div class="relative">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="searchPlayer" 
                    placeholder="Поиск по никнейму, SteamID64 или email..."
                    class="w-full bg-[#08040A] border border-[#FF0055]/30 text-white font-mono text-sm px-4 py-3.5 rounded-2xl focus:outline-none focus:border-[#FF0055] pl-11">
                <svg class="w-5 h-5 text-neutral-400 absolute left-4 top-4 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <!-- Players Table -->
            <div class="overflow-x-auto rounded-2xl border border-white/10">
                <table class="w-full text-left text-xs font-mono">
                    <thead class="bg-[#08040A] uppercase tracking-wider text-neutral-400 border-b border-white/10">
                        <tr>
                            <th class="p-4">Игрок</th>
                            <th class="p-4">SteamID64</th>
                            <th class="p-4">Рейтинг ELO</th>
                            <th class="p-4">Статус бана</th>
                            <th class="p-4">Варны</th>
                            <th class="p-4 text-right">Админ-действия</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($players as $p)
                            <tr class="hover:bg-white/[0.02] transition">
                                <td class="p-4">
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ $p->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . $p->name }}" class="w-10 h-10 rounded-xl border border-white/15 object-cover" alt="ava">
                                        <div>
                                            <div class="flex items-center space-x-1.5">
                                                <a href="{{ route('profile', ['id' => $p->id]) }}" class="font-cyber font-bold text-sm text-white hover:text-[#FF2A6D] transition">
                                                    {{ $p->name }}
                                                </a>
                                                @if($p->isAdmin())
                                                    <span class="px-1.5 py-0.2 rounded bg-[#FF0055] text-white text-[9px] font-bold">ADMIN</span>
                                                @endif
                                                @if($p->is_bot)
                                                    <span class="px-1.5 py-0.2 rounded bg-neutral-800 text-neutral-300 text-[9px]">BOT</span>
                                                @endif
                                            </div>
                                            <span class="text-neutral-500 text-[10px]">{{ $p->email }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="p-4">
                                    @if($p->steam_id)
                                        <span class="text-emerald-400 font-mono">{{ $p->steam_id }}</span>
                                    @else
                                        <span class="text-neutral-500 font-mono">Не привязан</span>
                                    @endif
                                </td>

                                <td class="p-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-cyber font-bold text-base text-white">{{ $p->elo }}</span>
                                        <div class="flex items-center space-x-1">
                                            <button wire:click="adjustElo({{ $p->id }}, 25)" title="+25 ELO" class="w-5 h-5 rounded bg-emerald-600/30 hover:bg-emerald-600 text-emerald-300 hover:text-white flex items-center justify-center font-bold text-[10px] transition cursor-pointer">+</button>
                                            <button wire:click="adjustElo({{ $p->id }}, -25)" title="-25 ELO" class="w-5 h-5 rounded bg-red-600/30 hover:bg-red-600 text-red-300 hover:text-white flex items-center justify-center font-bold text-[10px] transition cursor-pointer">-</button>
                                        </div>
                                    </div>
                                    <span class="text-[10px] text-neutral-400 font-mono">Уровень {{ $p->level }} ({{ $p->wins_count }}W / {{ $p->losses_count }}L)</span>
                                </td>

                                <td class="p-4">
                                    @if($p->isBanned())
                                        <span class="px-2.5 py-1 rounded-lg bg-red-500/20 text-red-400 border border-red-500/40 font-bold block w-max">
                                            БАН до {{ $p->banned_until->format('H:i d.m') }}
                                        </span>
                                        <span class="text-[10px] text-red-300 block mt-0.5 truncate max-w-xs">{{ $p->ban_reason }}</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-lg bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 font-bold">
                                            АКТИВЕН
                                        </span>
                                    @endif
                                </td>

                                <td class="p-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-bold {{ $p->warnings_count > 0 ? 'text-amber-400' : 'text-neutral-400' }}">{{ $p->warnings_count }}</span>
                                        @if($p->warnings_count > 0)
                                            <button wire:click="resetWarnings({{ $p->id }})" title="Сбросить варны" class="text-[10px] text-neutral-400 hover:text-white underline cursor-pointer">Сброс</button>
                                        @endif
                                    </div>
                                </td>

                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        @if($p->isBanned())
                                            <button 
                                                wire:click="unbanPlayer({{ $p->id }})" 
                                                class="px-3 py-1.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:brightness-110 text-white font-cyber font-bold text-[11px] uppercase tracking-wider rounded-xl shadow-[0_0_12px_rgba(16,185,129,0.4)] transition cursor-pointer">
                                                Снять бан
                                            </button>
                                        @else
                                            <button 
                                                wire:click="banPlayer({{ $p->id }}, 15, 'Блокировка администратором')" 
                                                class="px-2.5 py-1 bg-red-600/70 hover:bg-red-600 text-white font-mono text-[11px] rounded-lg transition cursor-pointer">
                                                Бан 15м
                                            </button>
                                            <button 
                                                wire:click="banPlayer({{ $p->id }}, 1440, 'Блокировка администратором на 24ч')" 
                                                class="px-2.5 py-1 bg-red-900/80 hover:bg-red-800 text-white font-mono text-[11px] rounded-lg transition cursor-pointer">
                                                Бан 1д
                                            </button>
                                        @endif

                                        <button 
                                            wire:click="toggleAdmin({{ $p->id }})" 
                                            title="{{ $p->isAdmin() ? 'Забрать права администратора' : 'Сделать администратором' }}"
                                            class="px-2.5 py-1 rounded-lg text-[11px] font-mono border transition cursor-pointer {{ $p->isAdmin() ? 'bg-[#FF0055]/20 text-[#FF0055] border-[#FF0055]/40 hover:bg-red-950' : 'bg-neutral-800 text-neutral-300 border-white/10 hover:text-white' }}">
                                            {{ $p->isAdmin() ? 'Админ ✓' : '+ Админ' }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-2">
                {{ $players->links() }}
            </div>
        </div>
    @endif

    <!-- ====================================================================== -->
    <!-- TAB 3: MATCHES (LIVE & HISTORY)                                        -->
    <!-- ====================================================================== -->
    @if($activeTab === 'matches')
        <div class="space-y-8">
            <!-- Active Matches (LIVE) -->
            <div class="bg-[#120817] border border-emerald-500/40 rounded-3xl p-6 sm:p-8 shadow-[0_0_35px_rgba(16,185,129,0.2)]">
                <div class="flex items-center justify-between pb-4 mb-6 border-b border-emerald-500/30">
                    <div class="flex items-center space-x-3">
                        <span class="w-3.5 h-3.5 rounded-full bg-emerald-400 shadow-[0_0_12px_#34d399] animate-pulse"></span>
                        <h2 class="text-xl font-cyber font-black text-white uppercase tracking-wider">
                            АКТИВНЫЕ МАТЧИ В РЕАЛЬНОМ ВРЕМЕНИ ({{ $activeMatches->count() }})
                        </h2>
                    </div>
                    <span class="text-xs font-mono uppercase tracking-widest text-emerald-400 bg-emerald-500/10 border border-emerald-500/30 px-3 py-1 rounded-lg">
                        MATCH LIVE CONTROL
                    </span>
                </div>

                @if($activeMatches->isEmpty())
                    <div class="text-center py-10 text-neutral-400 font-mono text-xs bg-[#08040A] rounded-2xl border border-white/10">
                        В данный момент нет активных дуэлей на сервере.
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($activeMatches as $aMatch)
                            <div class="p-5 bg-[#08040A] border-2 border-emerald-500/40 rounded-2xl flex flex-col lg:flex-row items-center justify-between gap-6">
                                <!-- Match Info -->
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 font-cyber font-bold text-lg flex items-center justify-center border border-emerald-500/40">
                                        #{{ $aMatch->id }}
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <span class="font-cyber font-bold text-base text-white">
                                                {{ $aMatch->player1->name }} <span class="text-[#FF0055]">vs</span> {{ $aMatch->player2->name ?? 'Оппонент' }}
                                            </span>
                                            <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 text-[10px] font-mono uppercase font-bold">
                                                {{ $aMatch->status }}
                                            </span>
                                        </div>
                                        <p class="text-xs font-mono text-neutral-400 mt-1">
                                            Карта: <span class="text-white font-bold">{{ $aMatch->map ? $aMatch->map->name : 'Map Veto' }}</span> • Счет: <span class="text-emerald-400 font-bold">{{ $aMatch->score_player1 }} : {{ $aMatch->score_player2 }}</span>
                                        </p>
                                        <div class="flex items-center space-x-3 text-[11px] font-mono mt-1 text-neutral-400">
                                            <span>P1 online: {!! $aMatch->player1_connected ? '<span class="text-emerald-400">🟢</span>' : '<span class="text-amber-400">🟡</span>' !!}</span>
                                            <span>•</span>
                                            <span>P2 online: {!! ($aMatch->player2_connected || ($aMatch->player2 && $aMatch->player2->is_bot)) ? '<span class="text-emerald-400">🟢</span>' : '<span class="text-amber-400">🟡</span>' !!}</span>
                                            @if($aMatch->connect_deadline)
                                                <span>•</span>
                                                <span>Таймер: <span class="text-amber-400 font-bold">{{ sprintf('%02d:%02d', floor($aMatch->connectRemainingSeconds() / 60), $aMatch->connectRemainingSeconds() % 60) }}</span></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-wrap items-center justify-end gap-2.5 w-full lg:w-auto">
                                    <a href="{{ route('match.lobby', ['uuid' => $aMatch->uuid]) }}" target="_blank" class="px-3.5 py-2 bg-[#120817] hover:bg-neutral-800 text-neutral-200 text-xs font-mono rounded-xl border border-white/20 transition">
                                        Комната ↗
                                    </a>

                                    <button 
                                        wire:click="extendMatchTimer({{ $aMatch->id }}, 5)" 
                                        class="px-3.5 py-2 bg-amber-500/20 hover:bg-amber-500 text-amber-300 hover:text-black font-mono text-xs rounded-xl border border-amber-500/40 transition cursor-pointer">
                                        +5 мин таймер
                                    </button>

                                    <button 
                                        wire:click="forceWinMatch({{ $aMatch->id }}, {{ $aMatch->player1_id }})" 
                                        class="px-3.5 py-2 bg-emerald-600/80 hover:bg-emerald-600 text-white font-cyber font-bold text-xs uppercase rounded-xl transition cursor-pointer">
                                        Победа {{ $aMatch->player1->name }}
                                    </button>

                                    @if($aMatch->player2)
                                    <button 
                                        wire:click="forceWinMatch({{ $aMatch->id }}, {{ $aMatch->player2_id }})" 
                                        class="px-3.5 py-2 bg-emerald-600/80 hover:bg-emerald-600 text-white font-cyber font-bold text-xs uppercase rounded-xl transition cursor-pointer">
                                        Победа {{ $aMatch->player2->name }}
                                    </button>
                                    @endif

                                    <button 
                                        wire:click="cancelMatch({{ $aMatch->id }})" 
                                        class="px-3.5 py-2 bg-red-600/80 hover:bg-red-600 text-white font-cyber font-bold text-xs uppercase rounded-xl transition cursor-pointer">
                                        Отменить матч
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Recent Finished Matches -->
            <div class="bg-[#120817] border border-[#FF0055]/30 rounded-3xl p-6 sm:p-8 shadow-[0_0_25px_rgba(255,0,85,0.15)]">
                <h3 class="text-xl font-cyber font-bold text-white uppercase tracking-wider mb-4 pb-3 border-b border-[#FF0055]/20">
                    История последних завершенных матчей
                </h3>

                <div class="space-y-2.5">
                    @foreach($recentMatches as $rM)
                        <div class="p-3.5 bg-[#08040A] rounded-2xl border border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs font-mono">
                            <div class="flex items-center space-x-3">
                                <span class="font-bold text-[#FF0055]">#{{ $rM->id }}</span>
                                <span class="text-white font-semibold">{{ $rM->player1->name }}</span>
                                <span class="font-cyber font-bold px-2 py-0.5 rounded bg-[#120817] border border-white/20 text-white">
                                    {{ $rM->score_player1 }} : {{ $rM->score_player2 }}
                                </span>
                                <span class="text-white font-semibold">{{ $rM->player2->name ?? 'Бот' }}</span>
                                @if($rM->forfeited_user_id)
                                    <span class="px-2 py-0.5 rounded bg-red-500/20 text-red-400 text-[10px] font-bold">FORFEIT</span>
                                @endif
                            </div>

                            <div class="flex items-center space-x-4">
                                <span class="text-neutral-400">{{ $rM->map ? $rM->map->name : 'Map' }}</span>
                                <span class="text-neutral-500">{{ $rM->finished_at ? $rM->finished_at->diffForHumans() : '' }}</span>
                                <a href="{{ route('match.lobby', ['uuid' => $rM->uuid]) }}" class="text-[#FF2A6D] hover:underline font-bold">
                                    Лобби →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- ====================================================================== -->
    <!-- TAB 4: MAP POOL MANAGEMENT                                             -->
    <!-- ====================================================================== -->
    @if($activeTab === 'mappool')
        <div class="bg-[#120817] border border-[#FF0055]/30 rounded-3xl p-6 sm:p-8 shadow-[0_0_35px_rgba(255,0,85,0.2)] space-y-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-4 border-b border-[#FF0055]/20 gap-3">
                <div>
                    <h2 class="text-2xl font-cyber font-black text-white uppercase tracking-wider">
                        СОРЕВНОВАТЕЛЬНЫЙ МАППУЛ CS2 (1v1)
                    </h2>
                    <p class="text-xs font-mono text-neutral-400 mt-0.5">
                        Включение/отключение карт в фазе Map Veto и принудительная смена карты на сервере
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($maps as $m)
                    <div class="relative bg-[#08040A] border-2 {{ $m->is_active ? 'border-[#FF0055]/50' : 'border-neutral-800 opacity-60' }} rounded-3xl overflow-hidden shadow-lg group transition hover:border-[#FF0055]">
                        <div class="h-36 overflow-hidden relative">
                            <img src="{{ $m->image }}" alt="{{ $m->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#08040A] via-transparent to-transparent"></div>
                            <div class="absolute top-3 right-3">
                                @if($m->is_active)
                                    <span class="px-2 py-1 rounded-lg bg-emerald-500 text-white font-cyber font-bold text-[10px] uppercase shadow">В ПУЛЕ</span>
                                @else
                                    <span class="px-2 py-1 rounded-lg bg-neutral-800 text-neutral-400 font-cyber font-bold text-[10px] uppercase">ОТКЛЮЧЕНА</span>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 space-y-3">
                            <div>
                                <h3 class="font-cyber font-bold text-lg text-white uppercase tracking-wider">{{ $m->name }}</h3>
                                <p class="text-xs font-mono text-neutral-400">Консольный код: <code class="text-[#FF0055]">{{ $m->code }}</code></p>
                            </div>

                            <div class="flex items-center space-x-2 pt-2 border-t border-white/10">
                                <button 
                                    wire:click="toggleMap({{ $m->id }})" 
                                    class="flex-1 py-2 text-xs font-cyber font-bold uppercase tracking-wider rounded-xl transition cursor-pointer {{ $m->is_active ? 'bg-neutral-800 hover:bg-neutral-700 text-neutral-300' : 'bg-emerald-600 hover:bg-emerald-500 text-white' }}">
                                    {{ $m->is_active ? 'Отключить' : 'Включить' }}
                                </button>

                                <button 
                                    wire:click="forceServerMap('{{ $m->code }}')" 
                                    title="Немедленно поставить эту карту на CS2 сервере через changelevel"
                                    class="px-3 py-2 bg-[#FF0055]/20 hover:bg-[#FF0055] text-[#FF0055] hover:text-white rounded-xl text-xs font-mono transition border border-[#FF0055]/40 cursor-pointer">
                                    ▶ На сервер
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- ====================================================================== -->
    <!-- TAB 5: RCON INTERACTIVE CONSOLE                                        -->
    <!-- ====================================================================== -->
    @if($activeTab === 'console')
        <div class="bg-[#120817] border border-[#FF0055]/30 rounded-3xl p-6 sm:p-8 shadow-[0_0_35px_rgba(255,0,85,0.2)] space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-[#FF0055]/20">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex items-center justify-center font-bold">💻</div>
                    <div>
                        <h2 class="text-xl font-cyber font-black text-white uppercase tracking-wider">
                            ЖИВАЯ RCON КОНСОЛЬ CS2 СЕРВЕРА
                        </h2>
                        <p class="text-xs font-mono text-neutral-400">
                            Прямой ввод команд разработчика в движок Source 2 Dedicated Server
                        </p>
                    </div>
                </div>

                <button 
                    wire:click="clearConsoleHistory" 
                    class="px-3.5 py-1.5 bg-neutral-900 hover:bg-neutral-800 text-neutral-400 hover:text-white text-xs font-mono rounded-xl border border-white/10 transition cursor-pointer">
                    Очистить вывод
                </button>
            </div>

            <!-- Terminal Output Box -->
            <div class="bg-[#050207] border-2 border-emerald-500/30 rounded-2xl p-5 font-mono text-xs text-emerald-400 h-96 overflow-y-auto space-y-4 shadow-inner">
                <div class="text-neutral-500 pb-2 border-b border-emerald-500/20">
                    === RIVALIS CS2 RCON INTERACTIVE TERMINAL // CONNECTED TO 89.248.236.141:27574 ===
                </div>

                @if(empty($consoleHistory))
                    <div class="text-neutral-500 italic py-6 text-center">
                        Консоль готова. Введите команду ниже (например: status, mp_restartgame 1, bot_kick) или используйте кнопки быстрого запуска.
                    </div>
                @else
                    @foreach(array_reverse($consoleHistory) as $item)
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2 text-white font-bold">
                                <span class="text-neutral-500">[{{ $item['time'] }}]</span>
                                <span class="text-[#FF0055]">CS2-RCON&gt;</span>
                                <span class="text-neutral-200">{{ $item['command'] }}</span>
                            </div>
                            <div class="pl-4 text-emerald-400 whitespace-pre-wrap leading-relaxed opacity-90">{{ $item['response'] }}</div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Command Input Bar -->
            <form wire:submit.prevent="executeRconCommand" class="flex items-center gap-3">
                <div class="relative flex-1">
                    <span class="absolute left-4 top-3.5 text-[#FF0055] font-mono font-bold select-none">&gt;</span>
                    <input 
                        type="text" 
                        wire:model="rconInput" 
                        placeholder="Введите RCON команду (например: status, sv_cheats 0, changelevel de_mirage, say [ADMIN]: привет)..."
                        class="w-full bg-[#050207] border border-[#FF0055]/40 text-emerald-300 font-mono text-sm px-4 py-3.5 pl-8 rounded-2xl focus:outline-none focus:border-[#FF0055]">
                </div>

                <button 
                    type="submit" 
                    class="px-8 py-3.5 bg-gradient-to-r from-[#FF0055] to-[#E1066A] hover:brightness-110 text-white font-cyber font-extrabold text-xs uppercase tracking-wider rounded-2xl shadow-[0_0_20px_rgba(255,0,85,0.4)] transition cursor-pointer">
                    Отправить
                </button>
            </form>

            <!-- Quick Console Snippets -->
            <div class="pt-2">
                <span class="text-xs font-mono uppercase text-neutral-400 block mb-2">Пресеты команд:</span>
                <div class="flex flex-wrap gap-2">
                    <button wire:click="executeRconCommand('status')" class="px-3 py-1.5 bg-[#08040A] hover:bg-neutral-800 text-neutral-300 text-xs font-mono rounded-xl border border-white/10 transition cursor-pointer">status</button>
                    <button wire:click="executeRconCommand('mp_restartgame 1')" class="px-3 py-1.5 bg-[#08040A] hover:bg-neutral-800 text-neutral-300 text-xs font-mono rounded-xl border border-white/10 transition cursor-pointer">mp_restartgame 1</button>
                    <button wire:click="executeRconCommand('bot_kick')" class="px-3 py-1.5 bg-[#08040A] hover:bg-neutral-800 text-neutral-300 text-xs font-mono rounded-xl border border-white/10 transition cursor-pointer">bot_kick</button>
                    <button wire:click="executeRconCommand('matchzy_remote_log_url &quot;http://45.140.19.240/api/matchzy/events&quot;')" class="px-3 py-1.5 bg-[#08040A] hover:bg-[#FF0055]/20 text-[#FF2A6D] text-xs font-mono rounded-xl border border-[#FF0055]/30 transition cursor-pointer">Привязать Webhook MatchZy</button>
                    <button wire:click="executeRconCommand('say [ADMIN]: Внимание, технические работы')" class="px-3 py-1.5 bg-[#08040A] hover:bg-neutral-800 text-neutral-300 text-xs font-mono rounded-xl border border-white/10 transition cursor-pointer">Сообщение в чат игры</button>
                </div>
            </div>
        </div>
    @endif

    <!-- ====================================================================== -->
    <!-- TAB 6: SERVER & WEBHOOK CONFIG                                         -->
    <!-- ====================================================================== -->
    @if($activeTab === 'server')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-6">
                <div class="bg-[#120817] border border-[#FF0055]/30 rounded-3xl p-6 sm:p-8 shadow-[0_0_25px_rgba(255,0,85,0.2)]">
                    <h2 class="text-xl font-cyber font-bold text-white uppercase tracking-wide mb-4 pb-3 border-b border-[#FF0055]/20">
                        Параметры RCON подключения
                    </h2>

                    <form wire:submit.prevent="saveSettings" class="space-y-4">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-2">
                                <label class="block text-xs font-mono uppercase tracking-wider text-neutral-400 mb-1.5">
                                    IP адрес сервера
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="serverIp" 
                                    placeholder="89.248.236.141"
                                    class="w-full bg-[#08040A] border border-[#FF0055]/30 text-white font-mono text-sm px-4 py-3 rounded-xl focus:outline-none focus:border-[#FF0055]">
                            </div>

                            <div>
                                <label class="block text-xs font-mono uppercase tracking-wider text-neutral-400 mb-1.5">
                                    Порт
                                </label>
                                <input 
                                    type="number" 
                                    wire:model="serverPort" 
                                    placeholder="27574"
                                    class="w-full bg-[#08040A] border border-[#FF0055]/30 text-white font-mono text-sm px-4 py-3 rounded-xl focus:outline-none focus:border-[#FF0055]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-mono uppercase tracking-wider text-neutral-400 mb-1.5">
                                RCON Пароль (из server.cfg)
                            </label>
                            <input 
                                type="password" 
                                wire:model="rconPassword" 
                                placeholder="Введите RCON пароль из server.cfg"
                                class="w-full bg-[#08040A] border border-[#FF0055]/30 text-white font-mono text-sm px-4 py-3 rounded-xl focus:outline-none focus:border-[#FF0055]">
                            <p class="text-[11px] text-neutral-500 mt-1.5 font-mono">
                                Панель Incloudgame → Файлы → game/csgo/cfg/server.cfg → строка <code>rcon_password</code>
                            </p>
                        </div>

                        <div class="pt-2 flex flex-col sm:flex-row items-center gap-3">
                            <button 
                                type="button" 
                                wire:click="testConnection"
                                class="w-full sm:w-auto px-5 py-3 bg-[#08040A] hover:bg-[#1A0D22] text-white font-cyber font-bold text-xs uppercase tracking-wider rounded-xl border border-[#FF0055]/30 hover:border-[#FF0055] transition flex items-center justify-center space-x-2 cursor-pointer">
                                <svg class="w-4 h-4 text-[#FF0055]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <span>Проверить RCON</span>
                            </button>

                            <button 
                                type="submit" 
                                class="w-full sm:w-auto px-7 py-3 bg-gradient-to-r from-[#FF0055] to-[#E1066A] hover:brightness-110 text-white font-cyber font-bold text-xs uppercase tracking-wider rounded-xl shadow-[0_0_20px_rgba(255,0,85,0.4)] transition cursor-pointer">
                                Сохранить настройки
                            </button>
                        </div>
                    </form>

                    @if($connectionStatus)
                        <div class="mt-6 pt-6 border-t border-[#FF0055]/20">
                            <div class="flex items-center space-x-2.5 mb-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $isConnected ? 'bg-emerald-400 shadow-[0_0_8px_#34d399]' : 'bg-red-500 shadow-[0_0_8px_red]' }}"></span>
                                <span class="text-xs font-mono font-bold {{ $isConnected ? 'text-emerald-400' : 'text-red-400' }}">
                                    {{ $connectionStatus }}
                                </span>
                            </div>

                            @if($serverConsoleOutput)
                                <div class="mt-3 bg-[#08040A] border border-[#FF0055]/30 rounded-xl p-4 font-mono text-xs text-emerald-400 overflow-x-auto max-h-60 whitespace-pre">
                                    {{ $serverConsoleOutput }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Incloudgame Info -->
            <div class="space-y-6">
                <div class="bg-[#120817] border border-[#FF0055]/30 rounded-3xl p-6 shadow-[0_0_25px_rgba(255,0,85,0.2)]">
                    <h3 class="text-sm font-cyber font-bold text-white uppercase tracking-wider mb-3 pb-2 border-b border-[#FF0055]/20">
                        О сервере Incloudgame
                    </h3>
                    <ul class="text-xs text-neutral-400 space-y-2.5 font-mono">
                        <li class="flex items-center justify-between py-1 border-b border-[#FF0055]/15">
                            <span>Имя:</span>
                            <span class="text-white font-semibold">UP FaceIt</span>
                        </li>
                        <li class="flex items-center justify-between py-1 border-b border-[#FF0055]/15">
                            <span>Адрес:</span>
                            <span class="text-emerald-400 font-bold">89.248.236.141:27574</span>
                        </li>
                        <li class="flex items-center justify-between py-1 border-b border-[#FF0055]/15">
                            <span>Протокол:</span>
                            <span class="text-white">Valve Source RCON</span>
                        </li>
                        <li class="flex items-center justify-between py-1">
                            <span>Статус:</span>
                            <span class="text-emerald-400 font-semibold flex items-center space-x-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                <span>ONLINE</span>
                            </span>
                        </li>
                    </ul>
                </div>

                <div class="bg-[#120817] border border-[#FF0055]/30 rounded-3xl p-6 text-xs text-neutral-400 shadow-[0_0_20px_rgba(255,0,85,0.15)]">
                    <h4 class="font-cyber font-bold text-white uppercase tracking-wider mb-3 pb-2 border-b border-[#FF0055]/20">
                        Автоматический Webhook
                    </h4>
                    <p class="text-[11px] text-neutral-300 font-mono leading-relaxed">
                        Плагин MatchZy на сервере автоматически отправляет события на адрес <code>http://45.140.19.240/api/matchzy/events</code>. Все фраги, счета и окончание матча обрабатываются сервером.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
