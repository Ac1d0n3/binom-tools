@props([
    'headingId',
    'titleKey' => null,
    'title' => null,
    'boxId',
    'language' => 'sql',
    'boxTitle' => '',
])

<x-tools.panel :heading-id="$headingId" :title-key="$titleKey" :title="$title" code>
    {{ $slot }}
    <div
        class="playbook-code"
        id="{{ $boxId }}"
        data-language="{{ $language }}"
        data-title="{{ $boxTitle }}"
    >
        <pre><code></code></pre>
    </div>
</x-tools.panel>
