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
                        rivalis: {
                            pink: '#FF0055',
                            pinkHover: '#FF2A6D',
                            pinkDark: '#C2003F',
                            neon: '#FF1A75',
                            magenta: '#E1066A',
                            dark: '#08040A',
                            darker: '#050207',
                            card: '#0F0716',
                            cardHover: '#160B20',
                            border: 'rgba(255, 0, 85, 0.22)',
                            borderBright: 'rgba(255, 0, 85, 0.55)',
                        }
                    },
                    fontFamily: {
                        orbitron: ['Orbitron', 'sans-serif'],
                        rajdhani: ['Rajdhani', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700;800&family=Orbitron:wght@600;700;800;900&family=Rajdhani:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: dark;
        }
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; 
            background-color: #060309;
            background-image: 
                radial-gradient(circle at 50% -10%, rgba(255, 0, 85, 0.22) 0%, transparent 55%),
                radial-gradient(circle at 95% 30%, rgba(225, 6, 106, 0.10) 0%, transparent 45%),
                radial-gradient(circle at 5% 75%, rgba(255, 26, 117, 0.08) 0%, transparent 50%),
                linear-gradient(rgba(255, 0, 85, 0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 0, 85, 0.025) 1px, transparent 1px);
            background-size: 100% 100%, 100% 100%, 100% 100%, 40px 40px, 40px 40px;
            background-attachment: fixed;
        }
        .font-faceit { font-family: 'Rajdhani', sans-serif; }
        .font-cyber { font-family: 'Orbitron', 'Rajdhani', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; font-variant-numeric: tabular-nums; }

        .neon-glow {
            box-shadow: 0 0 25px rgba(255, 0, 85, 0.45);
        }
        .neon-glow-lg {
            box-shadow: 0 0 40px rgba(255, 0, 85, 0.65), 0 0 15px rgba(255, 0, 85, 0.4);
        }
        .neon-border {
            border: 1px solid rgba(255, 0, 85, 0.35);
            box-shadow: inset 0 0 15px rgba(255, 0, 85, 0.08);
        }

        /* Scanline effect (subtle) */
        .scanlines {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            pointer-events: none;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%);
            background-size: 100% 4px;
            z-index: 999;
            opacity: 0.25;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #08040A;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 0, 85, 0.4);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #FF0055;
        }
    </style>
    @livewireStyles
</head>
<body class="bg-[#060309] text-neutral-100 min-h-screen flex flex-col antialiased selection:bg-[#FF0055] selection:text-white relative">

<div class="scanlines"></div>

<!-- Top Cyberpunk Accent Line -->
<div class="h-1 w-full bg-gradient-to-r from-transparent via-[#FF0055] to-transparent shadow-[0_0_20px_#FF0055] sticky top-0 z-50"></div>

