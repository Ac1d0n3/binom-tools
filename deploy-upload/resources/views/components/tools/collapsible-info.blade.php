@props([
    'summaryKey',
    'open' => false,
    'compact' => false,
])

<details
    {{ $attributes->class([
        'tools-collapsible-info',
        'tools-collapsible-info--compact' => $compact,
    ]) }}
    @if ($open) open @endif
>
    <summary class="tools-collapsible-info__summary" data-i18n="{{ $summaryKey }}">How it works</summary>
    <div class="tools-collapsible-info__body">
        {{ $slot }}
    </div>
</details>
