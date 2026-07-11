@props([
    'headingId',
    'titleKey' => null,
    'title' => null,
    'introKey' => null,
    'preId',
    'copyBtnId',
    'copyKey' => 'shared.copy',
])

<x-tools.panel :heading-id="$headingId" :title-key="$titleKey" :title="$title" code>
    @if ($introKey)
        <p class="tools-panel-lead" data-i18n="{{ $introKey }}"></p>
    @endif
    {{ $slot }}
    <pre class="pii-policy-code" id="{{ $preId }}"></pre>
    <button type="button" class="tools-btn" id="{{ $copyBtnId }}" data-dq-copy="{{ $preId }}" data-i18n="{{ $copyKey }}">Copy</button>
</x-tools.panel>
