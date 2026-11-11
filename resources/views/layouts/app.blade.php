<!DOCTYPE html>
<html lang="ru" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'RIVALIS — CS2 1v1 Matchmaking' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        faceit: {
                            orange: '#FF5500',
                            orangeHover: '#E04B00',
                            dark: '#0E0E10',
                            card: '#18181B',
                            border: '#27272A',
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0E0E10; }
        .font-faceit { font-family: 'Rajdhani', sans-serif; }
    </style>
    @livewireStyles
</head>
<body class="bg-faceit-dark text-neutral-100 min-h-screen flex flex-col">

<header class="border-b border-faceit-border bg-faceit-card/80 backdrop-blur sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center space-x-6">
            <a href="{{ auth()->check() ? route('home') : route('leaderboard') }}" class="flex items-center space-x-2">
                <div class="w-9 h-9 bg-[#FF5500] flex items-center justify-center rounded font-faceit font-bold text-2xl text-black">R</div>
                <span class="font-faceit text-xl font-bold text-white tracking-wide">RIVALIS <span class="text-[#FF5500]">CS2</span></span>
                <span class="text-[10px] uppercase tracking-widest text-neutral-400 bg-neutral-800 px-1.5 py-0.5 rounded">1v1</span>
            </a>
            <nav class="hidden md:flex items-center space-x-1">
                @auth
                <a href="{{ route('home') }}" class="px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('home') ? 'text-[#FF5500] bg-neutral-800' : 'text-neutral-300 hover:text-white hover:bg-neutral-800/60' }} transition">Матчмейкинг</a>
                @endauth
                <a href="{{ route('leaderboard') }}" class="px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('leaderboard') ? 'text-[#FF5500] bg-neutral-800' : 'text-neutral-300 hover:text-white hover:bg-neutral-800/60' }} transition">Лидерборд</a>
                @auth
                <a href="{{ route('server.settings') }}" class="px-3 py-2 rounded text-sm font-medium flex items-center space-x-1 {{ request()->routeIs('server.settings') ? 'text-[#FF5500] bg-neutral-800' : 'text-neutral-300 hover:text-white hover:bg-neutral-800/60' }} transition">
                    <span class="w-2 h-2 rounded-full {{ config('services.cs2.server_ip') ? 'bg-emerald-500 animate-pulse' : 'bg-neutral-500' }}"></span>
                    <span>Сервер</span>
                </a>
                @endauth
            </nav>
        </div>

        <!-- Right side -->
        <div class="flex items-center space-x-3">
            @auth
                @if(!auth()->user()->steam_id)
                <a href="{{ route('profile') }}" class="hidden sm:flex items-center space-x-1 bg-amber-500/10 border border-amber-500/30 text-amber-400 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-amber-500/20 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Привяжи Steam!</span>
                </a>
                @endif
                <a href="{{ route('profile') }}" class="flex items-center space-x-2 bg-neutral-900 border border-neutral-800 rounded-lg px-3 py-1.5 hover:border-[#FF5500]/50 transition group">
                    <img src="{{ auth()->user()->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed='.auth()->user()->name }}" class="w-8 h-8 rounded-full bg-neutral-800" alt="">
                    <div class="hidden sm:block text-right">
                        <div class="text-xs font-semibold text-white leading-tight">{{ auth()->user()->name }}</div>
                        <div class="text-[11px] font-mono text-neutral-400">{{ auth()->user()->elo }} <span class="text-[#FF5500]">ELO</span></div>
                    </div>
                    <div class="w-7 h-7 rounded-full flex items-center justify-center font-faceit font-bold text-sm {{ auth()->user()->level_badge_color }}">
                        {{ auth()->user()->level }}
                    </div>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-3 py-2 text-xs font-semibold text-neutral-400 hover:text-white bg-neutral-900 border border-neutral-800 rounded-lg hover:border-red-600/40 transition cursor-pointer">
                        Выйти
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-neutral-200 hover:text-white bg-neutral-800 rounded-lg border border-neutral-700 hover:border-neutral-600 transition">Войти</a>
                <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-semibold text-white bg-[#FF5500] hover:bg-[#E04B00] rounded-lg shadow transition">Регистрация</a>
            @endauth
        </div>
    </div>
</header>

@if(session('warning'))
<div class="bg-amber-500/10 border-b border-amber-500/30 py-2 px-4 text-center text-xs text-amber-400 font-semibold">
    {{ session('warning') }}
</div>
@endif

<main class="flex-1 max-w-7xl w-full mx-auto px-4 py-8">
    {{ $slot }}
</main>

<footer class="border-t border-faceit-border/50 py-6 text-center text-xs text-neutral-500">
    RIVALIS CS2 1v1 Matchmaking Platform — Учебная практика (УП) — Сервер: UP FaceIt (89.248.236.141:27574)
</footer>

@livewireScripts
</body>
</html>
