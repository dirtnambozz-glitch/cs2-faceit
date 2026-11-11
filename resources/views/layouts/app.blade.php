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
                            card: '#120817',
                            cardLight: '#1A0D22',
                            border: 'rgba(255, 0, 85, 0.22)',
                            borderBright: 'rgba(255, 0, 85, 0.55)',
                        },
                        faceit: {
                            orange: '#FF0055',
                            orangeHover: '#FF2A6D',
                            dark: '#08040A',
                            card: '#120817',
                            border: 'rgba(255, 0, 85, 0.22)',
                        }
                    },
                    fontFamily: {
                        orbitron: ['Orbitron', 'sans-serif'],
                        rajdhani: ['Rajdhani', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;700;800;900&family=Rajdhani:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #08040A;
            background-image: 
                radial-gradient(circle at 50% -20%, rgba(255, 0, 85, 0.18) 0%, transparent 60%),
                radial-gradient(circle at 100% 40%, rgba(225, 6, 106, 0.08) 0%, transparent 45%),
                radial-gradient(circle at 0% 80%, rgba(255, 26, 117, 0.06) 0%, transparent 50%),
                linear-gradient(rgba(255, 0, 85, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 0, 85, 0.03) 1px, transparent 1px);
            background-size: 100% 100%, 100% 100%, 100% 100%, 48px 48px, 48px 48px;
            background-attachment: fixed;
        }
        .font-faceit { font-family: 'Rajdhani', sans-serif; }
        .font-cyber { font-family: 'Orbitron', 'Rajdhani', sans-serif; }

        .neon-glow {
            box-shadow: 0 0 20px rgba(255, 0, 85, 0.45);
        }
        .neon-glow-lg {
            box-shadow: 0 0 35px rgba(255, 0, 85, 0.65), 0 0 10px rgba(255, 0, 85, 0.4);
        }
        .neon-text {
            text-shadow: 0 0 12px rgba(255, 0, 85, 0.8), 0 0 24px rgba(255, 0, 85, 0.4);
        }
        .neon-border {
            border: 1px solid rgba(255, 0, 85, 0.35);
            box-shadow: inset 0 0 15px rgba(255, 0, 85, 0.08);
        }
        .cyber-clip {
            clip-path: polygon(0 0, calc(100% - 14px) 0, 100% 14px, 100% 100%, 14px 100%, 0 calc(100% - 14px));
        }
        .cyber-clip-sm {
            clip-path: polygon(0 0, calc(100% - 8px) 0, 100% 8px, 100% 100%, 8px 100%, 0 calc(100% - 8px));
        }
        .cyber-btn {
            clip-path: polygon(12px 0, 100% 0, 100% calc(100% - 12px), calc(100% - 12px) 100%, 0 100%, 0 12px);
        }
    </style>
    @livewireStyles
</head>
<body class="bg-[#08040A] text-neutral-100 min-h-screen flex flex-col antialiased selection:bg-[#FF0055] selection:text-white">

<!-- Top Cyberpunk Accent Line -->
<div class="h-1 w-full bg-gradient-to-r from-transparent via-[#FF0055] to-transparent shadow-[0_0_15px_#FF0055]"></div>

<!-- Header -->
<header class="border-b border-[#FF0055]/20 bg-[#08040A]/85 backdrop-blur-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
        <!-- Logo & Brand -->
        <div class="flex items-center space-x-6">
            <a href="{{ auth()->check() ? route('home') : route('leaderboard') }}" class="flex items-center space-x-3 group">
                <div class="relative">
                    <img src="/images/rivalis-logo.jpg" alt="RIVALIS Logo" class="w-11 h-11 rounded-full object-cover border-2 border-[#FF0055] shadow-[0_0_15px_rgba(255,0,85,0.6)] group-hover:scale-105 transition-transform">
                    <div class="absolute -top-1 -right-1 w-3 h-3 bg-[#FF0055] rounded-full animate-ping"></div>
                </div>
                <div>
                    <span class="font-cyber font-extrabold text-2xl text-white tracking-widest uppercase group-hover:text-[#FF0055] transition-colors">
                        RIVALIS
                    </span>
                    <p class="text-[10px] font-mono tracking-widest text-neutral-400 uppercase hidden sm:block -mt-0.5">
                        CYBER ESPORTS PLATFORM
                    </p>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center space-x-2 pl-4 border-l border-[#FF0055]/20">
                @auth
                <a href="{{ route('home') }}" class="px-4 py-2 rounded-lg text-sm font-faceit font-bold uppercase tracking-wider {{ request()->routeIs('home') ? 'text-white bg-[#FF0055]/20 border border-[#FF0055] shadow-[0_0_15px_rgba(255,0,85,0.35)]' : 'text-neutral-300 hover:text-white hover:bg-[#FF0055]/10' }} transition">
                    Матчмейкинг
                </a>
                @endauth
                <a href="{{ route('leaderboard') }}" class="px-4 py-2 rounded-lg text-sm font-faceit font-bold uppercase tracking-wider {{ request()->routeIs('leaderboard') ? 'text-white bg-[#FF0055]/20 border border-[#FF0055] shadow-[0_0_15px_rgba(255,0,85,0.35)]' : 'text-neutral-300 hover:text-white hover:bg-[#FF0055]/10' }} transition">
                    Лидерборд
                </a>
                @auth
                <a href="{{ route('server.settings') }}" class="px-4 py-2 rounded-lg text-sm font-faceit font-bold uppercase tracking-wider {{ request()->routeIs('server.settings') ? 'text-white bg-[#FF0055]/20 border border-[#FF0055] shadow-[0_0_15px_rgba(255,0,85,0.35)]' : 'text-neutral-300 hover:text-white hover:bg-[#FF0055]/10' }} transition">
                    Сервер
                </a>
                @endauth
            </nav>
        </div>

        <!-- Right Side User HUD / Auth -->
        <div class="flex items-center space-x-3">
            @auth
                @if(!auth()->user()->steam_id)
                <a href="{{ route('profile') }}" class="hidden sm:flex items-center space-x-1.5 bg-[#FF0055]/15 border border-[#FF0055]/40 text-[#FF0055] px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-[#FF0055]/25 transition shadow-[0_0_10px_rgba(255,0,85,0.25)]">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Привяжи Steam!</span>
                </a>
                @endif
                <a href="{{ route('profile') }}" class="flex items-center space-x-3 bg-[#120817] border border-[#FF0055]/30 rounded-xl px-3.5 py-1.5 hover:border-[#FF0055] hover:shadow-[0_0_15px_rgba(255,0,85,0.35)] transition group">
                    <img src="{{ auth()->user()->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed='.auth()->user()->name }}" class="w-8 h-8 rounded-full bg-neutral-900 border border-[#FF0055]/40" alt="">
                    <div class="hidden sm:block text-right">
                        <div class="text-xs font-semibold text-white leading-tight group-hover:text-[#FF0055] transition-colors">{{ auth()->user()->name }}</div>
                        <div class="text-[11px] font-mono text-neutral-400">{{ auth()->user()->elo }} <span class="text-[#FF0055] font-bold">ELO</span></div>
                    </div>
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center font-faceit font-bold text-sm bg-gradient-to-br from-[#FF0055] to-[#E1066A] text-white shadow-[0_0_8px_rgba(255,0,85,0.5)]">
                        {{ auth()->user()->level }}
                    </div>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-3 py-2 text-xs font-faceit font-bold uppercase tracking-wider text-neutral-400 hover:text-white bg-[#120817] border border-[#FF0055]/20 rounded-xl hover:border-red-500 hover:bg-red-500/10 transition cursor-pointer">
                        Выйти
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-faceit font-bold uppercase tracking-wider text-neutral-200 hover:text-white bg-[#120817] rounded-xl border border-[#FF0055]/30 hover:border-[#FF0055] transition">Войти</a>
                <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-faceit font-bold uppercase tracking-wider text-white bg-gradient-to-r from-[#FF0055] to-[#E1066A] hover:brightness-110 rounded-xl shadow-[0_0_20px_rgba(255,0,85,0.4)] transition">Регистрация</a>
            @endauth
        </div>
    </div>
</header>

@if(session('warning'))
<div class="bg-[#FF0055]/15 border-b border-[#FF0055]/30 py-2.5 px-4 text-center text-xs text-[#FF2A6D] font-semibold tracking-wide">
    {{ session('warning') }}
</div>
@endif

<main class="flex-1 max-w-7xl w-full mx-auto px-4 py-8">
    {{ $slot }}
</main>

<footer class="border-t border-[#FF0055]/20 bg-[#08040A]/95 py-8 text-center text-xs text-neutral-500">
    <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center space-x-2">
            <span class="font-cyber font-bold text-white text-sm tracking-wider">RIVALIS</span>
            <span class="text-[#FF0055]">●</span>
            <span class="text-neutral-400">CS2 1v1 Competitive Matchmaking Platform</span>
        </div>
        <div class="text-neutral-400 text-[11px] font-mono">
            Учебная практика (УП) — Игровой сервер: <span class="text-[#FF0055] font-semibold">89.248.236.141:27574</span>
        </div>
    </div>
</footer>

@livewireScripts
</body>
</html>
