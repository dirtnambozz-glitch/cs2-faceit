<div wire:poll.2000ms="loadMatch">
    <!-- Match Room Top Header (Cyber Versus HUD) -->
    <div class="relative bg-[#120817] border border-[#FF0055]/40 rounded-3xl p-6 sm:p-8 mb-8 shadow-[0_0_35px_rgba(255,0,85,0.25)] overflow-hidden">
        <!-- Background Ambient Neon Glow -->
        <div class="absolute -right-20 -top-20 w-60 h-60 bg-[#FF0055]/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-60 h-60 bg-[#E1066A]/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <!-- Player 1 (Left) -->
            <div class="flex items-center space-x-4 flex-1 w-full md:w-auto">
                <div class="relative">
                    <img src="{{ $match->player1->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . $match->player1->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-[#FF0055] bg-neutral-950 object-cover shadow-[0_0_15px_rgba(255,0,85,0.4)]" alt="P1">
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-md flex items-center justify-center font-cyber text-xs font-bold bg-[#FF0055] text-white shadow">
                        {{ $match->player1->level }}
                    </div>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('profile', ['id' => $match->player1->id]) }}" class="text-xl sm:text-2xl font-cyber font-extrabold text-white tracking-wide hover:text-[#FF2A6D] transition">
                            {{ $match->player1->name }}
                        </a>
                        @if($match->player1->is_bot)
                            <span class="text-[10px] font-mono bg-[#FF0055]/20 text-[#FF0055] border border-[#FF0055]/40 px-2 py-0.5 rounded uppercase">AI BOT</span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2.5 mt-1 font-mono text-xs text-neutral-400">
                        <span class="text-[#FF0055] font-bold">{{ $match->player1->elo }} ELO</span>
                        <span>•</span>
                        <span>{{ $match->player1->winrate }}% WR</span>
                    </div>
                </div>
            </div>

            <!-- Match Center Status / Score -->
            <div class="text-center px-4 shrink-0">
                @if($match->status === 'voting')
                    <div class="inline-flex items-center space-x-2 bg-[#FF0055]/20 border border-[#FF0055] text-[#FF2A6D] px-4 py-1.5 rounded-full text-xs font-mono uppercase tracking-widest shadow-[0_0_15px_rgba(255,0,85,0.4)] animate-pulse">
                        <span class="w-2 h-2 rounded-full bg-[#FF0055] animate-ping"></span>
                        <span>ФАЗА ВЫБОРА КАРТ // MAP VETO</span>
                    </div>
                    <p class="text-xs font-mono text-neutral-400 mt-2">
                        Ход: <span class="text-white font-bold">{{ $match->currentTurnPlayer ? $match->currentTurnPlayer->name : 'Ожидание' }}</span>
                    </p>
                @elseif($match->status === 'ready' || $match->status === 'in_progress')
                    <div class="inline-flex items-center space-x-2 bg-emerald-500/20 border border-emerald-500/50 text-emerald-400 px-4 py-1.5 rounded-full text-xs font-mono uppercase tracking-widest shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        <span>МАТЧ ГОТОВ К ИГРЕ</span>
                    </div>
                    <div class="text-3xl font-cyber font-black text-white uppercase mt-1 tracking-wider">
                        {{ $match->map ? $match->map->name : 'CS2 Map' }}
                    </div>
                @elseif($match->status === 'finished')
                    <div class="inline-flex items-center space-x-2 bg-[#FF0055]/20 border border-[#FF0055]/40 text-[#FF2A6D] px-4 py-1.5 rounded-full text-xs font-mono uppercase tracking-widest mb-2">
                        <span>МАТЧ ЗАВЕРШЕН</span>
                    </div>
                    <div class="text-4xl sm:text-5xl font-cyber font-black text-white tracking-widest">
                        {{ $match->score_player1 }} : {{ $match->score_player2 }}
                    </div>
                @endif
            </div>

            <!-- Player 2 (Right) -->
            <div class="flex items-center space-x-4 flex-1 justify-end text-right w-full md:w-auto">
                <div>
                    <div class="flex items-center justify-end space-x-2">
                        @if($match->player2 && $match->player2->is_bot)
                            <span class="text-[10px] font-mono bg-[#FF0055]/20 text-[#FF0055] border border-[#FF0055]/40 px-2 py-0.5 rounded uppercase">AI BOT</span>
                        @endif
                        @if($match->player2)
                            <a href="{{ route('profile', ['id' => $match->player2->id]) }}" class="text-xl sm:text-2xl font-cyber font-extrabold text-white tracking-wide hover:text-[#FF2A6D] transition">
                                {{ $match->player2->name }}
                            </a>
                        @else
                            <span class="text-xl sm:text-2xl font-cyber font-extrabold text-white tracking-wide">
                                Ожидание соперника...
                            </span>
                        @endif
                    </div>
                    @if($match->player2)
                    <div class="flex items-center justify-end space-x-2.5 mt-1 font-mono text-xs text-neutral-400">
                        <span>{{ $match->player2->winrate }}% WR</span>
                        <span>•</span>
                        <span class="text-[#FF0055] font-bold">{{ $match->player2->elo }} ELO</span>
                    </div>
                    @endif
                </div>
                <div class="relative">
                    <img src="{{ $match->player2->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . ($match->player2->name ?? 'opponent') }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-[#FF0055] bg-neutral-950 object-cover shadow-[0_0_15px_rgba(255,0,85,0.4)]" alt="P2">
                    @if($match->player2)
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-md flex items-center justify-center font-cyber text-xs font-bold bg-[#FF0055] text-white shadow">
                        {{ $match->player2->level }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN BODY: VETO vs READY vs FINISHED -->
    @if($match->status === 'voting')
        <!-- MAP VETO SECTION -->
        <div class="bg-[#120817] border border-[#FF0055]/40 rounded-3xl p-6 sm:p-8 shadow-[0_0_30px_rgba(255,0,85,0.2)] mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-[#FF0055]/20">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-cyber font-bold text-white uppercase tracking-wider">
                        ВЫЧЕРКИВАНИЕ КАРТ // MAP VETO
                    </h2>
                    <p class="text-neutral-400 text-xs sm:text-sm mt-1 font-mono">
                        Каждый игрок по очереди вычеркивает карту. Оставшаяся карта станет ареной битвы.
                    </p>
                </div>
                <div class="text-right">
                    @if($match->current_turn_player_id === auth()->id())
                        <span class="px-5 py-2.5 bg-gradient-to-r from-[#FF0055] to-[#E1066A] text-white font-cyber font-bold text-xs uppercase tracking-widest rounded-xl shadow-[0_0_20px_rgba(255,0,85,0.6)] animate-pulse inline-block">
                            ВАШ ХОД — ЗАБАНИТЬ КАРТУ
                        </span>
                    @else
                        <span class="px-5 py-2.5 bg-neutral-900 text-neutral-400 font-mono text-xs uppercase tracking-widest rounded-xl border border-neutral-800 inline-block">
                            ОЖИДАНИЕ ХОДА СОПЕРНИКА...
                        </span>
                    @endif
                </div>
            </div>

            <!-- Active Maps Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($allMaps as $map)
                    @php
                        $isBanned = in_array($map->id, $bannedMapIds);
                        $canBan = ($match->current_turn_player_id === auth()->id()) && ! $isBanned;
                    @endphp
                    <div 
                        @if($canBan) wire:click="banMap({{ $map->id }})" @endif
                        class="relative rounded-2xl overflow-hidden border-2 transition-all duration-300 group
                            {{ $isBanned ? 'border-neutral-900 opacity-30 grayscale pointer-events-none' : ($canBan ? 'border-[#FF0055]/40 hover:border-[#FF0055] hover:shadow-[0_0_25px_rgba(255,0,85,0.5)] cursor-pointer hover:scale-[1.02]' : 'border-neutral-800 opacity-80') }}">
                        
                        <!-- Map Image Thumbnail -->
                        <div class="h-40 bg-neutral-950 bg-cover bg-center transition-transform duration-500 group-hover:scale-105" style="background-image: url('{{ $map->image }}')">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#08040A] via-[#08040A]/40 to-transparent"></div>
                        </div>

                        <!-- Card Content -->
                        <div class="absolute bottom-0 left-0 right-0 p-3.5 flex items-end justify-between">
                            <div>
                                <p class="text-[10px] font-mono text-[#FF0055] uppercase tracking-wider">{{ $map->code }}</p>
                                <p class="text-xl font-cyber font-bold text-white tracking-wide">{{ $map->name }}</p>
                            </div>

                            @if($isBanned)
                                <span class="px-2.5 py-1 bg-red-600/90 text-white font-cyber font-bold text-xs uppercase tracking-wider rounded-lg shadow">
                                    BANNED
                                </span>
                            @elseif($canBan)
                                <span class="px-3 py-1.5 bg-gradient-to-r from-[#FF0055] to-[#E1066A] text-white font-cyber font-bold text-xs uppercase tracking-wider rounded-xl shadow-[0_0_12px_rgba(255,0,85,0.6)] group-hover:brightness-110 transition">
                                    БАН
                                </span>
                            @endif
                        </div>

                        @if($isBanned)
                            <div class="absolute inset-0 flex items-center justify-center bg-black/70">
                                <svg class="w-12 h-12 text-red-500/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

    @elseif($match->status === 'ready' || $match->status === 'in_progress')
        <!-- MATCH READY: CONNECT TO CS2 SERVER -->
        <div class="bg-[#120817] border border-[#FF0055]/40 rounded-3xl p-6 sm:p-10 mb-8 shadow-[0_0_40px_rgba(255,0,85,0.3)]">
            <div class="max-w-3xl mx-auto text-center">
                <!-- Selected Map Banner -->
                <div class="inline-block relative rounded-3xl overflow-hidden border-2 border-[#FF0055] mb-6 shadow-[0_0_35px_rgba(255,0,85,0.4)] w-full max-w-xl">
                    <img src="{{ $match->map->image ?? '' }}" alt="{{ $match->map->name ?? '' }}" class="w-full h-52 sm:h-72 object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#08040A] via-[#08040A]/50 to-transparent flex flex-col justify-end p-6">
                        <span class="text-xs uppercase font-mono font-bold tracking-widest text-[#FF0055] bg-[#FF0055]/20 px-3 py-1 rounded-md w-max mx-auto border border-[#FF0055]/40 mb-1">
                            РЕШАЮЩАЯ КАРТА МАТЧА
                        </span>
                        <h3 class="text-4xl sm:text-5xl font-cyber font-black text-white tracking-wider uppercase mt-1">
                            {{ $match->map->name ?? 'Mirage' }}
                        </h3>
                    </div>
                </div>

                <!-- RCON Status Alert -->
                @if($rconStatusMessage)
                    <div class="mb-5 p-3.5 rounded-2xl flex items-center justify-between text-xs {{ $rconSuccess ? 'bg-emerald-500/10 border border-emerald-500/30 text-emerald-400' : 'bg-amber-500/10 border border-amber-500/30 text-amber-400' }}">
                        <div class="flex items-center space-x-2">
                            @if($rconSuccess)
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            @endif
                            <span>{{ $rconStatusMessage }}</span>
                        </div>
                        @if(!$rconSuccess)
                            <div class="flex items-center space-x-2">
                                <button wire:click="retryRcon" class="px-2.5 py-1 bg-neutral-800 hover:bg-neutral-700 text-white rounded text-[11px] font-semibold transition">
                                    Повторить смену карты
                                </button>
                                <a href="{{ route('server.settings') }}" class="px-2.5 py-1 bg-[#FF0055] hover:bg-[#FF2A6D] text-white rounded text-[11px] font-semibold transition">
                                    Настройки RCON →
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Server Info & Connect Options (Cyber Terminal Box) -->
                <div class="bg-[#08040A] border border-[#FF0055]/30 rounded-2xl p-6 mb-6 text-left">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#FF0055]/20">
                        <div class="flex items-center space-x-2.5">
                            <span class="w-3 h-3 rounded-full bg-emerald-400 shadow-[0_0_10px_#34d399] animate-pulse"></span>
                            <span class="text-sm font-cyber font-bold text-white uppercase tracking-wider">Сервер: UP FaceIt CS2</span>
                        </div>
                        <span class="text-xs text-[#FF0055] font-mono">{{ $match->server_ip }}:{{ $match->server_port }}</span>
                    </div>

                    <!-- Connect Command Box -->
                    <label class="block text-xs uppercase font-mono tracking-wider text-neutral-400 mb-2">
                        Команда подключения (для консоли CS2 ~):
                    </label>
                    <div class="flex items-center space-x-2">
                        <input 
                            type="text" 
                            readonly 
                            value="connect {{ $match->server_ip }}:{{ $match->server_port }}; password {{ $match->server_password }}" 
                            id="connectCmd"
                            class="w-full bg-[#120817] border border-[#FF0055]/40 text-emerald-400 font-mono text-sm px-4 py-3.5 rounded-xl focus:outline-none">
                        <button 
                            type="button" 
                            onclick="navigator.clipboard.writeText(document.getElementById('connectCmd').value); alert('Команда скопирована! Откройте консоль в CS2 (клавиша ~) и вставьте её.');"
                            class="px-5 py-3.5 bg-[#120817] hover:bg-[#FF0055] text-white font-faceit font-bold text-sm uppercase tracking-wider rounded-xl border border-[#FF0055]/40 hover:border-[#FF0055] transition cursor-pointer whitespace-nowrap">
                            Копировать
                        </button>
                    </div>

                    <!-- Glowing Big Neon Button for 1-Click Copy -->
                    <div class="mt-4">
                        <button 
                            type="button" 
                            id="copyMainBtn"
                            onclick="navigator.clipboard.writeText(document.getElementById('connectCmd').value); this.innerHTML='<span>✓ СКОПИРОВАНО! ВСТАВЬТЕ В КОНСОЛЬ CS2 (~)</span>'; this.classList.remove('from-[#FF0055]', 'to-[#E1066A]'); this.classList.add('bg-emerald-600');"
                            class="w-full py-4 bg-gradient-to-r from-[#FF0055] via-[#FF1A75] to-[#E1066A] hover:brightness-110 text-white font-cyber font-extrabold text-lg sm:text-xl uppercase tracking-widest rounded-2xl shadow-[0_0_30px_rgba(255,0,85,0.6)] text-center transition cursor-pointer flex items-center justify-center space-x-2 border border-white/20">
                            <span>📋 СКОПИРОВАТЬ IP ДЛЯ КОНСОЛИ CS2</span>
                        </button>
                    </div>

                    <!-- Step-by-step connection guide -->
                    <div class="mt-4 bg-[#120817]/70 rounded-xl p-3.5 text-xs text-neutral-400 flex flex-col sm:flex-row items-center justify-around gap-2 border border-[#FF0055]/20 font-mono">
                        <div class="flex items-center space-x-2">
                            <span class="w-5 h-5 rounded-md bg-[#FF0055]/20 text-[#FF0055] font-bold flex items-center justify-center text-[11px] border border-[#FF0055]/40">1</span>
                            <span>Запустите CS2</span>
                        </div>
                        <span class="text-[#FF0055]/40 hidden sm:inline">→</span>
                        <div class="flex items-center space-x-2">
                            <span class="w-5 h-5 rounded-md bg-[#FF0055]/20 text-[#FF0055] font-bold flex items-center justify-center text-[11px] border border-[#FF0055]/40">2</span>
                            <span>Консоль <kbd class="px-1.5 py-0.5 bg-neutral-900 text-white rounded font-mono text-[11px] border border-neutral-700">~</kbd></span>
                        </div>
                        <span class="text-[#FF0055]/40 hidden sm:inline">→</span>
                        <div class="flex items-center space-x-2">
                            <span class="w-5 h-5 rounded-md bg-[#FF0055]/20 text-[#FF0055] font-bold flex items-center justify-center text-[11px] border border-[#FF0055]/40">3</span>
                            <span>Вставьте и <kbd class="px-1.5 py-0.5 bg-neutral-900 text-white rounded font-mono text-[11px] border border-neutral-700">Enter</kbd></span>
                        </div>
                    </div>
                </div>

                <!-- Simulation / Score Entry Form for Testing & Defense -->
                <div class="bg-[#08040A] border border-[#FF0055]/30 rounded-2xl p-6 text-left">
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-[#FF0055]/20">
                        <span class="text-xs font-cyber font-bold uppercase tracking-wider text-white">
                            ФИКСАЦИЯ РЕЗУЛЬТАТА // ТЕСТИРОВАНИЕ УП
                        </span>
                        <span class="text-[11px] font-mono text-[#FF0055]">Авто-расчет ELO</span>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="flex items-center space-x-3 w-full sm:w-auto">
                            <span class="text-xs font-semibold text-neutral-300">{{ $match->player1->name }}:</span>
                            <input type="number" wire:model="scoreP1" min="0" max="30" class="w-16 bg-[#120817] border border-[#FF0055]/40 rounded-xl px-2 py-2 text-center font-cyber font-bold text-white text-lg focus:outline-none focus:border-[#FF0055]">
                            <span class="text-[#FF0055] font-bold text-xl">:</span>
                            <input type="number" wire:model="scoreP2" min="0" max="30" class="w-16 bg-[#120817] border border-[#FF0055]/40 rounded-xl px-2 py-2 text-center font-cyber font-bold text-white text-lg focus:outline-none focus:border-[#FF0055]">
                            <span class="text-xs font-semibold text-neutral-300">{{ $match->player2->name ?? 'Бот' }}:</span>
                        </div>

                        <div class="flex-1 flex justify-end w-full sm:w-auto">
                            <button 
                                wire:click="submitScore" 
                                class="w-full sm:w-auto px-6 py-3 bg-[#FF0055] hover:bg-[#FF2A6D] text-white font-cyber font-bold text-sm uppercase tracking-wider rounded-xl transition shadow-[0_0_20px_rgba(255,0,85,0.4)] cursor-pointer">
                                Завершить матч и рассчитать Elo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @elseif($match->status === 'finished')
        <!-- MATCH FINISHED SCREEN (CYBER VICTORY / DEFEAT BANNER) -->
        @php
            $isWinner = ($match->winner_id === auth()->id());
            $myEloChange = ($match->player1_id === auth()->id()) ? $match->elo_change_p1 : $match->elo_change_p2;
        @endphp
        <div class="bg-[#120817] border-2 {{ $isWinner ? 'border-emerald-500 shadow-[0_0_40px_rgba(16,185,129,0.3)]' : 'border-[#FF0055] shadow-[0_0_40px_rgba(255,0,85,0.3)]' }} rounded-3xl p-10 mb-8 text-center relative overflow-hidden">
            <div class="w-24 h-24 rounded-3xl flex items-center justify-center mx-auto mb-4 {{ $isWinner ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500' : 'bg-[#FF0055]/20 text-[#FF0055] border border-[#FF0055]' }}">
                @if($isWinner)
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @else
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @endif
            </div>

            <h2 class="text-4xl sm:text-6xl font-cyber font-black uppercase tracking-wider {{ $isWinner ? 'text-emerald-400' : 'text-[#FF0055]' }}">
                {{ $isWinner ? 'ПОБЕДА!' : 'ПОРАЖЕНИЕ' }}
            </h2>

            <div class="text-4xl font-cyber font-bold text-white mt-3 mb-5">
                {{ $match->score_player1 }} — {{ $match->score_player2 }}
            </div>

            <div class="inline-flex items-center space-x-3 bg-[#08040A] px-6 py-3 rounded-2xl border border-[#FF0055]/30 mb-8">
                <span class="text-xs uppercase font-mono text-neutral-400">Изменение рейтинга:</span>
                <span class="font-cyber font-bold text-2xl {{ $myEloChange >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                    {{ $myEloChange >= 0 ? "+{$myEloChange}" : $myEloChange }} ELO
                </span>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('home') }}" class="px-8 py-4 bg-gradient-to-r from-[#FF0055] to-[#E1066A] hover:brightness-110 text-white font-cyber font-bold text-base uppercase tracking-wider rounded-2xl transition shadow-[0_0_25px_rgba(255,0,85,0.5)]">
                    Вернуться в лобби
                </a>
                <a href="{{ route('leaderboard') }}" class="px-7 py-4 bg-[#08040A] hover:bg-[#1A0D22] text-neutral-200 hover:text-white font-cyber font-semibold text-base uppercase tracking-wider rounded-2xl border border-[#FF0055]/30 hover:border-[#FF0055] transition">
                    Таблица лидеров
                </a>
            </div>
        </div>
    @endif
</div>
