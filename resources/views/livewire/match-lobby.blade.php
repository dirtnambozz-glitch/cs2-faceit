<div wire:poll.1000ms="loadMatch" class="space-y-6">
    <!-- Match Versus Header (FACEIT Style) -->
    <div class="bg-[#12151B] border border-white/[0.07] rounded-3xl p-6 sm:p-8 shadow-sm">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <!-- Player 1 (Left) -->
            <div class="flex items-center space-x-4 flex-1 w-full md:w-auto">
                <div class="relative shrink-0">
                    <img src="{{ $match->player1->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . $match->player1->name }}" class="w-16 h-16 sm:w-18 sm:h-18 rounded-2xl border border-white/[0.1] bg-neutral-900 object-cover" alt="P1">
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-lg flex items-center justify-center font-heading font-bold text-xs bg-[#FF5500] text-white shadow-sm">
                        {{ $match->player1->level }}
                    </div>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('profile', ['id' => $match->player1->id]) }}" class="text-lg sm:text-xl font-heading font-bold text-white hover:text-[#FF5500] transition truncate">
                            {{ $match->player1->name }}
                        </a>
                        @if($match->player1->is_bot)
                            <span class="text-[10px] font-mono bg-white/[0.06] text-neutral-300 px-1.5 py-0.5 rounded uppercase">AI BOT</span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2 mt-1 font-mono text-xs text-neutral-400">
                        <span class="text-white font-semibold tabular-nums">{{ $match->player1->elo }} ELO</span>
                        <span class="text-neutral-600">•</span>
                        <span class="tabular-nums">{{ $match->player1->winrate }}% Winrate</span>
                    </div>
                </div>
            </div>

            <!-- Match Center Status -->
            <div class="text-center px-4 shrink-0 flex flex-col items-center">
                @if($match->status === 'voting')
                    <div class="inline-flex items-center space-x-2 bg-amber-500/10 border border-amber-500/25 text-amber-300 px-3.5 py-1 rounded-full text-xs font-mono uppercase tracking-wider mb-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                        <span>СТАДИЯ MAP VETO</span>
                    </div>
                    <div class="text-xs font-mono text-neutral-400 mt-1">
                        Ход: <span class="text-white font-semibold">{{ $match->currentTurnPlayer ? $match->currentTurnPlayer->name : 'Ожидание' }}</span>
                    </div>
                @elseif($match->status === 'ready' || $match->status === 'in_progress')
                    <div class="inline-flex items-center space-x-2 bg-emerald-500/10 border border-emerald-500/25 text-emerald-400 px-3.5 py-1 rounded-full text-xs font-mono uppercase tracking-wider mb-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        <span>МАТЧ АКТИВЕН</span>
                    </div>
                    <div class="text-2xl font-heading font-extrabold text-white uppercase tracking-tight">
                        {{ $match->map ? $match->map->name : 'CS2 Map' }}
                    </div>
                @elseif($match->status === 'finished')
                    <div class="inline-flex items-center space-x-2 bg-white/[0.06] border border-white/[0.08] text-neutral-300 px-3 py-0.5 rounded-full text-xs font-mono uppercase tracking-wider mb-1">
                        <span>МАТЧ ЗАВЕРШЕН</span>
                    </div>
                    <div class="text-3xl sm:text-4xl font-heading font-black text-white tracking-wider tabular-nums">
                        {{ $match->score_player1 }} : {{ $match->score_player2 }}
                    </div>
                @endif
            </div>

            <!-- Player 2 (Right) -->
            <div class="flex items-center space-x-4 flex-1 justify-end text-right w-full md:w-auto">
                <div class="min-w-0">
                    <div class="flex items-center justify-end space-x-2">
                        @if($match->player2 && $match->player2->is_bot)
                            <span class="text-[10px] font-mono bg-white/[0.06] text-neutral-300 px-1.5 py-0.5 rounded uppercase">AI BOT</span>
                        @endif
                        @if($match->player2)
                            <a href="{{ route('profile', ['id' => $match->player2->id]) }}" class="text-lg sm:text-xl font-heading font-bold text-white hover:text-[#FF5500] transition truncate">
                                {{ $match->player2->name }}
                            </a>
                        @else
                            <span class="text-lg font-heading font-bold text-neutral-400">
                                Ожидание...
                            </span>
                        @endif
                    </div>
                    @if($match->player2)
                    <div class="flex items-center justify-end space-x-2 mt-1 font-mono text-xs text-neutral-400">
                        <span class="tabular-nums">{{ $match->player2->winrate }}% Winrate</span>
                        <span class="text-neutral-600">•</span>
                        <span class="text-white font-semibold tabular-nums">{{ $match->player2->elo }} ELO</span>
                    </div>
                    @endif
                </div>
                <div class="relative shrink-0">
                    <img src="{{ $match->player2->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . ($match->player2->name ?? 'opponent') }}" class="w-16 h-16 sm:w-18 sm:h-18 rounded-2xl border border-white/[0.1] bg-neutral-900 object-cover" alt="P2">
                    @if($match->player2)
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-lg flex items-center justify-center font-heading font-bold text-xs bg-[#FF5500] text-white shadow-sm">
                        {{ $match->player2->level }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN BODY: VETO vs CONNECT vs RESULT -->
    @if($match->status === 'voting')
        <!-- MAP VETO SECTION -->
        <div class="bg-[#12151B] border border-white/[0.07] rounded-3xl p-6 sm:p-8 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-white/[0.06]">
                <div>
                    <h2 class="text-xl sm:text-2xl font-heading font-bold text-white tracking-tight">
                        Вычеркивание карт (Map Veto)
                    </h2>
                    <p class="text-neutral-400 text-xs sm:text-sm mt-0.5 font-mono">
                        Поочередный бан карт. Последняя оставшаяся карта определит локацию матча.
                    </p>
                </div>
                <div class="text-right shrink-0">
                    @if($match->current_turn_player_id === auth()->id())
                        <span class="px-4 py-2 bg-[#FF5500] text-white font-heading font-bold text-xs uppercase tracking-wider rounded-xl shadow-sm animate-pulse inline-block">
                            ВАШ ХОД — ЗАБАНИТЬ КАРТУ
                        </span>
                    @else
                        <span class="px-4 py-2 bg-white/[0.04] text-neutral-400 font-mono text-xs uppercase tracking-wider rounded-xl border border-white/[0.06] inline-block">
                            Ожидание хода соперника...
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
                        class="relative rounded-2xl overflow-hidden border transition-all duration-200 group
                            {{ $isBanned ? 'border-neutral-900 opacity-25 grayscale pointer-events-none' : ($canBan ? 'border-white/[0.1] hover:border-[#FF5500] cursor-pointer hover:-translate-y-0.5 shadow-sm' : 'border-white/[0.07] opacity-85') }}">
                        
                        <!-- Map Image Thumbnail -->
                        <div class="h-44 bg-neutral-950 bg-cover bg-center transition-transform duration-500 group-hover:scale-105" style="background-image: url('{{ $map->image }}')">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                        </div>

                        <!-- Card Content -->
                        <div class="absolute bottom-0 left-0 right-0 p-4 flex items-end justify-between">
                            <div>
                                <div class="text-[10px] font-mono text-neutral-400 uppercase tracking-wider">{{ $map->code }}</div>
                                <div class="text-lg font-heading font-bold text-white tracking-tight">{{ $map->name }}</div>
                            </div>

                            @if($isBanned)
                                <span class="px-2.5 py-1 bg-red-600/90 text-white font-mono font-bold text-xs uppercase rounded">
                                    BANNED
                                </span>
                            @elseif($canBan)
                                <span class="px-3 py-1.5 bg-[#FF5500] hover:bg-[#FF6E26] text-white font-heading font-bold text-xs uppercase rounded-lg shadow-sm transition">
                                    БАН
                                </span>
                            @endif
                        </div>

                        @if($isBanned)
                            <div class="absolute inset-0 flex items-center justify-center bg-black/60">
                                <svg class="w-10 h-10 text-red-500/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <div class="bg-[#12151B] border border-white/[0.07] rounded-3xl p-6 sm:p-10 shadow-sm">
            <div class="max-w-3xl mx-auto text-center">
                <!-- Selected Map Banner -->
                <div class="inline-block relative rounded-2xl overflow-hidden border border-white/[0.1] mb-6 shadow-md w-full max-w-xl group aspect-[16/9] max-h-72">
                    <img src="{{ $match->map->image ?? '' }}" alt="{{ $match->map->name ?? '' }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-6">
                        <span class="text-xs uppercase font-mono font-semibold tracking-wider text-[#FF5500] bg-black/60 backdrop-blur-sm px-3 py-1 rounded-md w-max mx-auto border border-white/[0.1] mb-1">
                            РЕШАЮЩАЯ КАРТА
                        </span>
                        <h3 class="text-3xl sm:text-4xl font-heading font-extrabold text-white uppercase tracking-tight mt-1">
                            {{ $match->map->name ?? 'Mirage' }}
                        </h3>
                    </div>
                </div>

                <!-- Connect & Leaver Timeout Box -->
                <div class="bg-[#0E1116] border border-white/[0.08] rounded-2xl p-6 mb-6 text-left">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-4 mb-4 border-b border-white/[0.06] gap-3">
                        <div class="flex items-center space-x-2">
                            <span class="w-2.5 h-2.5 rounded-full {{ $match->connectRemainingSeconds() > 0 ? 'bg-amber-400 animate-pulse' : 'bg-red-500' }}"></span>
                            <span class="font-heading font-bold text-sm text-white uppercase tracking-wider">
                                Таймер подключения к матчу
                            </span>
                        </div>
                        <div class="flex items-center space-x-2 font-mono">
                            <span class="text-xs text-neutral-400 uppercase">Осталось:</span>
                            <span class="font-bold text-xl tabular-nums {{ $match->connectRemainingSeconds() <= 60 ? 'text-red-400 animate-pulse' : 'text-amber-400' }}">
                                {{ sprintf('%02d:%02d', floor($match->connectRemainingSeconds() / 60), $match->connectRemainingSeconds() % 60) }}
                            </span>
                        </div>
                    </div>

                    <!-- Players Connection Badges -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                        <!-- Player 1 Status -->
                        <div class="bg-[#12151B] border border-white/[0.06] rounded-xl p-3.5 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $match->player1->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . $match->player1->name }}" class="w-9 h-9 rounded-lg border border-white/[0.1] object-cover" alt="P1">
                                <div>
                                    <div class="font-semibold text-xs text-white">{{ $match->player1->name }}</div>
                                    <div class="text-[10px] font-mono text-neutral-500">Слот 1</div>
                                </div>
                            </div>
                            @if($match->player1_connected)
                                <span class="px-2.5 py-1 rounded-md bg-emerald-500/10 text-emerald-400 border border-emerald-500/25 font-mono font-semibold text-xs flex items-center space-x-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    <span>НА СЕРВЕРЕ</span>
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-md bg-amber-500/10 text-amber-400 border border-amber-500/25 font-mono font-semibold text-xs flex items-center space-x-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                    <span>ОЖИДАЕТСЯ...</span>
                                </span>
                            @endif
                        </div>

                        <!-- Player 2 Status -->
                        @php
                            $isP2Bot = $match->player2 && $match->player2->is_bot;
                            $p2Connected = $match->player2_connected || $isP2Bot;
                        @endphp
                        <div class="bg-[#12151B] border border-white/[0.06] rounded-xl p-3.5 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $match->player2->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . ($match->player2->name ?? 'bot') }}" class="w-9 h-9 rounded-lg border border-white/[0.1] object-cover" alt="P2">
                                <div>
                                    <div class="font-semibold text-xs text-white">{{ $match->player2->name ?? 'Оппонент' }}</div>
                                    <div class="text-[10px] font-mono text-neutral-500">Слот 2</div>
                                </div>
                            </div>
                            @if($p2Connected)
                                <span class="px-2.5 py-1 rounded-md bg-emerald-500/10 text-emerald-400 border border-emerald-500/25 font-mono font-semibold text-xs flex items-center space-x-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    <span>НА СЕРВЕРЕ</span>
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-md bg-amber-500/10 text-amber-400 border border-amber-500/25 font-mono font-semibold text-xs flex items-center space-x-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                    <span>ОЖИДАЕТСЯ...</span>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Warning note -->
                    <div class="p-3 bg-red-950/25 border border-red-500/20 rounded-xl text-xs font-mono text-neutral-300 flex items-start space-x-2">
                        <span class="text-red-400 font-bold shrink-0">⚠️ Регламент:</span>
                        <span>При неявке на сервер в течение 5 минут или досрочном выходе игроку автоматически засчитывается поражение (-25 ELO) и выдается временный бан.</span>
                    </div>
                </div>

                <!-- RCON Status Alert -->
                @if($rconStatusMessage)
                    <div class="mb-5 p-3 rounded-xl flex items-center justify-between text-xs {{ $rconSuccess ? 'bg-emerald-500/10 border border-emerald-500/25 text-emerald-400' : 'bg-amber-500/10 border border-amber-500/25 text-amber-400' }}">
                        <div class="flex items-center space-x-2">
                            <span>{{ $rconStatusMessage }}</span>
                        </div>
                        @if(!$rconSuccess)
                            <div class="flex items-center space-x-2">
                                <button wire:click="retryRcon" class="px-2.5 py-1 bg-white/[0.08] hover:bg-white/[0.15] text-white rounded text-xs transition cursor-pointer">
                                    Повторить смену карты
                                </button>
                                <a href="{{ route('server.settings') }}" class="px-2.5 py-1 bg-[#FF5500] text-white rounded text-xs transition">
                                    Настройки RCON
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Server Info & Connect Options Box -->
                <div class="bg-[#0E1116] border border-white/[0.08] rounded-2xl p-6 mb-6 text-left">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-white/[0.06]">
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span class="text-sm font-semibold text-white">Сервер: UP FaceIt CS2 (Incloudgame)</span>
                        </div>
                        <span class="text-xs text-neutral-400 font-mono">{{ $match->server_ip }}:{{ $match->server_port }}</span>
                    </div>

                    <!-- Connect Command Box -->
                    <label class="block text-xs font-mono uppercase tracking-wider text-neutral-400 mb-2">
                        Команда подключения (для консоли CS2 ~):
                    </label>
                    <div class="flex items-center space-x-2">
                        <input 
                            type="text" 
                            readonly 
                            value="connect {{ $match->server_ip }}:{{ $match->server_port }}; password {{ $match->server_password }}" 
                            id="connectCmd"
                            class="w-full bg-[#12151B] border border-white/[0.1] text-emerald-400 font-mono text-sm px-4 py-3 rounded-xl focus:outline-none">
                        <button 
                            type="button" 
                            onclick="navigator.clipboard.writeText(document.getElementById('connectCmd').value); alert('Команда скопирована! Откройте консоль в CS2 (~) и вставьте.');"
                            class="px-5 py-3 bg-white/[0.06] hover:bg-white/[0.1] text-white font-medium text-xs uppercase tracking-wider rounded-xl border border-white/[0.08] transition cursor-pointer whitespace-nowrap">
                            Копировать
                        </button>
                    </div>

                    <!-- Primary Connect Button -->
                    <div class="mt-4 flex flex-col sm:flex-row gap-3">
                        <button 
                            type="button" 
                            id="copyMainBtn"
                            onclick="navigator.clipboard.writeText(document.getElementById('connectCmd').value); this.innerText='✓ СКОПИРОВАНО В БУФЕР ОБМЕНА'; this.classList.remove('bg-[#FF5500]', 'hover:bg-[#FF6E26]'); this.classList.add('bg-emerald-600');"
                            class="flex-1 py-3.5 bg-[#FF5500] hover:bg-[#FF6E26] text-white font-heading font-bold text-sm uppercase tracking-wider rounded-xl shadow-sm text-center transition cursor-pointer flex items-center justify-center space-x-2">
                            <span>📋 СКОПИРОВАТЬ IP ДЛЯ КОНСОЛИ (~)</span>
                        </button>
                        
                        <!-- Direct Steam Protocol Connect -->
                        <a 
                            href="steam://connect/{{ $match->server_ip }}:{{ $match->server_port }}/{{ $match->server_password }}" 
                            class="px-6 py-3.5 bg-white/[0.05] hover:bg-white/[0.09] text-white font-heading font-semibold text-sm uppercase tracking-wider rounded-xl border border-white/[0.1] text-center transition flex items-center justify-center space-x-2">
                            <span>ЗАПУСК ЧЕРЕЗ STEAM ↗</span>
                        </a>
                    </div>

                    @if(($match->player2 && $match->player2->is_bot) || (auth()->check() && auth()->user()->isAdmin()))
                        <div class="mt-3">
                            <button 
                                type="button" 
                                wire:click="startBotDuelInGame"
                                class="w-full py-2.5 bg-white/[0.03] hover:bg-white/[0.06] text-amber-300 font-mono text-xs rounded-xl border border-amber-500/30 transition cursor-pointer flex items-center justify-center space-x-2">
                                <span>🤖 Заспавнить Бота на сервере для дуэли (RCON)</span>
                            </button>
                        </div>
                    @endif

                    <!-- Step-by-step Connection Steps -->
                    <div class="mt-4 bg-[#12151B] rounded-xl p-3 text-xs text-neutral-400 flex flex-col sm:flex-row items-center justify-around gap-2 font-mono border border-white/[0.04]">
                        <div class="flex items-center space-x-2">
                            <span class="w-5 h-5 rounded bg-white/[0.08] text-white font-bold flex items-center justify-center text-[10px]">1</span>
                            <span>Запустите CS2</span>
                        </div>
                        <span class="text-neutral-600 hidden sm:inline">→</span>
                        <div class="flex items-center space-x-2">
                            <span class="w-5 h-5 rounded bg-white/[0.08] text-white font-bold flex items-center justify-center text-[10px]">2</span>
                            <span>Консоль <kbd class="px-1 py-0.5 bg-white/[0.08] text-white rounded">~</kbd></span>
                        </div>
                        <span class="text-neutral-600 hidden sm:inline">→</span>
                        <div class="flex items-center space-x-2">
                            <span class="w-5 h-5 rounded bg-white/[0.08] text-white font-bold flex items-center justify-center text-[10px]">3</span>
                            <span>Вставьте и Enter</span>
                        </div>
                    </div>
                </div>

                <!-- MatchZy Telemetry Status -->
                <div class="bg-[#0E1116] border border-white/[0.06] rounded-xl p-4 text-left flex items-center justify-between text-xs font-mono text-neutral-400">
                    <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span class="text-white font-medium">Автоматическая телеметрия MatchZy:</span>
                        <span>Счет раундов и Elo обновляются сервером автоматически.</span>
                    </div>
                    <span class="text-emerald-400 font-semibold">LIVE</span>
                </div>
            </div>
        </div>

    @elseif($match->status === 'finished')
        <!-- MATCH FINISHED SCREEN -->
        @php
            $isWinner = ($match->winner_id === auth()->id());
            $myEloChange = ($match->player1_id === auth()->id()) ? $match->elo_change_p1 : $match->elo_change_p2;
        @endphp
        <div class="bg-[#12151B] border border-white/[0.08] rounded-3xl p-10 mb-8 text-center relative overflow-hidden shadow-sm">
            @if($match->forfeited_user_id)
                <div class="mb-4 inline-flex items-center space-x-2 px-3 py-1 bg-red-950/40 border border-red-500/30 rounded-full text-red-300 text-xs font-mono uppercase tracking-wider">
                    <span>ТЕХНИЧЕСКОЕ ПОРАЖЕНИЕ (FORFEIT)</span>
                </div>
            @endif

            <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 {{ $isWinner ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-red-500/10 text-red-400 border border-red-500/30' }}">
                @if($isWinner)
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @else
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @endif
            </div>

            <h2 class="text-3xl sm:text-5xl font-heading font-black uppercase tracking-tight {{ $isWinner ? 'text-emerald-400' : 'text-red-400' }}">
                {{ $isWinner ? 'ПОБЕДА' : 'ПОРАЖЕНИЕ' }}
            </h2>

            @if($match->forfeited_user_id)
                <p class="text-xs font-mono text-neutral-400 mt-2">
                    {{ $match->forfeited_user_id === auth()->id() ? 'Вы получили техническое поражение за неявку или выход с сервера.' : 'Соперник не явился на сервер в отведенное время. Победа присуждена вам!' }}
                </p>
            @endif

            <div class="text-4xl font-mono font-bold text-white mt-3 mb-5 tabular-nums">
                {{ $match->score_player1 }} — {{ $match->score_player2 }}
            </div>

            <div class="inline-flex items-center space-x-3 bg-white/[0.04] px-5 py-2.5 rounded-xl border border-white/[0.08] mb-8">
                <span class="text-xs font-mono uppercase text-neutral-400">Рейтинг:</span>
                <span class="font-mono font-bold text-xl tabular-nums {{ $myEloChange >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                    {{ $myEloChange >= 0 ? "+{$myEloChange}" : $myEloChange }} ELO
                </span>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('home') }}" class="px-6 py-3 bg-[#FF5500] hover:bg-[#FF6E26] text-white font-heading font-bold text-sm uppercase tracking-wider rounded-xl transition shadow-sm">
                    Вернуться в лобби
                </a>
                <a href="{{ route('leaderboard') }}" class="px-6 py-3 bg-white/[0.04] hover:bg-white/[0.08] text-neutral-200 hover:text-white font-heading font-semibold text-sm uppercase tracking-wider rounded-xl border border-white/[0.08] transition">
                    Таблица лидеров
                </a>
            </div>
        </div>
    @endif
</div>
