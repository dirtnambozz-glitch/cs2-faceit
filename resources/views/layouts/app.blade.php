<!DOCTYPE html>
<html lang="ru" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'NEXUS ARENA — CS2 Competitive Platform' }}</title>
    <link rel="icon" type="image/jpeg" href="/images/rivalis-logo.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        nexus: {
                            orange: '#FF5500',
                            orangeHover: '#FF6A00',
                            orangeDark: '#D44400',
                            bg: '#0A0C0F',
                            card: '#12151B',
                            cardElevated: '#161A22',
                            cardBorder: 'rgba(255, 255, 255, 0.07)',
                            cardBorderHover: 'rgba(255, 85, 0, 0.4)',
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
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: dark;
        }
        body { 
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif; 
            background-color: #0A0C0F;
            background-image: 
                radial-gradient(ellipse at 105% 50%, rgba(255, 85, 0, 0.14) 0%, transparent 45%),
                radial-gradient(ellipse at -5% 50%, rgba(255, 85, 0, 0.10) 0%, transparent 45%),
                radial-gradient(ellipse at 50% 0%, rgba(255, 85, 0, 0.06) 0%, transparent 50%);
            background-attachment: fixed;
            min-height: 100vh;
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
            background: #0A0C0F;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #FF5500;
        }
    </style>
    @livewireStyles
</head>
<body class="text-neutral-200 min-h-screen flex flex-col antialiased selection:bg-[#FF5500] selection:text-white p-3 sm:p-5 lg:p-7">

