<div wire:poll.1000ms="pollQueue">
    <!-- User Quick Stats Bar (Cyberpunk HUD Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <!-- ELO Card -->
        <div class="relative bg-[#120817] border border-[#FF0055]/30 hover:border-[#FF0055] rounded-2xl p-5 flex items-center justify-between shadow-[0_4px_20px_rgba(0,0,0,0.5)] transition group overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-[#FF0055]/10 rounded-full blur-xl pointer-events-none group-hover:bg-[#FF0055]/20 transition"></div>
            <div>
                <div class="flex items-center space-x-1.5 text-[11px] font-mono uppercase tracking-wider text-[#FF0055]">
                    <span class="inline-block w-1.5 h-1.5 bg-[#FF0055] rounded-full"></span>
                    <span>Рейтинг Elo</span>
                </div>
                <p class="text-3xl font-cyber font-bold text-white mt-1 group-hover:text-[#FF2A6D] transition-colors">{{ $user->elo }}</p>
                <span class="text-[10px] font-mono text-neutral-400">Competitive Rating</span>
            </div>
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-cyber font-extrabold text-2xl bg-gradient-to-br from-[#FF0055] to-[#990033] text-white shadow-[0_0_20px_rgba(255,0,85,0.5)] border border-[#FF0055]/60">
                {{ $user->level }}
            </div>
        </div>

        <!-- Winrate Card -->
        <div class="relative bg-[#120817] border border-[#FF0055]/30 hover:border-[#FF0055] rounded-2xl p-5 shadow-[0_4px_20px_rgba(0,0,0,0.5)] transition group overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-[#FF0055]/10 rounded-full blur-xl pointer-events-none group-hover:bg-[#FF0055]/20 transition"></div>
            <div class="flex items-center space-x-1.5 text-[11px] font-mono uppercase tracking-wider text-[#FF0055]">
                <span class="inline-block w-1.5 h-1.5 bg-[#FF0055] rounded-full"></span>
                <span>Винрейт 1v1</span>
            </div>
            <div class="flex items-baseline space-x-2 mt-1">
                <span class="text-3xl font-cyber font-bold text-white group-hover:text-[#FF2A6D] transition-colors">{{ $user->winrate }}%</span>
                <span class="text-xs font-mono text-neutral-400">({{ $user->wins_count }}W / {{ $user->losses_count }}L)</span>
            </div>
            <div class="w-full bg-neutral-900 h-1.5 rounded-full mt-3 overflow-hidden border border-[#FF0055]/20">
                <div class="bg-gradient-to-r from-[#FF0055] to-[#FF2A6D] h-full shadow-[0_0_8px_#FF0055]" style="width: {{ min(100, max(0, $user->winrate)) }}%"></div>
            </div>
        </div>

        <!-- Matches Count -->
        <div class="relative bg-[#120817] border border-[#FF0055]/30 hover:border-[#FF0055] rounded-2xl p-5 shadow-[0_4px_20px_rgba(0,0,0,0.5)] transition group overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-[#FF0055]/10 rounded-full blur-xl pointer-events-none group-hover:bg-[#FF0055]/20 transition"></div>
            <div class="flex items-center space-x-1.5 text-[11px] font-mono uppercase tracking-wider text-[#FF0055]">
                <span class="inline-block w-1.5 h-1.5 bg-[#FF0055] rounded-full"></span>
                <span>Сыграно матчей</span>
            </div>
            <p class="text-3xl font-cyber font-bold text-white mt-1 group-hover:text-[#FF2A6D] transition-colors">{{ $user->matches_count }}</p>
            <span class="text-[10px] font-mono text-neutral-400">Total CS2 Duels</span>
        </div>
    </div>

    <!-- Main Cyber Hero Card with Background Gamer Art -->
    <div class="relative rounded-3xl mb-8 overflow-hidden border border-[#FF0055]/40 shadow-[0_0_40px_rgba(255,0,85,0.25)] bg-[#0C0512]">
        <!-- Hero Background Image with Cyber Overlay Gradients -->
        <div class="absolute inset-0">
            <img src="/images/rivalis-hero.jpg" alt="RIVALIS Cyber Gamer" class="w-full h-full object-cover object-center opacity-25 md:opacity-35 mix-blend-luminosity scale-105 filter contrast-125">
            <div class="absolute inset-0 bg-gradient-to-b from-[#08040A]/90 via-[#08040A]/60 to-[#08040A]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_transparent_0%,_#08040A_80%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-[#FF0055]/20 via-transparent to-transparent"></div>
        </div>

        <!-- Cyber Corner Accents -->
        <div class="absolute top-3 left-3 text-[#FF0055]/50 font-mono text-xs select-none">+ HUD // 01</div>
        <div class="absolute top-3 right-3 text-[#FF0055]/50 font-mono text-xs select-none">SYSTEM.ONLINE +</div>
        <div class="absolute bottom-3 left-3 text-[#FF0055]/50 font-mono text-xs select-none">+ RIVALIS.1V1</div>
        <div class="absolute bottom-3 right-3 text-[#FF0055]/50 font-mono text-xs select-none">FPS // 128T +</div>

        <!-- Hero Content (Centered) -->
        <div class="relative z-10 p-8 sm:p-14 md:p-16 max-w-3xl mx-auto text-center flex flex-col items-center">
            <!-- Big Title -->
            <h1 class="text-3xl sm:text-5xl md:text-6xl font-cyber font-black text-white tracking-wider uppercase leading-none">
                ПОИСК МАТЧА <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FF0055] via-[#FF2A6D] to-white">1 НА 1</span>
            </h1>
            <p class="text-neutral-300 text-sm sm:text-base mt-4 max-w-xl mx-auto leading-relaxed">
                Сражайтесь за рейтинговые очки Elo на соревновательных серверах CS2. Полноценная система вычеркивания карт (Map Veto), автоподключение и статистика.
            </p>

            <!-- Search Action Area (Centered) -->
            <div class="mt-8 w-full">
                @if(! $inQueue && ! $matchFound)
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <!-- Play 1v1 Matchmaking Button -->
                        <button 
                            wire:click="startQueue" 
                            class="w-full sm:w-auto px-8 py-5 bg-gradient-to-r from-[#FF0055] via-[#FF1A75] to-[#E1066A] hover:brightness-110 text-white font-cyber font-extrabold text-lg tracking-widest uppercase rounded-2xl shadow-[0_0_30px_rgba(255,0,85,0.6)] transition-all transform active:scale-95 flex items-center justify-center space-x-3 cursor-pointer border border-white/20">
                            <svg class="w-6 h-6 fill-current animate-pulse" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                            </svg>
                            <span>НАЙТИ ИГРУ 1v1</span>
                        </button>

                        <!-- Instant Bot Match (For Testing & Defense) -->
                        <button 
                            wire:click="startInstantBotMatch" 
                            class="w-full sm:w-auto px-6 py-5 bg-[#120817]/90 hover:bg-[#1A0D22] text-neutral-200 hover:text-white font-faceit font-bold text-base tracking-wider uppercase rounded-2xl border border-[#FF0055]/40 hover:border-[#FF0055] shadow-lg transition flex items-center justify-center space-x-2.5 cursor-pointer">
                            <svg class="w-5 h-5 text-[#FF0055]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Тест с Ботом (Демо)</span>
                        </button>
                    </div>
                @elseif($inQueue && ! $matchFound)
                    <!-- Queue In Progress Box (Centered) -->
                    <div class="bg-[#120817]/95 border-2 border-[#FF0055] rounded-2xl p-6 shadow-[0_0_35px_rgba(255,0,85,0.4)] max-w-md mx-auto">
                        <div class="flex items-center justify-center space-x-4 mb-4">
                            <div class="relative w-12 h-12 flex items-center justify-center">
                                <div class="absolute inset-0 rounded-full border-3 border-[#FF0055]/30 border-t-[#FF0055] animate-spin shadow-[0_0_15px_#FF0055]"></div>
                                <svg class="w-6 h-6 text-[#FF0055]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-xs font-mono uppercase tracking-widest text-[#FF0055]">Поиск соперника 1v1</p>
                                <p class="text-3xl font-cyber font-bold text-white tracking-wider">
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
                                class="flex-1 py-2.5 bg-neutral-900 hover:bg-neutral-800 text-neutral-300 hover:text-white rounded-xl text-xs font-faceit font-bold uppercase tracking-wider border border-neutral-700 transition cursor-pointer">
                                Отменить поиск
                            </button>
                            <button 
                                wire:click="startInstantBotMatch" 
                                class="flex-1 py-2.5 bg-[#FF0055]/20 hover:bg-[#FF0055]/30 text-[#FF0055] border border-[#FF0055]/50 rounded-xl text-xs font-faceit font-bold uppercase tracking-wider transition cursor-pointer">
                                Бот сразу
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- MATCH FOUND MODAL POPUP (CYBERPUNK READY CHECK) -->
    @if($matchFound)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/85 backdrop-blur-md p-4 animate-fadeIn">
        <div class="bg-[#120817] border-2 border-[#FF0055] rounded-3xl max-w-lg w-full p-8 text-center shadow-[0_0_60px_rgba(255,0,85,0.7)] relative overflow-hidden">
            <!-- Pulsing neon header bar -->
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-[#FF0055] via-white to-[#FF0055] shadow-[0_0_20px_#FF0055] animate-pulse"></div>

            <div class="w-20 h-20 bg-[#FF0055]/20 text-[#FF0055] rounded-2xl border border-[#FF0055] flex items-center justify-center mx-auto mb-5 shadow-[0_0_25px_rgba(255,0,85,0.6)]">
                <svg class="w-10 h-10 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h2 class="text-3xl sm:text-4xl font-cyber font-black text-white uppercase tracking-wider">
                МАТЧ НАЙДЕН!
            </h2>
            <p class="text-neutral-300 text-sm mt-2 mb-6">
                Соперник обнаружен в сети. Подтвердите готовность для перехода в комнату Map Veto!
            </p>

            <div class="flex items-center justify-center space-x-4">
                <button 
                    wire:click="acceptMatch" 
                    class="w-full py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:brightness-110 text-white font-cyber font-extrabold text-2xl uppercase tracking-wider rounded-2xl shadow-[0_0_30px_rgba(16,185,129,0.5)] transition transform active:scale-95 cursor-pointer border border-emerald-300/30">
                    ПРИНЯТЬ МАТЧ
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Matches List (Cyber Style) -->
    <div class="bg-[#120817] border border-[#FF0055]/30 rounded-3xl p-6 sm:p-8 shadow-[0_4px_24px_rgba(0,0,0,0.6)]">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-[#FF0055]/20">
            <div class="flex items-center space-x-3">
                <div class="w-3 h-3 bg-[#FF0055] rounded-sm shadow-[0_0_8px_#FF0055]"></div>
                <h2 class="font-cyber text-2xl font-bold tracking-wide uppercase text-white">
                    История последних матчей
                </h2>
            </div>
            <span class="text-xs font-mono uppercase tracking-widest text-[#FF0055] bg-[#FF0055]/10 border border-[#FF0055]/30 px-2.5 py-1 rounded">
                HISTORY
            </span>
        </div>

        @if($recentMatches->isEmpty())
            <div class="text-center py-16 text-neutral-500 text-sm">
                <p class="font-faceit text-lg text-neutral-400">У вас пока нет сыгранных матчей.</p>
                <p class="text-xs text-neutral-500 mt-1">Нажмите «Найти игру 1v1» выше, чтобы начать свое восхождение в топ!</p>
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
                    <div class="p-4 bg-[#0A040E] border border-[#FF0055]/15 hover:border-[#FF0055]/60 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition group">
                        <!-- Match Result Badge & Map -->
                        <div class="flex items-center space-x-4">
                            @if($match->status === 'finished')
                                <span class="w-24 text-center py-1.5 rounded-xl text-xs font-cyber font-bold uppercase tracking-wider {{ $isWinner ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/40 shadow-[0_0_10px_rgba(16,185,129,0.2)]' : 'bg-red-500/15 text-red-400 border border-red-500/40' }}">
                                    {{ $isWinner ? 'ПОБЕДА' : 'ПОРАЖЕНИЕ' }}
                                </span>
                            @else
                                <span class="w-24 text-center py-1.5 rounded-xl text-xs font-cyber font-bold uppercase tracking-wider bg-amber-500/15 text-amber-400 border border-amber-500/40">
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
                        <div class="flex items-center space-x-6">
                            <div class="text-center bg-[#120817] px-3.5 py-1 rounded-xl border border-[#FF0055]/20">
                                <span class="font-cyber text-lg font-bold text-white">{{ $myScore }} : {{ $oppScore }}</span>
                            </div>

                            <div class="flex items-center space-x-2.5">
                                <span class="text-xs font-mono text-[#FF0055]">vs</span>
                                <span class="text-sm font-semibold text-neutral-200">{{ $opponent ? $opponent->name : 'Оппонент' }}</span>
                                @if($opponent)
                                    <span class="w-6 h-6 rounded-lg flex items-center justify-center font-faceit text-xs font-bold bg-[#FF0055]/30 text-white border border-[#FF0055]/50">
                                        {{ $opponent->level }}
                                    </span>
                                @endif
                            </div>

                            <!-- Elo Change -->
                            <div class="w-16 text-right">
                                @if($eloDelta !== null)
                                    <span class="font-cyber font-bold text-sm {{ $eloDelta >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                        {{ $eloDelta >= 0 ? "+{$eloDelta}" : $eloDelta }}
                                    </span>
                                @endif
                            </div>

                            <!-- Action button -->
                            <a href="{{ route('match.lobby', ['uuid' => $match->uuid]) }}" class="px-4 py-2 bg-[#FF0055]/15 hover:bg-[#FF0055] text-neutral-200 hover:text-white rounded-xl text-xs font-faceit font-bold uppercase tracking-wider border border-[#FF0055]/30 hover:border-[#FF0055] transition shadow-sm">
                                Комната
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
