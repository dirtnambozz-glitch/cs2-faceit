<div>
    <div class="max-w-5xl mx-auto space-y-8">
        <!-- Page Title -->
        <div>
            <div class="flex items-center space-x-2 text-xs font-mono uppercase tracking-widest text-[#FF0055] mb-1">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                <span>ADMIN PANEL // SERVER & PLAYER BANS MANAGEMENT</span>
            </div>
            <h1 class="text-3xl font-cyber font-extrabold text-white uppercase tracking-wider">
                ПАНЕЛЬ АДМИНИСТРАТОРА <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FF0055] to-[#FF2A6D]">RIVALIS</span>
            </h1>
            <p class="text-neutral-400 text-xs sm:text-sm mt-1 font-mono">
                Управление банами игроков, снятие ограничений, предупреждения и конфигурация CS2 сервера
            </p>
        </div>

        @if(session()->has('message'))
            <div class="p-4 bg-emerald-500/15 border border-emerald-500/40 rounded-2xl text-emerald-400 text-sm font-semibold flex items-center space-x-2 shadow-[0_0_20px_rgba(16,185,129,0.2)]">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        <!-- SECTION 1: BANNED PLAYERS & UNBAN (MAIN USER REQUEST) -->
        <div class="bg-[#120817] border-2 border-[#FF0055]/50 rounded-3xl p-6 sm:p-8 shadow-[0_0_35px_rgba(255,0,85,0.25)] relative overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-6 border-b border-[#FF0055]/20 gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-red-500/20 text-red-400 border border-red-500/40 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-cyber font-black text-white uppercase tracking-wider">
                            АКТИВНЫЕ БАНЫ ИГРОКОВ ({{ $bannedUsers->count() }})
                        </h2>
                        <p class="text-xs font-mono text-neutral-400">
                            Снятие блокировок матчмейкинга, выданных за ливы или неготовность
                        </p>
                    </div>
                </div>
                <span class="text-xs font-mono uppercase tracking-widest text-[#FF0055] bg-[#FF0055]/10 border border-[#FF0055]/30 px-3 py-1.5 rounded-lg w-max">
                    BAN CONTROL
                </span>
            </div>

            <!-- List of Banned Users -->
            @if($bannedUsers->isEmpty())
                <div class="text-center py-10 bg-[#08040A] border border-[#FF0055]/15 rounded-2xl">
                    <div class="w-12 h-12 rounded-full bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="font-cyber font-bold text-white text-base">Активных банов нет!</p>
                    <p class="text-xs text-neutral-400 mt-1 font-mono">Все игроки платформы имеют доступ к поиску матчей.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($bannedUsers as $bUser)
                        <div class="p-4 bg-[#08040A] border border-red-500/30 hover:border-red-500 rounded-2xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4 transition shadow-[0_0_15px_rgba(239,68,68,0.1)]">
                            <!-- User info -->
                            <div class="flex items-center space-x-3.5">
                                <img src="{{ $bUser->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . $bUser->name }}" class="w-12 h-12 rounded-xl border border-red-500/50 bg-black object-cover" alt="avatar">
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('profile', ['id' => $bUser->id]) }}" class="font-cyber font-bold text-base text-white hover:text-[#FF2A6D] transition">
                                            {{ $bUser->name }}
                                        </a>
                                        <span class="text-[10px] font-mono bg-red-500/20 text-red-400 border border-red-500/40 px-2 py-0.5 rounded uppercase font-bold">
                                            BANNED
                                        </span>
                                    </div>
                                    <p class="text-xs text-neutral-400 font-mono mt-0.5">
                                        Причина: <span class="text-red-300 font-semibold">{{ $bUser->ban_reason ?? 'Не указана' }}</span>
                                    </p>
                                    <div class="flex items-center space-x-3 text-[11px] font-mono text-neutral-400 mt-1">
                                        <span>Осталось: <span class="text-white font-bold">{{ sprintf('%02d:%02d', floor($bUser->banRemainingSeconds() / 60), $bUser->banRemainingSeconds() % 60) }}</span></span>
                                        <span>•</span>
                                        <span>До: <span class="text-neutral-300">{{ $bUser->banned_until->format('H:i:s d.m.Y') }}</span></span>
                                        <span>•</span>
                                        <span>Варнов: <span class="text-amber-400 font-bold">{{ $bUser->warnings_count }}</span></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center space-x-2.5 w-full md:w-auto justify-end">
                                <button 
                                    wire:click="unbanUser({{ $bUser->id }})" 
                                    class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:brightness-110 text-white font-cyber font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-[0_0_15px_rgba(16,185,129,0.4)] transition cursor-pointer flex items-center space-x-1.5 border border-emerald-300/40">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>СНЯТЬ БАН</span>
                                </button>

                                <button 
                                    wire:click="resetWarnings({{ $bUser->id }})" 
                                    title="Сбросить количество предупреждений нарушителя"
                                    class="px-3 py-2.5 bg-[#120817] hover:bg-neutral-800 text-neutral-300 hover:text-white font-mono text-xs rounded-xl border border-white/10 transition cursor-pointer">
                                    Сбросить варны
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Player Search for Ban / Unban -->
            <div class="mt-8 pt-6 border-t border-[#FF0055]/20">
                <h3 class="text-sm font-cyber font-bold text-white uppercase tracking-wider mb-2">
                    Поиск игрока по никнейму / SteamID:
                </h3>
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="searchQuery" 
                        placeholder="Введите ник игрока (например, defense, bot...)"
                        class="w-full bg-[#08040A] border border-[#FF0055]/30 text-white font-mono text-sm px-4 py-3 rounded-xl focus:outline-none focus:border-[#FF0055]">
                </div>

                @if($searchResults->isNotEmpty())
                    <div class="mt-4 space-y-2 bg-[#08040A] border border-[#FF0055]/30 rounded-2xl p-4">
                        <p class="text-[11px] font-mono uppercase text-[#FF0055] tracking-wider mb-2">Результаты поиска:</p>
                        @foreach($searchResults as $sUser)
                            <div class="p-3 bg-[#120817] rounded-xl border border-white/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $sUser->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . $sUser->name }}" class="w-9 h-9 rounded-lg border border-white/20 object-cover" alt="ava">
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <span class="font-cyber font-bold text-sm text-white">{{ $sUser->name }}</span>
                                            @if($sUser->isBanned())
                                                <span class="text-[9px] font-mono bg-red-500/20 text-red-400 border border-red-500/40 px-1.5 py-0.2 rounded font-bold">ЗАБАНЕН</span>
                                            @else
                                                <span class="text-[9px] font-mono bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 px-1.5 py-0.2 rounded font-bold">АКТИВЕН</span>
                                            @endif
                                        </div>
                                        <span class="text-[11px] font-mono text-neutral-400">Steam: {{ $sUser->steam_id ?? 'Не привязан' }} • ELO: {{ $sUser->elo }} • Варнов: {{ $sUser->warnings_count }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2">
                                    @if($sUser->isBanned())
                                        <button 
                                            wire:click="unbanUser({{ $sUser->id }})" 
                                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-cyber font-bold text-xs uppercase tracking-wider rounded-lg transition cursor-pointer">
                                            Снять бан
                                        </button>
                                    @else
                                        <button 
                                            wire:click="manualBanUser({{ $sUser->id }})" 
                                            class="px-4 py-2 bg-red-600/80 hover:bg-red-600 text-white font-cyber font-bold text-xs uppercase tracking-wider rounded-lg transition cursor-pointer">
                                            Выдать бан (15 мин)
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- SECTION 2: SERVER SETTINGS & RCON -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Settings Form (Left 2 cols) -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-[#120817] border border-[#FF0055]/30 rounded-3xl p-6 sm:p-8 shadow-[0_0_25px_rgba(255,0,85,0.2)]">
                    <h2 class="text-xl font-cyber font-bold text-white uppercase tracking-wide mb-4 pb-3 border-b border-[#FF0055]/20">
                        Параметры RCON подключения
                    </h2>

                    <form wire:submit.prevent="saveSettings" class="space-y-4">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-2">
                                <label class="block text-xs font-mono uppercase tracking-wider text-neutral-400 mb-1.5">
                                    IP адрес сервера
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="serverIp" 
                                    placeholder="89.248.236.141"
                                    class="w-full bg-[#08040A] border border-[#FF0055]/30 text-white font-mono text-sm px-4 py-3 rounded-xl focus:outline-none focus:border-[#FF0055]">
                            </div>

                            <div>
                                <label class="block text-xs font-mono uppercase tracking-wider text-neutral-400 mb-1.5">
                                    Порт
                                </label>
                                <input 
                                    type="number" 
                                    wire:model="serverPort" 
                                    placeholder="27574"
                                    class="w-full bg-[#08040A] border border-[#FF0055]/30 text-white font-mono text-sm px-4 py-3 rounded-xl focus:outline-none focus:border-[#FF0055]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-mono uppercase tracking-wider text-neutral-400 mb-1.5">
                                RCON Пароль (из server.cfg)
                            </label>
                            <input 
                                type="password" 
                                wire:model="rconPassword" 
                                placeholder="Введите RCON пароль из server.cfg"
                                class="w-full bg-[#08040A] border border-[#FF0055]/30 text-white font-mono text-sm px-4 py-3 rounded-xl focus:outline-none focus:border-[#FF0055]">
                            <p class="text-[11px] text-neutral-500 mt-1.5 font-mono">
                                Панель Incloudgame → Файлы → game/csgo/cfg/server.cfg → строка <code>rcon_password</code>
                            </p>
                        </div>

                        <div class="pt-2 flex flex-col sm:flex-row items-center gap-3">
                            <button 
                                type="button" 
                                wire:click="testConnection"
                                class="w-full sm:w-auto px-5 py-3 bg-[#08040A] hover:bg-[#1A0D22] text-white font-cyber font-bold text-xs uppercase tracking-wider rounded-xl border border-[#FF0055]/30 hover:border-[#FF0055] transition flex items-center justify-center space-x-2 cursor-pointer">
                                <svg class="w-4 h-4 text-[#FF0055]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <span>Проверить RCON</span>
                            </button>

                            <button 
                                type="submit" 
                                class="w-full sm:w-auto px-7 py-3 bg-gradient-to-r from-[#FF0055] to-[#E1066A] hover:brightness-110 text-white font-cyber font-bold text-xs uppercase tracking-wider rounded-xl shadow-[0_0_20px_rgba(255,0,85,0.4)] transition cursor-pointer">
                                Сохранить настройки
                            </button>
                        </div>
                    </form>

                    <!-- Connection Status & Console output -->
                    @if($connectionStatus)
                        <div class="mt-6 pt-6 border-t border-[#FF0055]/20">
                            <div class="flex items-center space-x-2.5 mb-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $isConnected ? 'bg-emerald-400 shadow-[0_0_8px_#34d399]' : 'bg-red-500 shadow-[0_0_8px_red]' }}"></span>
                                <span class="text-xs font-mono font-bold {{ $isConnected ? 'text-emerald-400' : 'text-red-400' }}">
                                    {{ $connectionStatus }}
                                </span>
                            </div>

                            @if($serverConsoleOutput)
                                <div class="mt-3 bg-[#08040A] border border-[#FF0055]/30 rounded-xl p-4 font-mono text-xs text-emerald-400 overflow-x-auto max-h-60 whitespace-pre">
                                    {{ $serverConsoleOutput }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Server Quick Info (Right 1 col) -->
            <div class="space-y-6">
                <div class="bg-[#120817] border border-[#FF0055]/30 rounded-3xl p-6 shadow-[0_0_25px_rgba(255,0,85,0.2)]">
                    <h3 class="text-sm font-cyber font-bold text-white uppercase tracking-wider mb-3 pb-2 border-b border-[#FF0055]/20">
                        О сервере Incloudgame
                    </h3>
                    <ul class="text-xs text-neutral-400 space-y-2.5 font-mono">
                        <li class="flex items-center justify-between py-1 border-b border-[#FF0055]/15">
                            <span>Имя:</span>
                            <span class="text-white font-semibold">UP FaceIt</span>
                        </li>
                        <li class="flex items-center justify-between py-1 border-b border-[#FF0055]/15">
                            <span>Адрес:</span>
                            <span class="text-emerald-400 font-bold">89.248.236.141:27574</span>
                        </li>
                        <li class="flex items-center justify-between py-1 border-b border-[#FF0055]/15">
                            <span>Протокол:</span>
                            <span class="text-white">Valve Source RCON</span>
                        </li>
                        <li class="flex items-center justify-between py-1">
                            <span>Статус:</span>
                            <span class="text-emerald-400 font-semibold flex items-center space-x-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                <span>ONLINE</span>
                            </span>
                        </li>
                    </ul>
                </div>

                <div class="bg-[#120817] border border-[#FF0055]/30 rounded-3xl p-6 text-xs text-neutral-400 shadow-[0_0_20px_rgba(255,0,85,0.15)]">
                    <h4 class="font-cyber font-bold text-white uppercase tracking-wider mb-3 pb-2 border-b border-[#FF0055]/20">
                        Где в Incloudgame найти RCON?
                    </h4>
                    <ol class="list-decimal list-inside space-y-2 text-[11px] text-neutral-300 font-mono">
                        <li>Откройте панель сервера Incloudgame.</li>
                        <li>Перейдите во вкладку <strong>«Файлы»</strong>.</li>
                        <li>Откройте <code>game/csgo/cfg/server.cfg</code>.</li>
                        <li>Строка <code>rcon_password "..."</code> содержит ваш пароль.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
