@props([
    'user' => null,
    'src' => null,
    'size' => 'md',
    'alt' => null,
    'class' => '',
    'indicator' => null,
])

@php
    $resolvedSrc = $src;
    if (! $resolvedSrc && $user) {
        $resolvedSrc = is_object($user) ? ($user->avatar_url ?? $user->avatar) : ($user['avatar'] ?? null);
    }
    if (! $resolvedSrc) {
        $resolvedSrc = 'https://avatars.steamstatic.com/fef49e7fa7e1997310d705b2a6158ff8dc1cdfeb_full.jpg';
    }

    $sizes = [
        'xs' => 'w-6 h-6 rounded-md',
        'sm' => 'w-8 h-8 rounded-lg',
        'md' => 'w-9 h-9 rounded-lg',
        'lg' => 'w-16 h-16 sm:w-20 sm:h-20 rounded-xl',
        'xl' => 'w-24 h-24 sm:w-28 sm:h-28 rounded-2xl',
        'podium' => 'w-full h-full rounded-2xl',
        'none' => '',
    ];

    $sizeClasses = $sizes[$size] ?? $sizes['md'];
    $resolvedAlt = $alt ?? ($user->name ?? 'Player');
@endphp

<div class="relative inline-flex items-center justify-center shrink-0">
    <img 
        src="{{ $resolvedSrc }}" 
        alt="{{ $resolvedAlt }}"
        {{ $attributes->merge(['class' => "{$sizeClasses} object-cover bg-[#0A0D14] border border-[#1F273B] {$class}"]) }}
        loading="lazy"
    />
    @if ($indicator === 'online')
        <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-[#0E121B]" title="Online"></span>
    @elseif ($indicator === 'bot')
        <span class="absolute -bottom-1 -right-1 px-1 py-0.2 text-[9px] font-black uppercase tracking-wider rounded bg-[#FF5500] text-black font-mono">BOT</span>
    @endif
</div>
