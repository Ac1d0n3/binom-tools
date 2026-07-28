@props([
    'label',
    'labelDe' => null,
    'labelEn' => null,
])

<label {{ $attributes->class(['admin-hub__field']) }}>
    <span
        class="admin-hub__field-label"
        @if ($labelDe && $labelEn)
            data-text-de="{{ $labelDe }}"
            data-text-en="{{ $labelEn }}"
        @endif
    >{{ $label }}</span>
    {{ $slot }}
</label>
