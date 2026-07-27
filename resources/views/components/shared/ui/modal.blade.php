@props([
    'id' => null,
    'labelledBy' => null,
    'class' => '',
])

<dialog
    @if ($id) id="{{ $id }}" @endif
    {{ $attributes->class(['bn-shared-modal', $class]) }}
    @if ($labelledBy) aria-labelledby="{{ $labelledBy }}" @endif
>
    {{ $slot }}
</dialog>
