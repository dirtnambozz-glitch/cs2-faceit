<div wire:poll.1000ms="pollQueue" class="space-y-6">
    <!-- Matchmaking Ban Alert Banner -->
    @if($user && $user->isBanned())
        <div class="p-5 bg-[#181116] border border-red-500/40 rounded-2xl shadow-[0_4px_25px_rgba(239,68,68,0.2)] flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-red-500/10 text-red-400 border border-red-500/30 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-faceit font-bold text-red-200 uppercase tracking-wider flex items-center space-x-2">
                        <span>БЛОКИРОВКА МАТЧМЕЙКИНГА (COOLDOWN)</span>
                    </h3>
                    <p class="text-xs text-neutral-300 font-mono mt-0.5">
                        Причина: <span class="text-white font-semibold">{{ $user->ban_reason ?? 'Нарушение регламента очереди' }}</span>
                    </p>
                    <p class="text-[11px] text-neutral-400 mt-1">
                        Предупреждений: <span class="text-amber-400 font-bold font-mono">{{ $user->warnings_count }}</span> • Разблокировка через: <span class="text-white font-mono font-bold">{{ sprintf('%02d:%02d', floor($user->banRemainingSeconds() / 60), $user->banRemainingSeconds() % 60) }}</span>
                    </p>
                </div>
            </div>
            <div class="text-right shrink-0">
                <span class="px-3.5 py-1.5 rounded-lg bg-red-950/80 border border-red-500/40 text-red-200 text-xs font-mono font-bold">
                    До {{ $user->banned_until->format('H:i:s d.m.Y') }}
                </span>
            </div>
        </div>
    @endif

    <!-- MAIN BATTLE STATION (2 Columns Grid) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- LEFT 8 COLS: 1V1 QUEUE LAUNCHER & ARENA -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Arena Main Card -->
            <div class="bg-[#121622] border border-[#1F273B] rounded-2xl overflow-hidden shadow-xl relative">
                <!-- Atmospheric Top Header with CS2 Map Montage -->
                <div class="relative h-44 sm:h-52 bg-[#0E121B] overflow-hidden">
                    <img src="/images/rivalis-hero.jpg" alt="CS2 1v1 Arena" class="w-full h-full object-cover object-center opacity-30 filter contrast-125">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#121622] via-[#121622]/60 to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-[#121622] via-transparent to-[#121622]"></div>
                    
                    <!-- Content inside Header -->
                    <div class="absolute bottom-4 left-6 right-6 flex flex-col sm:flex-row sm:items-end justify-between gap-3">
                        <div>
                            <div class="inline-flex items-center space-x-2 px-2.5 py-1 rounded-md bg-[#0A0D14]/80 border border-white/10 text-[11px] font-mono text-[#FF5500] mb-1.5 backdrop-blur-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#FF5500] animate-pulse"></span>
                                <span>OFFICIAL 1V1 MATCHMAKING • SEASON 1</span>
                            </div>
                            <h1 class="text-2xl sm:text-4xl font-faceit font-black text-white uppercase tracking-wider leading-none">
                                СОРЕВНОВАТЕЛЬНАЯ АРЕНА 1 НА 1
                            </h1>
                        </div>
                        <div class="hidden sm:flex items-center space-x-2 text-xs font-mono text-neutral-400">
                            <span class="px-2.5 py-1 rounded-lg bg-[#0A0D14]/80 border border-[#1F273B] text-emerald-400 font-bold">128 TICK</span>
                            <span class="px-2.5 py-1 rounded-lg bg-[#0A0D14]/80 border border-[#1F273B] text-white">MAP VETO</span>
                        </div>
                    </div>
                </div>

                <!-- Arena Body -->
                <div class="p-6">
                    <!-- Map Pool Preview Strip -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between text-xs font-mono text-neutral-400 mb-2.5">
                            <span class="uppercase tracking-wider">АКТИВНЫЙ МАППУЛ (7 КАРТ CS2):</span>
                            <span class="text-neutral-500">Система вычеркивания перед матчем</span>
                        </div>
                        <div class="grid grid-cols-7 gap-2">
                            @php
                                $mapsPreview = [
                                    ['name' => 'Mirage', 'code' => 'de_mirage', 'img' => '/images/maps/de_mirage.jpg'],
                                    ['name' => 'Inferno', 'code' => 'de_inferno', 'img' => '/images/maps/de_inferno.jpg'],
                                    ['name' => 'Nuke', 'code' => 'de_nuke', 'img' => '/images/maps/de_nuke.jpg'],
                                    ['name' => 'Dust II', 'code' => 'de_dust2', 'img' => '/images/maps/de_dust2.jpg'],
                                    ['name' => 'Ancient', 'code' => 'de_ancient', 'img' => '/images/maps/de_ancient.jpg'],
                                    ['name' => 'Anubis', 'code' => 'de_anubis', 'img' => '/images/maps/de_anubis.jpg'],
                                    ['name' => 'Vertigo', 'code' => 'de_vertigo', 'img' => '/images/maps/de_vertigo.jpg'],
                                ];
                            @endphp
                            @foreach($mapsPreview as $mp)
                                <div class="relative rounded-lg overflow-hidden border border-[#1F273B] group bg-[#0A0D14] h-14 sm:h-16" title="{{ $mp['name'] }} ({{ $mp['code'] }})">
                                    <img src="{{ $mp['img'] }}" alt="{{ $mp['name'] }}" class="w-full h-full object-cover opacity-60 group-hover:opacity-90 transition-opacity">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-1">
                                        <span class="text-[9px] font-faceit font-bold text-white uppercase tracking-tight truncate w-full text-center">
                                            {{ $mp['name'] }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- QUEUE ACTION CONTROLLER -->
                    <div class="bg-[#0E121B] border border-[#1F273B] rounded-2xl p-6 relative">
                        @if(! $inQueue && ! $matchFound)
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                                        <h3 class="font-faceit font-bold text-lg text-white uppercase tracking-wider">
                                            Поиск соревновательной дуэли
                                        </h3>
                                    </div>
                                    <p class="text-xs font-mono text-neutral-400">
                                        Матчмейкинг найдет соперника с близким рейтингом (текущий: <strong class="text-white">{{ $user->elo }} ELO</strong>).
                                    </p>
                                </div>

                                <div class="flex flex-col sm:flex-row items-center gap-3 shrink-0">
                                    <!-- Main Queue Button -->
                                    <button 
                                        wire:click="startQueue" 
                                        class="w-full sm:w-auto px-7 py-4 bg-[#FF5500] hover:bg-[#FF6E1A] active:bg-[#D94000] text-white font-faceit font-extrabold text-base tracking-wider uppercase rounded-xl shadow-[0_4px_20px_rgba(255,85,0,0.35)] transition transform active:scale-95 flex items-center justify-center space-x-2.5 cursor-pointer">
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                                        </svg>
                                        <span>НАЙТИ ИГРУ 1V1</span>
                                    </button>

                                    <!-- Quick Practice Bot Duel -->
                                    <button 
                                        wire:click="startInstantBotMatch" 
                                        class="w-full sm:w-auto px-4 py-4 bg-[#171C2B] hover:bg-[#1B2236] text-neutral-300 hover:text-white font-faceit font-bold text-xs uppercase tracking-wider rounded-xl border border-[#1F273B] transition flex items-center justify-center space-x-2 cursor-pointer"
                                        title="Быстрый тестовый матч с ботом для проверки сервера и карты">
                                        <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Тест с ботом</span>
                                    </button>
                                </div>
                            </div>
                        @elseif($inQueue && ! $matchFound)
                            <!-- Searching Radar HUD -->
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                                <div class="flex items-center space-x-4">
                                    <div class="relative w-14 h-14 flex items-center justify-center shrink-0">
                                        <div class="absolute inset-0 rounded-full border-2 border-[#FF5500]/30 border-t-[#FF5500] animate-spin"></div>
                                        <div class="w-8 h-8 rounded-full bg-[#FF5500]/10 flex items-center justify-center">
                                            <span class="w-2.5 h-2.5 rounded-full bg-[#FF5500] animate-pulse"></span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <span class="font-faceit font-bold text-lg text-white uppercase tracking-wider">
                                                ПОИСК СОПЕРНИКА 1V1
                                            </span>
                                            <span class="font-mono text-xs text-[#FF5500] bg-[#FF5500]/10 border border-[#FF5500]/30 px-2 py-0.5 rounded">
                                                LIVE
                                            </span>
                                        </div>
                                        <p class="text-xs text-neutral-400 font-mono mt-0.5">
                                            Диапазон ELO: ~{{ max(0, $user->elo - 120) }} — {{ $user->elo + 120 }} • Очередь активна
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <!-- Timer -->
                                    <div class="text-right">
                                        <span class="text-[10px] font-mono uppercase text-neutral-500 block">ВРЕМЯ В ПОИСКЕ</span>
                                        <span class="text-2xl font-mono font-bold text-white tracking-wider">
                                            {{ sprintf('%02d:%02d', floor($queueSeconds / 60), $queueSeconds % 60) }}
                                        </span>
                                    </div>

                                    <!-- Cancel / Bot buttons -->
                                    <div class="flex items-center space-x-2">
                                        <button 
                                            wire:click="cancelQueue" 
                                            class="px-4 py-2.5 bg-[#171C2B] hover:bg-neutral-800 text-neutral-300 hover:text-white rounded-xl text-xs font-faceit font-bold uppercase tracking-wider border border-[#1F273B] transition cursor-pointer">
                                            Отмена
                                        </button>
                                        <button 
                                            wire:click="startInstantBotMatch" 
                                            class="px-3.5 py-2.5 bg-[#FF5500]/15 hover:bg-[#FF5500]/25 text-[#FF5500] border border-[#FF5500]/40 rounded-xl text-xs font-faceit font-bold uppercase tracking-wider transition cursor-pointer">
                                            Бот сразу
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Telemetry Badges Footer -->
                    <div class="mt-4 pt-4 border-t border-[#1F273B] flex flex-wrap items-center justify-between text-xs font-mono text-neutral-400 gap-2">
                        <div class="flex items-center space-x-4">
                            <span>Регион: <strong class="text-white">RU - Moscow</strong></span>
                            <span>Пинг: <strong class="text-emerald-400">~5-15 ms</strong></span>
                        </div>
                        <div class="flex items-center space-x-2 text-neutral-500 text-[11px]">
                            <span>Защита от ливеров:</span>
                            <span class="text-amber-400 font-semibold">5 мин таймаут / автобан</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT 4 COLS: PLAYER COMBAT DOSSIER (PASSPORT) -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-[#121622] border border-[#1F273B] rounded-2xl p-6 shadow-xl relative overflow-hidden">
                <!-- Header Dossier -->
                <div class="flex items-center space-x-4 pb-5 border-b border-[#1F273B]">
                    <div class="relative shrink-0">
                        <img src="{{ $user->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . $user->name }}" class="w-16 h-16 rounded-xl border border-white/10 bg-[#0A0D14] object-cover" alt="Avatar">
                        <div class="absolute -bottom-2 -right-2">
                            <x-faceit-level :level="$user->level" size="md" />
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center space-x-1.5">
                            <h2 class="text-xl font-faceit font-extrabold text-white tracking-wide">
                                {{ $user->name }}
                            </h2>
                            @if($user->isAdmin())
                                <span class="text-[9px] font-mono font-bold bg-[#FF5500] text-white px-1.5 py-0.2 rounded">ADMIN</span>
                            @endif
                        </div>
                        <div class="flex items-center space-x-2 text-xs font-mono text-neutral-400 mt-0.5">
                            <span class="text-[#FF5500] font-bold">{{ $user->elo }} ELO</span>
                            <span>•</span>
                            <span>Tier {{ $user->level }}</span>
                        </div>
                        @if($user->steam_id)
                            <span class="text-[10px] font-mono text-emerald-400 block mt-1">✓ Steam привязан</span>
                        @else
                            <a href="{{ route('profile') }}" class="text-[10px] font-mono text-amber-400 hover:underline block mt-1">⚠️ Привязать Steam ID</a>
                        @endif
                    </div>
                </div>

                <!-- ELO Progress Meter -->
                @php
                    // Compute next level requirement
                    $nextLevelThresholds = [
                        1 => 801,
                        2 => 951,
                        3 => 1101,
                        4 => 1251,
                        5 => 1401,
                        6 => 1551,
                        7 => 1701,
                        8 => 1851,
                        9 => 2001,
                        10 => 2001,
                    ];
                    $prevThresholds = [
                        1 => 0,
                        2 => 801,
                        3 => 951,
                        4 => 1101,
                        5 => 1251,
                        6 => 1401,
                        7 => 1551,
                        8 => 1701,
                        9 => 1851,
                        10 => 2001,
                    ];
                    $curLevel = max(1, min(10, $user->level));
                    $targetElo = $nextLevelThresholds[$curLevel];
                    $baseElo = $prevThresholds[$curLevel];
                    $eloSpan = max(1, $targetElo - $baseElo);
                    $eloProgress = min(100, max(0, round((($user->elo - $baseElo) / $eloSpan) * 100)));
                    $remainingToNext = max(0, $targetElo - $user->elo);
                @endphp

                <div class="py-4 border-b border-[#1F273B] space-y-2">
                    <div class="flex items-center justify-between text-xs font-mono">
                        <span class="text-neutral-400 uppercase">ПРОГРЕСС ДО TIER {{ min(10, $curLevel + 1) }}</span>
                        @if($curLevel < 10)
                            <span class="text-white font-bold">{{ $remainingToNext }} ELO осталось</span>
                        @else
                            <span class="text-amber-400 font-bold">MAX LEVEL (TIER 10)</span>
                        @endif
                    </div>
                    <div class="w-full bg-[#0A0D14] h-2 rounded-full overflow-hidden border border-white/5">
                        <div class="bg-gradient-to-r from-[#FF5500] to-[#FF8800] h-full rounded-full transition-all duration-500" style="width: {{ $eloProgress }}%"></div>
                    </div>
                    <div class="flex items-center justify-between text-[10px] font-mono text-neutral-500">
                        <span>{{ $baseElo }} ELO</span>
                        <span class="text-[#FF5500] font-bold">{{ $user->elo }} ELO</span>
                        <span>{{ $targetElo }} ELO</span>
                    </div>
                </div>

                <!-- Combat Matrix (Winrate / Wins / Losses) -->
                <div class="grid grid-cols-2 gap-3 pt-4 text-center">
                    <div class="bg-[#0E121B] border border-[#1F273B] rounded-xl p-3">
                        <span class="text-[10px] font-mono text-neutral-400 uppercase block">ВИНРЕЙТ 1V1</span>
                        <span class="text-2xl font-faceit font-extrabold {{ $user->winrate >= 50 ? 'text-emerald-400' : 'text-white' }} mt-0.5 block">
                            {{ $user->winrate }}%
                        </span>
                        <span class="text-[10px] font-mono text-neutral-500">{{ $user->wins_count }}W / {{ $user->losses_count }}L</span>
                    </div>

                    <div class="bg-[#0E121B] border border-[#1F273B] rounded-xl p-3">
                        <span class="text-[10px] font-mono text-neutral-400 uppercase block">ВСЕГО МАТЧЕЙ</span>
                        <span class="text-2xl font-faceit font-extrabold text-white mt-0.5 block">
                            {{ $user->matches_count }}
                        </span>
                        <span class="text-[10px] font-mono text-emerald-400">CS2 DUELS</span>
                    </div>
                </div>

                <!-- Quick Profile Link -->
                <div class="mt-4 pt-3 border-t border-[#1F273B]">
                    <a href="{{ route('profile') }}" class="w-full py-2.5 bg-[#171C2B] hover:bg-[#1B2236] text-neutral-200 hover:text-white rounded-xl text-xs font-faceit font-bold uppercase tracking-wider border border-[#1F273B] flex items-center justify-center space-x-1.5 transition">
                        <span>Полный боевой профиль →</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- MATCH FOUND MODAL POPUP (FACEIT READY CHECK) -->
    @if($matchFound)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/85 backdrop-blur-md p-4 animate-fadeIn">
        <div class="bg-[#121622] border-2 {{ $accepted ? 'border-emerald-500 shadow-[0_0_50px_rgba(16,185,129,0.35)]' : 'border-[#FF5500] shadow-[0_0_50px_rgba(255,85,0,0.35)]' }} rounded-2xl max-w-md w-full p-8 text-center relative overflow-hidden transition-all duration-300">
            <!-- Pulsing timer bar -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r {{ $accepted ? 'from-emerald-400 to-teal-400' : 'from-[#FF5500] to-[#FF8833]' }}"></div>

            <!-- Icon -->
            <div class="w-16 h-16 {{ $accepted ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/40' : 'bg-[#FF5500]/10 text-[#FF5500] border-[#FF5500]/40' }} rounded-2xl border flex items-center justify-center mx-auto mb-4 transition">
                @if($accepted)
                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                @else
                    <svg class="w-8 h-8 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                @endif
            </div>

            <!-- Title -->
            <h2 class="text-2xl sm:text-3xl font-faceit font-extrabold text-white uppercase tracking-wider">
                {{ $accepted ? 'ВЫ ГОТОВЫ!' : 'МАТЧ НАЙДЕН!' }}
            </h2>

            <!-- FACEIT Ready counter: 1/2 or 2/2 -->
            <div class="my-3 inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-[#0A0D14] border border-[#1F273B]">
                <span class="w-2.5 h-2.5 rounded-full {{ $acceptedCount >= 2 ? 'bg-emerald-400' : 'bg-[#FF5500] animate-ping' }}"></span>
                <span class="font-faceit font-bold text-xs uppercase tracking-wider text-neutral-200">
                    ГОТОВНОСТЬ: <span class="{{ $acceptedCount >= 2 ? 'text-emerald-400' : 'text-[#FF5500]' }} font-mono font-bold">{{ $acceptedCount }} / 2</span>
                </span>
            </div>

            <p class="text-neutral-300 text-xs sm:text-sm max-w-sm mx-auto mb-5 leading-relaxed">
                @if($accepted)
                    Ожидание подтверждения от соперника. Если он не подтвердит готовность, матч отменится, а нарушитель получит бан.
                @else
                    Подтвердите участие в дуэли 1v1 до истечения таймера. Непринятие матча карается автоматической блокировкой!
                @endif
            </p>

            <!-- Digital Countdown Bar -->
            <div class="mb-5 bg-[#0A0D14] border border-[#1F273B] rounded-xl p-3.5 flex items-center justify-between">
                <span class="text-xs uppercase font-mono text-neutral-400">ВРЕМЯ НА ПРИНЯТИЕ:</span>
                <span class="font-mono font-bold text-xl {{ $acceptRemainingSeconds <= 5 ? 'text-red-500 animate-pulse' : 'text-emerald-400' }}">
                    {{ sprintf('%02d', max(0, $acceptRemainingSeconds)) }} сек
                </span>
            </div>

            <!-- Action Button -->
            <div class="flex items-center justify-center">
                @if(! $accepted)
                    <button 
                        wire:click="acceptMatch" 
                        class="w-full py-4 bg-emerald-500 hover:bg-emerald-400 active:bg-emerald-600 text-neutral-950 font-faceit font-black text-xl uppercase tracking-wider rounded-xl shadow-[0_4px_25px_rgba(16,185,129,0.35)] transition transform active:scale-95 cursor-pointer flex items-center justify-center space-x-2 border border-emerald-300/40">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>ПРИНЯТЬ МАТЧ</span>
                    </button>
                @else
                    <div class="w-full py-3.5 bg-emerald-950/60 border border-emerald-500/40 rounded-xl flex items-center justify-center space-x-2.5 text-emerald-300 font-faceit font-bold tracking-wider text-sm">
                        <svg class="w-4 h-4 animate-spin text-emerald-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span>ОЖИДАНИЕ СОПЕРНИКА ({{ $acceptedCount }}/2)...</span>
                    </div>
                @endif
            </div>

            <div class="mt-4 text-[10px] font-mono text-neutral-500 uppercase tracking-wider">
                FACEIT READY CHECK SYSTEM • 20 SEC
            </div>
        </div>
    </div>
    @endif

    <!-- RECENT MATCHES LIST -->
    <div class="bg-[#121622] border border-[#1F273B] rounded-2xl p-6 shadow-xl">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#1F273B]">
            <div class="flex items-center space-x-2.5">
                <span class="w-2 h-2 bg-[#FF5500] rounded-sm"></span>
                <h2 class="font-faceit text-xl font-bold tracking-wide uppercase text-white">
                    История последних матчей
                </h2>
            </div>
            <span class="text-xs font-mono uppercase tracking-wider text-neutral-400">
                1V1 RECENT
            </span>
        </div>

        @if($recentMatches->isEmpty())
            <div class="text-center py-12 text-neutral-500 text-sm">
                <p class="font-faceit text-base text-neutral-400 uppercase">Матчей пока не найдено</p>
                <p class="text-xs text-neutral-500 mt-1">Нажмите «Найти игру 1v1», чтобы начать соревнование!</p>
            </div>
        @else
            <div class="space-y-2.5">
                @foreach($recentMatches as $match)
                    @php
                        $isPlayer1 = ($match->player1_id === $user->id);
                        $isWinner = ($match->winner_id === $user->id);
                        $opponent = $isPlayer1 ? $match->player2 : $match->player1;
                        $myScore = $isPlayer1 ? $match->score_player1 : $match->score_player2;
                        $oppScore = $isPlayer1 ? $match->score_player2 : $match->score_player1;
                        $eloDelta = $isPlayer1 ? $match->elo_change_p1 : $match->elo_change_p2;
                    @endphp
                    <div class="p-3.5 bg-[#0E121B] border border-[#1F273B] hover:border-white/20 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 transition group">
                        <!-- Result & Map -->
                        <div class="flex items-center space-x-3.5">
                            @if($match->status === 'finished')
                                <span class="w-24 text-center py-1.5 rounded-lg text-xs font-mono font-bold uppercase tracking-wider {{ $isWinner ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'bg-red-500/15 text-red-400 border border-red-500/30' }}">
                                    {{ $isWinner ? 'ПОБЕДА' : 'ПОРАЖЕНИЕ' }}
                                </span>
                            @else
                                <span class="w-24 text-center py-1.5 rounded-lg text-xs font-mono font-bold uppercase tracking-wider bg-amber-500/15 text-amber-400 border border-amber-500/30">
                                    {{ $match->status }}
                                </span>
                            @endif

                            <div>
                                <p class="text-sm font-faceit font-bold text-white group-hover:text-[#FF5500] transition-colors">
                                    {{ $match->map ? $match->map->name : 'Map Veto' }}
                                </p>
                                <p class="text-[11px] font-mono text-neutral-400">
                                    {{ $match->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        <!-- Score & Opponent & Action -->
                        <div class="flex items-center space-x-5">
                            <div class="text-center bg-[#121622] px-3 py-1 rounded-lg border border-[#1F273B]">
                                <span class="font-mono text-sm font-bold text-white">{{ $myScore }} : {{ $oppScore }}</span>
                            </div>

                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-mono text-neutral-500">vs</span>
                                @if($opponent)
                                    <a href="{{ route('profile', ['id' => $opponent->id]) }}" class="text-sm font-medium text-neutral-200 hover:text-[#FF5500] transition">
                                        {{ $opponent->name }}
                                    </a>
                                    <x-faceit-level :level="$opponent->level" size="xs" />
                                @else
                                    <span class="text-sm font-medium text-neutral-400">Оппонент</span>
                                @endif
                            </div>

                            <!-- Elo Change -->
                            <div class="w-16 text-right">
                                @if($eloDelta !== null)
                                    <span class="font-mono font-bold text-sm {{ $eloDelta >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                        {{ $eloDelta >= 0 ? "+{$eloDelta}" : $eloDelta }}
                                    </span>
                                @endif
                            </div>

                            <!-- Action button -->
                            <a href="{{ route('match.lobby', ['uuid' => $match->uuid]) }}" class="px-3.5 py-1.5 bg-[#171C2B] hover:bg-[#FF5500] text-neutral-300 hover:text-white rounded-lg text-xs font-faceit font-bold uppercase tracking-wider border border-[#1F273B] hover:border-[#FF5500] transition">
                                Комната
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
