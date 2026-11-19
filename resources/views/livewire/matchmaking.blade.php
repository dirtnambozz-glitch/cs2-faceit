<div wire:poll.1000ms="pollQueue" class="space-y-8 animate-fadeIn">
    <!-- Matchmaking Ban Alert Banner -->
    @if($user && $user->isBanned())
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-red-950/90 via-[#1C060B]/90 to-red-950/90 border border-red-500/60 p-5 shadow-[0_0_35px_rgba(239,68,68,0.35)] backdrop-blur-xl">
            <div class="absolute -right-12 -top-12 w-36 h-36 bg-red-600/15 rounded-full blur-2xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-red-500/20 text-red-400 border border-red-500/50 flex items-center justify-center shrink-0 shadow-[0_0_15px_rgba(239,68,68,0.3)] animate-pulse">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                            <h3 class="text-base sm:text-lg font-cyber font-bold text-red-100 uppercase tracking-wider">
                                ВРЕМЕННАЯ БЛОКИРОВКА МАТЧМЕЙКИНГА (COOLDOWN)
                            </h3>
                        </div>
                        <p class="text-xs text-red-300 font-mono mt-1">
                            Причина: <span class="text-white font-semibold">{{ $user->ban_reason ?? 'Нарушение регламента очереди / неявка' }}</span>
                        </p>
                        <p class="text-[11px] text-neutral-400 mt-1 font-mono">
                            Предупреждений: <span class="text-amber-400 font-bold">{{ $user->warnings_count }}</span> • Разблокировка через: <span class="text-white font-bold font-mono">{{ sprintf('%02d:%02d', floor($user->banRemainingSeconds() / 60), $user->banRemainingSeconds() % 60) }}</span>
                        </p>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <span class="px-4 py-2 rounded-xl bg-red-950/80 border border-red-500/50 text-red-200 text-xs font-mono font-bold tracking-wider shadow">
                        До {{ $user->banned_until->format('H:i:s d.m.Y') }}
                    </span>
                </div>
            </div>
        </div>
    @endif

    <!-- User Quick Stats Bar (Cyberpunk HUD Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- ELO Card -->
        <div class="relative bg-gradient-to-b from-[#14081B] to-[#0A040E] border border-white/[0.08] hover:border-[#FF0055]/60 rounded-2xl p-5 shadow-[0_8px_24px_rgba(0,0,0,0.5)] transition-all duration-300 group overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-[#FF0055]/10 rounded-full blur-2xl pointer-events-none group-hover:bg-[#FF0055]/25 transition duration-500"></div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <div class="flex items-center space-x-1.5 text-[11px] font-mono uppercase tracking-widest text-[#FF0055]">
                        <span class="inline-block w-1.5 h-1.5 bg-[#FF0055] rounded-full shadow-[0_0_6px_#FF0055]"></span>
                        <span>Рейтинг Elo</span>
                    </div>
                    <p class="text-3xl sm:text-4xl font-cyber font-black text-white mt-1 group-hover:text-[#FF2A6D] transition-colors tabular-nums">
                        {{ $user->elo }}
                    </p>
                    <span class="text-[10px] font-mono text-neutral-400 uppercase tracking-wider">Competitive Rating</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-cyber font-black text-2xl bg-gradient-to-br from-[#FF0055] via-[#E1066A] to-[#990033] text-white shadow-[0_0_20px_rgba(255,0,85,0.45)] border border-[#FF0055]/50 group-hover:scale-105 transition-transform duration-300">
                        {{ $user->level }}
                    </div>
                    <span class="text-[9px] font-mono uppercase text-[#FF0055] font-semibold mt-1">LVL {{ $user->level }}</span>
                </div>
            </div>
        </div>

        <!-- Winrate Card -->
        <div class="relative bg-gradient-to-b from-[#14081B] to-[#0A040E] border border-white/[0.08] hover:border-[#FF0055]/60 rounded-2xl p-5 shadow-[0_8px_24px_rgba(0,0,0,0.5)] transition-all duration-300 group overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-[#FF0055]/10 rounded-full blur-2xl pointer-events-none group-hover:bg-[#FF0055]/25 transition duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1.5 text-[11px] font-mono uppercase tracking-widest text-[#FF0055]">
                        <span class="inline-block w-1.5 h-1.5 bg-[#FF0055] rounded-full shadow-[0_0_6px_#FF0055]"></span>
                        <span>Винрейт 1v1</span>
                    </div>
                    <span class="text-xs font-mono text-neutral-400">
                        {{ $user->wins_count }}W • {{ $user->losses_count }}L
                    </span>
                </div>
                <div class="flex items-baseline space-x-2 mt-1">
                    <span class="text-3xl sm:text-4xl font-cyber font-black text-white group-hover:text-[#FF2A6D] transition-colors tabular-nums">
                        {{ $user->winrate }}%
                    </span>
                </div>
                <!-- Precision Progress Bar -->
                <div class="w-full bg-[#08040A] h-2 rounded-full mt-3 overflow-hidden border border-white/[0.06] p-0.5">
                    <div class="bg-gradient-to-r from-[#FF0055] via-[#FF2A6D] to-emerald-400 h-full rounded-full transition-all duration-500 shadow-[0_0_10px_#FF0055]" style="width: {{ min(100, max(0, $user->winrate)) }}%"></div>
                </div>
            </div>
        </div>

        <!-- Matches Count -->
        <div class="relative bg-gradient-to-b from-[#14081B] to-[#0A040E] border border-white/[0.08] hover:border-[#FF0055]/60 rounded-2xl p-5 shadow-[0_8px_24px_rgba(0,0,0,0.5)] transition-all duration-300 group overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-[#FF0055]/10 rounded-full blur-2xl pointer-events-none group-hover:bg-[#FF0055]/25 transition duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center space-x-1.5 text-[11px] font-mono uppercase tracking-widest text-[#FF0055]">
                    <span class="inline-block w-1.5 h-1.5 bg-[#FF0055] rounded-full shadow-[0_0_6px_#FF0055]"></span>
                    <span>Сыграно матчей</span>
                </div>
                <p class="text-3xl sm:text-4xl font-cyber font-black text-white mt-1 group-hover:text-[#FF2A6D] transition-colors tabular-nums">
                    {{ $user->matches_count }}
                </p>
                <span class="text-[10px] font-mono text-neutral-400 uppercase tracking-wider">Total Duels Recorded</span>
            </div>
        </div>
    </div>

    <!-- Main Cyber Hero Card with Background Gamer Art -->
    <div class="relative rounded-3xl overflow-hidden border border-[#FF0055]/30 shadow-[0_0_50px_rgba(255,0,85,0.2)] bg-[#0C0512]">
        <!-- Hero Background Image with Cyber Overlay Gradients -->
        <div class="absolute inset-0">
            <img src="/images/rivalis-hero.jpg" alt="RIVALIS Cyber Gamer" class="w-full h-full object-cover object-center opacity-25 md:opacity-35 mix-blend-luminosity scale-105 filter contrast-125">
            <div class="absolute inset-0 bg-gradient-to-b from-[#08040A]/95 via-[#08040A]/70 to-[#08040A]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_transparent_0%,_#08040A_85%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-[#FF0055]/20 via-transparent to-transparent"></div>
        </div>

        <!-- Cyber Corner Accents -->
        <div class="absolute top-3.5 left-4 text-[#FF0055]/60 font-mono text-[11px] tracking-widest select-none flex items-center space-x-1">
            <span class="inline-block w-1 h-1 bg-[#FF0055]"></span>
            <span>ARENA // 01</span>
        </div>
        <div class="absolute top-3.5 right-4 text-[#FF0055]/60 font-mono text-[11px] tracking-widest select-none">
            SYSTEM.LIVE • TICK 128
        </div>
        <div class="absolute bottom-3.5 left-4 text-[#FF0055]/50 font-mono text-[10px] tracking-widest select-none hidden sm:block">
            RIVALIS 1v1 COMPETITIVE ENGINE
        </div>
        <div class="absolute bottom-3.5 right-4 text-emerald-400/80 font-mono text-[10px] tracking-widest select-none flex items-center space-x-1.5 hidden sm:flex">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>VALVE VAC SECURED</span>
        </div>

        <!-- Hero Content (Centered) -->
        <div class="relative z-10 px-6 py-12 sm:py-16 md:py-20 max-w-3xl mx-auto text-center flex flex-col items-center">
            <!-- Big Cyber Title -->
            <div class="inline-flex items-center space-x-2 px-4 py-1 rounded-full bg-[#1A0826]/80 border border-[#FF0055]/40 text-[#FF2A6D] text-xs font-mono uppercase tracking-widest mb-4 shadow-[0_0_15px_rgba(255,0,85,0.25)]">
                <span class="w-1.5 h-1.5 rounded-full bg-[#FF0055] animate-ping"></span>
                <span>MATCHMAKING QUEUE // DUEL ARENA</span>
            </div>

            <h1 class="text-3xl sm:text-5xl md:text-6xl font-cyber font-black text-white tracking-wider uppercase leading-none">
                ПОИСК МАТЧА <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FF0055] via-[#FF2A6D] to-white">1 НА 1</span>
            </h1>

            <p class="text-neutral-300 text-sm sm:text-base mt-4 max-w-xl mx-auto leading-relaxed font-sans">
                Сражайтесь за рейтинговые очки Elo на соревновательных серверах CS2. Полноценная система вычеркивания карт (Map Veto), автоподключение и статистика.
            </p>

            <!-- Search Action Area (Centered) -->
            <div class="mt-8 w-full">
                @if(! $inQueue && ! $matchFound)
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <!-- Play 1v1 Matchmaking Button -->
                        <button 
                            wire:click="startQueue" 
                            class="w-full sm:w-auto px-10 py-5 bg-gradient-to-r from-[#FF0055] via-[#FF1A75] to-[#E1066A] hover:brightness-110 text-white font-cyber font-black text-lg tracking-widest uppercase rounded-2xl shadow-[0_0_35px_rgba(255,0,85,0.65)] transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0 active:scale-95 flex items-center justify-center space-x-3 cursor-pointer border border-white/25">
                            <svg class="w-6 h-6 fill-current animate-pulse" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                            </svg>
                            <span>НАЙТИ ИГРУ 1v1</span>
                        </button>

                        <!-- Instant Bot Match (For Testing & Solo play) -->
                        <button 
                            wire:click="startInstantBotMatch" 
                            class="w-full sm:w-auto px-7 py-5 bg-[#120817]/90 hover:bg-[#1C0D26] text-neutral-200 hover:text-white font-faceit font-bold text-base tracking-wider uppercase rounded-2xl border border-white/10 hover:border-[#FF0055]/80 shadow-[0_4px_20px_rgba(0,0,0,0.5)] transition-all duration-200 flex items-center justify-center space-x-2.5 cursor-pointer">
                            <svg class="w-5 h-5 text-[#FF0055]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Тест с Ботом (Демо)</span>
                        </button>
                    </div>
                @elseif($inQueue && ! $matchFound)
                    <!-- Queue In Progress Box (Centered) -->
                    <div class="bg-gradient-to-b from-[#160B20]/95 to-[#0F0716]/95 border-2 border-[#FF0055] rounded-3xl p-7 shadow-[0_0_40px_rgba(255,0,85,0.4)] max-w-md mx-auto backdrop-blur-xl">
                        <div class="flex items-center justify-center space-x-5 mb-5">
                            <div class="relative w-14 h-14 flex items-center justify-center">
                                <div class="absolute inset-0 rounded-full border-3 border-[#FF0055]/30 border-t-[#FF0055] animate-spin shadow-[0_0_20px_#FF0055]"></div>
                                <svg class="w-6 h-6 text-[#FF0055]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-xs font-mono uppercase tracking-widest text-[#FF0055]">Поиск соперника 1v1</p>
                                <p class="text-3xl sm:text-4xl font-cyber font-black text-white tracking-wider tabular-nums">
                                    {{ sprintf('%02d:%02d', floor($queueSeconds / 60), $queueSeconds % 60) }}
                                </p>
                            </div>
                        </div>

                        <p class="text-xs text-neutral-400 mb-5 font-mono text-center">
                            Подбор игрока с близким рейтингом (~{{ $user->elo }} ELO)...
                        </p>

                        <div class="flex items-center justify-center space-x-3">
                            <button 
                                wire:click="cancelQueue" 
                                class="flex-1 py-3 bg-neutral-900/90 hover:bg-neutral-800 text-neutral-300 hover:text-white rounded-xl text-xs font-faceit font-bold uppercase tracking-wider border border-white/10 hover:border-white/30 transition cursor-pointer">
                                Отменить поиск
                            </button>
                            <button 
                                wire:click="startInstantBotMatch" 
                                class="flex-1 py-3 bg-[#FF0055]/15 hover:bg-[#FF0055]/30 text-[#FF2A6D] hover:text-white border border-[#FF0055]/40 rounded-xl text-xs font-faceit font-bold uppercase tracking-wider transition cursor-pointer">
                                Бот сразу
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- MATCH FOUND MODAL POPUP (FACEIT-STYLE READY CHECK) -->
    @if($matchFound)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-md p-4 animate-fadeIn">
        <div class="bg-gradient-to-b from-[#150A1F] to-[#0D0514] border-2 {{ $accepted ? 'border-emerald-500 shadow-[0_0_60px_rgba(16,185,129,0.55)]' : 'border-[#FF0055] shadow-[0_0_60px_rgba(255,0,85,0.65)]' }} rounded-3xl max-w-lg w-full p-8 text-center relative overflow-hidden transition-all duration-300">
            <!-- Pulsing neon header bar / timer bar -->
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r {{ $accepted ? 'from-emerald-400 via-white to-emerald-400 shadow-[0_0_20px_#10B981]' : 'from-[#FF0055] via-white to-[#FF0055] shadow-[0_0_20px_#FF0055]' }}"></div>

            <!-- Icon -->
            <div class="w-20 h-20 {{ $accepted ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/60 shadow-[0_0_30px_rgba(16,185,129,0.45)]' : 'bg-[#FF0055]/20 text-[#FF0055] border-[#FF0055]/60 shadow-[0_0_25px_rgba(255,0,85,0.5)]' }} rounded-2xl border flex items-center justify-center mx-auto mb-4 transition">
                @if($accepted)
                    <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                @else
                    <svg class="w-10 h-10 animate-bounce text-[#FF0055]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                @endif
            </div>

            <!-- Title -->
            <h2 class="text-3xl sm:text-4xl font-cyber font-black text-white uppercase tracking-wider">
                {{ $accepted ? 'ВЫ ГОТОВЫ!' : 'МАТЧ НАЙДЕН!' }}
            </h2>

            <!-- FACEIT Ready counter: 1/2 or 2/2 -->
            <div class="my-3.5 inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-[#08040A] border border-white/10">
                <span class="w-2.5 h-2.5 rounded-full {{ $acceptedCount >= 2 ? 'bg-emerald-400 shadow-[0_0_8px_#34d399]' : 'bg-[#FF0055] animate-ping' }}"></span>
                <span class="font-cyber font-bold text-sm tracking-widest text-neutral-200">
                    ГОТОВНОСТЬ: <span class="{{ $acceptedCount >= 2 ? 'text-emerald-400' : 'text-[#FF0055]' }}">{{ $acceptedCount }} / 2</span>
                </span>
            </div>

            <p class="text-neutral-300 text-xs sm:text-sm max-w-sm mx-auto mb-5 leading-relaxed font-sans">
                @if($accepted)
                    Ожидаем подтверждения от соперника. Если он не подтвердит за отведенное время, поиск возобновится, а нарушитель получит бан.
                @else
                    Подтвердите участие в дуэли 1v1 до истечения таймера. Непринятие матча карается автоматическим баном!
                @endif
            </p>

            <!-- Digital Countdown Bar -->
            <div class="mb-6 bg-[#08040A] border border-white/10 rounded-2xl p-4 flex items-center justify-between">
                <span class="text-xs uppercase font-mono text-neutral-400">ВРЕМЯ НА ПРИНЯТИЕ:</span>
                <span class="font-cyber font-black text-2xl tabular-nums {{ $acceptRemainingSeconds <= 5 ? 'text-red-500 animate-pulse' : 'text-emerald-400' }}">
                    {{ sprintf('%02d', max(0, $acceptRemainingSeconds)) }} сек
                </span>
            </div>

            <!-- Action Button -->
            <div class="flex items-center justify-center">
                @if(! $accepted)
                    <button 
                        wire:click="acceptMatch" 
                        class="w-full py-5 bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 hover:brightness-110 text-white font-cyber font-black text-2xl uppercase tracking-widest rounded-2xl shadow-[0_0_40px_rgba(16,185,129,0.7)] transition transform active:scale-95 cursor-pointer border border-emerald-300/40 flex items-center justify-center space-x-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>ПРИНЯТЬ МАТЧ</span>
                    </button>
                @else
                    <div class="w-full py-4 bg-emerald-950/70 border border-emerald-500/50 rounded-2xl flex items-center justify-center space-x-3 text-emerald-300 font-cyber font-bold tracking-wider text-base">
                        <svg class="w-5 h-5 animate-spin text-emerald-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span>ОЖИДАНИЕ СОПЕРНИКА ({{ $acceptedCount }}/2)...</span>
                    </div>
                @endif
            </div>

            <div class="mt-4 text-[10px] font-mono text-neutral-500 uppercase tracking-widest">
                FACEIT READY CHECK SYSTEM // 20 SEC TIMEOUT
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Matches List (Cyber Style) -->
    <div class="bg-gradient-to-b from-[#120817] to-[#0A040E] border border-white/[0.08] hover:border-[#FF0055]/40 rounded-3xl p-6 sm:p-8 shadow-[0_8px_32px_rgba(0,0,0,0.6)] transition-all">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/[0.08]">
            <div class="flex items-center space-x-3">
                <div class="w-2.5 h-2.5 bg-[#FF0055] rounded-full shadow-[0_0_8px_#FF0055]"></div>
                <h2 class="font-cyber text-2xl font-bold tracking-wide uppercase text-white">
                    История последних матчей
                </h2>
            </div>
            <span class="text-xs font-mono uppercase tracking-widest text-[#FF0055] bg-[#FF0055]/10 border border-[#FF0055]/30 px-3 py-1 rounded-lg">
                MATCH HISTORY
            </span>
        </div>

        @if($recentMatches->isEmpty())
            <div class="text-center py-16 text-neutral-500 text-sm">
                <div class="w-16 h-16 rounded-2xl bg-[#08040A] border border-white/10 flex items-center justify-center mx-auto mb-3 text-neutral-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <p class="font-faceit text-lg text-neutral-400">У вас пока нет сыгранных матчей.</p>
                <p class="text-xs text-neutral-500 mt-1 font-mono">Нажмите «Найти игру 1v1» выше, чтобы начать свое восхождение в топ!</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($recentMatches as $match)
                    @php
                        $isPlayer1 = ($match->player1_id === $user->id);
                        $isWinner = ($match->winner_id === $user->id);
                        $opponent = $isPlayer1 ? $match->player2 : $match->player1;
                        $myScore = $isPlayer1 ? $match->score_player1 : $match->score_player2;
                        $oppScore = $isPlayer1 ? $match->score_player2 : $match->score_player1;
                        $eloDelta = $isPlayer1 ? $match->elo_change_p1 : $match->elo_change_p2;
                    @endphp
                    <div class="p-4 bg-[#08040C] border border-white/[0.06] hover:border-[#FF0055]/50 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all duration-200 group hover:-translate-y-0.5 shadow-sm">
                        <!-- Match Result Badge & Map -->
                        <div class="flex items-center space-x-4">
                            @if($match->status === 'finished')
                                <span class="w-28 text-center py-2 rounded-xl text-xs font-cyber font-black uppercase tracking-wider {{ $isWinner ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/40 shadow-[0_0_12px_rgba(16,185,129,0.25)]' : 'bg-red-500/15 text-red-400 border border-red-500/40' }}">
                                    {{ $isWinner ? 'ПОБЕДА' : 'ПОРАЖЕНИЕ' }}
                                </span>
                            @else
                                <span class="w-28 text-center py-2 rounded-xl text-xs font-cyber font-bold uppercase tracking-wider bg-amber-500/15 text-amber-400 border border-amber-500/40">
                                    {{ $match->status }}
                                </span>
                            @endif

                            <div>
                                <p class="text-base font-cyber font-bold text-white group-hover:text-[#FF2A6D] transition-colors">
                                    {{ $match->map ? $match->map->name : 'Map Veto' }}
                                </p>
                                <p class="text-xs font-mono text-neutral-400">
                                    {{ $match->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        <!-- Score & Opponent & Action -->
                        <div class="flex items-center space-x-4 sm:space-x-6">
                            <div class="text-center bg-[#120817] px-4 py-1.5 rounded-xl border border-white/[0.08]">
                                <span class="font-cyber text-lg font-black text-white tabular-nums">{{ $myScore }} : {{ $oppScore }}</span>
                            </div>

                            <div class="flex items-center space-x-2.5">
                                <span class="text-xs font-mono text-[#FF0055]">vs</span>
                                @if($opponent)
                                    <a href="{{ route('profile', ['id' => $opponent->id]) }}" class="text-sm font-semibold text-neutral-200 hover:text-[#FF2A6D] transition">
                                        {{ $opponent->name }}
                                    </a>
                                    <span class="w-6 h-6 rounded-lg flex items-center justify-center font-faceit text-xs font-bold bg-[#FF0055]/30 text-white border border-[#FF0055]/50">
                                        {{ $opponent->level }}
                                    </span>
                                @else
                                    <span class="text-sm font-semibold text-neutral-200">Оппонент</span>
                                @endif
                            </div>

                            <!-- Elo Change -->
                            <div class="w-16 text-right">
                                @if($eloDelta !== null)
                                    <span class="font-cyber font-bold text-sm tabular-nums {{ $eloDelta >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                        {{ $eloDelta >= 0 ? "+{$eloDelta}" : $eloDelta }}
                                    </span>
                                @endif
                            </div>

                            <!-- Action button -->
                            <a href="{{ route('match.lobby', ['uuid' => $match->uuid]) }}" class="px-4 py-2 bg-[#FF0055]/15 hover:bg-[#FF0055] text-neutral-200 hover:text-white rounded-xl text-xs font-faceit font-bold uppercase tracking-wider border border-[#FF0055]/30 hover:border-[#FF0055] transition shadow-sm whitespace-nowrap">
                                Комната
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
