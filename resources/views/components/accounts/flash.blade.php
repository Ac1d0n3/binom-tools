@props([
    'statusMap' => [],
])

@php
    $status = session('status');
    $messageKey = is_string($status) ? ($statusMap[$status] ?? null) : null;
@endphp

@if ($status)
    <p class="sp-password-note" role="status" @if ($messageKey) data-i18n="{{ $messageKey }}" @endif>
        @if ($messageKey)
            Saved.
        @else
            {{ is_string($status) ? $status : 'Saved.' }}
        @endif
    </p>
@endif

@if ($errors->any())
    <div class="sp-field-error" role="alert">{{ $errors->first() }}</div>
@endif
