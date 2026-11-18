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
    <!-- New Kinetic Blade R-Mark -->
    <div class="relative shrink-0 {{ $iconSize }} transition-transform duration-300 group-hover:scale-105">
        <svg viewBox="0 0 200 200" fill="none" class="w-full h-full drop-shadow-[0_0_14px_rgba(255,85,0,0.4)]">
            <defs>
                <linearGradient id="rivOrg_{{ $size }}" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#FFA028"/>
                    <stop offset="45%" stop-color="#FF5500"/>
                    <stop offset="100%" stop-color="#C93400"/>
                </linearGradient>
                <linearGradient id="rivFlare_{{ $size }}" x1="0%" y1="100%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#FF7700"/>
                    <stop offset="100%" stop-color="#FFC043"/>
                </linearGradient>
                <linearGradient id="rivSteel_{{ $size }}" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#FFFFFF"/>
                    <stop offset="35%" stop-color="#CBD5E1"/>
                    <stop offset="75%" stop-color="#64748B"/>
                    <stop offset="100%" stop-color="#334155"/>
                </linearGradient>
            </defs>

            <!-- 1. Left Backbone Stem (Titanium Bevel) -->
            <path d="M42 32 L78 32 L60 172 L24 172 Z" fill="url(#rivSteel_{{ $size }})"/>
            <path d="M42 32 L78 32 L72 68 L36 68 Z" fill="#FFFFFF" fill-opacity="0.6"/>
            <polygon points="46,88 64,88 61,114 43,114" fill="#0B0E14"/>

            <!-- 2. Upper Combat Blade Loop (Talon Arc) -->
            <path d="M78 32 L144 32 C172 32 188 48 184 76 C180 102 160 114 132 116 L124 116 L130 96 C148 95 160 88 162 76 C164 62 152 52 136 52 L75 52 Z" fill="url(#rivOrg_{{ $size }})"/>
            <path d="M78 32 L144 32 C160 32 174 40 178 54 L136 52 L75 52 Z" fill="url(#rivFlare_{{ $size }})"/>

            <!-- 3. Slashing Diagonal Combat Blade -->
            <path d="M102 104 L140 104 L188 172 L150 172 L118 126 L98 126 Z" fill="url(#rivOrg_{{ $size }})"/>
            <path d="M140 104 L188 172 L172 172 L130 114 Z" fill="url(#rivFlare_{{ $size }})"/>
            <polygon points="102,104 118,126 98,126" fill="#A52A00"/>

            <!-- 4. Counter-Blade Clash (Blue Steel) -->
            <polygon points="156,22 182,22 158,58 132,58" fill="url(#rivSteel_{{ $size }})"/>
            <polygon points="146,40 172,40 166,50 140,50" fill="#38BDF8" fill-opacity="0.9"/>

            <!-- 5. 1v1 Clash Reticle Core -->
            <circle cx="102" cy="104" r="5" fill="#FFFFFF"/>
            <circle cx="102" cy="104" r="2.2" fill="#FF5500"/>
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
