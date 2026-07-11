@props([
    'variant' => 'ghost',
])

@if (config('tools.links.repository'))
    @php
        $buttonClass = match ($variant) {
            'primary' => 'tools-btn tools-btn--primary',
            default => 'tools-btn tools-btn--ghost',
        };
    @endphp

    <a
        href="{{ config('tools.links.repository') }}"
        {{ $attributes->merge(['class' => $buttonClass]) }}
        target="_blank"
        rel="noopener noreferrer"
    >
        <i class="fa-brands fa-github" aria-hidden="true"></i>
        @if ($variant === 'primary')
            <span>{{ $slot->isEmpty() ? 'Git-Repo klonen' : $slot }}</span>
        @else
            <span data-i18n="playbooks.downloadStarter">Git-Repo klonen</span>
        @endif
    </a>
@endif
