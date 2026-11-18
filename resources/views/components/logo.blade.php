@props([
    'size' => 'md',
    'showText' => true,
    'subtitle' => 'COMPETITIVE CS2',
])

@php
$iconSize = match($size) {
    'xs' => 'w-6 h-6',
    'sm' => 'w-8 h-8',
    'md' => 'w-10 h-10',
    'lg' => 'w-14 h-14',
    'xl' => 'w-20 h-20',
    default => 'w-10 h-10',
};

$titleSize = match($size) {
    'xs' => 'text-lg',
    'sm' => 'text-xl',
    'md' => 'text-2xl',
    'lg' => 'text-3xl',
    'xl' => 'text-4xl',
    default => 'text-2xl',
};
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center space-x-3 select-none group']) }}>
    <!-- Vector Blade Emblem -->
    <div class="relative shrink-0 {{ $iconSize }} transition-transform duration-300 group-hover:scale-105">
        <svg viewBox="0 0 200 200" fill="none" class="w-full h-full drop-shadow-[0_0_12px_rgba(255,85,0,0.35)]">
            <defs>
                <linearGradient id="bladeOrg_{{ $size }}" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#FF6B00"/>
                    <stop offset="60%" stop-color="#FF5500"/>
                    <stop offset="100%" stop-color="#D94000"/>
                </linearGradient>
                <linearGradient id="bladeAmb_{{ $size }}" x1="0%" y1="100%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#FF8A00"/>
                    <stop offset="100%" stop-color="#FFA826"/>
                </linearGradient>
                <linearGradient id="bladeDk_{{ $size }}" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#1B2233"/>
                    <stop offset="100%" stop-color="#0E121B"/>
                </linearGradient>
                <linearGradient id="bladeSlv_{{ $size }}" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#FFFFFF"/>
                    <stop offset="60%" stop-color="#CBD5E1"/>
                    <stop offset="100%" stop-color="#64748B"/>
                </linearGradient>
            </defs>

            <!-- Outer Hexagon Frame -->
            <polygon points="100,12 176,56 176,144 100,188 24,144 24,56" fill="url(#bladeDk_{{ $size }})" stroke="#222C3E" stroke-width="3"/>
            
            <!-- Left Blade (Titanium Steel) -->
            <path d="M54 70 L96 34 L96 118 L64 146 L54 136 Z" fill="url(#bladeSlv_{{ $size }})"/>
            <path d="M64 146 L96 118 L96 156 L76 166 Z" fill="#475569"/>

            <!-- Right Interlocking Blade (FACEIT Radiant Orange) -->
            <path d="M146 70 L104 34 L104 126 L136 154 L146 144 Z" fill="url(#bladeOrg_{{ $size }})"/>
            <path d="M104 74 L146 70 L126 128 L104 104 Z" fill="url(#bladeAmb_{{ $size }})"/>
            <path d="M136 154 L104 126 L104 164 L124 174 Z" fill="#B33600"/>

            <!-- Center Core Slit -->
            <polygon points="100,44 108,100 100,148 92,100" fill="#0C0E14"/>
            <polygon points="100,72 105,98 100,120 95,98" fill="#FF5500"/>
            <circle cx="100" cy="98" r="3.5" fill="#FFFFFF"/>
        </svg>
        <div class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-[#FF5500] rounded-full animate-pulse shadow-[0_0_8px_#FF5500]"></div>
    </div>

    <!-- Wordmark & Subtitle -->
    @if($showText)
        <div class="flex flex-col">
            <div class="flex items-center space-x-1 leading-none">
                <span class="font-faceit font-extrabold {{ $titleSize }} tracking-wider text-white uppercase group-hover:text-[#FF5500] transition-colors">
                    RIVALIS
                </span>
                <span class="text-[#FF5500] font-black {{ $titleSize }}">.</span>
                <span class="font-mono text-[10px] text-[#FF5500] bg-[#FF5500]/10 border border-[#FF5500]/30 px-1.5 py-0.5 rounded ml-1 tracking-widest hidden sm:inline-block">
                    1V1
                </span>
            </div>
            @if($subtitle)
                <p class="text-[9px] font-mono tracking-widest text-neutral-400 uppercase -mt-0.5">
                    {{ $subtitle }}
                </p>
            @endif
        </div>
    @endif
</div>
