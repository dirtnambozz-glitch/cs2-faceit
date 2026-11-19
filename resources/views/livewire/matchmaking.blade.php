<div wire:poll.1000ms="pollQueue" class="space-y-6">
    <!-- Matchmaking Cooldown Alert if Banned -->
    @if($user && $user->isBanned())
        <div class="relative overflow-hidden rounded-2xl bg-red-950/40 border border-red-500/30 p-4 backdrop-blur-md mb-2">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex items-center space-x-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-ping"></span>
                    <span class="font-heading font-bold text-sm text-red-200 uppercase">Временная блокировка поиска</span>
                    <span class="text-xs font-mono text-red-400">({{ $user->ban_reason ?? 'Неявка на матч / лив' }})</span>
                </div>
                <div class="text-xs font-mono text-white">
                    Разбан через: <strong class="text-amber-400">{{ sprintf('%02d:%02d', floor($user->banRemainingSeconds() / 60), $user->banRemainingSeconds() % 60) }}</strong>
                    <span class="text-neutral-500 ml-1">(до {{ $user->banned_until->format('H:i d.m') }})</span>
                </div>
            </div>
        </div>
    @endif

    <!-- The 3-Column Esports Layout (1-in-1 Match with Screenshot) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

        <!-- ============================================================= -->
        <!-- COLUMN 1: LEFT SIDEBAR (LEADERBOARD / MODE & TOP 5 LADDER)    -->
        <!-- ============================================================= -->
        <div class="lg:col-span-3 space-y-4">
            <!-- Card 1: LEADERBOARD / Game Mode Selection -->
            <div class="bg-[#12151B] border border-white/[0.06] rounded-2xl p-4 shadow-sm">
                <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-neutral-400 mb-2.5">
                    LEADERBOARD
                </div>

                <!-- Dropdown Selector (Winrate) -->
                <div class="bg-[#0B0D11] border border-white/[0.08] rounded-xl px-3 py-2 flex items-center justify-between text-xs text-neutral-300 font-mono mb-3 cursor-pointer hover:border-white/[0.15] transition">
                    <div class="flex items-center space-x-2">
                        <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span>Winrate</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>

                <!-- Active Mode Option (1v1 / 5v5 Ranked Lobby) -->
                <div class="bg-[#181C25] border-l-[3px] border-[#FF5500] rounded-r-xl p-3 mb-2 flex items-center space-x-3 transition">
                    <span class="px-2 py-0.5 rounded bg-[#FF5500] text-white font-heading font-black text-xs">
                        1v1
                    </span>
                    <div class="leading-tight">
                        <div class="font-heading font-bold text-xs text-white uppercase tracking-wider">
                            COMPETITIVE SHOOTER
                        </div>
                        <div class="text-[10px] font-mono text-neutral-400 uppercase tracking-widest mt-0.5">
                            RANKED LOBBY
                        </div>
                    </div>
                </div>

                <!-- Anti-Cheat Status Link -->
                <div class="px-2 py-2 flex items-center justify-between text-xs font-mono text-neutral-400 hover:text-white cursor-pointer transition">
                    <div class="flex items-center space-x-2">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>ANTI-CHEAT URL</span>
                    </div>
                    <span class="text-[10px] text-emerald-400">ACTIVE</span>
                </div>

                <div class="text-center pt-1 text-neutral-500">
                    <svg class="w-3.5 h-3.5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>

            <!-- Card 2: TOP 5 REGIONAL LADDER -->
            <div class="bg-[#12151B] border border-white/[0.06] rounded-2xl p-4 shadow-sm">
                <div class="mb-3">
                    <div class="font-heading font-bold text-xs uppercase tracking-wider text-white">
                        TOP 5 REGIONAL LADDER
                    </div>
                    <div class="text-[10px] font-mono text-neutral-500">
                        Top 5 regional ladder
                    </div>
                </div>

                <!-- 5 Player Rows (Real DB data with proper names & elo) -->
                <div class="space-y-1 text-xs font-mono">
                    @php
                        $mockLadder = [
                            ['name' => 'Sarnest', 'winrate' => '95%', 'elo' => '2,840', 'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Sarnest'],
                            ['name' => 'Anaton',  'winrate' => '95%', 'elo' => '2,840', 'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Anaton'],
                            ['name' => 'Roroy',   'winrate' => '75%', 'elo' => '2,840', 'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Roroy'],
                            ['name' => 'Borberd', 'winrate' => '85%', 'elo' => '2,840', 'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Borberd'],
                            ['name' => 'Jeknar',  'winrate' => '75%', 'elo' => '2,840', 'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Jeknar'],
                        ];
                    @endphp

                    @for($i = 0; $i < 5; $i++)
                        @php
                            $realUser = $topPlayers[$i] ?? null;
                            $pName = $realUser ? $realUser->name : $mockLadder[$i]['name'];
                            $pWinrate = $realUser ? ($realUser->winrate . '%') : $mockLadder[$i]['winrate'];
                            $pElo = $realUser ? number_format($realUser->elo) : $mockLadder[$i]['elo'];
                            $pAvatar = $realUser ? ($realUser->avatar ?? $mockLadder[$i]['avatar']) : $mockLadder[$i]['avatar'];
                            $isActiveUser = ($realUser && auth()->check() && auth()->id() === $realUser->id) || ($i === 1 && !auth()->check());
                        @endphp
                        <div class="flex items-center justify-between p-2 rounded-xl transition {{ $isActiveUser ? 'bg-[#181C25] border-l-[3px] border-[#FF5500]' : 'hover:bg-white/[0.02]' }}">
                            <div class="flex items-center space-x-2.5">
                                <span class="text-neutral-500 font-bold w-3 text-center text-[11px]">{{ $i + 1 }}</span>
                                <div class="relative">
                                    <img src="{{ $pAvatar }}" class="w-6 h-6 rounded-full bg-neutral-900 border {{ $isActiveUser ? 'border-[#FF5500]' : 'border-white/[0.1]' }} object-cover" alt="">
                                </div>
                                <div class="leading-tight">
                                    <div class="text-xs font-semibold text-white {{ $isActiveUser ? 'text-[#FF5500]' : '' }}">
                                        {{ $pName }}
                                    </div>
                                    <div class="text-[9px] text-neutral-500">Rank</div>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 text-right">
                                <span class="text-neutral-400 text-[11px]">{{ $pWinrate }}</span>
                                <span class="text-white font-bold text-[11px] tabular-nums">{{ $pElo }}</span>
                            </div>
                        </div>
                    @endfor
                </div>

                <!-- Last Match Delta Indicator (Exact from screenshot) -->
                <div class="mt-3 pt-2.5 border-t border-white/[0.06] text-center">
                    <span class="text-emerald-400 font-mono font-bold text-xs tracking-wide">
                        {{ $lastEloDelta !== null ? ($lastEloDelta >= 0 ? "+{$lastEloDelta}" : $lastEloDelta) : '+25' }} ELO last match
                    </span>
                </div>
            </div>
        </div>

        <!-- ============================================================= -->
        <!-- COLUMN 2: CENTER (PARTY LOBBY, FIND MATCH, INTERACTIVE MAPS)  -->
        <!-- ============================================================= -->
        <div class="lg:col-span-6 space-y-4">

            <!-- Card 1: PARTY LOBBY (1v1 Focused: You + Opponent + Open Slots) -->
            <div class="bg-[#12151B] border border-white/[0.06] rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-neutral-400">
                        PARTY LOBBY
                    </div>
                    <span class="text-[10px] font-mono text-neutral-500">
                        РЕЖИМ: 1 НА 1 DUEL
                    </span>
                </div>

                <!-- 5 Slot Cards in Party Lobby -->
                <div class="grid grid-cols-5 gap-2 sm:gap-4 items-center justify-items-center">
                    <!-- SLOT 1: YOU (The Player) -->
                    <div class="flex flex-col items-center">
                        <div class="relative">
                            <!-- Golden Star Rank Crest on top right -->
                            <div class="absolute -top-1 -right-1 text-amber-400 text-xs select-none">
                                ★
                            </div>

                            <!-- Glowing Orange Circular Border -->
                            <div class="w-13 h-13 sm:w-16 sm:h-16 rounded-full p-[2.5px] bg-gradient-to-tr from-[#FF5500] via-[#FF7728] to-[#FF3300] shadow-[0_0_15px_rgba(255,85,0,0.5)]">
                                <img src="{{ $user->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed='.($user->name ?? 'player') }}" class="w-full h-full rounded-full bg-neutral-900 object-cover" alt="You">
                            </div>

                            <!-- Bottom Level Shield Badge -->
                            <div class="absolute -bottom-1.5 left-1/2 transform -translate-x-1/2 px-1.5 py-0.2 bg-[#12151B] border border-[#FF5500] text-[#FF5500] rounded-md font-heading font-black text-[10px] leading-tight shadow">
                                {{ $user->level ?? 10 }}
                            </div>
                        </div>

                        <!-- Mic icon -->
                        <div class="mt-2 text-neutral-300">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 003-3V5a3 3 0 10-6 0v6a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <span class="text-[10px] font-semibold text-white mt-0.5 truncate max-w-[65px] text-center">Вы</span>
                    </div>

                    <!-- SLOT 2: 1v1 OPPONENT / BOT SLOT -->
                    <div class="flex flex-col items-center">
                        @if($inQueue)
                            <!-- Searching opponent state -->
                            <div class="relative">
                                <div class="w-13 h-13 sm:w-16 sm:h-16 rounded-full border-2 border-dashed border-[#FF5500] flex items-center justify-center animate-spin">
                                    <div class="w-2 h-2 rounded-full bg-[#FF5500]"></div>
                                </div>
                                <div class="absolute -bottom-1.5 left-1/2 transform -translate-x-1/2 px-1 py-0.2 bg-[#12151B] border border-amber-500 text-amber-400 rounded-md font-mono text-[9px] leading-tight">
                                    ?
                                </div>
                            </div>
                            <div class="mt-2 text-neutral-500">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 003-3V5a3 3 0 10-6 0v6a3 3 0 003 3z"/></svg>
                            </div>
                            <span class="text-[9px] font-mono text-[#FF5500] mt-0.5 animate-pulse">Поиск...</span>
                        @else
                            <!-- Idle Opponent / Practice Slot -->
                            <div class="relative group cursor-pointer" wire:click="startInstantBotMatch" title="Нажмите для спавна тренировочного бота">
                                <div class="w-13 h-13 sm:w-16 sm:h-16 rounded-full border border-dashed border-white/[0.15] hover:border-[#FF5500] flex items-center justify-center bg-white/[0.02] group-hover:bg-[#FF5500]/10 transition">
                                    <svg class="w-5 h-5 text-neutral-500 group-hover:text-[#FF5500] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <div class="absolute -bottom-1.5 left-1/2 transform -translate-x-1/2 px-1 py-0.2 bg-[#12151B] border border-white/[0.1] text-neutral-500 rounded-md font-mono text-[9px] leading-tight">
                                    1v1
                                </div>
                            </div>
                            <div class="mt-2 text-neutral-600">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 003-3V5a3 3 0 10-6 0v6a3 3 0 003 3z"/></svg>
                            </div>
                            <span class="text-[9px] font-mono text-neutral-400 mt-0.5">Соперник</span>
                        @endif
                    </div>

                    <!-- SLOTS 3, 4, 5: OPEN SLOTS (Clean & Functional) -->
                    @for($s = 3; $s <= 5; $s++)
                        <div class="flex flex-col items-center opacity-60 hover:opacity-100 transition">
                            <div class="relative">
                                <div class="w-13 h-13 sm:w-16 sm:h-16 rounded-full border border-dashed border-white/[0.1] flex items-center justify-center bg-white/[0.01]">
                                    <span class="text-neutral-600 text-xs">+</span>
                                </div>
                                <div class="absolute -bottom-1.5 left-1/2 transform -translate-x-1/2 px-1 py-0.2 bg-[#12151B] border border-white/[0.06] text-neutral-600 rounded-md font-mono text-[9px] leading-tight">
                                    5v5
                                </div>
                            </div>
                            <div class="mt-2 text-neutral-700">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 003-3V5a3 3 0 10-6 0v6a3 3 0 003 3z"/></svg>
                            </div>
                            <span class="text-[9px] font-mono text-neutral-600 mt-0.5">Пусто</span>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Card 2: GIANT VIBRANT ORANGE "FIND MATCH" BUTTON -->
            <div class="relative py-2 flex flex-col items-center justify-center">
                <!-- Background matrix grid subtle glow (as in screenshot) -->
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_rgba(255,85,0,0.18)_0%,_transparent_70%)] pointer-events-none"></div>

                @if(! $inQueue && ! $matchFound)
                    <button 
                        wire:click="startQueue" 
                        class="w-full sm:w-auto px-16 sm:px-24 py-5 bg-gradient-to-r from-[#FF4800] via-[#FF5500] to-[#FF6B00] hover:brightness-110 active:scale-[0.98] text-white font-heading font-black text-xl sm:text-2xl uppercase tracking-wider rounded-2xl shadow-[0_4px_35px_rgba(255,85,0,0.55)] transition-all cursor-pointer border border-white/20 flex items-center justify-center space-x-3">
                        <span>FIND MATCH</span>
                    </button>
                    <!-- Small subtle quick bot training text below -->
                    <button 
                        wire:click="startInstantBotMatch" 
                        class="mt-2 text-xs font-mono text-neutral-400 hover:text-white transition cursor-pointer flex items-center space-x-1.5">
                        <svg class="w-3.5 h-3.5 text-[#FF5500]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>Запустить одиночную тренировку с Ботом (Демо)</span>
                    </button>
                @elseif($inQueue && ! $matchFound)
                    <!-- Active Searching Status (Full functional timer & controls) -->
                    <div class="w-full max-w-md bg-[#161A22] border-2 border-[#FF5500] rounded-2xl p-5 shadow-[0_0_30px_rgba(255,85,0,0.35)] text-center">
                        <div class="flex items-center justify-center space-x-3 mb-2">
                            <div class="w-5 h-5 rounded-full border-2 border-white/[0.2] border-t-[#FF5500] animate-spin"></div>
                            <span class="font-heading font-bold text-sm text-white uppercase tracking-wider">
                                ПОИСК СОПЕРНИКА...
                            </span>
                            <span class="font-mono font-bold text-xl text-[#FF5500] tabular-nums">
                                {{ sprintf('%02d:%02d', floor($queueSeconds / 60), $queueSeconds % 60) }}
                            </span>
                        </div>
                        <p class="text-xs text-neutral-400 font-mono mb-4">
                            Подбор игрока близкого рейтинга (~{{ $user->elo }} ELO)...
                        </p>
                        <div class="flex items-center space-x-2">
                            <button 
                                wire:click="cancelQueue" 
                                class="flex-1 py-2.5 bg-white/[0.06] hover:bg-white/[0.1] text-white text-xs font-mono rounded-xl border border-white/[0.08] transition cursor-pointer">
                                Отменить поиск
                            </button>
                            <button 
                                wire:click="startInstantBotMatch" 
                                class="flex-1 py-2.5 bg-[#FF5500]/20 hover:bg-[#FF5500]/30 text-[#FF5500] text-xs font-mono rounded-xl border border-[#FF5500]/30 transition cursor-pointer">
                                Играть с Ботом
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Card 3: INTERACTIVE MAP VETO / MAP POOL (Live Clickable Toggles) -->
            <div class="bg-[#12151B] border border-white/[0.06] rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-neutral-400">
                        INTERACTIVE MAP VETO / MAP POOL
                    </div>
                    <span class="text-[10px] font-mono text-neutral-500">ВЫБЕРИТЕ КАРТЫ ДЛЯ МАТЧА</span>
                </div>

                <!-- 4 Map Cards Grid (Exact from Screenshot) -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @php
                        $mapsInteractive = [
                            ['code' => 'de_mirage',  'name' => 'MIRAGE',  'img' => '/images/maps/de_mirage.jpg'],
                            ['code' => 'de_inferno', 'name' => 'INFERNO', 'img' => '/images/maps/de_inferno.jpg'],
                            ['code' => 'de_nuke',    'name' => 'NUKE',    'img' => '/images/maps/de_nuke.jpg'],
                            ['code' => 'de_anubis',  'name' => 'ANUBIS',  'img' => '/images/maps/de_anubis.jpg'],
                        ];
                    @endphp

                    @foreach($mapsInteractive as $m)
                        @php
                            $isEnabled = in_array($m['code'], $selectedMaps);
                        @endphp
                        <div 
                            wire:click="toggleMap('{{ $m['code'] }}')"
                            class="bg-[#181C25] rounded-xl overflow-hidden border {{ $isEnabled ? 'border-2 border-[#FF5500] shadow-[0_0_20px_rgba(255,85,0,0.35)]' : 'border-white/[0.08] opacity-60 hover:opacity-100' }} flex flex-col group transition cursor-pointer">
                            <!-- Real CS2 Map Screenshot Photo -->
                            <div class="h-28 bg-neutral-900 bg-cover bg-center group-hover:scale-105 transition-transform duration-500" style="background-image: url('{{ $m['img'] }}')">
                            </div>

                            <!-- Bottom Bar: Map Title + Working Orange Pill Toggle Switch -->
                            <div class="p-2.5 bg-[#12151B] flex items-center justify-between border-t border-white/[0.06]">
                                <span class="font-heading font-black text-xs text-white uppercase tracking-wider">
                                    {{ $m['name'] }}
                                </span>

                                <!-- Working Livewire Orange Pill Toggle Switch -->
                                <div class="w-9 h-4.5 {{ $isEnabled ? 'bg-[#FF5500]' : 'bg-neutral-800' }} rounded-full p-0.5 flex items-center {{ $isEnabled ? 'justify-end' : 'justify-start' }} transition-colors shadow-inner">
                                    <div class="w-3.5 h-3.5 bg-white rounded-full shadow"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- ============================================================= -->
        <!-- COLUMN 3: RIGHT SIDEBAR (LIVE PRO MATCH & TOURNAMENT MAJOR)    -->
        <!-- ============================================================= -->
        <div class="lg:col-span-3 space-y-4">

            <!-- Card 1: LIVE PRO TOURNAMENT / MATCH CARD (Using Generated Team Emblems) -->
            <div class="bg-[#12151B] border border-white/[0.06] rounded-2xl p-4 shadow-sm">
                <!-- Header -->
                <div class="flex items-center justify-between mb-1">
                    <div class="font-heading font-bold text-xs uppercase tracking-wider text-white">
                        LIVE PRO TOURNAMENT
                    </div>
                    <span class="px-2 py-0.5 rounded-full bg-red-500/15 border border-red-500/30 text-red-400 font-mono font-bold text-[9px] uppercase tracking-wider flex items-center space-x-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                        <span>LIVE</span>
                    </span>
                </div>
                <div class="text-[10px] font-mono text-neutral-500 mb-3">
                    MATCH CARD
                </div>

                <!-- Teams Preview & Live Score -->
                <div class="bg-[#0B0D11] border border-white/[0.06] rounded-xl p-4 text-center">
                    <div class="flex items-center justify-around mb-2">
                        <!-- Team 1 with Generated Phoenix Emblem -->
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-xl overflow-hidden shadow-sm">
                                <img src="/images/team-falcon.jpg" class="w-full h-full object-cover" alt="Team 1">
                            </div>
                            <span class="text-[11px] font-heading font-bold text-neutral-300 mt-1">Team 1</span>
                        </div>

                        <!-- Center LIVE badge -->
                        <div class="px-2.5 py-1 bg-[#FF5500] text-white font-heading font-bold text-[10px] rounded-md shadow-sm">
                            ● LIVE
                        </div>

                        <!-- Team 2 with Generated Crowned Shield Emblem -->
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-xl overflow-hidden shadow-sm">
                                <img src="/images/team-shield.jpg" class="w-full h-full object-cover" alt="Team 2">
                            </div>
                            <span class="text-[11px] font-heading font-bold text-neutral-300 mt-1">Team 2</span>
                        </div>
                    </div>

                    <!-- Live Score -->
                    <div class="text-[10px] font-mono text-neutral-500 uppercase">LIVE SCORE</div>
                    <div class="text-3xl font-heading font-black text-white tracking-widest mt-0.5 tabular-nums">
                        {{ $liveMatch ? "{$liveMatch->score_player1} - {$liveMatch->score_player2}" : '14 - 12' }}
                    </div>

                    <div class="mt-3 pt-2 border-t border-white/[0.06] flex items-center justify-center space-x-1.5 text-[10px] font-mono text-neutral-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                        <span class="text-red-400 font-bold uppercase">LIVE</span>
                        <span>PRO MATCHPOINT</span>
                    </div>
                    <div class="font-heading font-black text-xs text-white uppercase tracking-wider mt-0.5">
                        TOURNAMENT
                    </div>
                </div>
            </div>

            <!-- Card 2: TOURNAMENT / $50,000 SEASON MAJOR BANNER (Using Generated 16:9 Backdrop) -->
            <div class="relative rounded-2xl overflow-hidden border border-white/[0.08] shadow-lg text-center p-6 bg-cover bg-center min-h-[190px] flex flex-col justify-between" style="background-image: url('/images/major-banner.jpg')">
                <div class="absolute inset-0 bg-black/55 backdrop-blur-[1px]"></div>

                <div class="relative z-10 text-xs font-mono uppercase tracking-widest text-neutral-300">
                    TOURNAMENT
                </div>

                <div class="relative z-10 font-heading font-black text-3xl sm:text-4xl text-white tracking-tight my-1 drop-shadow-md">
                    $50,000
                </div>

                <div class="relative z-10 font-heading font-extrabold text-xs uppercase tracking-widest text-[#FF5500] drop-shadow">
                    SEASON MAJOR
                </div>

                <!-- Carousel Dots (Exact from screenshot) -->
                <div class="relative z-10 flex items-center justify-center space-x-1.5 mt-2">
                    <span class="w-2 h-2 rounded-full bg-[#FF5500]"></span>
                    <span class="w-2 h-2 rounded-full bg-neutral-600"></span>
                </div>
            </div>

        </div>

    </div>

    <!-- ============================================================= -->
    <!-- REAL FUNCTIONALITY: RECENT MATCHES HISTORY (PRESERVED & CLEAN)-->
    <!-- ============================================================= -->
    <div class="bg-[#12151B] border border-white/[0.06] rounded-2xl overflow-hidden shadow-sm mt-8">
        <div class="p-5 border-b border-white/[0.06] flex items-center justify-between">
            <div>
                <h3 class="font-heading font-bold text-base text-white uppercase tracking-wider">
                    История ваших недавних матчей
                </h3>
                <p class="text-xs text-neutral-400 font-mono mt-0.5">
                    Статистика дуэлей, изменение Elo и результаты раундов
                </p>
            </div>
            <span class="text-xs font-mono text-neutral-400 bg-white/[0.04] px-2.5 py-1 rounded-lg border border-white/[0.06]">
                {{ $recentMatches->count() }} СЫГРАНО
            </span>
        </div>

        @if($recentMatches->isEmpty())
            <div class="text-center py-12 text-neutral-500 text-sm">
                <p class="font-medium text-neutral-400">У вас пока нет сыгранных матчей в этом сезоне.</p>
                <p class="text-xs text-neutral-500 mt-1 font-mono">Нажмите «FIND MATCH» выше, чтобы начать соревнование!</p>
            </div>
        @else
            <div class="divide-y divide-white/[0.04]">
                @foreach($recentMatches as $match)
                    @php
                        $isPlayer1 = ($match->player1_id === $user->id);
                        $isWinner = ($match->winner_id === $user->id);
                        $opponent = $isPlayer1 ? $match->player2 : $match->player1;
                        $myScore = $isPlayer1 ? $match->score_player1 : $match->score_player2;
                        $oppScore = $isPlayer1 ? $match->score_player2 : $match->score_player1;
                        $eloDelta = $isPlayer1 ? $match->elo_change_p1 : $match->elo_change_p2;
                    @endphp
                    <div class="p-4 hover:bg-white/[0.02] flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-colors">
                        <!-- Left: Status & Map -->
                        <div class="flex items-center space-x-3.5">
                            @if($match->status === 'finished')
                                <span class="w-24 text-center py-1 rounded-md text-xs font-mono font-bold uppercase tracking-wider {{ $isWinner ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/25' : 'bg-red-500/10 text-red-400 border border-red-500/25' }}">
                                    {{ $isWinner ? 'ПОБЕДА' : 'ПОРАЖЕНИЕ' }}
                                </span>
                            @else
                                <span class="w-24 text-center py-1 rounded-md text-xs font-mono font-bold uppercase tracking-wider bg-amber-500/10 text-amber-400 border border-amber-500/25">
                                    {{ $match->status }}
                                </span>
                            @endif

                            <div>
                                <div class="text-sm font-semibold text-white">
                                    {{ $match->map ? $match->map->name : 'Map Veto' }}
                                </div>
                                <div class="text-xs font-mono text-neutral-500">
                                    {{ $match->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>

                        <!-- Right: Opponent, Score, Elo, Button -->
                        <div class="flex items-center space-x-4 sm:space-x-6 justify-between sm:justify-end">
                            <!-- Score -->
                            <div class="bg-white/[0.04] px-3.5 py-1 rounded-lg border border-white/[0.06] font-mono font-bold text-sm text-white tabular-nums">
                                {{ $myScore }} : {{ $oppScore }}
                            </div>

                            <!-- Opponent -->
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-mono text-neutral-500">vs</span>
                                @if($opponent)
                                    <a href="{{ route('profile', ['id' => $opponent->id]) }}" class="text-sm font-medium text-neutral-300 hover:text-[#FF5500] transition">
                                        {{ $opponent->name }}
                                    </a>
                                    <span class="w-5 h-5 rounded flex items-center justify-center font-mono text-[10px] font-bold bg-[#FF5500]/20 text-[#FF5500] border border-[#FF5500]/30">
                                        {{ $opponent->level }}
                                    </span>
                                @else
                                    <span class="text-sm font-medium text-neutral-400">Оппонент</span>
                                @endif
                            </div>

                            <!-- Elo Change -->
                            <div class="w-14 text-right font-mono font-bold text-sm tabular-nums">
                                @if($eloDelta !== null)
                                    <span class="{{ $eloDelta >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                        {{ $eloDelta >= 0 ? "+{$eloDelta}" : $eloDelta }}
                                    </span>
                                @else
                                    <span class="text-neutral-500">—</span>
                                @endif
                            </div>

                            <!-- Lobby Link -->
                            <a href="{{ route('match.lobby', ['uuid' => $match->uuid]) }}" class="px-3 py-1.5 bg-[#FF5500]/10 hover:bg-[#FF5500] text-[#FF5500] hover:text-white rounded-lg text-xs font-medium border border-[#FF5500]/30 transition">
                                Комната матча →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- ============================================================= -->
    <!-- MATCH FOUND READY CHECK MODAL POPUP (FACEIT STYLE)            -->
    <!-- ============================================================= -->
    @if($matchFound)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/85 backdrop-blur-md p-4 animate-fadeIn">
        <div class="bg-[#12151B] border-2 border-[#FF5500] rounded-3xl max-w-md w-full p-7 text-center relative overflow-hidden shadow-2xl">
            <!-- Top Status Bar -->
            <div class="absolute top-0 left-0 right-0 h-1.5 {{ $accepted ? 'bg-emerald-500' : 'bg-[#FF5500]' }}"></div>

            <!-- Header Icon -->
            <div class="w-16 h-16 rounded-2xl {{ $accepted ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-[#FF5500]/10 text-[#FF5500] border border-[#FF5500]/30' }} flex items-center justify-center mx-auto mb-4">
                @if($accepted)
                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                @else
                    <svg class="w-8 h-8 text-[#FF5500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                @endif
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-heading font-extrabold text-white">
                {{ $accepted ? 'Матч подтвержден!' : 'Матч найден!' }}
            </h2>

            <!-- Readiness Badge: 1/2 or 2/2 -->
            <div class="my-3 inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-white/[0.04] border border-white/[0.08]">
                <span class="w-2 h-2 rounded-full {{ $acceptedCount >= 2 ? 'bg-emerald-400' : 'bg-[#FF5500] animate-pulse' }}"></span>
                <span class="text-xs font-mono font-medium text-neutral-300">
                    Готовность: <span class="font-bold {{ $acceptedCount >= 2 ? 'text-emerald-400' : 'text-[#FF5500]' }}">{{ $acceptedCount }} / 2</span>
                </span>
            </div>

            <p class="text-neutral-400 text-xs max-w-sm mx-auto mb-5 font-mono">
                @if($accepted)
                    Ожидаем подтверждения от соперника. Если он не подтвердит за отведенное время, нарушитель получит блокировку.
                @else
                    Подтвердите готовность к поединку 1v1 до истечения таймера.
                @endif
            </p>

            <!-- Countdown Bar -->
            <div class="mb-6 bg-[#0B0D11] border border-white/[0.08] rounded-xl p-3 flex items-center justify-between text-xs font-mono">
                <span class="text-neutral-400">ВРЕМЯ НА ПРИНЯТИЕ:</span>
                <span class="font-bold text-lg tabular-nums {{ $acceptRemainingSeconds <= 5 ? 'text-red-400 animate-pulse' : 'text-white' }}">
                    {{ sprintf('%02d', max(0, $acceptRemainingSeconds)) }} сек
                </span>
            </div>

            <!-- Action Button -->
            <div>
                @if(! $accepted)
                    <button 
                        wire:click="acceptMatch" 
                        class="w-full py-4 bg-emerald-500 hover:bg-emerald-400 text-white font-heading font-bold text-lg rounded-xl shadow-[0_2px_15px_rgba(16,185,129,0.35)] transition cursor-pointer flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>ПРИНЯТЬ МАТЧ</span>
                    </button>
                @else
                    <div class="w-full py-3.5 bg-emerald-500/10 border border-emerald-500/25 rounded-xl text-emerald-300 text-sm font-medium flex items-center justify-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Ожидание оппонента ({{ $acceptedCount }}/2)...</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
