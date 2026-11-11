<div class="max-w-md mx-auto py-8">
    <div class="bg-faceit-card border border-faceit-border rounded-2xl p-8 shadow-2xl">
        <!-- Logo & Header -->
        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-faceit-orange flex items-center justify-center rounded-xl font-faceit font-bold text-3xl text-black shadow-lg shadow-faceit-orange/25 mx-auto mb-3">
                R
            </div>
            <h1 class="text-3xl font-faceit font-bold text-white uppercase tracking-wider">
                Регистрация в RIVALIS
            </h1>
            <p class="text-neutral-400 text-xs mt-1">
                Присоединяйтесь к соревновательным 1v1 матчам CS2
            </p>
        </div>

        <!-- Quick Steam Register Button -->
        <a href="{{ route('auth.steam') }}" class="w-full py-3.5 bg-[#171a21] hover:bg-[#2a475e] text-white font-faceit font-bold text-base uppercase tracking-wider rounded-xl border border-neutral-700 transition flex items-center justify-center space-x-3 mb-6 shadow-lg group">
            <svg class="w-6 h-6 fill-current text-neutral-300 group-hover:text-white transition" viewBox="0 0 24 24">
                <path d="M12 2a10 10 0 0 0-10 10c0 4.7 3.25 8.65 7.65 9.72l2.36-3.46a3.5 3.5 0 0 1-.01-.26c0-.3.04-.6.12-.88l-2.8-2a4.98 4.98 0 0 1-1.32.18c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5c0 .33-.04.66-.11.97l2.84 2.03c.52-.3 1.12-.48 1.77-.48 1.93 0 3.5 1.57 3.5 3.5s-1.57 3.5-3.5 3.5c-.32 0-.63-.05-.92-.13l-3.32 2.37A10 10 0 0 0 12 22a10 10 0 0 0 10-10A10 10 0 0 0 12 2zm-4 10.5a2.5 2.5 0 1 1 5 0 2.5 2.5 0 0 1-5 0zm7.5 5.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0z"/>
            </svg>
            <span>Быстрая регистрация через Steam</span>
        </a>

        <!-- Divider -->
        <div class="relative flex items-center justify-center my-6">
            <div class="border-t border-faceit-border w-full"></div>
            <span class="bg-faceit-card px-3 text-xs uppercase font-semibold text-neutral-500 relative">или форма регистрации</span>
        </div>

        <form wire:submit.prevent="register" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-neutral-400 mb-1">
                    Никнейм в игре (Username)
                </label>
                <input 
                    type="text" 
                    wire:model="name" 
                    placeholder="s1mple"
                    class="w-full bg-neutral-900 border border-neutral-700 text-white text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-faceit-orange">
                @error('name') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-neutral-400 mb-1">
                    Email адрес
                </label>
                <input 
                    type="email" 
                    wire:model="email" 
                    placeholder="player@example.com"
                    class="w-full bg-neutral-900 border border-neutral-700 text-white text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-faceit-orange">
                @error('email') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="text-xs font-semibold uppercase tracking-wider text-neutral-400">
                        SteamID64 (или ссылка на Steam)
                    </label>
                    <a href="https://steamid.io/" target="_blank" class="text-[11px] text-faceit-orange hover:underline">
                        Где найти?
                    </a>
                </div>
                <input 
                    type="text" 
                    wire:model="steamIdInput" 
                    placeholder="76561198000000000 или ссылка на профиль"
                    class="w-full bg-neutral-900 border border-neutral-700 text-white font-mono text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-faceit-orange">
                <p class="text-[11px] text-neutral-500 mt-1">
                    Необходим серверу CS2 для идентификации и входа в лобби
                </p>
                @error('steamIdInput') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-neutral-400 mb-1">
                        Пароль
                    </label>
                    <input 
                        type="password" 
                        wire:model="password" 
                        placeholder="••••••••"
                        class="w-full bg-neutral-900 border border-neutral-700 text-white text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-faceit-orange">
                    @error('password') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-neutral-400 mb-1">
                        Повтор пароля
                    </label>
                    <input 
                        type="password" 
                        wire:model="password_confirmation" 
                        placeholder="••••••••"
                        class="w-full bg-neutral-900 border border-neutral-700 text-white text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-faceit-orange">
                </div>
            </div>

            <button 
                type="submit" 
                class="w-full py-3.5 bg-faceit-orange hover:bg-faceit-orangeHover text-white font-faceit font-bold text-lg uppercase tracking-wider rounded-xl shadow-lg shadow-faceit-orange/25 transition cursor-pointer mt-2">
                Зарегистрироваться
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-neutral-400">
            Уже есть аккаунт? 
            <a href="{{ route('login') }}" class="text-faceit-orange hover:underline font-semibold ml-1">
                Войти
            </a>
        </div>
    </div>
</div>
