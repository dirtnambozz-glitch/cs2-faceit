<div>
    <div class="max-w-4xl mx-auto">
        <!-- Page Title -->
        <div class="mb-8">
            <div class="flex items-center space-x-2 text-xs font-mono uppercase tracking-widest text-[#FF0055] mb-1">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                <span>SERVER CONFIGURATION // RCON & MATCHZY</span>
            </div>
            <h1 class="text-3xl font-cyber font-extrabold text-white uppercase tracking-wider">
                ПОДКЛЮЧЕНИЕ СЕРВЕРА <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FF0055] to-[#FF2A6D]">CS2</span>
            </h1>
            <p class="text-neutral-400 text-xs sm:text-sm mt-1 font-mono">
                Интеграция выделенного сервера Incloudgame с платформой матчмейкинга по протоколу Valve Source RCON
            </p>
        </div>

        @if(session()->has('message'))
            <div class="mb-6 p-4 bg-emerald-500/15 border border-emerald-500/40 rounded-2xl text-emerald-400 text-sm font-semibold flex items-center space-x-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif

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
