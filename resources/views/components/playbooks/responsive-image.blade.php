@props([
    'src' => null,
    'alt' => '',
    'class' => '',
    'loading' => 'lazy',
    'fetchpriority' => null,
    'decoding' => null,
    'sizes' => '(max-width: 768px) 100vw, 960px',
    'fallbackOnError' => false,
])

@php
    $sources = filled($src) ? \App\Support\PlaybookImagePath::pictureSources($src) : null;
    $fallbackUrl = filled($src) ? \App\Support\PlaybookImagePath::assetUrl($src) ?? $src : null;
    $errorHandler = $fallbackOnError
        ? 'this.setAttribute(\'data-failed\',\'1\');const root=this.closest(\'[data-playbook-hero-root]\');if(root){root.classList.add(\'is-hero-failed\');}'
        : null;
@endphp

@if ($fallbackUrl)
    @if ($sources)
        <picture @class([$class => filled($class) && ! $fallbackOnError])>
            <source srcset="{{ $sources['webp'] }}" type="image/webp" @if ($sizes) sizes="{{ $sizes }}" @endif>
            <img
                src="{{ $sources['fallback'] }}"
                alt="{{ $alt }}"
                @class([$class => filled($class)])
                loading="{{ $loading }}"
                @if ($sizes) sizes="{{ $sizes }}" @endif
                @if ($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
                @if ($decoding) decoding="{{ $decoding }}" @endif
                @if ($fallbackOnError) data-playbook-hero-image @endif
                @if ($errorHandler) onerror="{{ $errorHandler }}" @endif
                {{ $attributes }}
            />
        </picture>
    @else
        <img
            src="{{ $fallbackUrl }}"
            alt="{{ $alt }}"
            @class([$class => filled($class)])
            loading="{{ $loading }}"
            @if ($sizes) sizes="{{ $sizes }}" @endif
            @if ($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
            @if ($decoding) decoding="{{ $decoding }}" @endif
            @if ($fallbackOnError) data-playbook-hero-image @endif
            @if ($errorHandler) onerror="{{ $errorHandler }}" @endif
            {{ $attributes }}
        />
    @endif
@endif
