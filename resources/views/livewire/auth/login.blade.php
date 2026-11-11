<div class="max-w-md mx-auto py-12">
    <div class="bg-faceit-card border border-faceit-border rounded-2xl p-8 shadow-2xl">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="w-12 h-12 bg-faceit-orange flex items-center justify-center rounded-xl font-faceit font-bold text-3xl text-black shadow-lg shadow-faceit-orange/25 mx-auto mb-3">
                F
            </div>
            <h1 class="text-3xl font-faceit font-bold text-white uppercase tracking-wider">
                Вход на платформу
            </h1>
            <p class="text-neutral-400 text-xs mt-1">
                Соревновательный матчмейкинг CS2 1v1
            </p>
        </div>

        <!-- Flash messages / Errors -->
        @if ($errors->has('steam'))
            <div class="mb-5 p-3 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-xs flex items-start space-x-2">
                <svg class="w-4 h-4 text-red-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>{{ $errors->first('steam') }}</span>
            </div>
        @endif

        @if (session('status'))
            <div class="mb-5 p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs flex items-center space-x-2">
                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <!-- Quick Steam Login Button -->
        <a href="{{ route('auth.steam') }}" class="w-full py-3.5 bg-[#171a21] hover:bg-[#2a475e] text-white font-faceit font-bold text-base uppercase tracking-wider rounded-xl border border-neutral-700 transition flex items-center justify-center space-x-3 mb-4 shadow-lg group">
            <svg class="w-6 h-6 fill-current text-neutral-300 group-hover:text-white transition" viewBox="0 0 24 24">
                <path d="M12 2a10 10 0 0 0-10 10c0 4.7 3.25 8.65 7.65 9.72l2.36-3.46a3.5 3.5 0 0 1-.01-.26c0-.3.04-.6.12-.88l-2.8-2a4.98 4.98 0 0 1-1.32.18c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5c0 .33-.04.66-.11.97l2.84 2.03c.52-.3 1.12-.48 1.77-.48 1.93 0 3.5 1.57 3.5 3.5s-1.57 3.5-3.5 3.5c-.32 0-.63-.05-.92-.13l-3.32 2.37A10 10 0 0 0 12 22a10 10 0 0 0 10-10A10 10 0 0 0 12 2zm-4 10.5a2.5 2.5 0 1 1 5 0 2.5 2.5 0 0 1-5 0zm7.5 5.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0z"/>
            </svg>
            <span>Войти через Steam</span>
        </a>

        <!-- Manual Steam ID Fallback Form -->
        <div class="mb-6 bg-neutral-900/60 border border-neutral-800 rounded-xl p-3">
            <div class="text-[11px] font-semibold text-neutral-400 uppercase tracking-wider mb-2 flex items-center justify-between">
                <span>Или войти по SteamID64</span>
                <span class="text-neutral-500 font-normal lowercase">(без авторизации)</span>
            </div>
            <form method="POST" action="{{ route('auth.steam.direct') }}" class="flex space-x-2">
                @csrf
                <input 
                    type="text" 
                    name="steam_input" 
                    value="{{ old('steam_input') }}"
                    placeholder="76561198... или ссылка на профиль"
                    class="flex-1 bg-neutral-900 border border-neutral-700 text-white text-xs px-3 py-2 rounded-lg focus:outline-none focus:border-faceit-orange font-mono">
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-neutral-800 hover:bg-faceit-orange hover:text-black text-neutral-200 font-faceit font-bold text-xs uppercase tracking-wider rounded-lg border border-neutral-700 transition">
                    Войти
                </button>
            </form>
            @error('steam_input') <span class="text-[11px] text-red-400 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Divider -->
        <div class="relative flex items-center justify-center my-6">
            <div class="border-t border-faceit-border w-full"></div>
            <span class="bg-faceit-card px-3 text-xs uppercase font-semibold text-neutral-500 relative">или с email</span>
        </div>

        <!-- Login Form -->
        <form wire:submit.prevent="login" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-neutral-400 mb-1.5">
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
                <label class="block text-xs font-semibold uppercase tracking-wider text-neutral-400 mb-1.5">
                    Пароль
                </label>
                <input 
                    type="password" 
                    wire:model="password" 
                    placeholder="••••••••"
                    class="w-full bg-neutral-900 border border-neutral-700 text-white text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-faceit-orange">
                @error('password') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-between text-xs text-neutral-400">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" wire:model="remember" class="rounded bg-neutral-900 border-neutral-700 text-faceit-orange focus:ring-0">
                    <span>Запомнить меня</span>
                </label>
            </div>

            <button 
                type="submit" 
                class="w-full py-3.5 bg-faceit-orange hover:bg-faceit-orangeHover text-white font-faceit font-bold text-lg uppercase tracking-wider rounded-xl shadow-lg shadow-faceit-orange/25 transition cursor-pointer">
                Войти в аккаунт
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-neutral-400">
            Нет аккаунта? 
            <a href="{{ route('register') }}" class="text-faceit-orange hover:underline font-semibold ml-1">
                Зарегистрироваться
            </a>
        </div>
    </div>
</div>
