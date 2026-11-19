<div wire:poll.1000ms="pollQueue" class="space-y-6">
    <!-- Matchmaking Cooldown Alert if Banned -->
    @if($user && $user->isBanned())
        <div class="relative overflow-hidden rounded-2xl bg-red-950/40 border border-red-500/30 p-4 backdrop-blur-md mb-2">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-ping"></span>
                    <span class="font-heading font-bold text-sm text-red-200 uppercase">Временная блокировка поиска</span>
                    <span class="text-xs font-mono text-red-400">({{ $user->ban_reason ?? 'Неявка на матч' }})</span>
                </div>
                <span class="text-xs font-mono text-white">Разбан через: <strong>{{ sprintf('%02d:%02d', floor($user->banRemainingSeconds() / 60), $user->banRemainingSeconds() % 60) }}</strong></span>
            </div>
        </div>
    @endif

    <!-- The 3-Column Esports Layout (One-in-One with Screenshot) -->
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

                <!-- Dropdown Selector -->
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

                <!-- Active Mode Option (5v5 / 1v1 Competitive Ranked) -->
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
                <div class="px-2 py-2 flex items-center space-x-2 text-xs font-mono text-neutral-400 hover:text-white cursor-pointer transition">
                    <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>ANTI-CHEAT URL</span>
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

                <!-- 5 Player Rows -->
                <div class="space-y-1 text-xs font-mono">
                    @php
                        // Default mock fallback for screenshot 1-in-1 accuracy if not enough users
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
                            $isActiveUser = ($realUser && auth()->check() && auth()->id() === $realUser->id) || ($i === 1);
                        @endphp
                        <div class="flex items-center justify-between p-2 rounded-xl transition {{ $isActiveUser ? 'bg-[#181C25] border-l-[3px] border-[#FF5500]' : 'hover:bg-white/[0.02]' }}">
                            <!-- Rank Number & Player Info -->
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

                            <!-- Winrate & Elo -->
                            <div class="flex items-center space-x-3 text-right">
                                <span class="text-neutral-400 text-[11px]">{{ $pWinrate }}</span>
                                <span class="text-white font-bold text-[11px] tabular-nums">{{ $pElo }}</span>
                            </div>
                        </div>
                    @endfor
                </div>

                <!-- Last Match Delta Indicator (As in Screenshot) -->
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

            <!-- Card 1: PARTY LOBBY (5 Circular Slots with Glowing Orange Rings) -->
            <div class="bg-[#12151B] border border-white/[0.06] rounded-2xl p-5 shadow-sm">
                <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-neutral-400 mb-4">
                    PARTY LOBBY
                </div>

                <!-- 5 Circular Player Slots -->
                <div class="grid grid-cols-5 gap-2 sm:gap-4 items-center justify-items-center">
                    @php
                        $slots = [
                            ['lvl' => $user->level ?? 9, 'icon' => 'mic', 'avatar' => $user->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=Slot1', 'active' => true],
                            ['lvl' => 9, 'icon' => 'mic', 'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Slot2', 'active' => false],
                            ['lvl' => 1, 'icon' => 'mic', 'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Slot3', 'active' => true],
                            ['lvl' => 9, 'icon' => 'mic', 'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Slot4', 'active' => false],
                            ['lvl' => 9, 'icon' => 'mic', 'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Slot5', 'active' => true],
                        ];
                    @endphp

                    @foreach($slots as $slot)
                        <div class="flex flex-col items-center">
                            <!-- Slot Wrapper with Top-right Hexagon Badge -->
                            <div class="relative">
                                <!-- Top right star/crest icon -->
                                <div class="absolute -top-1 -right-1 text-amber-400 text-xs select-none">
                                    ★
                                </div>

                                <!-- Circular Avatar with Glowing Orange Border -->
                                <div class="w-13 h-13 sm:w-16 sm:h-16 rounded-full p-[2.5px] {{ $slot['active'] ? 'bg-gradient-to-tr from-[#FF5500] via-[#FF7728] to-[#FF3300] shadow-[0_0_15px_rgba(255,85,0,0.45)]' : 'bg-neutral-800 border border-white/[0.1]' }}">
                                    <img src="{{ $slot['avatar'] }}" class="w-full h-full rounded-full bg-neutral-900 object-cover" alt="">
                                </div>

                                <!-- Bottom Rank Badge Shield (Hexagon / Pill) -->
                                <div class="absolute -bottom-1.5 left-1/2 transform -translate-x-1/2 px-1.5 py-0.2 bg-[#12151B] border {{ $slot['active'] ? 'border-[#FF5500] text-[#FF5500]' : 'border-neutral-700 text-neutral-400' }} rounded-md font-heading font-black text-[10px] leading-tight shadow">
                                    {{ $slot['lvl'] }}
                                </div>
                            </div>

                            <!-- Bottom Mic Icon -->
                            <div class="mt-2.5 text-neutral-500 hover:text-neutral-300 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 003-3V5a3 3 0 10-6 0v6a3 3 0 003 3z"/>
                                </svg>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Card 2: GIANT VIBRANT ORANGE "FIND MATCH" BUTTON -->
            <div class="relative py-2 flex flex-col items-center justify-center">
                <!-- Background matrix grid subtle glow -->
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
                        class="mt-2 text-xs font-mono text-neutral-400 hover:text-white transition cursor-pointer">
                        Тренировочный тест с Ботом ↗
                    </button>
                @elseif($inQueue && ! $matchFound)
                    <!-- Active Searching Status -->
                    <div class="w-full max-w-md bg-[#161A22] border-2 border-[#FF5500] rounded-2xl p-5 shadow-[0_0_30px_rgba(255,85,0,0.35)] text-center">
                        <div class="flex items-center justify-center space-x-3 mb-3">
                            <div class="w-5 h-5 rounded-full border-2 border-white/[0.2] border-t-[#FF5500] animate-spin"></div>
                            <span class="font-heading font-bold text-sm text-white uppercase tracking-wider">
                                ПОИСК МАТЧА...
                            </span>
                            <span class="font-mono font-bold text-xl text-[#FF5500] tabular-nums">
                                {{ sprintf('%02d:%02d', floor($queueSeconds / 60), $queueSeconds % 60) }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button 
                                wire:click="cancelQueue" 
                                class="flex-1 py-2 bg-white/[0.06] hover:bg-white/[0.1] text-white text-xs font-mono rounded-xl border border-white/[0.08] transition">
                                Отменить
                            </button>
                            <button 
                                wire:click="startInstantBotMatch" 
                                class="flex-1 py-2 bg-[#FF5500]/20 hover:bg-[#FF5500]/30 text-[#FF5500] text-xs font-mono rounded-xl border border-[#FF5500]/30 transition">
                                Бот сразу
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Card 3: INTERACTIVE MAP VETO / MAP POOL (4 Cards with Real Map Photos + Toggles) -->
            <div class="bg-[#12151B] border border-white/[0.06] rounded-2xl p-5 shadow-sm">
                <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-neutral-400 mb-3">
                    INTERACTIVE MAP VETO / MAP POOL
                </div>

                <!-- 4 Map Cards Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @php
                        $mapsInteractive = [
                            ['name' => 'MIRAGE',  'img' => '/images/maps/de_mirage.jpg',  'active' => true],
                            ['name' => 'INFERNO', 'img' => '/images/maps/de_inferno.jpg', 'active' => false],
                            ['name' => 'NUKE',    'img' => '/images/maps/de_nuke.jpg',    'active' => false],
                            ['name' => 'ANUBIS',  'img' => '/images/maps/de_anubis.jpg',  'active' => false],
                        ];
                    @endphp

                    @foreach($mapsInteractive as $m)
                        <div class="bg-[#181C25] rounded-xl overflow-hidden border {{ $m['active'] ? 'border-2 border-[#FF5500] shadow-[0_0_20px_rgba(255,85,0,0.35)]' : 'border-white/[0.08]' }} flex flex-col group transition">
                            <!-- Map Screenshot Photo (Exact perspective from screenshot) -->
                            <div class="h-28 bg-neutral-900 bg-cover bg-center group-hover:scale-105 transition-transform duration-500" style="background-image: url('{{ $m['img'] }}')">
                            </div>

                            <!-- Bottom Bar: Map Title + Orange Pill Toggle Switch -->
                            <div class="p-2.5 bg-[#12151B] flex items-center justify-between border-t border-white/[0.06]">
                                <span class="font-heading font-black text-xs text-white uppercase tracking-wider">
                                    {{ $m['name'] }}
                                </span>

                                <!-- Orange Pill Toggle Switch (Exact from screenshot) -->
                                <div class="w-9 h-4.5 bg-[#FF5500] rounded-full p-0.5 flex items-center justify-end shadow-inner cursor-pointer">
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

            <!-- Card 1: LIVE PRO TOURNAMENT / MATCH CARD -->
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
                <div class="text-[10px] font-mono text-neutral-500 mb-4">
                    MATCH CARD
                </div>

                <!-- Teams Preview & Live Score -->
                <div class="bg-[#0B0D11] border border-white/[0.06] rounded-xl p-4 text-center">
                    <div class="flex items-center justify-around mb-3">
                        <!-- Team 1 -->
                        <div class="flex flex-col items-center">
                            <div class="w-9 h-9 text-[#FF5500] flex items-center justify-center">
                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2L2 12h3v8h6v-6h2v6h6v-8h3L12 2z"/>
                                </svg>
                            </div>
                            <span class="text-[11px] font-heading font-bold text-neutral-300 mt-1">Team 1</span>
                        </div>

                        <!-- Center LIVE badge -->
                        <div class="px-2.5 py-1 bg-[#FF5500] text-white font-heading font-bold text-[10px] rounded-md shadow-sm">
                            ● LIVE
                        </div>

                        <!-- Team 2 -->
                        <div class="flex flex-col items-center">
                            <div class="w-9 h-9 text-neutral-300 flex items-center justify-center">
                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                                </svg>
                            </div>
                            <span class="text-[11px] font-heading font-bold text-neutral-300 mt-1">Team 2</span>
                        </div>
                    </div>

                    <!-- Live Score -->
                    <div class="text-[10px] font-mono text-neutral-500 uppercase">LIVE SCORE</div>
                    <div class="text-3xl font-heading font-black text-white tracking-widest mt-0.5 tabular-nums">
                        {{ $liveMatch ? "{$liveMatch->score_player1} - {$liveMatch->score_player2}" : '14 - 12' }}
                    </div>

                    <div class="mt-3 pt-2.5 border-t border-white/[0.06] flex items-center justify-center space-x-1.5 text-[10px] font-mono text-neutral-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                        <span class="text-red-400 font-bold uppercase">LIVE</span>
                        <span>PRO MATCHPOINT</span>
                    </div>
                    <div class="font-heading font-black text-xs text-white uppercase tracking-wider mt-0.5">
                        TOURNAMENT
                    </div>
                </div>
            </div>

            <!-- Card 2: TOURNAMENT / $50,000 SEASON MAJOR BANNER -->
            <div class="relative bg-gradient-to-b from-[#181C25] to-[#0E1116] border border-white/[0.08] rounded-2xl p-6 text-center shadow-lg overflow-hidden group">
                <!-- Glowing Orange Chevron Corner Accents -->
                <div class="absolute -top-6 -left-6 w-16 h-16 bg-[#FF5500]/15 rounded-full blur-xl pointer-events-none"></div>
                <div class="absolute -bottom-6 -right-6 w-16 h-16 bg-[#FF5500]/15 rounded-full blur-xl pointer-events-none"></div>

                <div class="text-xs font-mono uppercase tracking-widest text-neutral-400 mb-1">
                    TOURNAMENT
                </div>

                <!-- Giant $50,000 Typography -->
                <div class="font-heading font-black text-3xl sm:text-4xl text-white tracking-tight my-2">
                    $50,000
                </div>

                <div class="font-heading font-extrabold text-xs uppercase tracking-widest text-[#FF5500]">
                    SEASON MAJOR
                </div>

                <!-- Carousel Pagination Dots (Exact from screenshot) -->
                <div class="flex items-center justify-center space-x-1.5 mt-5">
                    <span class="w-2 h-2 rounded-full bg-[#FF5500]"></span>
                    <span class="w-2 h-2 rounded-full bg-neutral-700"></span>
                </div>
            </div>

        </div>

    </div>

    <!-- Match Found Ready Check Modal (FACEIT Style) -->
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
                    Ожидаем подтверждения от соперника. Если он не подтвердит, нарушитель получит блокировку.
                @else
                    Подтвердите готовность к поединку до истечения таймера.
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
