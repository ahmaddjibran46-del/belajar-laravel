@props(['variant' => 'brown', 'size' => 'md'])

@php
    $cupSize = match ($size) {
        'sm' => 'w-7 h-7',
        'lg' => 'w-14 h-14',
        default => 'w-9 h-9',
    };
    $titleSize = match ($size) {
        'sm' => 'text-sm leading-none',
        'lg' => 'text-2xl leading-none',
        default => 'text-lg leading-none',
    };
    $subSize = match ($size) {
        'sm' => 'text-[8px]',
        'lg' => 'text-xs',
        default => 'text-[10px]',
    };
    $color = $variant === 'white' ? 'text-white' : 'text-coffee-700';
@endphp

<div {{ $attributes->merge(['class' => "inline-flex items-center gap-2 $color"]) }}>
    <svg viewBox="0 0 32 32" fill="none" class="{{ $cupSize }} shrink-0">
        <path d="M11 6.5c0-1 .8-1.5.8-2.5S11 2.5 11 2.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
        <path d="M15.5 6.5c0-1 .8-1.5.8-2.5S15.5 2.5 15.5 2.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
        <path d="M20 6.5c0-1 .8-1.5.8-2.5S20 2.5 20 2.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
        <path d="M6.5 12h17.2l-1.1 10.2a3 3 0 0 1-3 2.7H10.6a3 3 0 0 1-3-2.7L6.5 12Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
        <path d="M23.7 14h1.8a2.6 2.6 0 0 1 0 5.2h-2.3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M4.5 27.5h23" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
    </svg>
    <span class="flex flex-col">
        <span class="{{ $titleSize }} font-extrabold tracking-tight">BONITO</span>
        <span class="{{ $subSize }} font-semibold tracking-[0.25em]">COFFEE</span>
    </span>
</div>
