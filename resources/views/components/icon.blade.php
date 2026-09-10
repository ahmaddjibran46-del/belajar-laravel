@props(['name'])

@php
    $paths = [
        'mail' => '<path d="M3.5 5.5h17v13h-17z" stroke-linejoin="round"/><path d="m4 6 8 6.5L20 6" stroke-linejoin="round"/>',
        'lock' => '<rect x="5" y="10.5" width="14" height="9" rx="2"/><path d="M7.5 10.5V8a4.5 4.5 0 0 1 9 0v2.5"/><circle cx="12" cy="14.7" r="1.2" fill="currentColor" stroke="none"/>',
        'search' => '<circle cx="11" cy="11" r="6.5"/><path d="m20 20-4.3-4.3" stroke-linecap="round"/>',
        'hamburger' => '<path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/>',
        'user' => '<circle cx="12" cy="8.5" r="3.5"/><path d="M4.5 20a7.5 7.5 0 0 1 15 0" stroke-linecap="round"/>',
        'arrow-left' => '<path d="M19 12H5M11 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round"/>',
        'grid' => '<rect x="4" y="4" width="7" height="7" rx="1.5"/><rect x="13" y="4" width="7" height="7" rx="1.5"/><rect x="4" y="13" width="7" height="7" rx="1.5"/><rect x="13" y="13" width="7" height="7" rx="1.5"/>',
        'list' => '<path d="M8 6h12M8 12h12M8 18h12" stroke-linecap="round"/><circle cx="4" cy="6" r="1" fill="currentColor"/><circle cx="4" cy="12" r="1" fill="currentColor"/><circle cx="4" cy="18" r="1" fill="currentColor"/>',
        'tag' => '<path d="M11.5 4h-6A1.5 1.5 0 0 0 4 5.5v6L13 20l7-7-9-9Z" stroke-linejoin="round"/><circle cx="8.3" cy="8.3" r="1.3" fill="currentColor" stroke="none"/>',
        'table' => '<rect x="3.5" y="5" width="17" height="14" rx="2"/><path d="M3.5 10h17M9.5 10v9" />',
        'download' => '<path d="M12 4v11m0 0-4-4m4 4 4-4" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 18.5h14" stroke-linecap="round"/>',
        'logout' => '<path d="M9 21H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 17l5-5-5-5M21 12H9" stroke-linecap="round" stroke-linejoin="round"/>',
        'check' => '<path d="m5 13 4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>',
        'cart' => '<circle cx="9" cy="20" r="1.4" fill="currentColor" stroke="none"/><circle cx="18" cy="20" r="1.4" fill="currentColor" stroke="none"/><path d="M3 4h2l2.2 11.2a2 2 0 0 0 2 1.6h7.6a2 2 0 0 0 2-1.6L21 8H6" stroke-linecap="round" stroke-linejoin="round"/>',
        'plus' => '<path d="M12 5v14M5 12h14" stroke-linecap="round"/>',
        'minus' => '<path d="M5 12h14" stroke-linecap="round"/>',
    ];
    $path = $paths[$name] ?? '';
@endphp

<svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.7']) }}>
    {!! $path !!}
</svg>
