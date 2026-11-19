<div class="max-w-md mx-auto py-8">
    <div class="bg-[#0B0E14] border border-white/[0.08] rounded-3xl p-8 shadow-sm">
        <!-- Logo & Title -->
        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#FF5500] to-[#CC3A00] flex items-center justify-center mx-auto mb-3 shadow-[0_2px_12px_rgba(255,85,0,0.35)]">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-heading font-extrabold text-white tracking-tight">
                Регистрация в RIVALIS
            </h1>
            <p class="text-neutral-400 text-xs mt-1 font-mono">
                Присоединяйтесь к дуэлям 1v1 в Counter-Strike 2
            </p>
        </div>

        <!-- Quick Steam Register -->
        <a href="{{ route('auth.steam') }}" class="w-full py-3.5 bg-[#171a21] hover:bg-[#202734] text-white font-medium text-sm rounded-xl border border-white/[0.1] hover:border-white/[0.2] transition flex items-center justify-center space-x-2.5 mb-5 shadow-sm">
            <svg class="w-5 h-5 fill-current text-neutral-300" viewBox="0 0 24 24">
                <path d="M12 2a10 10 0 0 0-10 10c0 4.7 3.25 8.65 7.65 9.72l2.36-3.46a3.5 3.5 0 0 1-.01-.26c0-.3.04-.6.12-.88l-2.8-2a4.98 4.98 0 0 1-1.32.18c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5c0 .33-.04.66-.11.97l2.84 2.03c.52-.3 1.12-.48 1.77-.48 1.93 0 3.5 1.57 3.5 3.5s-1.57 3.5-3.5 3.5c-.32 0-.63-.05-.92-.13l-3.32 2.37A10 10 0 0 0 12 22a10 10 0 0 0 10-10A10 10 0 0 0 12 22zm-4 10.5a2.5 2.5 0 1 1 5 0 2.5 2.5 0 0 1-5 0zm7.5 5.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0z"/>
            </svg>
            <span>Регистрация через Steam</span>
        </a>

        <!-- Divider -->
        <div class="relative flex items-center justify-center my-5">
            <div class="border-t border-white/[0.06] w-full"></div>
            <span class="bg-[#0B0E14] px-3 text-xs font-mono text-neutral-500 relative">или форма регистрации</span>
        </div>

        <form wire:submit.prevent="register" class="space-y-3.5">
            <div>
                <label class="block text-xs font-mono uppercase text-neutral-400 mb-1">
                    Никнейм игрока
                </label>
                <input 
                    type="text" 
                    wire:model="name" 
                    placeholder="s1mple"
                    class="w-full bg-[#0E131C] border border-white/[0.08] focus:border-[#FF5500] text-white text-sm px-3.5 py-2.5 rounded-xl focus:outline-none transition-colors">
                @error('name') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-mono uppercase text-neutral-400 mb-1">
                    Email
                </label>
                <input 
                    type="email" 
                    wire:model="email" 
                    placeholder="player@rivalis.gg"
                    class="w-full bg-[#0E131C] border border-white/[0.08] focus:border-[#FF5500] text-white text-sm px-3.5 py-2.5 rounded-xl focus:outline-none transition-colors">
                @error('email') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="text-xs font-mono uppercase text-neutral-400">
                        SteamID64 (или ссылка)
                    </label>
                    <a href="https://steamid.io/" target="_blank" class="text-[11px] text-[#FF5500] hover:underline font-mono">
                        Где найти? ↗
                    </a>
                </div>
                <input 
                    type="text" 
                    wire:model="steamIdInput" 
                    placeholder="76561198000000000"
                    class="w-full bg-[#0E131C] border border-white/[0.08] focus:border-[#FF5500] text-white font-mono text-sm px-3.5 py-2.5 rounded-xl focus:outline-none transition-colors">
                @error('steamIdInput') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-mono uppercase text-neutral-400 mb-1">
                        Пароль
                    </label>
                    <input 
                        type="password" 
                        wire:model="password" 
                        placeholder="••••••••"
                        class="w-full bg-[#0E131C] border border-white/[0.08] focus:border-[#FF5500] text-white text-sm px-3.5 py-2.5 rounded-xl focus:outline-none transition-colors">
                    @error('password') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-mono uppercase text-neutral-400 mb-1">
                        Повтор пароля
                    </label>
                    <input 
                        type="password" 
                        wire:model="password_confirmation" 
                        placeholder="••••••••"
                        class="w-full bg-[#0E131C] border border-white/[0.08] focus:border-[#FF5500] text-white text-sm px-3.5 py-2.5 rounded-xl focus:outline-none transition-colors">
                </div>
            </div>

            <button 
                type="submit" 
                class="w-full py-3.5 bg-[#FF5500] hover:bg-[#FF6E26] text-white font-medium text-sm rounded-xl transition cursor-pointer mt-2 shadow-sm">
                Завершить регистрацию
            </button>
        </form>

        <div class="mt-5 text-center text-xs text-neutral-400">
            Уже есть аккаунт? 
            <a href="{{ route('login') }}" class="text-[#FF5500] hover:underline font-medium ml-1">
                Войти в RIVALIS
            </a>
        </div>
    </div>
</div>
