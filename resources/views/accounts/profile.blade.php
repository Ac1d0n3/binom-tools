@extends('layouts.tools', [
    'viteEntries' => ['resources/css/sprint-planner.css'],
])

@section('title', 'Account — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide sp-app">
        <h1 class="tools-page-title" data-i18n="accounts.profileTitle">Account</h1>
        <p class="tools-page-lead">{{ $account['email'] }}</p>

        <x-accounts.flash :status-map="[
            'profile-updated' => 'accounts.saved',
        ]" />

        <form method="post" action="{{ locale_route('accounts.profile.update') }}" class="sp-lock-form" style="max-width:40rem">
            @csrf
            @method('PUT')

            <label class="sp-field">
                <span data-i18n="accounts.displayName">Display name</span>
                <input type="text" name="displayName" class="tools-input" value="{{ old('displayName', $account['displayName']) }}" required>
            </label>

            @if (! empty($profileAvatarEnabled))
                <label class="sp-field">
                    <span data-i18n="accounts.shortName">Trigram</span>
                    <input
                        type="text"
                        name="shortName"
                        class="tools-input"
                        minlength="2"
                        maxlength="3"
                        pattern="[A-Za-z]{2,3}"
                        title="2–3 letters"
                        value="{{ old('shortName', $account['shortName'] ?? '') }}"
                    >
                    <span class="sp-field-hint" data-i18n="accounts.shortNameHint">2–3 letters (A–Z), optional.</span>
                </label>

                <x-accounts.icon-picker :selected="old('avatarIcon', $account['avatarIcon'] ?? '')" />

                <x-accounts.color-swatches :selected="old('colorToken', $account['colorToken'] ?? 'accent-1')" />
            @endif

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
