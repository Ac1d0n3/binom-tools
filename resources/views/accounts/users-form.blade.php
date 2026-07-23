@extends('layouts.tools', [
    'viteEntries' => ['resources/css/sprint-planner.css'],
])

@php
    $isEdit = is_array($user);
    $titleKey = $isEdit ? 'accounts.editUser' : 'accounts.addUser';
    $action = $isEdit
        ? locale_route('accounts.users.update', ['userId' => $user['id']])
        : locale_route('accounts.users.store');
    $isSelf = $isEdit && ($actorId ?? null) === ($user['id'] ?? null);
@endphp

@section('title', ($isEdit ? 'Edit user' : 'Add user') . ' — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide sp-app">
        <p class="sp-action-row">
            <a href="{{ locale_route('accounts.users') }}" class="tools-btn tools-btn--secondary" data-i18n="accounts.backToUsers">Back to users</a>
        </p>

        <h1 class="tools-page-title" data-i18n="{{ $titleKey }}">{{ $isEdit ? 'Edit user' : 'Add user' }}</h1>
        @if ($isEdit)
            <p class="tools-page-lead"><code>{{ $user['id'] }}</code></p>
        @endif

        <x-accounts.flash />

        <form method="post" action="{{ $action }}" class="sp-lock-form" id="accounts-user-form" style="max-width:40rem">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="sp-split">
                <label class="sp-field">
                    <span data-i18n="accounts.email">Email</span>
                    <input type="email" name="email" class="tools-input" value="{{ old('email', $user['email'] ?? '') }}" required>
                </label>
                <label class="sp-field">
                    <span data-i18n="accounts.displayName">Display name</span>
                    <input type="text" name="displayName" class="tools-input" value="{{ old('displayName', $user['displayName'] ?? '') }}" required>
                </label>
            </div>

            @if (! $isEdit)
                <input type="hidden" name="generatePassword" value="0">
                <label class="sp-check">
                    <input type="checkbox" name="generatePassword" value="1" id="accounts-generate-password" @checked(old('generatePassword', '1') == '1' || old('generatePassword') === true || old('generatePassword') === 1 || old('generatePassword') === null)>
                    <span data-i18n="accounts.generatePassword">Generate temporary password</span>
                </label>
                <input type="hidden" name="sendInvite" value="0">
                <label class="sp-check">
                    <input type="checkbox" name="sendInvite" value="1" id="accounts-send-invite" @checked(old('sendInvite', '1') == '1' || old('sendInvite') === true || old('sendInvite') === 1 || old('sendInvite') === null)>
                    <span data-i18n="accounts.sendInvite">Send invitation email with password</span>
                </label>
                <input type="hidden" name="mustChangePassword" value="0">
                <label class="sp-check">
                    <input type="checkbox" name="mustChangePassword" value="1" @checked(old('mustChangePassword', '1') == '1' || old('mustChangePassword') === true || old('mustChangePassword') === 1 || old('mustChangePassword') === null)>
                    <span data-i18n="accounts.mustChangePassword">Must change password on first login</span>
                </label>
                <p class="sp-field-hint" data-i18n="accounts.inviteHint">
                    Default: generate a password, email it, and require a change after login.
                </p>
            @else
                <input type="hidden" name="generatePassword" value="0">
                <label class="sp-check">
                    <input type="checkbox" name="generatePassword" value="1" id="accounts-generate-password" @checked((string) old('generatePassword', '0') === '1')>
                    <span data-i18n="accounts.resetGeneratePassword">Reset with generated temporary password</span>
                </label>
                <input type="hidden" name="sendInvite" value="0">
                <label class="sp-check">
                    <input type="checkbox" name="sendInvite" value="1" id="accounts-send-invite" @checked((string) old('sendInvite', '0') === '1')>
                    <span data-i18n="accounts.resendInvite">Email the new password to the user</span>
                </label>
                <input type="hidden" name="mustChangePassword" value="0">
                <label class="sp-check">
                    <input type="checkbox" name="mustChangePassword" value="1" @checked((string) old('mustChangePassword', ($user['mustChangePassword'] ?? false) ? '1' : '0') === '1')>
                    <span data-i18n="accounts.mustChangePassword">Must change password on next login</span>
                </label>
            @endif

            <label class="sp-field" id="accounts-password-field">
                <span data-i18n="{{ $isEdit ? 'accounts.newPasswordOptional' : 'accounts.passwordManual' }}">
                    {{ $isEdit ? 'New password (optional)' : 'Password (if not generated)' }}
                </span>
                <input
                    type="password"
                    name="password"
                    class="tools-input"
                    minlength="8"
                    autocomplete="new-password"
                    id="accounts-password-input"
                >
            </label>

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
                    value="{{ old('shortName', $user['shortName'] ?? '') }}"
                >
                <span class="sp-field-hint" data-i18n="accounts.shortNameHint">2–3 letters (A–Z), optional.</span>
            </label>

            <x-accounts.icon-picker :selected="old('avatarIcon', $user['avatarIcon'] ?? '')" />

            <x-accounts.color-swatches :selected="old('colorToken', $user['colorToken'] ?? 'accent-1')" />

            <x-accounts.checkbox-filter
                :items="$teams"
                name="teamIds"
                :selected="old('teamIds', $user['teamIds'] ?? [])"
                legend-key="accounts.teams"
                search-placeholder-key="accounts.filterTeams"
            />

            <label class="sp-check">
                <input type="checkbox" name="canManageUsers" value="1" @checked(old('canManageUsers', $user['canManageUsers'] ?? false))>
                <span data-i18n="accounts.canManageUsers">Can manage users</span>
            </label>
            <label class="sp-check">
                <input type="checkbox" name="canManageTeams" value="1" @checked(old('canManageTeams', $user['canManageTeams'] ?? false))>
                <span data-i18n="accounts.canManageTeams">Can manage teams</span>
            </label>
            <label class="sp-check">
                <input type="checkbox" name="active" value="1" @checked(old('active', $user['active'] ?? true))>
                <span data-i18n="accounts.active">Active</span>
            </label>

            <div class="sp-action-row">
                <a href="{{ locale_route('accounts.users') }}" class="tools-btn tools-btn--secondary" data-i18n="accounts.cancel">Cancel</a>
                <button type="submit" class="tools-btn tools-btn--primary" data-i18n="{{ $isEdit ? 'accounts.save' : 'accounts.addUserSubmit' }}">
                    {{ $isEdit ? 'Save' : 'Add user' }}
                </button>
            </div>
        </form>

        @if ($isEdit && ! $isSelf)
            <form
                method="post"
                action="{{ locale_route('accounts.users.destroy', ['userId' => $user['id']]) }}"
                class="sp-lock-form"
                style="max-width:40rem;margin-top:1.5rem"
                onsubmit="return confirm(@json(__('Delete this user?')));"
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="tools-btn tools-btn--danger" data-i18n="accounts.deleteUser">Delete user</button>
            </form>
        @endif
    </div>

    <script>
        (function () {
            const generate = document.getElementById('accounts-generate-password');
            const passwordInput = document.getElementById('accounts-password-input');
            const passwordField = document.getElementById('accounts-password-field');
            if (!generate || !passwordInput || !passwordField) return;

            const sync = () => {
                const on = generate.checked;
                passwordField.hidden = on;
                passwordInput.required = !on && {{ $isEdit ? 'false' : 'true' }};
                if (on) passwordInput.value = '';
            };
            generate.addEventListener('change', sync);
            sync();
        })();
    </script>
@endsection
