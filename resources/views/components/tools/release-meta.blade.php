@props([
    'variant' => 'inline',
])

@php
    $releaseLabel = tools_release_label();
    $showBeta = tools_is_beta();
@endphp

@if ($releaseLabel || $showBeta)
    <div {{ $attributes->merge(['class' => 'tools-release-meta tools-release-meta--' . $variant]) }}>
        @if ($releaseLabel)
            <span class="tools-release-meta__version">{{ $releaseLabel }}</span>
        @endif
        @if ($showBeta)
            <span class="tools-beta-badge" data-i18n="meta.beta">BETA</span>
        @endif
    </div>
@endif