<!-- Master Esports App Container (As in Screenshot) -->
<div class="max-w-[1440px] w-full mx-auto bg-[#0E1116] border border-white/[0.08] rounded-[28px] shadow-[0_20px_60px_rgba(0,0,0,0.8)] overflow-hidden flex flex-col flex-1">

    <!-- Top Master Header Bar (One-in-One with Screenshot) -->
    <header class="border-b border-white/[0.06] bg-[#11141B] px-6 lg:px-8 h-20 flex items-center justify-between shrink-0">
        <!-- Left: Nexus Arena Logo -->
        <a href="{{ auth()->check() ? route('home') : route('leaderboard') }}" class="flex items-center space-x-3.5 group">
            <!-- Orange Chevron Logo Mark -->
            <div class="w-10 h-10 flex items-center justify-center">
                <svg class="w-9 h-9 text-[#FF5500] group-hover:scale-105 transition-transform" viewBox="0 0 40 40" fill="none">
                    <path d="M8 8L16 20L8 32H14L22 20L14 8H8Z" fill="#FF5500"/>
                    <path d="M26 8L18 20L26 32H32L24 20L32 8H26Z" fill="#FF7728"/>
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="font-heading font-black text-xl lg:text-2xl text-white tracking-wider uppercase leading-none">
                    NEXUS ARENA
                </span>
                <span class="text-[9px] font-mono tracking-widest text-[#FF5500] uppercase font-semibold mt-0.5">
                    RIVALIS ENGINE
                </span>
            </div>
        </a>

        <!-- Center: Primary Navigation Tabs -->
        <nav class="hidden md:flex items-center space-x-8 h-full">
            <a href="{{ route('home') }}" class="h-full flex items-center text-xs lg:text-sm font-heading font-bold uppercase tracking-wider transition-colors relative {{ request()->routeIs('home') ? 'text-[#FF5500]' : 'text-neutral-400 hover:text-white' }}">
                <span>PLAY</span>
                @if(request()->routeIs('home'))
                    <span class="absolute bottom-0 left-0 right-0 h-[3px] bg-[#FF5500] rounded-t-full shadow-[0_0_12px_#FF5500]"></span>
                @endif
            </a>

            <a href="{{ route('leaderboard') }}" class="h-full flex items-center text-xs lg:text-sm font-heading font-bold uppercase tracking-wider transition-colors relative {{ request()->routeIs('leaderboard') ? 'text-[#FF5500]' : 'text-neutral-400 hover:text-white' }}">
                <span>LADDERS</span>
                @if(request()->routeIs('leaderboard'))
                    <span class="absolute bottom-0 left-0 right-0 h-[3px] bg-[#FF5500] rounded-t-full shadow-[0_0_12px_#FF5500]"></span>
                @endif
            </a>

            <a href="{{ route('home') }}" class="h-full flex items-center text-xs lg:text-sm font-heading font-bold uppercase tracking-wider text-neutral-400 hover:text-white transition-colors">
                <span>TOURNAMENTS</span>
            </a>

            <a href="{{ route('home') }}" class="h-full flex items-center text-xs lg:text-sm font-heading font-bold uppercase tracking-wider text-neutral-400 hover:text-white transition-colors">
                <span>HUBS</span>
            </a>

            @auth
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="h-full flex items-center text-xs lg:text-sm font-heading font-bold uppercase tracking-wider transition-colors relative {{ request()->routeIs('admin.dashboard') || request()->routeIs('server.settings') ? 'text-[#FF5500]' : 'text-neutral-400 hover:text-white' }}">
                <span>ANTI-CHEAT</span>
                @if(request()->routeIs('admin.dashboard') || request()->routeIs('server.settings'))
                    <span class="absolute bottom-0 left-0 right-0 h-[3px] bg-[#FF5500] rounded-t-full shadow-[0_0_12px_#FF5500]"></span>
                @endif
            </a>
            @else
            <a href="{{ route('profile') }}" class="h-full flex items-center text-xs lg:text-sm font-heading font-bold uppercase tracking-wider text-neutral-400 hover:text-white transition-colors">
                <span>ANTI-CHEAT</span>
            </a>
            @endif
            @endauth
        </nav>

        <!-- Right: Server Online Pill + User Profile Badge -->
        <div class="flex items-center space-x-4">
            <!-- Online Counter Pill -->
            <div class="hidden sm:inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-[#161A22] border border-white/[0.06] text-xs font-mono text-emerald-400 shadow-inner">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_#34d399]"></span>
                <span class="font-bold text-neutral-200">142,890</span>
                <span class="text-neutral-400 uppercase text-[10px] tracking-wider">PLAYERS ONLINE</span>
            </div>

            @auth
                <!-- User Profile Widget (Exact circular ring from screenshot) -->
                <a href="{{ route('profile') }}" class="flex items-center space-x-3 group cursor-pointer bg-white/[0.02] hover:bg-white/[0.05] p-1.5 pr-3 rounded-2xl border border-white/[0.06] hover:border-white/[0.12] transition">
                    <div class="relative">
                        <!-- Orange Glowing Ring around circular avatar -->
                        <div class="w-11 h-11 rounded-full p-[2px] bg-gradient-to-tr from-[#FF5500] via-[#FF7728] to-[#FF3300] shadow-[0_0_12px_rgba(255,85,0,0.5)]">
                            <img src="{{ auth()->user()->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed='.auth()->user()->name }}" class="w-full h-full rounded-full bg-neutral-900 object-cover" alt="">
                        </div>
                        <!-- Hexagon / Star rank badge at bottom -->
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-[#FF5500] text-white flex items-center justify-center font-heading font-black text-[10px] border border-white/20 shadow">
                            {{ auth()->user()->level }}
                        </div>
                    </div>
                    <div class="text-left leading-tight hidden sm:block">
                        <div class="font-heading font-black text-xs text-white uppercase tracking-wider group-hover:text-[#FF5500] transition-colors">
                            LVL {{ auth()->user()->level }}
                        </div>
                        <div class="text-[11px] font-mono text-neutral-400 mt-0.5">
                            <span class="text-white font-bold tabular-nums">{{ number_format(auth()->user()->elo) }}</span> ELO
                        </div>
                    </div>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                    @csrf
                    <button type="submit" title="Выйти" class="p-2 text-neutral-500 hover:text-white transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-heading font-bold uppercase tracking-wider text-neutral-300 hover:text-white bg-white/[0.04] rounded-xl border border-white/[0.08] transition">Войти</a>
                <a href="{{ route('register') }}" class="px-4 py-2 text-xs font-heading font-bold uppercase tracking-wider text-white bg-[#FF5500] hover:bg-[#FF6A00] rounded-xl shadow-sm transition">Регистрация</a>
            @endauth
        </div>
    </header>

    @if(session('warning'))
    <div class="bg-amber-500/10 border-b border-amber-500/20 py-2.5 px-6 text-center text-xs text-amber-200 font-medium">
        ⚠️ {{ session('warning') }}
    </div>
    @endif

    <!-- Content View -->
    <main class="flex-1 p-4 sm:p-6 lg:p-7 overflow-y-auto">
        {{ $slot }}
    </main>
</div>

@livewireScripts
</body>
</html>
