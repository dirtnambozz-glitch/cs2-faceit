<div wire:poll.1000ms="pollQueue">
    <!-- Matchmaking Ban Alert Banner -->
    @if($user && $user->isBanned())
        <div class="mb-6 p-5 bg-[#181014] border border-red-500/50 rounded-2xl shadow-[0_4px_25px_rgba(239,68,68,0.25)] flex flex-col sm:flex-row items-center justify-between gap-4">
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
                        Причина: <span class="text-white font-semibold">{{ $user->ban_reason ?? 'Нарушение правил очереди / лив' }}</span>
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

    <!-- User Quick Stats Bar (FACEIT HUD Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- ELO Card -->
        <div class="bg-[#121620] border border-[#22293A] hover:border-[#FF5500]/50 rounded-2xl p-5 flex items-center justify-between transition group">
            <div>
                <div class="flex items-center space-x-1.5 text-xs font-mono uppercase tracking-wider text-neutral-400">
                    <span class="inline-block w-1.5 h-1.5 bg-[#FF5500] rounded-full"></span>
                    <span>Рейтинг ELO</span>
                </div>
                <div class="flex items-baseline space-x-1.5 mt-1">
                    <span class="text-3xl font-faceit font-extrabold text-white tracking-wide group-hover:text-[#FF5500] transition-colors">{{ $user->elo }}</span>
                    <span class="text-xs font-mono font-bold text-[#FF5500]">ELO</span>
                </div>
                <div class="text-[11px] font-mono text-neutral-400 mt-1">
                    FACEIT Skill Tier
                </div>
            </div>
            <x-faceit-level :level="$user->level" size="lg" />
        </div>

        <!-- Winrate Card -->
        <div class="bg-[#121620] border border-[#22293A] hover:border-[#FF5500]/50 rounded-2xl p-5 transition group">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-1.5 text-xs font-mono uppercase tracking-wider text-neutral-400">
                    <span class="inline-block w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                    <span>Винрейт 1v1</span>
                </div>
                <span class="text-xs font-mono text-neutral-400">{{ $user->wins_count }}W / {{ $user->losses_count }}L</span>
            </div>
            <div class="flex items-baseline space-x-2 mt-1">
                <span class="text-3xl font-faceit font-extrabold text-white tracking-wide group-hover:text-emerald-400 transition-colors">{{ $user->winrate }}%</span>
                <span class="text-xs font-mono text-neutral-400">доля побед</span>
            </div>
            <div class="w-full bg-[#181E2B] h-1.5 rounded-full mt-3 overflow-hidden border border-white/5">
                <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-full rounded-full" style="width: {{ min(100, max(0, $user->winrate)) }}%"></div>
            </div>
        </div>

        <!-- Matches Count Card -->
        <div class="bg-[#121620] border border-[#22293A] hover:border-[#FF5500]/50 rounded-2xl p-5 transition group">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-1.5 text-xs font-mono uppercase tracking-wider text-neutral-400">
                    <span class="inline-block w-1.5 h-1.5 bg-[#FF5500] rounded-full"></span>
                    <span>Сыграно матчей</span>
                </div>
                <span class="text-[10px] font-mono uppercase text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded">128 TICK</span>
            </div>
            <div class="flex items-baseline space-x-2 mt-1">
                <span class="text-3xl font-faceit font-extrabold text-white tracking-wide group-hover:text-white transition-colors">{{ $user->matches_count }}</span>
                <span class="text-xs font-mono text-neutral-400">дуэлей</span>
            </div>
            <div class="text-[11px] font-mono text-neutral-400 mt-2">
                Режим: Incloudgame 1v1 Arena
            </div>
        </div>
    </div>

    <!-- Main Competitive Matchmaking Hub -->
    <div class="relative rounded-2xl mb-8 overflow-hidden border border-[#22293A] bg-[#121620] shadow-xl">
        <!-- Background Art with Dark Gradient -->
        <div class="absolute inset-0">
            <img src="/images/rivalis-hero.jpg" alt="CS2 Arena" class="w-full h-full object-cover object-center opacity-15 filter contrast-125">
            <div class="absolute inset-0 bg-gradient-to-b from-[#0C0E14]/85 via-[#121620]/90 to-[#121620]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-[#FF5500]/10 via-transparent to-transparent"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 p-8 sm:p-12 max-w-3xl mx-auto text-center flex flex-col items-center">
            <!-- Mode Tag -->
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#181E2B] border border-[#22293A] text-xs font-mono text-neutral-300 mb-3">
                <span class="w-2 h-2 rounded-full bg-[#FF5500] animate-pulse"></span>
                <span>СОРЕВНОВАТЕЛЬНАЯ ОЧЕРЕДЬ // 1V1 DUELS</span>
            </div>

            <h1 class="text-3xl sm:text-5xl font-faceit font-black text-white tracking-wider uppercase leading-tight">
                ПОИСК МАТЧА 1 НА 1
            </h1>
            <p class="text-neutral-300 text-sm sm:text-base mt-3 max-w-xl mx-auto leading-relaxed">
                Сражайтесь за рейтинговые очки ELO на официальных серверах CS2. Полноценная система вычеркивания карт (Map Veto), статистика каждого раунда и античит.
            </p>

            <!-- Features Bar -->
            <div class="grid grid-cols-3 gap-3 my-6 w-full max-w-lg text-left text-xs font-mono text-neutral-400">
                <div class="p-2.5 bg-[#181E2B]/80 rounded-xl border border-white/5 flex items-center space-x-2">
                    <span class="text-[#FF5500]">⚡</span>
                    <span>MatchZy Auto-Sync</span>
                </div>
                <div class="p-2.5 bg-[#181E2B]/80 rounded-xl border border-white/5 flex items-center space-x-2">
                    <span class="text-[#FF5500]">🗺️</span>
                    <span>7 CS2 Map Pool</span>
                </div>
                <div class="p-2.5 bg-[#181E2B]/80 rounded-xl border border-white/5 flex items-center space-x-2">
                    <span class="text-emerald-400">🛡️</span>
                    <span>Leaver Penalty</span>
                </div>
            </div>

            <!-- Action Area -->
            <div class="w-full">
                @if(! $inQueue && ! $matchFound)
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <!-- Play 1v1 Matchmaking Button -->
                        <button 
                            wire:click="startQueue" 
                            class="w-full sm:w-auto px-8 py-4 bg-[#FF5500] hover:bg-[#FF6E1A] active:bg-[#E04B00] text-white font-faceit font-extrabold text-lg tracking-wider uppercase rounded-xl shadow-[0_4px_25px_rgba(255,85,0,0.35)] transition transform active:scale-95 flex items-center justify-center space-x-2.5 cursor-pointer border border-[#FF7733]/40">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                            </svg>
                            <span>НАЙТИ ИГРУ 1v1</span>
                        </button>

                        <!-- Instant Bot Match (For Testing & Practice) -->
                        <button 
                            wire:click="startInstantBotMatch" 
                            class="w-full sm:w-auto px-6 py-4 bg-[#181E2B] hover:bg-[#1D2435] text-neutral-200 hover:text-white font-faceit font-bold text-sm tracking-wider uppercase rounded-xl border border-[#22293A] hover:border-white/20 transition flex items-center justify-center space-x-2 cursor-pointer">
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Матч с Ботом (Демо)</span>
                        </button>
                    </div>
                @elseif($inQueue && ! $matchFound)
                    <!-- Queue In Progress Box -->
                    <div class="bg-[#181E2B] border-2 border-[#FF5500]/60 rounded-2xl p-6 shadow-[0_0_30px_rgba(255,85,0,0.25)] max-w-md mx-auto">
                        <div class="flex items-center justify-center space-x-4 mb-4">
                            <div class="relative w-12 h-12 flex items-center justify-center shrink-0">
                                <div class="absolute inset-0 rounded-full border-2 border-[#FF5500]/30 border-t-[#FF5500] animate-spin"></div>
                                <svg class="w-5 h-5 text-[#FF5500]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-xs font-mono uppercase tracking-wider text-[#FF5500]">Поиск соперника 1v1</p>
                                <p class="text-3xl font-mono font-bold text-white tracking-wider">
                                    {{ sprintf('%02d:%02d', floor($queueSeconds / 60), $queueSeconds % 60) }}
                                </p>
                            </div>
                        </div>

                        <p class="text-xs text-neutral-400 mb-4 font-mono text-center">
                            Подбор игрока с близким рейтингом (~{{ $user->elo }} ELO)...
                        </p>

                        <div class="flex items-center justify-center space-x-3">
                            <button 
                                wire:click="cancelQueue" 
                                class="flex-1 py-2.5 bg-[#121620] hover:bg-neutral-800 text-neutral-300 hover:text-white rounded-xl text-xs font-faceit font-bold uppercase tracking-wider border border-[#22293A] transition cursor-pointer">
                                Отменить поиск
                            </button>
                            <button 
                                wire:click="startInstantBotMatch" 
                                class="flex-1 py-2.5 bg-[#FF5500]/15 hover:bg-[#FF5500]/25 text-[#FF5500] border border-[#FF5500]/40 rounded-xl text-xs font-faceit font-bold uppercase tracking-wider transition cursor-pointer">
                                Бот сразу
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- MATCH FOUND MODAL POPUP (FACEIT READY CHECK) -->
    @if($matchFound)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/85 backdrop-blur-md p-4 animate-fadeIn">
        <div class="bg-[#121620] border-2 {{ $accepted ? 'border-emerald-500 shadow-[0_0_50px_rgba(16,185,129,0.35)]' : 'border-[#FF5500] shadow-[0_0_50px_rgba(255,85,0,0.35)]' }} rounded-2xl max-w-md w-full p-8 text-center relative overflow-hidden transition-all duration-300">
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
            <div class="my-3 inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-[#0C0E14] border border-[#22293A]">
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
            <div class="mb-5 bg-[#0C0E14] border border-[#22293A] rounded-xl p-3.5 flex items-center justify-between">
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

    <!-- Recent Matches List -->
    <div class="bg-[#121620] border border-[#22293A] rounded-2xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#22293A]">
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
                    <div class="p-3.5 bg-[#0C0E14] border border-[#22293A] hover:border-white/20 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 transition group">
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
                            <div class="text-center bg-[#121620] px-3 py-1 rounded-lg border border-[#22293A]">
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
                            <a href="{{ route('match.lobby', ['uuid' => $match->uuid]) }}" class="px-3 py-1.5 bg-[#181E2B] hover:bg-[#FF5500] text-neutral-300 hover:text-white rounded-lg text-xs font-faceit font-bold uppercase tracking-wider border border-[#22293A] hover:border-[#FF5500] transition">
                                Комната
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
