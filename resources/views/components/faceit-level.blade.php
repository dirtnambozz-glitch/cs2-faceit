@props([
    'level' => 1,
    'size' => 'md',
])

@php
$lvl = max(1, min(10, (int)$level));

$styles = match($lvl) {
    1 => 'bg-[#52525b] text-neutral-100 border-[#71717a]',
    2 => 'bg-[#16a34a] text-white border-[#22c55e]',
    3 => 'bg-[#15803d] text-white border-[#16a34a]',
    4 => 'bg-[#ca8a04] text-neutral-950 font-black border-[#eab308]',
    5 => 'bg-[#d97706] text-neutral-950 font-black border-[#f59e0b]',
    6 => 'bg-[#ea580c] text-white border-[#f97316]',
    7 => 'bg-[#f97316] text-white border-[#ea580c]',
    8 => 'bg-[#ff5500] text-white border-[#ff7733] shadow-[0_0_12px_rgba(255,85,0,0.4)]',
    9 => 'bg-[#dc2626] text-white border-[#ef4444] shadow-[0_0_14px_rgba(220,38,38,0.45)]',
    10 => 'bg-gradient-to-br from-[#ef4444] via-[#dc2626] to-[#991b1b] text-white border-[#fbbf24] shadow-[0_0_18px_rgba(239,68,68,0.6)] ring-1 ring-[#fbbf24]/60',
    default => 'bg-[#52525b] text-white border-[#71717a]',
};

$sizeClasses = match($size) {
    'xs' => 'w-5 h-5 text-[10px] rounded',
    'sm' => 'w-6 h-6 text-xs rounded-md',
    'md' => 'w-8 h-8 text-sm rounded-lg',
    'lg' => 'w-12 h-12 text-xl rounded-xl',
    'xl' => 'w-16 h-16 text-2xl rounded-2xl',
    default => 'w-8 h-8 text-sm rounded-lg',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center justify-center font-bold font-mono tracking-tight border select-none {$styles} {$sizeClasses}"]) }} title="FACEIT Level {{ $lvl }}">
    {{ $lvl }}
</span>
