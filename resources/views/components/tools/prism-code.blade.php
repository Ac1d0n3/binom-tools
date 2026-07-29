@props([
    'boxId',
    'language' => 'sql',
    'boxTitle' => '',
    'titleKey' => null,
])

{{-- Single code chrome only — no outer panel/label wrapper (title lives in playbook-code header). --}}
<div
    class="playbook-code"
    id="{{ $boxId }}"
    data-language="{{ $language }}"
    @if ($titleKey) data-title-key="{{ $titleKey }}" @endif
    data-title="{{ $boxTitle }}"
>
    <pre><code></code></pre>
</div>
