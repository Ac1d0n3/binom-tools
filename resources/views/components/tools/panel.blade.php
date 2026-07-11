@props([
    'headingId',
    'titleKey' => null,
    'title' => null,
    'descriptionKey' => null,
    'code' => false,
    'scenario' => false,
])

<section
    {{ $attributes->class([
        'pii-policy-panel',
        'pii-policy-panel--code' => $code,
        'pii-policy-panel--scenario' => $scenario,
    ]) }}
    aria-labelledby="{{ $headingId }}"
>
    <header class="pii-policy-panel__header">
        <h3 id="{{ $headingId }}">
            @if ($titleKey)
                <span data-i18n="{{ $titleKey }}">{{ $title ?? '' }}</span>
            @else
                {{ $title }}
            @endif
        </h3>
        @if ($descriptionKey)
            <p data-i18n="{{ $descriptionKey }}"></p>
        @endif
    </header>
    {{ $slot }}
</section>
