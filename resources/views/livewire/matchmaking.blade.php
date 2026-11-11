<div wire:poll.1000ms="pollQueue">
    <!-- User Quick Stats Bar -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-faceit-card border border-faceit-border rounded-xl p-5 flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wider font-semibold text-neutral-400">Рейтинг Elo</p>
                <p class="text-3xl font-faceit font-bold text-white mt-1">{{ $user->elo }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl flex items-center justify-center font-faceit font-bold text-xl shadow-lg {{ $user->level_badge_color }}">
                {{ $user->level }}
            </div>
        </div>

        <div class="bg-faceit-card border border-faceit-border rounded-xl p-5">
            <p class="text-xs uppercase tracking-wider font-semibold text-neutral-400">Винрейт</p>
            <div class="flex items-baseline space-x-2 mt-1">
                <span class="text-3xl font-faceit font-bold text-white">{{ $user->winrate }}%</span>
                <span class="text-xs text-neutral-400">({{ $user->wins_count }}В / {{ $user->losses_count }}П)</span>
            </div>
        </div>

        <div class="bg-faceit-card border border-faceit-border rounded-xl p-5">
            <p class="text-xs uppercase tracking-wider font-semibold text-neutral-400">Сыграно матчей</p>
            <p class="text-3xl font-faceit font-bold text-white mt-1">{{ $user->matches_count }}</p>
        </div>

        <div class="bg-faceit-card border border-faceit-border rounded-xl p-5">
            <p class="text-xs uppercase tracking-wider font-semibold text-neutral-400">Статус сервера CS2</p>
            <div class="flex items-center space-x-2 mt-2">
                <span class="w-3 h-3 rounded-full {{ env('CS2_SERVER_IP') ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                <span class="text-sm font-semibold {{ env('CS2_SERVER_IP') ? 'text-emerald-400' : 'text-amber-400' }}">
                    {{ env('CS2_SERVER_IP') ? 'Incloudgame подключен' : 'Демо режим' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Main Matchmaking Hero Card -->
    <div class="relative bg-gradient-to-b from-neutral-900 to-faceit-card border border-faceit-border rounded-2xl p-8 mb-8 overflow-hidden shadow-2xl">
        <!-- Background Decorative Pattern -->
        <div class="absolute -right-16 -bottom-16 w-96 h-96 bg-faceit-orange/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-2xl mx-auto text-center py-6">
            <div class="inline-flex items-center space-x-2 bg-faceit-orange/10 border border-faceit-orange/30 text-faceit-orange px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider mb-4">
                <span class="w-2 h-2 rounded-full bg-faceit-orange animate-ping"></span>
                <span>CS2 Соревновательный 1v1</span>
            </div>

            <h1 class="text-3xl sm:text-5xl font-faceit font-bold text-white tracking-wide uppercase">
                Поиск рейтингового матча
            </h1>
            <p class="text-neutral-400 text-sm sm:text-base mt-2">
                Сражайтесь 1 на 1 на официальных соревновательных картах CS2. Система Map Veto, реальный сервер и пересчет Elo.
            </p>

            <!-- Search Action Area -->
            <div class="mt-8">
                @if(! $inQueue && ! $matchFound)
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <!-- Play 1v1 Matchmaking Button -->
                        <button 
                            wire:click="startQueue" 
                            class="w-full sm:w-auto px-8 py-4 bg-faceit-orange hover:bg-faceit-orangeHover text-white font-faceit font-bold text-xl tracking-wider uppercase rounded-xl shadow-lg shadow-faceit-orange/25 transition transform active:scale-95 flex items-center justify-center space-x-3 cursor-pointer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Найти игру</span>
                        </button>

                        <!-- Instant Bot Match (For Testing & Defense) -->
                        <button 
                            wire:click="startInstantBotMatch" 
                            class="w-full sm:w-auto px-6 py-4 bg-neutral-800 hover:bg-neutral-700 text-neutral-200 hover:text-white font-faceit font-semibold text-lg tracking-wider uppercase rounded-xl border border-faceit-border transition flex items-center justify-center space-x-2 cursor-pointer">
                            <svg class="w-5 h-5 text-faceit-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Мгновенный матч с Ботом (Тест)</span>
                        </button>
                    </div>
                @elseif($inQueue && ! $matchFound)
                    <!-- Queue In Progress Box -->
                    <div class="bg-neutral-900/90 border border-faceit-orange/40 rounded-2xl p-6 shadow-xl max-w-md mx-auto">
                        <div class="flex items-center justify-center space-x-3 mb-4">
                            <div class="relative w-10 h-10 flex items-center justify-center">
                                <div class="absolute inset-0 rounded-full border-2 border-faceit-orange/30 border-t-faceit-orange animate-spin"></div>
                                <svg class="w-5 h-5 text-faceit-orange" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-xs uppercase tracking-wider text-neutral-400 font-semibold">Поиск соперника 1v1</p>
                                <p class="text-2xl font-mono font-bold text-white">
                                    {{ sprintf('%02d:%02d', floor($queueSeconds / 60), $queueSeconds % 60) }}
                                </p>
                            </div>
                        </div>

                        <div class="text-xs text-neutral-400 mb-4">
                            Подбор игрока с близким Elo (~{{ $user->elo }})...
                        </div>

                        <div class="flex items-center justify-center space-x-3">
                            <button 
                                wire:click="cancelQueue" 
                                class="px-4 py-2 bg-neutral-800 hover:bg-neutral-700 text-neutral-300 hover:text-white rounded-lg text-sm font-semibold transition cursor-pointer">
                                Отменить поиск
                            </button>
                            <button 
                                wire:click="startInstantBotMatch" 
                                class="px-4 py-2 bg-faceit-orange/20 hover:bg-faceit-orange/30 text-faceit-orange border border-faceit-orange/40 rounded-lg text-sm font-semibold transition cursor-pointer">
                                Подключить бота сразу
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- MATCH FOUND MODAL POPUP (FACEIT READY CHECK) -->
    @if($matchFound)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4 animate-fadeIn">
        <div class="bg-neutral-900 border-2 border-faceit-orange rounded-2xl max-w-lg w-full p-8 text-center shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-2 bg-faceit-orange animate-pulse"></div>

            <div class="w-16 h-16 bg-faceit-orange/20 text-faceit-orange rounded-full flex items-center justify-center mx-auto mb-4 ring-8 ring-faceit-orange/10">
                <svg class="w-8 h-8 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h2 class="text-3xl font-faceit font-bold text-white uppercase tracking-wider">
                Матч найден!
            </h2>
            <p class="text-neutral-400 text-sm mt-1 mb-6">
                Ваш соперник готов. Подтвердите участие для перехода к выбору карт.
            </p>

            <div class="flex items-center justify-center space-x-4">
                <button 
                    wire:click="acceptMatch" 
                    class="w-full py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-faceit font-bold text-2xl uppercase tracking-wider rounded-xl shadow-lg shadow-emerald-600/30 transition transform active:scale-95 cursor-pointer">
                    Принять матч
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Matches List -->
    <div class="bg-faceit-card border border-faceit-border rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-faceit text-2xl font-bold tracking-wide uppercase text-white">
                Последние матчи
            </h2>
            <span class="text-xs uppercase tracking-wider font-semibold text-neutral-400">История</span>
        </div>

        @if($recentMatches->isEmpty())
            <div class="text-center py-12 text-neutral-500 text-sm">
                Вы ещё не сыграли ни одного матча. Нажмите «Найти игру» выше, чтобы начать!
            </div>
        @else
            <div class="divide-y divide-faceit-border">
                @foreach($recentMatches as $match)
                    @php
                        $isPlayer1 = ($match->player1_id === $user->id);
                        $isWinner = ($match->winner_id === $user->id);
                        $opponent = $isPlayer1 ? $match->player2 : $match->player1;
                        $myScore = $isPlayer1 ? $match->score_player1 : $match->score_player2;
                        $oppScore = $isPlayer1 ? $match->score_player2 : $match->score_player1;
                        $eloDelta = $isPlayer1 ? $match->elo_change_p1 : $match->elo_change_p2;
                    @endphp
                    <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-neutral-800/40 px-3 rounded-lg transition">
                        <!-- Match Result Badge & Map -->
                        <div class="flex items-center space-x-4">
                            @if($match->status === 'finished')
                                <span class="w-20 text-center py-1 rounded text-xs font-bold uppercase tracking-wider {{ $isWinner ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' }}">
                                    {{ $isWinner ? 'Победа' : 'Поражение' }}
                                </span>
                            @else
                                <span class="w-20 text-center py-1 rounded text-xs font-bold uppercase tracking-wider bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                    {{ $match->status }}
                                </span>
                            @endif

                            <div>
                                <p class="text-sm font-semibold text-white">
                                    {{ $match->map ? $match->map->name : 'Veto в процессе' }}
                                </p>
                                <p class="text-xs text-neutral-400">
                                    {{ $match->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        <!-- Score & Opponent -->
                        <div class="flex items-center space-x-6">
                            <div class="text-center">
                                <span class="font-mono text-lg font-bold text-white">{{ $myScore }} : {{ $oppScore }}</span>
                            </div>

                            <div class="flex items-center space-x-2">
                                <span class="text-xs text-neutral-400">vs</span>
                                <span class="text-sm font-medium text-neutral-200">{{ $opponent ? $opponent->name : 'Оппонент' }}</span>
                                @if($opponent)
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center font-faceit text-xs font-bold {{ $opponent->level_badge_color }}">
                                        {{ $opponent->level }}
                                    </span>
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
                            <a href="{{ route('match.lobby', ['uuid' => $match->uuid]) }}" class="px-3 py-1.5 bg-neutral-800 hover:bg-neutral-700 text-neutral-300 hover:text-white rounded text-xs font-semibold transition">
                                Комната
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
