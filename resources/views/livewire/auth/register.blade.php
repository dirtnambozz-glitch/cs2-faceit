<div class="max-w-md mx-auto py-8">
    <div class="bg-[#121620] border border-[#22293A] rounded-2xl p-8 sm:p-10 shadow-xl relative overflow-hidden">
        <!-- Subtle Top Accent -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-[#FF5500] to-transparent"></div>

        <!-- Logo & Header -->
        <div class="text-center mb-6">
            <div class="relative inline-block mb-3">
                <img src="/images/rivalis-logo.jpg" alt="RIVALIS Logo" class="w-14 h-14 rounded-xl object-cover border border-[#FF5500]/50 shadow-[0_0_15px_rgba(255,85,0,0.3)]">
                <div class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-[#FF5500] rounded-full animate-pulse"></div>
            </div>
            <h1 class="text-2xl sm:text-3xl font-faceit font-extrabold text-white uppercase tracking-wider">
                РЕГИСТРАЦИЯ В <span class="text-[#FF5500]">RIVALIS</span>
            </h1>
            <p class="text-neutral-400 text-xs mt-1 font-mono">
                Присоединяйтесь к соревновательным 1v1 дуэлям CS2
            </p>
        </div>

        <!-- Quick Steam Register Button -->
        <a href="{{ route('auth.steam') }}" class="w-full py-3.5 bg-[#171a21] hover:bg-[#202530] text-white font-faceit font-bold text-sm uppercase tracking-wider rounded-xl border border-neutral-700 hover:border-[#FF5500]/60 transition flex items-center justify-center space-x-2.5 mb-5 shadow group">
            <svg class="w-5 h-5 fill-current text-neutral-300 group-hover:text-[#FF5500] transition" viewBox="0 0 24 24">
                <path d="M12 2a10 10 0 0 0-10 10c0 4.7 3.25 8.65 7.65 9.72l2.36-3.46a3.5 3.5 0 0 1-.01-.26c0-.3.04-.6.12-.88l-2.8-2a4.98 4.98 0 0 1-1.32.18c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5c0 .33-.04.66-.11.97l2.84 2.03c.52-.3 1.12-.48 1.77-.48 1.93 0 3.5 1.57 3.5 3.5s-1.57 3.5-3.5 3.5c-.32 0-.63-.05-.92-.13l-3.32 2.37A10 10 0 0 0 12 22a10 10 0 0 0 10-10A10 10 0 0 0 12 22zm-4 10.5a2.5 2.5 0 1 1 5 0 2.5 2.5 0 0 1-5 0zm7.5 5.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0z"/>
            </svg>
            <span>Регистрация через Steam OpenID</span>
        </a>

        <!-- Divider -->
        <div class="relative flex items-center justify-center my-5">
            <div class="border-t border-[#22293A] w-full"></div>
            <span class="bg-[#121620] px-3 text-xs uppercase font-mono text-neutral-500 relative">или стандартная анкета</span>
        </div>

        <form wire:submit.prevent="register" class="space-y-3.5">
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-neutral-400 mb-1">
                    Игровой никнейм (Username)
                </label>
                <input 
                    type="text" 
                    wire:model="name" 
                    placeholder="s1mple"
                    class="w-full bg-[#0C0E14] border border-[#22293A] text-white text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#FF5500]">
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
                    class="w-full bg-[#0C0E14] border border-[#22293A] text-white text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#FF5500]">
                @error('email') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="text-xs font-mono uppercase tracking-wider text-neutral-400">
                        SteamID64 (или ссылка на профиль)
                    </label>
                    <a href="https://steamid.io/" target="_blank" class="text-[11px] text-[#FF5500] hover:underline font-mono">
                        Где найти? ↗
                    </a>
                </div>
                <input 
                    type="text" 
                    wire:model="steamIdInput" 
                    placeholder="76561198000000000"
                    class="w-full bg-[#0C0E14] border border-[#22293A] text-white font-mono text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#FF5500]">
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
                        class="w-full bg-[#0C0E14] border border-[#22293A] text-white text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#FF5500]">
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
                        class="w-full bg-[#0C0E14] border border-[#22293A] text-white text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#FF5500]">
                </div>
            </div>

            <button 
                type="submit" 
                class="w-full py-3 bg-[#FF5500] hover:bg-[#FF6E1A] text-white font-faceit font-bold text-base uppercase tracking-wider rounded-xl shadow-[0_0_15px_rgba(255,85,0,0.3)] transition cursor-pointer mt-2">
                Завершить регистрацию
            </button>
        </form>

        <div class="mt-5 text-center text-xs text-neutral-400">
            Уже есть аккаунт? 
            <a href="{{ route('login') }}" class="text-[#FF5500] hover:underline font-semibold ml-1">
                Войти в RIVALIS
            </a>
        </div>
    </div>
</div>
