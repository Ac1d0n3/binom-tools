@props([
    'labelKey' => null,
    'label' => null,
    'full' => false,
])

<label {{ $attributes->class([
    'pii-policy-field',
    'pii-policy-field--full' => $full,
]) }}>
    <span @if ($labelKey) data-i18n="{{ $labelKey }}" @endif>{{ $label }}</span>
    {{ $slot }}
</label>
