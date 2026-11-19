<div wire:poll.1000ms="pollQueue" class="space-y-6">
    <!-- Matchmaking Cooldown Alert -->
    @if($user && $user->isBanned())
        <div class="relative overflow-hidden rounded-2xl bg-red-950/40 border border-red-500/30 p-5 backdrop-blur-md">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center space-x-3.5">
                    <div class="w-10 h-10 rounded-xl bg-red-500/10 text-red-400 border border-red-500/20 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center space-x-2">
                            <h3 class="text-sm font-semibold text-red-200 uppercase tracking-wide">
                                Временная блокировка матчмейкинга
                            </h3>
                            <span class="text-xs font-mono text-red-400 bg-red-500/10 px-2 py-0.5 rounded border border-red-500/20">
                                Cooldown
                            </span>
                        </div>
                        <p class="text-xs text-neutral-300 mt-1 font-mono">
                            Причина: <span class="text-white font-medium">{{ $user->ban_reason ?? 'Неявка на сервер / выход из матча' }}</span>
                        </p>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <div class="text-xs font-mono text-neutral-400">
                        Разблокировка через: <span class="text-white font-bold">{{ sprintf('%02d:%02d', floor($user->banRemainingSeconds() / 60), $user->banRemainingSeconds() % 60) }}</span>
                    </div>
                    <div class="text-[11px] font-mono text-red-400 mt-0.5">
                        До {{ $user->banned_until->format('H:i d.m.Y') }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- User Competitive Dashboard Bar (Real Esports HUD) -->
    <div class="bg-[#0B0E14] border border-white/[0.07] rounded-2xl p-5 shadow-sm">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
            <!-- Left: Player Level & Elo Summary -->
            <div class="flex items-center space-x-4 w-full lg:w-auto">
                <!-- Level Badge Shield -->
                <div class="relative shrink-0">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#1C2536] to-[#0E131C] border border-white/[0.12] flex flex-col items-center justify-center shadow-inner">
                        <span class="text-[9px] font-mono font-semibold uppercase text-neutral-400 -mb-0.5">LVL</span>
                        <span class="font-heading font-black text-2xl text-white">{{ $user->level }}</span>
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-md bg-[#FF5500] text-white flex items-center justify-center text-[10px] font-bold shadow">
                        ★
                    </div>
                </div>

                <div>
                    <div class="flex items-center space-x-2">
                        <span class="font-heading font-bold text-lg text-white">{{ $user->name }}</span>
                        @if($user->steam_id)
                            <span class="text-[10px] font-mono text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-1.5 py-0.5 rounded">
                                STEAM VERIFIED
                            </span>
                        @endif
                    </div>
                    <div class="flex items-baseline space-x-2 mt-0.5">
                        <span class="font-mono font-bold text-xl text-white tabular-nums">{{ $user->elo }}</span>
                        <span class="text-xs font-mono text-neutral-400 uppercase tracking-wider">ELO POINTS</span>
                    </div>
                </div>
            </div>

            <!-- Middle: Quick Performance Metrics -->
            <div class="grid grid-cols-3 gap-6 sm:gap-10 w-full lg:w-auto border-y lg:border-y-0 lg:border-x border-white/[0.06] py-3 lg:py-0 lg:px-8">
                <div>
                    <span class="text-[11px] font-mono uppercase tracking-wider text-neutral-400 block">Винрейт</span>
                    <div class="flex items-baseline space-x-1.5 mt-0.5">
                        <span class="font-mono font-bold text-lg text-white tabular-nums">{{ $user->winrate }}%</span>
                    </div>
                    <div class="w-20 bg-white/[0.06] h-1.5 rounded-full mt-1.5 overflow-hidden">
                        <div class="bg-[#FF5500] h-full rounded-full" style="width: {{ min(100, max(0, $user->winrate)) }}%"></div>
                    </div>
                </div>

                <div>
                    <span class="text-[11px] font-mono uppercase tracking-wider text-neutral-400 block">Матчей</span>
                    <div class="flex items-baseline space-x-1.5 mt-0.5">
                        <span class="font-mono font-bold text-lg text-white tabular-nums">{{ $user->matches_count }}</span>
                    </div>
                    <span class="text-[10px] font-mono text-neutral-500">1v1 Duels</span>
                </div>

                <div>
                    <span class="text-[11px] font-mono uppercase tracking-wider text-neutral-400 block">Побед / Поражений</span>
                    <div class="flex items-baseline space-x-1 mt-0.5 font-mono text-base font-semibold">
                        <span class="text-emerald-400 tabular-nums">{{ $user->wins_count }}W</span>
                        <span class="text-neutral-600">/</span>
                        <span class="text-neutral-400 tabular-nums">{{ $user->losses_count }}L</span>
                    </div>
                    <span class="text-[10px] font-mono text-neutral-500">Total Score</span>
                </div>
            </div>

            <!-- Right: Queue Status info -->
            <div class="flex items-center space-x-3 w-full lg:w-auto justify-end">
                <div class="text-right">
                    <div class="flex items-center justify-end space-x-1.5 text-xs font-mono text-emerald-400">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="font-medium">Сервер активен</span>
                    </div>
                    <span class="text-[11px] font-mono text-neutral-400">Incloudgame • 128 Tick</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Matchmaking Play Hub (Clean Esports Arena Design) -->
    <div class="bg-[#0B0E14] border border-white/[0.07] rounded-3xl p-6 sm:p-10 relative overflow-hidden shadow-sm">
        <div class="max-w-2xl mx-auto text-center">
            <!-- Format Badge -->
            <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-white/[0.04] border border-white/[0.08] text-neutral-300 text-xs font-mono mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[#FF5500]"></span>
                <span>CS2 1v1 COMPETITIVE • MAP VETO • ELO RANKED</span>
            </div>

            <!-- Main Headline -->
            <h1 class="text-3xl sm:text-4xl font-heading font-extrabold text-white tracking-tight">
                Соревновательный Поиск 1 на 1
            </h1>

            <p class="text-neutral-400 text-sm mt-2.5 max-w-lg mx-auto leading-relaxed">
                Сражайтесь один на один по турнирным правилам MR9. Автоматический подбор соперника равного уровня, бан карт и мгновенный пересчет очков Elo.
            </p>

            <!-- Action Area -->
            <div class="mt-8">
                @if(! $inQueue && ! $matchFound)
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5">
                        <!-- Find Match Primary Action -->
                        <button 
                            wire:click="startQueue" 
                            class="w-full sm:w-auto px-10 py-4 bg-[#FF5500] hover:bg-[#FF6E26] active:bg-[#D44400] text-white font-heading font-bold text-base tracking-wide rounded-xl shadow-[0_2px_16px_rgba(255,85,0,0.35)] transition-all transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center space-x-2.5 cursor-pointer">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                            </svg>
                            <span>НАЙТИ ИГРУ 1v1</span>
                        </button>

                        <!-- Bot Test Match Secondary Action -->
                        <button 
                            wire:click="startInstantBotMatch" 
                            class="w-full sm:w-auto px-6 py-4 bg-white/[0.04] hover:bg-white/[0.08] text-neutral-300 hover:text-white font-medium text-sm rounded-xl border border-white/[0.08] hover:border-white/[0.16] transition flex items-center justify-center space-x-2 cursor-pointer">
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Разминка с Ботом</span>
                        </button>
                    </div>

                    <!-- Additional Details Strip -->
                    <div class="mt-8 pt-6 border-t border-white/[0.06] flex flex-wrap items-center justify-center gap-6 text-xs font-mono text-neutral-400">
                        <div class="flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>MatchZy Телеметрия</span>
                        </div>
                        <div class="flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>5 минут на подключение</span>
                        </div>
                        <div class="flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span>7 Соревновательных карт</span>
                        </div>
                    </div>
                @elseif($inQueue && ! $matchFound)
                    <!-- Active Queue Searching Box -->
                    <div class="bg-[#0E131C] border border-white/[0.1] rounded-2xl p-6 max-w-md mx-auto shadow-sm">
                        <div class="flex items-center justify-center space-x-4 mb-4">
                            <div class="relative w-10 h-10 flex items-center justify-center">
                                <div class="w-10 h-10 rounded-full border-2 border-white/[0.1] border-t-[#FF5500] animate-spin"></div>
                            </div>
                            <div class="text-left">
                                <div class="text-xs font-mono text-neutral-400 uppercase tracking-wider">Идет поиск соперника</div>
                                <div class="text-2xl font-mono font-bold text-white tabular-nums">
                                    {{ sprintf('%02d:%02d', floor($queueSeconds / 60), $queueSeconds % 60) }}
                                </div>
                            </div>
                        </div>

                        <p class="text-xs font-mono text-neutral-400 mb-5 text-center">
                            Поиск подходящего оппонента в диапазоне ~{{ $user->elo }} ELO...
                        </p>

                        <div class="flex items-center space-x-2.5">
                            <button 
                                wire:click="cancelQueue" 
                                class="flex-1 py-2.5 bg-white/[0.05] hover:bg-white/[0.08] text-neutral-300 hover:text-white rounded-xl text-xs font-medium border border-white/[0.08] transition cursor-pointer">
                                Отменить
                            </button>
                            <button 
                                wire:click="startInstantBotMatch" 
                                class="flex-1 py-2.5 bg-[#FF5500]/15 hover:bg-[#FF5500]/25 text-[#FF8539] hover:text-white rounded-xl text-xs font-medium border border-[#FF5500]/30 transition cursor-pointer">
                                Играть с ботом
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Match Found Ready Check Modal (FACEIT Style) -->
    @if($matchFound)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-4 animate-fadeIn">
        <div class="bg-[#0E131C] border border-white/[0.12] rounded-3xl max-w-md w-full p-7 text-center relative overflow-hidden shadow-2xl">
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

            <p class="text-neutral-400 text-xs max-w-sm mx-auto mb-5">
                @if($accepted)
                    Ожидаем подтверждения от соперника. Если он не примет матч, нарушитель получит временный бан.
                @else
                    Подтвердите участие в дуэли. Непринятие матча наказывается блокировкой поиска.
                @endif
            </p>

            <!-- Countdown Bar -->
            <div class="mb-6 bg-white/[0.03] border border-white/[0.06] rounded-xl p-3 flex items-center justify-between text-xs font-mono">
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

    <!-- Competitive Map Pool Showcase -->
    <div class="bg-[#0B0E14] border border-white/[0.07] rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-white/[0.06]">
            <div>
                <h3 class="font-heading font-bold text-base text-white">Активный Маппул (7 Карт)</h3>
                <p class="text-xs text-neutral-400 mt-0.5 font-mono">Официальный соревновательный набор для стадии Map Veto</p>
            </div>
            <span class="text-xs font-mono text-neutral-500">VALVE ACTIVE DUTY</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-2.5">
            @php
                $mapsShowcase = [
                    ['name' => 'Mirage', 'code' => 'de_mirage', 'img' => '/images/maps/de_mirage.jpg'],
                    ['name' => 'Dust II', 'code' => 'de_dust2', 'img' => '/images/maps/de_dust2.jpg'],
                    ['name' => 'Inferno', 'code' => 'de_inferno', 'img' => '/images/maps/de_inferno.jpg'],
                    ['name' => 'Nuke', 'code' => 'de_nuke', 'img' => '/images/maps/de_nuke.jpg'],
                    ['name' => 'Ancient', 'code' => 'de_ancient', 'img' => '/images/maps/de_ancient.jpg'],
                    ['name' => 'Anubis', 'code' => 'de_anubis', 'img' => '/images/maps/de_anubis.jpg'],
                    ['name' => 'Vertigo', 'code' => 'de_vertigo', 'img' => '/images/maps/de_vertigo.jpg'],
                ];
            @endphp
            @foreach($mapsShowcase as $ms)
                <div class="relative rounded-xl overflow-hidden group aspect-[4/3] border border-white/[0.08]">
                    <img src="{{ $ms['img'] }}" alt="{{ $ms['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
                    <div class="absolute bottom-2 left-2 right-2">
                        <div class="text-[10px] font-mono text-neutral-400 uppercase">{{ $ms['code'] }}</div>
                        <div class="text-xs font-bold text-white font-heading">{{ $ms['name'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Matches History Table (Clean FACEIT / HLTV standard) -->
    <div class="bg-[#0B0E14] border border-white/[0.07] rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-white/[0.06] flex items-center justify-between">
            <div>
                <h3 class="font-heading font-bold text-base text-white">История последних матчей</h3>
                <p class="text-xs text-neutral-400 font-mono mt-0.5">Результаты ваших недавних соревновательных дуэлей</p>
            </div>
            <span class="text-xs font-mono text-neutral-400 bg-white/[0.04] px-2.5 py-1 rounded-lg border border-white/[0.06]">
                {{ $recentMatches->count() }} МАТЧЕЙ
            </span>
        </div>

        @if($recentMatches->isEmpty())
            <div class="text-center py-12 text-neutral-500 text-sm">
                <p class="font-medium text-neutral-400">У вас пока нет сыгранных матчей.</p>
                <p class="text-xs text-neutral-500 mt-1 font-mono">Нажмите «НАЙТИ ИГРУ 1v1», чтобы сыграть свою первую рейтинговую дуэль.</p>
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
                            <div class="bg-white/[0.04] px-3 py-1 rounded-lg border border-white/[0.06] font-mono font-bold text-sm text-white tabular-nums">
                                {{ $myScore }} : {{ $oppScore }}
                            </div>

                            <!-- Opponent -->
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-mono text-neutral-500">vs</span>
                                @if($opponent)
                                    <a href="{{ route('profile', ['id' => $opponent->id]) }}" class="text-sm font-medium text-neutral-300 hover:text-white transition">
                                        {{ $opponent->name }}
                                    </a>
                                    <span class="w-5 h-5 rounded flex items-center justify-center font-mono text-[10px] font-bold bg-white/[0.08] text-white">
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
                            <a href="{{ route('match.lobby', ['uuid' => $match->uuid]) }}" class="px-3 py-1.5 bg-white/[0.04] hover:bg-white/[0.08] text-neutral-300 hover:text-white rounded-lg text-xs font-medium border border-white/[0.08] transition">
                                Матч
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
