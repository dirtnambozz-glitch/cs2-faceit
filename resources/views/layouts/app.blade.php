<!DOCTYPE html>
<html lang="ru" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'RIVALIS — Киберспортивный CS2 1v1 Матчмейкинг' }}</title>
    <link rel="icon" type="image/jpeg" href="/images/rivalis-logo.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        faceit: {
                            orange: '#FF5500',
                            orangeHover: '#FF6E1A',
                            orangeDark: '#E04B00',
                            canvas: '#0C0E14',
                            card: '#121620',
                            cardLight: '#181E2B',
                            cardHover: '#1D2435',
                            border: '#22293A',
                            borderLight: 'rgba(255, 255, 255, 0.08)',
                            muted: '#94A3B8',
                        },
                        rivalis: {
                            pink: '#FF5500',
                            pinkHover: '#FF6E1A',
                            pinkDark: '#E04B00',
                            neon: '#FF5500',
                            magenta: '#FF6E1A',
                            dark: '#0C0E14',
                            darker: '#080A0E',
                            card: '#121620',
                            cardLight: '#181E2B',
                            border: '#22293A',
                            borderBright: 'rgba(255, 85, 0, 0.45)',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                        rajdhani: ['Rajdhani', 'sans-serif'],
                        mono: ['JetBrains Mono', 'Menlo', 'Monaco', 'Courier New', 'monospace'],
                        faceit: ['Rajdhani', 'sans-serif'],
                        cyber: ['Rajdhani', 'sans-serif'],
                        orbitron: ['Rajdhani', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600;700&family=Rajdhani:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #0C0E14;
            background-image: 
                radial-gradient(circle at 50% 0%, rgba(255, 85, 0, 0.08) 0%, transparent 50%),
                linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 100% 100%, 40px 40px, 40px 40px;
            background-attachment: fixed;
        }
        .font-faceit { font-family: 'Rajdhani', sans-serif; }
        .font-cyber { font-family: 'Rajdhani', sans-serif; }

        /* Custom subtle scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #0C0E14; }
        ::-webkit-scrollbar-thumb { background: #22293A; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #FF5500; }
    </style>
    @livewireStyles
</head>
<body class="bg-[#0C0E14] text-neutral-100 min-h-screen flex flex-col antialiased selection:bg-[#FF5500] selection:text-white">

<!-- Subtle FACEIT Orange Top Accent Line -->
<div class="h-0.5 w-full bg-gradient-to-r from-transparent via-[#FF5500] to-transparent shadow-[0_0_12px_#FF5500]"></div>

<!-- Header -->
<header class="border-b border-[#22293A] bg-[#0C0E14]/90 backdrop-blur-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 h-16 sm:h-20 flex items-center justify-between">
        <!-- Logo & Navigation -->
        <div class="flex items-center space-x-6">
            <a href="{{ auth()->check() ? route('home') : route('leaderboard') }}" class="flex items-center space-x-3 group">
                <div class="relative">
                    <img src="/images/rivalis-logo.jpg" alt="RIVALIS Logo" class="w-10 h-10 rounded-xl object-cover border border-[#FF5500]/60 shadow-[0_0_12px_rgba(255,85,0,0.35)] group-hover:scale-105 transition-transform">
                    <div class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-[#FF5500] rounded-full animate-pulse"></div>
                </div>
                <div>
                    <div class="flex items-center space-x-1.5">
                        <span class="font-faceit font-extrabold text-2xl text-white tracking-wider uppercase group-hover:text-[#FF5500] transition-colors">
                            RIVALIS
                        </span>
                        <span class="text-[#FF5500] font-black text-2xl">/</span>
                    </div>
                    <p class="text-[10px] font-mono tracking-widest text-neutral-400 uppercase hidden sm:block -mt-1">
                        CS2 COMPETITIVE 1V1
                    </p>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center space-x-1 pl-4 border-l border-[#22293A]">
                @auth
                <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-lg text-sm font-faceit font-bold uppercase tracking-wider {{ request()->routeIs('home') ? 'text-[#FF5500] bg-[#FF5500]/10 border border-[#FF5500]/40' : 'text-neutral-300 hover:text-white hover:bg-white/5' }} transition">
                    Матчмейкинг
                </a>
                @endauth
                <a href="{{ route('leaderboard') }}" class="px-3.5 py-2 rounded-lg text-sm font-faceit font-bold uppercase tracking-wider {{ request()->routeIs('leaderboard') ? 'text-[#FF5500] bg-[#FF5500]/10 border border-[#FF5500]/40' : 'text-neutral-300 hover:text-white hover:bg-white/5' }} transition">
                    Лидерборд
                </a>
                @auth
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 rounded-lg text-sm font-faceit font-bold uppercase tracking-wider flex items-center space-x-1.5 {{ request()->routeIs('admin.dashboard') || request()->routeIs('server.settings') ? 'text-[#FF5500] bg-[#FF5500]/10 border border-[#FF5500]/40' : 'text-neutral-300 hover:text-white hover:bg-white/5' }} transition">
                    <svg class="w-4 h-4 text-[#FF5500]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Админ-панель</span>
                </a>
                @endif
                @endauth
            </nav>
        </div>

        <!-- Right Side User HUD / Auth -->
        <div class="flex items-center space-x-3">
            @auth
                @if(!auth()->user()->steam_id)
                <a href="{{ route('profile') }}" class="hidden sm:flex items-center space-x-1.5 bg-amber-500/10 border border-amber-500/30 text-amber-400 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-amber-500/20 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Привяжи Steam!</span>
                </a>
                @endif
                <a href="{{ route('profile') }}" class="flex items-center space-x-3 bg-[#121620] border border-[#22293A] rounded-xl px-3.5 py-1.5 hover:border-[#FF5500]/60 transition group">
                    <img src="{{ auth()->user()->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed='.auth()->user()->name }}" class="w-8 h-8 rounded-lg bg-neutral-900 border border-white/10 object-cover" alt="">
                    <div class="hidden sm:block text-right">
                        <div class="text-xs font-semibold text-white leading-tight group-hover:text-[#FF5500] transition-colors flex items-center justify-end space-x-1.5">
                            <span>{{ auth()->user()->name }}</span>
                            @if(auth()->user()->isAdmin())
                                <span class="text-[9px] font-mono font-bold bg-[#FF5500] text-white px-1.5 py-0.5 rounded">ADMIN</span>
                            @endif
                        </div>
                        <div class="text-[11px] font-mono text-neutral-400">{{ auth()->user()->elo }} <span class="text-[#FF5500] font-bold">ELO</span></div>
                    </div>
                    <x-faceit-level :level="auth()->user()->level" size="sm" />
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-3 py-2 text-xs font-faceit font-bold uppercase tracking-wider text-neutral-400 hover:text-white bg-[#121620] border border-[#22293A] rounded-xl hover:border-red-500/50 hover:bg-red-500/10 transition cursor-pointer">
                        Выйти
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-faceit font-bold uppercase tracking-wider text-neutral-200 hover:text-white bg-[#121620] rounded-xl border border-[#22293A] hover:border-white/20 transition">Войти</a>
                <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-faceit font-bold uppercase tracking-wider text-white bg-[#FF5500] hover:bg-[#FF6E1A] rounded-xl shadow-[0_0_15px_rgba(255,85,0,0.35)] transition">Регистрация</a>
            @endauth
        </div>
    </div>
</header>

@if(session('warning'))
<div class="bg-amber-500/10 border-b border-amber-500/30 py-2.5 px-4 text-center text-xs text-amber-300 font-semibold tracking-wide flex items-center justify-center space-x-2">
    <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    <span>{{ session('warning') }}</span>
</div>
@endif

@if(session('status'))
<div class="bg-emerald-500/10 border-b border-emerald-500/30 py-2.5 px-4 text-center text-xs text-emerald-300 font-semibold tracking-wide flex items-center justify-center space-x-2">
    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <span>{{ session('status') }}</span>
</div>
@endif

<main class="flex-1 max-w-7xl w-full mx-auto px-4 py-8">
    {{ $slot }}
</main>

<footer class="border-t border-[#22293A] bg-[#0C0E14] py-8 text-xs text-neutral-500">
    <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center space-x-2">
            <span class="font-faceit font-bold text-white text-base tracking-wider">RIVALIS</span>
            <span class="text-[#FF5500]">/</span>
            <span class="text-neutral-400">CS2 1v1 Competitive Matchmaking Platform</span>
        </div>
        <div class="flex items-center space-x-3 text-neutral-400 text-xs font-mono">
            <span class="inline-flex items-center space-x-1.5 bg-[#121620] border border-[#22293A] px-2.5 py-1 rounded-md">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Сервер: <strong class="text-white">89.248.236.141:27574</strong> (128 Tick)</span>
            </span>
        </div>
    </div>
</footer>

@livewireScripts
</body>
</html>
