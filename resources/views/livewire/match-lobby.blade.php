<div wire:poll.2000ms="loadMatch">
    <!-- Match Room Top Header -->
    <div class="bg-faceit-card border border-faceit-border rounded-2xl p-6 mb-8 shadow-xl">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <!-- Player 1 (Left) -->
            <div class="flex items-center space-x-4 flex-1">
                <img src="{{ $match->player1->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . $match->player1->name }}" class="w-16 h-16 rounded-xl border-2 border-faceit-border bg-neutral-900" alt="P1">
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="text-xl font-faceit font-bold text-white tracking-wide">{{ $match->player1->name }}</span>
                        @if($match->player1->is_bot)
                            <span class="text-[10px] bg-neutral-800 text-neutral-400 px-1.5 py-0.5 rounded uppercase">BOT</span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2 mt-1">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center font-faceit text-xs font-bold {{ $match->player1->level_badge_color }}">
                            {{ $match->player1->level }}
                        </span>
                        <span class="text-sm font-mono text-neutral-400">{{ $match->player1->elo }} ELO</span>
                        <span class="text-xs text-neutral-500">({{ $match->player1->winrate }}% WR)</span>
                    </div>
                </div>
            </div>

            <!-- Match Center Status / Score -->
            <div class="text-center px-6">
                @if($match->status === 'voting')
                    <div class="inline-flex items-center space-x-2 bg-faceit-orange/20 border border-faceit-orange/40 text-faceit-orange px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wider animate-pulse">
                        <span>Фаза Veto (Выбор карт)</span>
                    </div>
                    <p class="text-xs text-neutral-400 mt-2">
                        Очередь: <span class="text-white font-bold">{{ $match->currentTurnPlayer ? $match->currentTurnPlayer->name : 'Ожидание' }}</span>
                    </p>
                @elseif($match->status === 'ready' || $match->status === 'in_progress')
                    <div class="inline-flex items-center space-x-2 bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        <span>Матч готов к игре</span>
                    </div>
                    <div class="text-2xl font-faceit font-bold text-white mt-1">
                        {{ $match->map ? $match->map->name : 'CS2 Map' }}
                    </div>
                @elseif($match->status === 'finished')
                    <div class="inline-flex items-center space-x-2 bg-neutral-800 border border-neutral-700 text-neutral-300 px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-2">
                        <span>Матч завершен</span>
                    </div>
                    <div class="text-4xl font-mono font-bold text-white tracking-widest">
                        {{ $match->score_player1 }} : {{ $match->score_player2 }}
                    </div>
                @endif
            </div>

            <!-- Player 2 (Right) -->
            <div class="flex items-center space-x-4 flex-1 justify-end text-right">
                <div>
                    <div class="flex items-center justify-end space-x-2">
                        @if($match->player2 && $match->player2->is_bot)
                            <span class="text-[10px] bg-neutral-800 text-neutral-400 px-1.5 py-0.5 rounded uppercase">BOT</span>
                        @endif
                        <span class="text-xl font-faceit font-bold text-white tracking-wide">
                            {{ $match->player2 ? $match->player2->name : 'Ожидание соперника...' }}
                        </span>
                    </div>
                    @if($match->player2)
                    <div class="flex items-center justify-end space-x-2 mt-1">
                        <span class="text-xs text-neutral-500">({{ $match->player2->winrate }}% WR)</span>
                        <span class="text-sm font-mono text-neutral-400">{{ $match->player2->elo }} ELO</span>
                        <span class="w-6 h-6 rounded-full flex items-center justify-center font-faceit text-xs font-bold {{ $match->player2->level_badge_color }}">
                            {{ $match->player2->level }}
                        </span>
                    </div>
                    @endif
                </div>
                <img src="{{ $match->player2->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . ($match->player2->name ?? 'opponent') }}" class="w-16 h-16 rounded-xl border-2 border-faceit-border bg-neutral-900" alt="P2">
            </div>
        </div>
    </div>

    <!-- MAIN BODY: VETO vs READY vs FINISHED -->
    @if($match->status === 'voting')
        <!-- MAP VETO SECTION -->
        <div class="bg-faceit-card border border-faceit-border rounded-2xl p-6 shadow-xl mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-faceit font-bold text-white uppercase tracking-wider">
                        Вычеркивание карт (Map Veto)
                    </h2>
                    <p class="text-neutral-400 text-sm mt-0.5">
                        Каждый игрок по очереди банит по одной карте, пока не останется решающая карта.
                    </p>
                </div>
                <div class="text-right">
                    @if($match->current_turn_player_id === auth()->id())
                        <span class="px-4 py-2 bg-faceit-orange text-white font-faceit font-bold text-sm uppercase tracking-wider rounded-lg shadow-lg shadow-faceit-orange/20 animate-pulse">
                            Ваш ход — выберите карту для бана
                        </span>
                    @else
                        <span class="px-4 py-2 bg-neutral-800 text-neutral-400 font-faceit font-semibold text-sm uppercase tracking-wider rounded-lg">
                            Ход соперника...
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
                        class="relative rounded-xl overflow-hidden border-2 transition-all duration-200 group
                            {{ $isBanned ? 'border-red-900/40 opacity-40 grayscale pointer-events-none' : ($canBan ? 'border-neutral-700 hover:border-faceit-orange cursor-pointer hover:scale-[1.02] shadow-lg' : 'border-neutral-800 opacity-80') }}">
                        
                        <!-- Map Image Thumbnail -->
                        <div class="h-36 bg-neutral-900 bg-cover bg-center" style="background-image: url('{{ $map->image }}')">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                        </div>

                        <!-- Card Content -->
                        <div class="absolute bottom-0 left-0 right-0 p-3 flex items-end justify-between">
                            <div>
                                <p class="text-xs font-mono text-neutral-400">{{ $map->code }}</p>
                                <p class="text-lg font-faceit font-bold text-white tracking-wide">{{ $map->name }}</p>
                            </div>

                            @if($isBanned)
                                <span class="px-2 py-0.5 bg-red-600/90 text-white font-faceit font-bold text-xs uppercase tracking-wider rounded">
                                    BANNED
                                </span>
                            @elseif($canBan)
                                <span class="px-2.5 py-1 bg-faceit-orange hover:bg-faceit-orangeHover text-white font-faceit font-bold text-xs uppercase tracking-wider rounded shadow transition">
                                    Забанить
                                </span>
                            @endif
                        </div>

                        @if($isBanned)
                            <div class="absolute inset-0 flex items-center justify-center bg-black/60">
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
        <div class="bg-gradient-to-b from-neutral-900 to-faceit-card border border-faceit-border rounded-2xl p-8 mb-8 shadow-2xl">
            <div class="max-w-3xl mx-auto text-center">
                <!-- Selected Map Banner -->
                <div class="inline-block relative rounded-2xl overflow-hidden border-2 border-emerald-500/50 mb-6 shadow-2xl">
                    <img src="{{ $match->map->image ?? '' }}" alt="{{ $match->map->name ?? '' }}" class="w-full h-48 sm:h-64 object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent flex flex-col justify-end p-6">
                        <span class="text-xs uppercase font-bold tracking-widest text-emerald-400">РЕШАЮЩАЯ КАРТА МАТЧА</span>
                        <h3 class="text-4xl font-faceit font-bold text-white tracking-wider uppercase mt-1">
                            {{ $match->map->name ?? 'Mirage' }}
                        </h3>
                    </div>
                </div>

                <!-- RCON Status Alert -->
                @if($rconStatusMessage)
                    <div class="mb-5 p-3 rounded-xl flex items-center justify-between text-xs {{ $rconSuccess ? 'bg-emerald-500/10 border border-emerald-500/30 text-emerald-400' : 'bg-amber-500/10 border border-amber-500/30 text-amber-400' }}">
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
                                <a href="{{ route('server.settings') }}" class="px-2.5 py-1 bg-faceit-orange hover:bg-faceit-orangeHover text-white rounded text-[11px] font-semibold transition">
                                    Настройки RCON →
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Server Info & Connect Options -->
                <div class="bg-neutral-950/80 border border-neutral-800 rounded-xl p-6 mb-6 text-left">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 animate-ping"></span>
                            <span class="text-sm font-semibold text-white">Сервер Incloudgame: {{ env('CS2_SERVER_NAME', 'UP FaceIt') }}</span>
                        </div>
                        <span class="text-xs text-neutral-400 font-mono">{{ $match->server_ip }}:{{ $match->server_port }}</span>
                    </div>

                    <!-- Connect Command Box -->
                    <label class="block text-xs uppercase font-semibold text-neutral-400 mb-1">
                        Консольная команда для подключения (в консоли `~` в CS2):
                    </label>
                    <div class="flex items-center space-x-2">
                        <input 
                            type="text" 
                            readonly 
                            value="connect {{ $match->server_ip }}:{{ $match->server_port }}; password {{ $match->server_password }}" 
                            id="connectCmd"
                            class="w-full bg-neutral-900 border border-neutral-700 text-emerald-400 font-mono text-sm px-4 py-3 rounded-lg focus:outline-none">
                        <button 
                            type="button" 
                            onclick="navigator.clipboard.writeText(document.getElementById('connectCmd').value); alert('Команда скопирована! Откройте консоль в CS2 и вставьте её.');"
                            class="px-5 py-3 bg-neutral-800 hover:bg-neutral-700 text-white font-faceit font-semibold text-sm uppercase tracking-wider rounded-lg border border-neutral-700 transition cursor-pointer whitespace-nowrap">
                            Копировать
                        </button>
                    </div>

                    <!-- FACEIT Style Copy Connect Button -->
                    <div class="mt-4">
                        <button 
                            type="button" 
                            id="copyMainBtn"
                            onclick="navigator.clipboard.writeText(document.getElementById('connectCmd').value); this.innerHTML='<span>✓ СКОПИРОВАНО! ВСТАВЬТЕ В КОНСОЛЬ CS2 (~)</span>'; this.classList.remove('bg-faceit-orange'); this.classList.add('bg-emerald-600');"
                            class="w-full py-4 bg-faceit-orange hover:bg-faceit-orangeHover text-white font-faceit font-bold text-xl uppercase tracking-wider rounded-xl shadow-xl shadow-faceit-orange/25 text-center transition cursor-pointer flex items-center justify-center space-x-2">
                            <span>📋 СКОПИРОВАТЬ IP ДЛЯ КОНСОЛИ CS2</span>
                        </button>
                    </div>

                    <!-- Step-by-step connection guide -->
                    <div class="mt-3 bg-neutral-900/60 rounded-lg p-3 text-xs text-neutral-400 flex items-center justify-around border border-neutral-800/80">
                        <div class="flex items-center space-x-2">
                            <span class="w-5 h-5 rounded-full bg-faceit-orange/20 text-faceit-orange font-bold flex items-center justify-center text-[11px]">1</span>
                            <span>Запустите CS2</span>
                        </div>
                        <span class="text-neutral-600">→</span>
                        <div class="flex items-center space-x-2">
                            <span class="w-5 h-5 rounded-full bg-faceit-orange/20 text-faceit-orange font-bold flex items-center justify-center text-[11px]">2</span>
                            <span>Откройте консоль клавишей <kbd class="px-1.5 py-0.5 bg-neutral-800 text-neutral-200 rounded font-mono text-[11px]">~</kbd></span>
                        </div>
                        <span class="text-neutral-600">→</span>
                        <div class="flex items-center space-x-2">
                            <span class="w-5 h-5 rounded-full bg-faceit-orange/20 text-faceit-orange font-bold flex items-center justify-center text-[11px]">3</span>
                            <span>Вставьте команду и нажмите <kbd class="px-1.5 py-0.5 bg-neutral-800 text-neutral-200 rounded font-mono text-[11px]">Enter</kbd></span>
                        </div>
                    </div>
                </div>

                <!-- Simulation / Score Entry Form for Testing & Defense -->
                <div class="bg-neutral-900/60 border border-faceit-border rounded-xl p-5 text-left">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold uppercase tracking-wider text-neutral-400">
                            Фиксация результата (Для УП / Ручной ввод счета)
                        </span>
                        <span class="text-xs text-neutral-500">Авто-пересчет Elo</span>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="flex items-center space-x-3 w-full sm:w-auto">
                            <span class="text-xs font-semibold text-neutral-300">{{ $match->player1->name }}:</span>
                            <input type="number" wire:model="scoreP1" min="0" max="30" class="w-16 bg-neutral-800 border border-neutral-700 rounded px-2 py-1 text-center font-mono font-bold text-white text-lg">
                            <span class="text-neutral-500 font-bold">:</span>
                            <input type="number" wire:model="scoreP2" min="0" max="30" class="w-16 bg-neutral-800 border border-neutral-700 rounded px-2 py-1 text-center font-mono font-bold text-white text-lg">
                            <span class="text-xs font-semibold text-neutral-300">{{ $match->player2->name ?? 'Бот' }}:</span>
                        </div>

                        <div class="flex-1 flex justify-end w-full sm:w-auto">
                            <button 
                                wire:click="submitScore" 
                                class="w-full sm:w-auto px-6 py-2.5 bg-faceit-orange hover:bg-faceit-orangeHover text-white font-faceit font-bold text-base uppercase tracking-wider rounded-lg transition shadow-lg shadow-faceit-orange/20 cursor-pointer">
                                Завершить матч и рассчитать Elo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @elseif($match->status === 'finished')
        <!-- MATCH FINISHED SCREEN (VICTORY / DEFEAT BANNER) -->
        @php
            $isWinner = ($match->winner_id === auth()->id());
            $myEloChange = ($match->player1_id === auth()->id()) ? $match->elo_change_p1 : $match->elo_change_p2;
        @endphp
        <div class="bg-gradient-to-b from-neutral-900 to-faceit-card border {{ $isWinner ? 'border-emerald-500/50' : 'border-red-500/50' }} rounded-2xl p-10 mb-8 text-center shadow-2xl relative overflow-hidden">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 {{ $isWinner ? 'bg-emerald-500/20 text-emerald-400 ring-8 ring-emerald-500/10' : 'bg-red-500/20 text-red-400 ring-8 ring-red-500/10' }}">
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

            <h2 class="text-4xl sm:text-6xl font-faceit font-bold uppercase tracking-wider {{ $isWinner ? 'text-emerald-400' : 'text-red-400' }}">
                {{ $isWinner ? 'Победа!' : 'Поражение' }}
            </h2>

            <div class="text-3xl font-mono font-bold text-white mt-3 mb-4">
                {{ $match->score_player1 }} — {{ $match->score_player2 }}
            </div>

            <div class="inline-flex items-center space-x-2 bg-neutral-900 px-6 py-2.5 rounded-full border border-neutral-700 mb-8">
                <span class="text-xs uppercase font-semibold text-neutral-400">Изменение рейтинга:</span>
                <span class="font-mono font-bold text-xl {{ $myEloChange >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                    {{ $myEloChange >= 0 ? "+{$myEloChange}" : $myEloChange }} ELO
                </span>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('home') }}" class="px-8 py-3.5 bg-faceit-orange hover:bg-faceit-orangeHover text-white font-faceit font-bold text-lg uppercase tracking-wider rounded-xl transition shadow-lg shadow-faceit-orange/20">
                    Вернуться в лобби
                </a>
                <a href="{{ route('leaderboard') }}" class="px-6 py-3.5 bg-neutral-800 hover:bg-neutral-700 text-neutral-200 hover:text-white font-faceit font-semibold text-lg uppercase tracking-wider rounded-xl border border-neutral-700 transition">
                    Таблица лидеров
                </a>
            </div>
        </div>
    @endif
</div>