<!-- Header -->
<header class="border-b border-[#FF0055]/25 bg-[#08040A]/90 backdrop-blur-xl sticky top-1 z-40 transition-all">
    <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
        <!-- Logo & Brand -->
        <div class="flex items-center space-x-6">
            <a href="{{ auth()->check() ? route('home') : route('leaderboard') }}" class="flex items-center space-x-3.5 group">
                <div class="relative">
                    <img src="/images/rivalis-logo.jpg" alt="RIVALIS Logo" class="w-11 h-11 rounded-2xl object-cover border-2 border-[#FF0055] shadow-[0_0_20px_rgba(255,0,85,0.7)] group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-400 rounded-full animate-ping"></div>
                    <div class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-400 rounded-full shadow-[0_0_8px_#34d399]"></div>
                </div>
                <div>
                    <span class="font-cyber font-black text-2xl text-white tracking-widest uppercase group-hover:text-[#FF2A6D] transition-colors flex items-center space-x-1">
                        <span>RIVALIS</span>
                        <span class="text-[#FF0055] text-xs font-mono">1v1</span>
                    </span>
                    <p class="text-[9px] font-mono tracking-widest text-[#FF0055]/80 uppercase hidden sm:block -mt-1 font-bold">
                        COMPETITIVE CS2 MATCHMAKING
                    </p>
                </div>
            </a>

            <!-- Navigation Links (Pill Tabs) -->
            <nav class="hidden md:flex items-center space-x-2 pl-5 border-l border-white/10">
                @auth
                <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl text-xs font-cyber font-bold uppercase tracking-wider transition-all duration-200 {{ request()->routeIs('home') ? 'text-white bg-gradient-to-r from-[#FF0055] to-[#E1066A] shadow-[0_0_20px_rgba(255,0,85,0.5)] border border-white/20' : 'text-neutral-400 hover:text-white hover:bg-white/5' }}">
                    Матчмейкинг
                </a>
                @endauth

                <a href="{{ route('leaderboard') }}" class="px-4 py-2 rounded-xl text-xs font-cyber font-bold uppercase tracking-wider transition-all duration-200 {{ request()->routeIs('leaderboard') ? 'text-white bg-gradient-to-r from-[#FF0055] to-[#E1066A] shadow-[0_0_20px_rgba(255,0,85,0.5)] border border-white/20' : 'text-neutral-400 hover:text-white hover:bg-white/5' }}">
                    Лидерборд
                </a>

                @auth
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl text-xs font-cyber font-bold uppercase tracking-wider flex items-center space-x-2 transition-all duration-200 {{ request()->routeIs('admin.dashboard') || request()->routeIs('server.settings') ? 'text-white bg-gradient-to-r from-[#FF0055] to-[#E1066A] shadow-[0_0_20px_rgba(255,0,85,0.5)] border border-white/20' : 'text-[#FF2A6D] hover:text-white hover:bg-[#FF0055]/15 border border-[#FF0055]/30' }}">
                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
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
                <a href="{{ route('profile') }}" class="hidden sm:flex items-center space-x-2 bg-gradient-to-r from-amber-500/20 to-amber-600/20 border border-amber-500/50 text-amber-300 px-3.5 py-2 rounded-xl text-xs font-mono font-bold hover:brightness-125 transition shadow-[0_0_15px_rgba(245,158,11,0.25)] animate-pulse">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Привяжи Steam!</span>
                </a>
                @endif

                <!-- User Profile Pill -->
                <a href="{{ route('profile') }}" class="flex items-center space-x-3 bg-[#0F0716] border border-[#FF0055]/30 rounded-2xl px-3.5 py-1.5 hover:border-[#FF0055] hover:shadow-[0_0_20px_rgba(255,0,85,0.35)] transition-all group">
                    <img src="{{ auth()->user()->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed='.auth()->user()->name }}" class="w-9 h-9 rounded-xl bg-neutral-900 border border-[#FF0055]/40 object-cover" alt="">
                    <div class="hidden sm:block text-right">
                        <div class="text-xs font-cyber font-bold text-white leading-tight group-hover:text-[#FF2A6D] transition-colors flex items-center justify-end space-x-1.5">
                            <span>{{ auth()->user()->name }}</span>
                            @if(auth()->user()->isAdmin())
                                <span class="text-[8px] font-mono font-extrabold bg-[#FF0055] text-white px-1.5 py-0.2 rounded shadow">ADMIN</span>
                            @endif
                        </div>
                        <div class="text-[11px] font-mono text-neutral-400"><span class="text-emerald-400 font-bold">{{ auth()->user()->elo }}</span> <span class="text-[#FF0055] text-[10px]">ELO</span></div>
                    </div>
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center font-cyber font-black text-sm bg-gradient-to-br from-[#FF0055] to-[#990033] text-white shadow-[0_0_12px_rgba(255,0,85,0.6)] border border-[#FF0055]/60">
                        {{ auth()->user()->level }}
                    </div>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Выйти из аккаунта" class="px-3 py-2 text-xs font-cyber font-bold uppercase tracking-wider text-neutral-400 hover:text-white bg-[#0F0716] border border-white/10 rounded-xl hover:border-red-500 hover:bg-red-500/10 transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="px-5 py-2.5 text-xs font-cyber font-bold uppercase tracking-wider text-neutral-200 hover:text-white bg-[#0F0716] rounded-xl border border-white/15 hover:border-[#FF0055] transition">Войти</a>
                <a href="{{ route('register') }}" class="px-6 py-2.5 text-xs font-cyber font-extrabold uppercase tracking-wider text-white bg-gradient-to-r from-[#FF0055] to-[#E1066A] hover:brightness-110 rounded-xl shadow-[0_0_25px_rgba(255,0,85,0.5)] transition border border-white/20">Регистрация</a>
            @endauth
        </div>
    </div>
</header>

@if(session('warning'))
<div class="bg-gradient-to-r from-[#FF0055]/20 via-[#FF0055]/30 to-[#FF0055]/20 border-b border-[#FF0055]/50 py-3 px-4 text-center text-xs text-white font-mono font-semibold tracking-wider shadow-[0_0_15px_rgba(255,0,85,0.3)]">
    ⚠️ {{ session('warning') }}
</div>
@endif

<main class="flex-1 max-w-7xl w-full mx-auto px-4 py-8">
    {{ $slot }}
</main>

<footer class="border-t border-[#FF0055]/20 bg-[#08040A]/95 py-8 text-center text-xs text-neutral-500 relative z-10">
    <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center space-x-2.5">
            <span class="font-cyber font-black text-white text-base tracking-wider">RIVALIS</span>
            <span class="text-[#FF0055]">●</span>
            <span class="text-neutral-400 font-mono text-[11px]">CS2 1v1 Competitive Esports Platform</span>
        </div>
        <div class="text-neutral-400 text-[11px] font-mono flex items-center space-x-2">
            <span class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_6px_#34d399]"></span>
            <span>Dedicated Server: <span class="text-[#FF0055] font-bold">89.248.236.141:27574</span></span>
        </div>
    </div>
</footer>

@livewireScripts
</body>
</html>
