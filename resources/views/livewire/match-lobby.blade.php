<div wire:poll.1000ms="loadMatch">
    <!-- Match Room Header (FACEIT Versus HUD) -->
    <div class="bg-[#121620] border border-[#22293A] rounded-2xl p-6 sm:p-8 mb-6 shadow-xl relative overflow-hidden">
        <!-- Subtle Top Border Accent -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#FF5500] via-neutral-700 to-[#FF5500]"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <!-- Player 1 (Left) -->
            <div class="flex items-center space-x-4 flex-1 w-full md:w-auto">
                <div class="relative shrink-0">
                    <img src="{{ $match->player1->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . $match->player1->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl border border-[#22293A] bg-[#0C0E14] object-cover" alt="P1">
                    <div class="absolute -bottom-1.5 -right-1.5">
                        <x-faceit-level :level="$match->player1->level" size="sm" />
                    </div>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('profile', ['id' => $match->player1->id]) }}" class="text-xl sm:text-2xl font-faceit font-extrabold text-white tracking-wide hover:text-[#FF5500] transition">
                            {{ $match->player1->name }}
                        </a>
                        @if($match->player1->is_bot)
                            <span class="text-[10px] font-mono bg-[#181E2B] text-neutral-400 border border-[#22293A] px-2 py-0.5 rounded uppercase">BOT</span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2.5 mt-1 font-mono text-xs text-neutral-400">
                        <span class="text-[#FF5500] font-bold">{{ $match->player1->elo }} ELO</span>
                        <span>•</span>
                        <span>{{ $match->player1->winrate }}% WR</span>
                    </div>
                    @if($match->player1->steam_id)
                        <div class="text-[10px] font-mono text-neutral-500 mt-0.5">Steam: {{ $match->player1->steam_id }}</div>
                    @endif
                </div>
            </div>

            <!-- Match Center Status / Score -->
            <div class="text-center px-6 shrink-0 py-2">
                @if($match->status === 'voting')
                    <div class="inline-flex items-center space-x-2 bg-[#FF5500]/10 border border-[#FF5500]/40 text-[#FF5500] px-4 py-1.5 rounded-full text-xs font-mono uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-[#FF5500] animate-pulse"></span>
                        <span>MAP VETO // ВЫЧЕРКИВАНИЕ КАРТ</span>
                    </div>
                    <p class="text-xs font-mono text-neutral-400 mt-2">
                        Очередь: <strong class="text-white">{{ $match->currentTurnPlayer ? $match->currentTurnPlayer->name : 'Ожидание' }}</strong>
                    </p>
                @elseif($match->status === 'ready' || $match->status === 'in_progress')
                    <div class="inline-flex items-center space-x-2 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-1.5 rounded-full text-xs font-mono uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>СЕРВЕР ГОТОВ К ИГРЕ</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-faceit font-black text-white uppercase mt-1 tracking-wider">
                        {{ $match->map ? $match->map->name : 'CS2 Map' }}
                    </div>
                @elseif($match->status === 'finished')
                    <div class="inline-flex items-center space-x-2 bg-[#181E2B] border border-[#22293A] text-neutral-300 px-4 py-1.5 rounded-full text-xs font-mono uppercase tracking-wider mb-2">
                        <span>МАТЧ ЗАВЕРШЕН</span>
                    </div>
                    <div class="text-3xl sm:text-4xl font-mono font-bold text-white tracking-widest">
                        {{ $match->score_player1 }} : {{ $match->score_player2 }}
                    </div>
                @endif
            </div>

            <!-- Player 2 (Right) -->
            <div class="flex items-center space-x-4 flex-1 justify-end text-right w-full md:w-auto">
                <div>
                    <div class="flex items-center justify-end space-x-2">
                        @if($match->player2 && $match->player2->is_bot)
                            <span class="text-[10px] font-mono bg-[#181E2B] text-neutral-400 border border-[#22293A] px-2 py-0.5 rounded uppercase">BOT</span>
                        @endif
                        @if($match->player2)
                            <a href="{{ route('profile', ['id' => $match->player2->id]) }}" class="text-xl sm:text-2xl font-faceit font-extrabold text-white tracking-wide hover:text-[#FF5500] transition">
                                {{ $match->player2->name }}
                            </a>
                        @else
                            <span class="text-xl sm:text-2xl font-faceit font-extrabold text-neutral-400 tracking-wide">
                                Ожидание...
                            </span>
                        @endif
                    </div>
                    @if($match->player2)
                    <div class="flex items-center justify-end space-x-2.5 mt-1 font-mono text-xs text-neutral-400">
                        <span>{{ $match->player2->winrate }}% WR</span>
                        <span>•</span>
                        <span class="text-[#FF5500] font-bold">{{ $match->player2->elo }} ELO</span>
                    </div>
                    @if($match->player2->steam_id)
                        <div class="text-[10px] font-mono text-neutral-500 mt-0.5">Steam: {{ $match->player2->steam_id }}</div>
                    @endif
                    @endif
                </div>
                <div class="relative shrink-0">
                    <img src="{{ $match->player2->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . ($match->player2->name ?? 'opponent') }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl border border-[#22293A] bg-[#0C0E14] object-cover" alt="P2">
                    @if($match->player2)
                    <div class="absolute -bottom-1.5 -right-1.5">
                        <x-faceit-level :level="$match->player2->level" size="sm" />
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN BODY: VETO vs READY vs FINISHED -->
    @if($match->status === 'voting')
        <!-- MAP VETO SECTION -->
        <div class="bg-[#121620] border border-[#22293A] rounded-2xl p-6 sm:p-8 shadow-xl mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-[#22293A]">
                <div>
                    <h2 class="text-xl sm:text-2xl font-faceit font-bold text-white uppercase tracking-wider">
                        ВЫЧЕРКИВАНИЕ КАРТ // MAP VETO
                    </h2>
                    <p class="text-neutral-400 text-xs sm:text-sm mt-1 font-mono">
                        Игроки по очереди банят нежелательные локации. Последняя оставшаяся карта станет официальной ареной матча.
                    </p>
                </div>
                <div class="text-right shrink-0">
                    @if($match->current_turn_player_id === auth()->id())
                        <span class="px-4 py-2 bg-[#FF5500] text-white font-faceit font-bold text-xs uppercase tracking-wider rounded-xl shadow-[0_0_15px_rgba(255,85,0,0.35)] animate-pulse inline-block">
                            ВАШ ХОД — ЗАБАНИТЬ КАРТУ
                        </span>
                    @else
                        <span class="px-4 py-2 bg-[#181E2B] text-neutral-400 font-mono text-xs uppercase tracking-wider rounded-xl border border-[#22293A] inline-block">
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
                        class="relative rounded-xl overflow-hidden border transition-all duration-200 group
                            {{ $isBanned ? 'border-neutral-800 opacity-30 grayscale pointer-events-none' : ($canBan ? 'border-[#22293A] hover:border-[#FF5500] hover:shadow-[0_0_20px_rgba(255,85,0,0.3)] cursor-pointer hover:scale-[1.02]' : 'border-[#22293A] opacity-80') }}">
                        
                        <!-- Map Image Thumbnail -->
                        <div class="h-36 bg-[#0C0E14] bg-cover bg-center transition-transform duration-300 group-hover:scale-105" style="background-image: url('{{ $map->image }}')">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0C0E14] via-[#0C0E14]/40 to-transparent"></div>
                        </div>

                        <!-- Card Content -->
                        <div class="absolute bottom-0 left-0 right-0 p-3.5 flex items-end justify-between">
                            <div>
                                <p class="text-[10px] font-mono text-neutral-400 uppercase tracking-wider">{{ $map->code }}</p>
                                <p class="text-lg font-faceit font-bold text-white tracking-wide">{{ $map->name }}</p>
                            </div>

                            @if($isBanned)
                                <span class="px-2.5 py-1 bg-red-600/90 text-white font-mono font-bold text-[11px] uppercase tracking-wider rounded">
                                    BANNED
                                </span>
                            @elseif($canBan)
                                <span class="px-3 py-1.5 bg-[#FF5500] hover:bg-[#FF6E1A] text-white font-faceit font-bold text-xs uppercase tracking-wider rounded-lg shadow group-hover:brightness-110 transition">
                                    ЗАБАНИТЬ
                                </span>
                            @endif
                        </div>

                        @if($isBanned)
                            <div class="absolute inset-0 flex items-center justify-center bg-black/60">
                                <svg class="w-10 h-10 text-red-500/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

    @elseif($match->status === 'ready' || $match->status === 'in_progress')
        <!-- MATCH READY: CONNECT TO CS2 SERVER -->
        <div class="bg-[#121620] border border-[#22293A] rounded-2xl p-6 sm:p-10 mb-8 shadow-xl">
            <div class="max-w-3xl mx-auto text-center">
                <!-- Selected Map Banner -->
                <div class="inline-block relative rounded-2xl overflow-hidden border border-[#22293A] mb-6 shadow-2xl w-full max-w-xl">
                    <img src="{{ $match->map->image ?? '' }}" alt="{{ $match->map->name ?? '' }}" class="w-full h-48 sm:h-64 object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0C0E14] via-[#0C0E14]/50 to-transparent flex flex-col justify-end p-6">
                        <span class="text-xs uppercase font-mono font-bold tracking-wider text-[#FF5500] bg-[#FF5500]/15 px-3 py-1 rounded-md w-max mx-auto border border-[#FF5500]/30 mb-1">
                            РЕШАЮЩАЯ КАРТА МАТЧА
                        </span>
                        <h3 class="text-3xl sm:text-4xl font-faceit font-black text-white tracking-wider uppercase mt-1">
                            {{ $match->map->name ?? 'Mirage' }}
                        </h3>
                    </div>
                </div>

                <!-- FACEIT CONNECT & LEAVER TIMEOUT HUD -->
                <div class="bg-[#0C0E14] border {{ $match->connectRemainingSeconds() <= 60 && !$match->player1_connected && !$match->player2_connected ? 'border-red-500/80 shadow-[0_0_30px_rgba(239,68,68,0.2)]' : 'border-[#22293A]' }} rounded-2xl p-6 mb-6 text-left">
                    <div class="flex flex-col sm:flex-row items-center justify-between pb-4 mb-4 border-b border-[#22293A] gap-3">
                        <div class="flex items-center space-x-2.5">
                            <span class="w-2.5 h-2.5 rounded-full {{ $match->connectRemainingSeconds() > 0 ? 'bg-amber-400 animate-pulse' : 'bg-red-500' }}"></span>
                            <span class="font-faceit font-bold text-sm uppercase tracking-wider text-white">
                                ТАЙМЕР ПОДКЛЮЧЕНИЯ И ЗАЩИТА ОТ ЛИВЕРОВ
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-mono text-neutral-400 uppercase">ОСТАЛОСЬ:</span>
                            <span class="font-mono font-bold text-2xl {{ $match->connectRemainingSeconds() <= 60 ? 'text-red-400 animate-pulse' : 'text-amber-400' }}">
                                {{ sprintf('%02d:%02d', floor($match->connectRemainingSeconds() / 60), $match->connectRemainingSeconds() % 60) }}
                            </span>
                        </div>
                    </div>

                    <!-- Players Connection Badges -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                        <!-- Player 1 Status -->
                        <div class="bg-[#121620] border border-[#22293A] rounded-xl p-3.5 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $match->player1->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . $match->player1->name }}" class="w-9 h-9 rounded-lg border border-white/10 object-cover" alt="P1">
                                <div>
                                    <p class="font-faceit font-bold text-sm text-white">{{ $match->player1->name }}</p>
                                    <p class="text-[10px] font-mono text-neutral-400">Слот 1</p>
                                </div>
                            </div>
                            @if($match->player1_connected)
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 font-mono font-bold text-xs uppercase tracking-wider flex items-center space-x-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    <span>НА СЕРВЕРЕ</span>
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/30 font-mono font-bold text-xs uppercase tracking-wider flex items-center space-x-1.5 animate-pulse">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                    <span>ОЖИДАЕТСЯ...</span>
                                </span>
                            @endif
                        </div>

                        <!-- Player 2 Status -->
                        @php
                            $isP2Bot = $match->player2 && $match->player2->is_bot;
                            $p2Connected = $match->player2_connected || $isP2Bot;
                        @endphp
                        <div class="bg-[#121620] border border-[#22293A] rounded-xl p-3.5 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $match->player2->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . ($match->player2->name ?? 'bot') }}" class="w-9 h-9 rounded-lg border border-white/10 object-cover" alt="P2">
                                <div>
                                    <p class="font-faceit font-bold text-sm text-white">{{ $match->player2->name ?? 'Оппонент' }}</p>
                                    <p class="text-[10px] font-mono text-neutral-400">Слот 2</p>
                                </div>
                            </div>
                            @if($p2Connected)
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 font-mono font-bold text-xs uppercase tracking-wider flex items-center space-x-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    <span>НА СЕРВЕРЕ</span>
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/30 font-mono font-bold text-xs uppercase tracking-wider flex items-center space-x-1.5 animate-pulse">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                    <span>ОЖИДАЕТСЯ...</span>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Warning note -->
                    <div class="p-3 bg-red-950/30 border border-red-500/20 rounded-xl text-[11px] font-mono text-red-200 flex items-start space-x-2">
                        <span class="text-red-400 font-bold shrink-0">⚠️ ШТРАФ:</span>
                        <span>Если игрок не подключится к CS2 до истечения таймера (или покинет матч и не вернется за 3 минуты), ему автоматически присуждается техническое поражение, вычитаются <strong>25 ELO</strong> и выдается бан поиска.</span>
                    </div>
                </div>

                <!-- RCON Status Alert -->
                @if($rconStatusMessage)
                    <div class="mb-5 p-3.5 rounded-xl flex items-center justify-between text-xs {{ $rconSuccess ? 'bg-emerald-500/10 border border-emerald-500/30 text-emerald-400' : 'bg-amber-500/10 border border-amber-500/30 text-amber-400' }}">
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
                                <button wire:click="retryRcon" class="px-2.5 py-1 bg-[#181E2B] hover:bg-[#22293A] text-white rounded text-[11px] font-semibold transition">
                                    Повторить
                                </button>
                                <a href="{{ route('server.settings') }}" class="px-2.5 py-1 bg-[#FF5500] hover:bg-[#FF6E1A] text-white rounded text-[11px] font-semibold transition">
                                    Настройки RCON →
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Server Info & Connect Options -->
                <div class="bg-[#0C0E14] border border-[#22293A] rounded-2xl p-6 mb-6 text-left">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#22293A]">
                        <div class="flex items-center space-x-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-sm font-faceit font-bold text-white uppercase tracking-wider">Сервер: UP FaceIt CS2</span>
                        </div>
                        <span class="text-xs text-[#FF5500] font-mono">{{ $match->server_ip }}:{{ $match->server_port }}</span>
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
                            class="w-full bg-[#121620] border border-[#22293A] text-emerald-400 font-mono text-sm px-4 py-3 rounded-xl focus:outline-none">
                        <button 
                            type="button" 
                            onclick="navigator.clipboard.writeText(document.getElementById('connectCmd').value); alert('Команда скопирована! Откройте консоль в CS2 (клавиша ~) и вставьте её.');"
                            class="px-5 py-3 bg-[#181E2B] hover:bg-[#22293A] text-white font-faceit font-bold text-sm uppercase tracking-wider rounded-xl border border-[#22293A] transition cursor-pointer whitespace-nowrap">
                            Копировать
                        </button>
                    </div>

                    <!-- Glowing Big Button for 1-Click Copy -->
                    <div class="mt-4">
                        <button 
                            type="button" 
                            id="copyMainBtn"
                            onclick="navigator.clipboard.writeText(document.getElementById('connectCmd').value); this.innerHTML='<span>✓ СКОПИРОВАНО! ВСТАВЬТЕ В КОНСОЛЬ CS2 (~)</span>'; this.classList.remove('bg-[#FF5500]', 'hover:bg-[#FF6E1A]'); this.classList.add('bg-emerald-600');"
                            class="w-full py-4 bg-[#FF5500] hover:bg-[#FF6E1A] text-white font-faceit font-extrabold text-lg uppercase tracking-wider rounded-xl shadow-[0_4px_25px_rgba(255,85,0,0.35)] text-center transition cursor-pointer flex items-center justify-center space-x-2 border border-[#FF7733]/40">
                            <span>📋 СКОПИРОВАТЬ IP ДЛЯ КОНСОЛИ CS2</span>
                        </button>
                    </div>

                    @if(($match->player2 && $match->player2->is_bot) || (auth()->check() && auth()->user()->isAdmin()))
                        <div class="mt-3">
                            <button 
                                type="button" 
                                wire:click="startBotDuelInGame"
                                class="w-full py-3 bg-[#181E2B] hover:bg-[#1D2435] text-amber-300 hover:text-white font-faceit font-bold text-sm uppercase tracking-wider rounded-xl border border-amber-500/30 transition cursor-pointer flex items-center justify-center space-x-2">
                                <span>🤖 ЗАСПАВНИТЬ БОТА В CS2 ДЛЯ 1v1 ТЕСТА (RCON)</span>
                            </button>
                        </div>
                    @endif

                    <!-- Step-by-step connection guide -->
                    <div class="mt-4 bg-[#121620] rounded-xl p-3.5 text-xs text-neutral-400 flex flex-col sm:flex-row items-center justify-around gap-2 border border-[#22293A] font-mono">
                        <div class="flex items-center space-x-2">
                            <span class="w-5 h-5 rounded-md bg-[#FF5500]/15 text-[#FF5500] font-bold flex items-center justify-center text-[11px] border border-[#FF5500]/30">1</span>
                            <span>Запустите CS2</span>
                        </div>
                        <span class="text-neutral-600 hidden sm:inline">→</span>
                        <div class="flex items-center space-x-2">
                            <span class="w-5 h-5 rounded-md bg-[#FF5500]/15 text-[#FF5500] font-bold flex items-center justify-center text-[11px] border border-[#FF5500]/30">2</span>
                            <span>Консоль <kbd class="px-1.5 py-0.5 bg-[#0C0E14] text-white rounded font-mono text-[11px] border border-neutral-700">~</kbd></span>
                        </div>
                        <span class="text-neutral-600 hidden sm:inline">→</span>
                        <div class="flex items-center space-x-2">
                            <span class="w-5 h-5 rounded-md bg-[#FF5500]/15 text-[#FF5500] font-bold flex items-center justify-center text-[11px] border border-[#FF5500]/30">3</span>
                            <span>Вставьте и <kbd class="px-1.5 py-0.5 bg-[#0C0E14] text-white rounded font-mono text-[11px] border border-neutral-700">Enter</kbd></span>
                        </div>
                    </div>
                </div>

                <!-- MatchZy Automatic Telemetry Box -->
                <div class="bg-[#0C0E14] border border-emerald-500/20 rounded-2xl p-5 text-left">
                    <div class="flex items-center justify-between mb-2.5 pb-2 border-b border-white/5">
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-xs font-faceit font-bold uppercase tracking-wider text-emerald-400">
                                АВТОМАТИЧЕСКАЯ СИНХРОНИЗАЦИЯ С СЕРВЕРОМ // MATCHZY
                            </span>
                        </div>
                        <span class="text-[11px] font-mono text-emerald-400">ONLINE</span>
                    </div>

                    <p class="text-xs text-neutral-400 font-mono leading-relaxed">
                        Сервер автоматически передает статистику каждого раунда и финальный счет матча. По окончании игры рейтинг Elo игроков будет пересчитан автоматически.
                    </p>
                </div>
            </div>
        </div>

    @elseif($match->status === 'finished')
        <!-- MATCH FINISHED SCREEN -->
        @php
            $isWinner = ($match->winner_id === auth()->id());
            $myEloChange = ($match->player1_id === auth()->id()) ? $match->elo_change_p1 : $match->elo_change_p2;
        @endphp
        <div class="bg-[#121620] border-2 {{ $isWinner ? 'border-emerald-500/70 shadow-[0_0_40px_rgba(16,185,129,0.25)]' : 'border-red-500/70 shadow-[0_0_40px_rgba(239,68,68,0.25)]' }} rounded-2xl p-8 sm:p-12 mb-8 text-center relative overflow-hidden">
            @if($match->forfeited_user_id)
                <div class="mb-4 inline-flex items-center space-x-2 px-4 py-1.5 bg-red-950/60 border border-red-500/40 rounded-full text-red-300 text-xs font-mono uppercase tracking-wider">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>ТЕХНИЧЕСКОЕ ПОРАЖЕНИЕ (FORFEIT) // НЕЯВКА ИЛИ ВЫХОД С СЕРВЕРА</span>
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

            <h2 class="text-4xl sm:text-5xl font-faceit font-black uppercase tracking-wider {{ $isWinner ? 'text-emerald-400' : 'text-red-400' }}">
                {{ $isWinner ? 'ПОБЕДА!' : 'ПОРАЖЕНИЕ' }}
            </h2>

            @if($match->forfeited_user_id)
                <p class="text-sm font-mono text-neutral-300 mt-2">
                    {{ $match->forfeited_user_id === auth()->id() ? 'Вы получили техническое поражение за неявку или выход с сервера.' : 'Соперник не явился на сервер в течение 5 минут. Победа присуждена вам!' }}
                </p>
            @endif

            <div class="text-3xl sm:text-4xl font-mono font-bold text-white mt-3 mb-5">
                {{ $match->score_player1 }} — {{ $match->score_player2 }}
            </div>

            <div class="inline-flex items-center space-x-3 bg-[#0C0E14] px-6 py-3 rounded-xl border border-[#22293A] mb-8">
                <span class="text-xs uppercase font-mono text-neutral-400">Изменение рейтинга:</span>
                <span class="font-mono font-bold text-2xl {{ $myEloChange >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                    {{ $myEloChange >= 0 ? "+{$myEloChange}" : $myEloChange }} ELO
                </span>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('home') }}" class="px-6 py-3.5 bg-[#FF5500] hover:bg-[#FF6E1A] text-white font-faceit font-bold text-sm uppercase tracking-wider rounded-xl transition shadow-[0_0_20px_rgba(255,85,0,0.3)]">
                    Вернуться в лобби
                </a>
                <a href="{{ route('leaderboard') }}" class="px-6 py-3.5 bg-[#181E2B] hover:bg-[#22293A] text-neutral-200 hover:text-white font-faceit font-semibold text-sm uppercase tracking-wider rounded-xl border border-[#22293A] transition">
                    Таблица лидеров
                </a>
            </div>
        </div>
    @endif
</div>
