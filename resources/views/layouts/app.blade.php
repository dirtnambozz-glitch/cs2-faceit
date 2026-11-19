<!DOCTYPE html>
<html lang="ru" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'RIVALIS — CS2 1v1 Competitive Arena' }}</title>
    <link rel="icon" type="image/jpeg" href="/images/rivalis-logo.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff5ed',
                            100: '#ffe8d5',
                            200: '#ffd0aa',
                            300: '#ffb074',
                            400: '#ff8539',
                            500: '#FF5500',
                            600: '#eb4400',
                            700: '#c23300',
                            800: '#9b2a05',
                            900: '#7c2509',
                        },
                        obsidian: {
                            950: '#07090D',
                            900: '#0B0E14',
                            850: '#0F131C',
                            800: '#131924',
                            750: '#18202E',
                            700: '#1E2738',
                            600: '#2A364D',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                        heading: ['"Space Grotesk"', '"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: dark;
        }
        body { 
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif; 
            background-color: #07090D;
            background-image: 
                radial-gradient(ellipse at 50% -20%, rgba(255, 85, 0, 0.08) 0%, transparent 60%),
                radial-gradient(ellipse at 85% 15%, rgba(42, 54, 77, 0.25) 0%, transparent 50%),
                radial-gradient(ellipse at 15% 85%, rgba(255, 85, 0, 0.03) 0%, transparent 40%);
            background-attachment: fixed;
        }
        .font-mono {
            font-variant-numeric: tabular-nums;
        }
        /* Custom Clean Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #07090D;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #FF5500;
        }
    </style>
    @livewireStyles
</head>
<body class="bg-[#07090D] text-neutral-200 min-h-screen flex flex-col antialiased selection:bg-[#FF5500] selection:text-white">

<!-- Subtle Ambient Accent Line -->
<div class="h-[2px] w-full bg-gradient-to-r from-transparent via-[#FF5500] to-transparent opacity-80 sticky top-0 z-50"></div>

<!-- Top Navigation Bar -->
<header class="border-b border-white/[0.06] bg-[#0B0E14]/85 backdrop-blur-md sticky top-[2px] z-40 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
        <!-- Brand & Nav -->
        <div class="flex items-center space-x-8">
            <a href="{{ auth()->check() ? route('home') : route('leaderboard') }}" class="flex items-center space-x-3 group">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#FF5500] to-[#CC3A00] flex items-center justify-center shadow-[0_2px_12px_rgba(255,85,0,0.35)] transition-transform group-hover:scale-105">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="flex items-baseline space-x-1.5">
                    <span class="font-heading font-bold text-xl text-white tracking-tight">RIVALIS</span>
                    <span class="text-[10px] font-mono font-bold uppercase text-[#FF5500] tracking-wider bg-[#FF5500]/10 px-1.5 py-0.5 rounded border border-[#FF5500]/20">1v1</span>
                </div>
            </a>

            <!-- Nav Links -->
            <nav class="hidden md:flex items-center space-x-1">
                @auth
                <a href="{{ route('home') }}" class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('home') ? 'text-white bg-white/[0.08] shadow-sm font-semibold' : 'text-neutral-400 hover:text-white hover:bg-white/[0.04]' }}">
                    Матчмейкинг
                </a>
                @endauth

                <a href="{{ route('leaderboard') }}" class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('leaderboard') ? 'text-white bg-white/[0.08] shadow-sm font-semibold' : 'text-neutral-400 hover:text-white hover:bg-white/[0.04]' }}">
                    Таблица лидеров
                </a>

                @auth
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition-all flex items-center space-x-1.5 {{ request()->routeIs('admin.dashboard') || request()->routeIs('server.settings') ? 'text-white bg-white/[0.08] font-semibold' : 'text-neutral-400 hover:text-white hover:bg-white/[0.04]' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#FF5500]"></span>
                    <span>Управление</span>
                </a>
                @endif
                @endauth
            </nav>
        </div>

        <!-- Right Side: Server Telemetry + User Controls -->
        <div class="flex items-center space-x-4">
            <!-- Server Live Pill -->
            <div class="hidden lg:flex items-center space-x-2 px-3 py-1 rounded-full bg-white/[0.03] border border-white/[0.06] text-xs font-mono text-neutral-400">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Server 1v1</span>
                <span class="text-neutral-600">•</span>
                <span class="text-neutral-300">Tick 128</span>
            </div>

            @auth
                @if(!auth()->user()->steam_id)
                <a href="{{ route('profile') }}" class="hidden sm:inline-flex items-center space-x-1.5 bg-amber-500/10 border border-amber-500/30 text-amber-300 px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-amber-500/20 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Привяжите Steam</span>
                </a>
                @endif

                <!-- User Profile Pill -->
                <a href="{{ route('profile') }}" class="flex items-center space-x-3 bg-white/[0.03] hover:bg-white/[0.06] border border-white/[0.08] hover:border-white/[0.16] rounded-xl px-2.5 py-1.5 transition group">
                    <img src="{{ auth()->user()->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed='.auth()->user()->name }}" class="w-7 h-7 rounded-lg bg-neutral-900 border border-white/[0.1] object-cover" alt="">
                    <div class="text-left leading-tight hidden sm:block">
                        <div class="text-xs font-semibold text-white group-hover:text-[#FF5500] transition-colors flex items-center space-x-1.5">
                            <span>{{ auth()->user()->name }}</span>
                            @if(auth()->user()->isAdmin())
                                <span class="text-[9px] font-mono font-bold bg-[#FF5500] text-white px-1 rounded">ADM</span>
                            @endif
                        </div>
                        <div class="text-[11px] font-mono text-neutral-400">
                            <span class="text-white font-semibold tabular-nums">{{ auth()->user()->elo }}</span> <span class="text-neutral-500 text-[10px]">ELO</span>
                        </div>
                    </div>
                    <!-- Level badge -->
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center font-heading font-bold text-xs bg-gradient-to-br from-[#FF5500] to-[#D44400] text-white shadow-sm">
                        {{ auth()->user()->level }}
                    </div>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Выйти" class="p-2 text-neutral-400 hover:text-white hover:bg-white/[0.05] rounded-lg transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-semibold uppercase tracking-wider text-neutral-300 hover:text-white hover:bg-white/[0.05] rounded-lg transition">Войти</a>
                <a href="{{ route('register') }}" class="px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white bg-[#FF5500] hover:bg-[#FF6E26] rounded-lg shadow-sm transition">Регистрация</a>
            @endauth
        </div>
    </div>
</header>

@if(session('warning'))
<div class="bg-amber-500/10 border-b border-amber-500/20 py-2.5 px-4 text-center text-xs text-amber-200 font-medium">
    ⚠️ {{ session('warning') }}
</div>
@endif

<!-- Main Content Area -->
<main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 py-8">
    {{ $slot }}
</main>

<!-- Minimal Professional Esports Footer -->
<footer class="border-t border-white/[0.06] bg-[#07090D] py-8 text-neutral-500 text-xs mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <span class="font-heading font-bold text-white tracking-tight text-sm">RIVALIS</span>
            <span class="text-neutral-700">•</span>
            <span>Competitive CS2 1v1 Platform</span>
        </div>
        <div class="flex items-center space-x-4 font-mono text-[11px] text-neutral-400">
            <div class="flex items-center space-x-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>Server: <span class="text-neutral-200 font-medium">89.248.236.141:27574</span></span>
            </div>
            <span class="text-neutral-700">•</span>
            <span>Valve VAC Secured</span>
        </div>
    </div>
</footer>

@livewireScripts
</body>
</html>
