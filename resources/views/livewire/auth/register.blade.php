<div class="max-w-md mx-auto py-8">
    <div class="bg-[#120817] border border-[#FF0055]/40 rounded-3xl p-8 sm:p-10 shadow-[0_0_40px_rgba(255,0,85,0.3)] relative overflow-hidden">
        <!-- Ambient Glow -->
        <div class="absolute -top-16 -right-16 w-36 h-36 bg-[#FF0055]/20 rounded-full blur-2xl pointer-events-none"></div>

        <!-- Logo & Header -->
        <div class="text-center mb-6">
            <div class="relative inline-block mb-3">
                <img src="/images/rivalis-logo.jpg" alt="RIVALIS Logo" class="w-16 h-16 rounded-2xl object-cover border-2 border-[#FF0055] shadow-[0_0_20px_rgba(255,0,85,0.6)]">
                <div class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-[#FF0055] rounded-full animate-ping"></div>
            </div>
            <h1 class="text-3xl font-cyber font-extrabold text-white uppercase tracking-wider">
                РЕГИСТРАЦИЯ В <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FF0055] to-[#FF2A6D]">RIVALIS</span>
            </h1>
            <p class="text-neutral-400 text-xs mt-1.5 font-mono">
                Присоединяйтесь к соревновательным 1v1 дуэлям CS2
            </p>
        </div>

        <!-- Quick Steam Register Button -->
        <a href="{{ route('auth.steam') }}" class="w-full py-4 bg-[#171a21] hover:bg-[#222936] text-white font-cyber font-bold text-sm uppercase tracking-wider rounded-2xl border border-neutral-700 hover:border-[#FF0055] transition flex items-center justify-center space-x-3 mb-6 shadow-lg group">
            <svg class="w-6 h-6 fill-current text-neutral-300 group-hover:text-[#FF0055] transition" viewBox="0 0 24 24">
                <path d="M12 2a10 10 0 0 0-10 10c0 4.7 3.25 8.65 7.65 9.72l2.36-3.46a3.5 3.5 0 0 1-.01-.26c0-.3.04-.6.12-.88l-2.8-2a4.98 4.98 0 0 1-1.32.18c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5c0 .33-.04.66-.11.97l2.84 2.03c.52-.3 1.12-.48 1.77-.48 1.93 0 3.5 1.57 3.5 3.5s-1.57 3.5-3.5 3.5c-.32 0-.63-.05-.92-.13l-3.32 2.37A10 10 0 0 0 12 22a10 10 0 0 0 10-10A10 10 0 0 0 12 2zm-4 10.5a2.5 2.5 0 1 1 5 0 2.5 2.5 0 0 1-5 0zm7.5 5.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0z"/>
            </svg>
            <span>Регистрация через Steam</span>
        </a>

        <!-- Divider -->
        <div class="relative flex items-center justify-center my-6">
            <div class="border-t border-[#FF0055]/20 w-full"></div>
            <span class="bg-[#120817] px-3 text-xs uppercase font-mono text-neutral-500 relative">или форма регистрации</span>
        </div>

        <form wire:submit.prevent="register" class="space-y-4">
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-neutral-400 mb-1">
                    Никнейм в игре (Username)
                </label>
                <input 
                    type="text" 
                    wire:model="name" 
                    placeholder="s1mple"
                    class="w-full bg-[#08040A] border border-[#FF0055]/30 text-white text-sm px-4 py-3 rounded-xl focus:outline-none focus:border-[#FF0055]">
                @error('name') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-neutral-400 mb-1">
                    Email адрес
                </label>
                <input 
                    type="email" 
                    wire:model="email" 
                    placeholder="player@rivalis.gg"
                    class="w-full bg-[#08040A] border border-[#FF0055]/30 text-white text-sm px-4 py-3 rounded-xl focus:outline-none focus:border-[#FF0055]">
                @error('email') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="text-xs font-mono uppercase tracking-wider text-neutral-400">
                        SteamID64 (или ссылка)
                    </label>
                    <a href="https://steamid.io/" target="_blank" class="text-[11px] text-[#FF2A6D] hover:underline font-mono">
                        Где найти? ↗
                    </a>
                </div>
                <input 
                    type="text" 
                    wire:model="steamIdInput" 
                    placeholder="76561198000000000 или ссылка на профиль"
                    class="w-full bg-[#08040A] border border-[#FF0055]/30 text-white font-mono text-sm px-4 py-3 rounded-xl focus:outline-none focus:border-[#FF0055]">
                <p class="text-[11px] text-neutral-500 mt-1 font-mono">
                    Необходим для входа на сервер CS2 и синхронизации статистики
                </p>
                @error('steamIdInput') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-mono uppercase tracking-wider text-neutral-400 mb-1">
                        Пароль
                    </label>
                    <input 
                        type="password" 
                        wire:model="password" 
                        placeholder="••••••••"
                        class="w-full bg-[#08040A] border border-[#FF0055]/30 text-white text-sm px-4 py-3 rounded-xl focus:outline-none focus:border-[#FF0055]">
                    @error('password') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-mono uppercase tracking-wider text-neutral-400 mb-1">
                        Повтор пароля
                    </label>
                    <input 
                        type="password" 
                        wire:model="password_confirmation" 
                        placeholder="••••••••"
                        class="w-full bg-[#08040A] border border-[#FF0055]/30 text-white text-sm px-4 py-3 rounded-xl focus:outline-none focus:border-[#FF0055]">
                </div>
            </div>

            <button 
                type="submit" 
                class="w-full py-4 bg-gradient-to-r from-[#FF0055] to-[#E1066A] hover:brightness-110 text-white font-cyber font-bold text-base uppercase tracking-wider rounded-2xl shadow-[0_0_20px_rgba(255,0,85,0.4)] transition cursor-pointer mt-2">
                Завершить регистрацию
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-neutral-400">
            Уже есть аккаунт? 
            <a href="{{ route('login') }}" class="text-[#FF2A6D] hover:underline font-semibold ml-1">
                Войти в RIVALIS
            </a>
        </div>
    </div>
</div>
