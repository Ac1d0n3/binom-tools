@props([
    'src' => null,
    'alt' => '',
    'class' => '',
    'loading' => 'lazy',
    'fetchpriority' => null,
    'decoding' => null,
])

@php
    $sources = filled($src) ? \App\Support\PlaybookImagePath::pictureSources($src) : null;
    $fallbackUrl = filled($src) ? \App\Support\PlaybookImagePath::assetUrl($src) ?? $src : null;
@endphp

@if ($fallbackUrl)
    @if ($sources)
        <picture>
            <source srcset="{{ $sources['webp'] }}" type="image/webp">
            <img
                src="{{ $sources['fallback'] }}"
                alt="{{ $alt }}"
                @class([$class => filled($class)])
                loading="{{ $loading }}"
                @if ($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
                @if ($decoding) decoding="{{ $decoding }}" @endif
                {{ $attributes }}
            />
        </picture>
    @else
        <img
            src="{{ $fallbackUrl }}"
            alt="{{ $alt }}"
            @class([$class => filled($class)])
            loading="{{ $loading }}"
            @if ($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
            @if ($decoding) decoding="{{ $decoding }}" @endif
            {{ $attributes }}
        />
    @endif
@endif
