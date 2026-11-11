<div>
    <div class="max-w-4xl mx-auto">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-faceit font-bold text-white uppercase tracking-wider">
                Подключение сервера CS2 (Incloudgame)
            </h1>
            <p class="text-neutral-400 text-sm mt-1">
                Интеграция игрового сервера с платформой матчмейкинга через Source RCON Protocol
            </p>
        </div>

        @if(session()->has('message'))
            <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/40 rounded-xl text-emerald-400 text-sm font-semibold flex items-center space-x-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Settings Form (Left 2 cols) -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-faceit-card border border-faceit-border rounded-2xl p-6 shadow-xl">
                    <h2 class="text-xl font-faceit font-bold text-white uppercase tracking-wide mb-4">
                        Параметры RCON подключения
                    </h2>

                    <form wire:submit.prevent="saveSettings" class="space-y-4">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-2">
                                <label class="block text-xs font-semibold uppercase tracking-wider text-neutral-400 mb-1.5">
                                    IP адрес сервера
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="serverIp" 
                                    placeholder="89.248.236.141"
                                    class="w-full bg-neutral-900 border border-neutral-700 text-white font-mono text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-faceit-orange">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-neutral-400 mb-1.5">
                                    Порт
                                </label>
                                <input 
                                    type="number" 
                                    wire:model="serverPort" 
                                    placeholder="27574"
                                    class="w-full bg-neutral-900 border border-neutral-700 text-white font-mono text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-faceit-orange">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-neutral-400 mb-1.5">
                                RCON Пароль (из server.cfg)
                            </label>
                            <input 
                                type="password" 
                                wire:model="rconPassword" 
                                placeholder="Введите RCON пароль из server.cfg"
                                class="w-full bg-neutral-900 border border-neutral-700 text-white font-mono text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-faceit-orange">
                            <p class="text-[11px] text-neutral-500 mt-1">
                                Указывается в панели Incloudgame -> Файлы -> game/csgo/cfg/server.cfg в строке <code>rcon_password</code>
                            </p>
                        </div>

                        <div class="pt-2 flex flex-col sm:flex-row items-center gap-3">
                            <button 
                                type="button" 
                                wire:click="testConnection"
                                class="w-full sm:w-auto px-5 py-2.5 bg-neutral-800 hover:bg-neutral-700 text-white font-faceit font-bold text-sm uppercase tracking-wider rounded-xl border border-neutral-700 transition flex items-center justify-center space-x-2 cursor-pointer">
                                <svg class="w-4 h-4 text-faceit-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <span>Проверить RCON</span>
                            </button>

                            <button 
                                type="submit" 
                                class="w-full sm:w-auto px-6 py-2.5 bg-faceit-orange hover:bg-faceit-orangeHover text-white font-faceit font-bold text-sm uppercase tracking-wider rounded-xl shadow-lg shadow-faceit-orange/25 transition cursor-pointer">
                                Сохранить настройки
                            </button>
                        </div>
                    </form>

                    <!-- Connection Status & Console output -->
                    @if($connectionStatus)
                        <div class="mt-6 pt-6 border-t border-faceit-border">
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $isConnected ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                <span class="text-xs font-semibold {{ $isConnected ? 'text-emerald-400' : 'text-red-400' }}">
                                    {{ $connectionStatus }}
                                </span>
                            </div>

                            @if($serverConsoleOutput)
                                <div class="mt-3 bg-black border border-neutral-800 rounded-xl p-4 font-mono text-xs text-emerald-400 overflow-x-auto max-h-60 whitespace-pre">
                                    {{ $serverConsoleOutput }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Server Quick Info (Right 1 col) -->
            <div class="space-y-6">
                <div class="bg-faceit-card border border-faceit-border rounded-2xl p-6 shadow-xl">
                    <h3 class="text-sm font-faceit font-bold text-white uppercase tracking-wider mb-3">
                        О сервере Incloudgame
                    </h3>
                    <ul class="text-xs text-neutral-400 space-y-2">
                        <li class="flex items-center justify-between py-1 border-b border-faceit-border/40">
                            <span>Название:</span>
                            <span class="text-white font-semibold">UP FaceIt</span>
                        </li>
                        <li class="flex items-center justify-between py-1 border-b border-faceit-border/40">
                            <span>Адрес:</span>
                            <span class="text-emerald-400 font-mono">89.248.236.141:27574</span>
                        </li>
                        <li class="flex items-center justify-between py-1 border-b border-faceit-border/40">
                            <span>Протокол:</span>
                            <span class="text-white">Valve Source RCON</span>
                        </li>
                        <li class="flex items-center justify-between py-1">
                            <span>Статус:</span>
                            <span class="text-emerald-400 font-semibold flex items-center space-x-1">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                <span>ONLINE</span>
                            </span>
                        </li>
                    </ul>
                </div>

                <div class="bg-neutral-900/60 border border-neutral-800 rounded-2xl p-5 text-xs text-neutral-400">
                    <h4 class="font-bold text-neutral-200 uppercase tracking-wider mb-2">
                        Где в Incloudgame найти RCON?
                    </h4>
                    <ol class="list-decimal list-inside space-y-1.5 text-[11px] text-neutral-400">
                        <li>Откройте панель управления сервером Incloudgame.</li>
                        <li>Перейдите во вкладку <strong>«Файлы»</strong> (Файловый менеджер).</li>
                        <li>Откройте папку <code>game/csgo/cfg/</code> и файл <code>server.cfg</code>.</li>
                        <li>Строка <code>rcon_password "..."</code> содержит ваш пароль.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
