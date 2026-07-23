@props([
    'statusMap' => [],
])

@php
    $status = session('status');
    $messageKey = is_string($status) ? ($statusMap[$status] ?? null) : null;
    $plainPassword = session('invite_plain_password');
    $inviteError = session('invite_error');
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

@if (is_string($plainPassword) && $plainPassword !== '')
    <p class="sp-password-note" role="status">
        <span data-i18n="accounts.flash.temporaryPasswordLabel">Temporary password (copy now):</span>
        <code>{{ $plainPassword }}</code>
    </p>
@endif

@if (is_string($inviteError) && $inviteError !== '')
    <p class="sp-field-error" role="alert">{{ $inviteError }}</p>
@endif

@if ($errors->any())
    <div class="sp-field-error" role="alert">{{ $errors->first() }}</div>
@endif
