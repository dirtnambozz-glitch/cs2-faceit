<div class="max-w-md mx-auto py-10">
    <div class="bg-[#120817] border border-[#FF0055]/40 rounded-3xl p-8 sm:p-10 shadow-[0_0_40px_rgba(255,0,85,0.3)] relative overflow-hidden">
        <!-- Ambient Glow -->
        <div class="absolute -top-16 -right-16 w-36 h-36 bg-[#FF0055]/20 rounded-full blur-2xl pointer-events-none"></div>

        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="relative inline-block mb-3">
                <img src="/images/rivalis-logo.jpg" alt="RIVALIS Logo" class="w-16 h-16 rounded-2xl object-cover border-2 border-[#FF0055] shadow-[0_0_20px_rgba(255,0,85,0.6)]">
                <div class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-[#FF0055] rounded-full animate-ping"></div>
            </div>
            <h1 class="text-3xl font-cyber font-extrabold text-white uppercase tracking-wider">
                ВХОД В <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FF0055] to-[#FF2A6D]">RIVALIS</span>
            </h1>
            <p class="text-neutral-400 text-xs mt-1.5 font-mono">
                Киберспортивный матчмейкинг CS2 1v1
            </p>
        </div>

        <!-- Flash messages / Errors -->
        @if ($errors->has('steam'))
            <div class="mb-5 p-3.5 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-400 text-xs flex items-start space-x-2">
                <svg class="w-4 h-4 text-red-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>{{ $errors->first('steam') }}</span>
            </div>
        @endif

        @if (session('status'))
            <div class="mb-5 p-3.5 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs flex items-center space-x-2">
                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <!-- Quick Steam Login Button -->
        <a href="{{ route('auth.steam') }}" class="w-full py-4 bg-[#171a21] hover:bg-[#222936] text-white font-cyber font-bold text-sm uppercase tracking-wider rounded-2xl border border-neutral-700 hover:border-[#FF0055] transition flex items-center justify-center space-x-3 mb-4 shadow-lg group">
            <svg class="w-6 h-6 fill-current text-neutral-300 group-hover:text-[#FF0055] transition" viewBox="0 0 24 24">
                <path d="M12 2a10 10 0 0 0-10 10c0 4.7 3.25 8.65 7.65 9.72l2.36-3.46a3.5 3.5 0 0 1-.01-.26c0-.3.04-.6.12-.88l-2.8-2a4.98 4.98 0 0 1-1.32.18c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5c0 .33-.04.66-.11.97l2.84 2.03c.52-.3 1.12-.48 1.77-.48 1.93 0 3.5 1.57 3.5 3.5s-1.57 3.5-3.5 3.5c-.32 0-.63-.05-.92-.13l-3.32 2.37A10 10 0 0 0 12 22a10 10 0 0 0 10-10A10 10 0 0 0 12 2zm-4 10.5a2.5 2.5 0 1 1 5 0 2.5 2.5 0 0 1-5 0zm7.5 5.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0z"/>
            </svg>
            <span>Войти через Steam</span>
        </a>

        <!-- Manual Steam ID Fallback Form -->
        <div class="mb-6 bg-[#08040A] border border-[#FF0055]/30 rounded-2xl p-3.5">
            <div class="text-[11px] font-mono font-semibold text-neutral-400 uppercase tracking-wider mb-2 flex items-center justify-between">
                <span class="text-[#FF0055]">ВХОД ПО STEAMID64</span>
                <span class="text-neutral-500 lowercase">(без пароля)</span>
            </div>
            <form method="POST" action="{{ route('auth.steam.direct') }}" class="flex space-x-2">
                @csrf
                <input 
                    type="text" 
                    name="steam_input" 
                    value="{{ old('steam_input') }}"
                    placeholder="76561198... или ссылка на Steam"
                    class="flex-1 bg-[#120817] border border-neutral-700 text-white text-xs px-3 py-2 rounded-xl focus:outline-none focus:border-[#FF0055] font-mono">
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-[#FF0055]/20 hover:bg-[#FF0055] text-white font-cyber font-bold text-xs uppercase tracking-wider rounded-xl border border-[#FF0055]/40 transition">
                    Войти
                </button>
            </form>
            @error('steam_input') <span class="text-[11px] text-red-400 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Divider -->
        <div class="relative flex items-center justify-center my-6">
            <div class="border-t border-[#FF0055]/20 w-full"></div>
            <span class="bg-[#120817] px-3 text-xs uppercase font-mono text-neutral-500 relative">или с email</span>
        </div>

        <!-- Login Form -->
        <form wire:submit.prevent="login" class="space-y-4">
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-neutral-400 mb-1.5">
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
                <label class="block text-xs font-mono uppercase tracking-wider text-neutral-400 mb-1.5">
                    Пароль
                </label>
                <input 
                    type="password" 
                    wire:model="password" 
                    placeholder="••••••••"
                    class="w-full bg-[#08040A] border border-[#FF0055]/30 text-white text-sm px-4 py-3 rounded-xl focus:outline-none focus:border-[#FF0055]">
                @error('password') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-between text-xs text-neutral-400">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" wire:model="remember" class="rounded bg-[#08040A] border-[#FF0055]/40 text-[#FF0055] focus:ring-0">
                    <span>Запомнить меня</span>
                </label>
            </div>

            <button 
                type="submit" 
                class="w-full py-4 bg-gradient-to-r from-[#FF0055] to-[#E1066A] hover:brightness-110 text-white font-cyber font-bold text-base uppercase tracking-wider rounded-2xl shadow-[0_0_20px_rgba(255,0,85,0.4)] transition cursor-pointer">
                Войти в систему
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-neutral-400">
            Нет аккаунта? 
            <a href="{{ route('register') }}" class="text-[#FF2A6D] hover:underline font-semibold ml-1">
                Создать профиль RIVALIS
            </a>
        </div>
    </div>
</div>
