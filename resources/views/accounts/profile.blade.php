@extends('layouts.tools')

@section('title', 'Account — ' . config('app.name'))

@section('content')
    <div class="tools-content">
        <h1 class="tools-page-title" data-i18n="accounts.profileTitle">Account</h1>
        <p class="tools-page-lead">{{ $account['email'] }}</p>

        @if (session('status'))
            <p class="sp-password-note" role="status" data-i18n="accounts.saved">Saved.</p>
        @endif
        @if ($errors->any())
            <div class="sp-field-error" role="alert">{{ $errors->first() }}</div>
        @endif

        <form method="post" action="{{ locale_route('accounts.profile.update') }}" class="sp-lock-form" style="max-width:28rem">
            @csrf
            @method('PUT')
            <label class="sp-field">
                <span data-i18n="accounts.displayName">Display name</span>
                <input type="text" name="displayName" class="tools-input" value="{{ old('displayName', $account['displayName']) }}" required>
            </label>
            <label class="sp-field">
                <span data-i18n="accounts.currentPassword">Current password (to change password)</span>
                <input type="password" name="current_password" class="tools-input" autocomplete="current-password">
            </label>
            <label class="sp-field">
                <span data-i18n="accounts.newPassword">New password</span>
                <input type="password" name="password" class="tools-input" autocomplete="new-password" minlength="8">
            </label>
            <label class="sp-field">
                <span data-i18n="accounts.confirmPassword">Confirm new password</span>
                <input type="password" name="password_confirmation" class="tools-input" autocomplete="new-password" minlength="8">
            </label>
            <button type="submit" class="tools-btn tools-btn--primary" data-i18n="accounts.save">Save</button>
        </form>
    </div>
@endsection
