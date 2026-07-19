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

        <form method="post" action="{{ $action }}" class="sp-lock-form" style="max-width:40rem">
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

            <label class="sp-field">
                <span data-i18n="{{ $isEdit ? 'accounts.newPasswordOptional' : 'accounts.password' }}">
                    {{ $isEdit ? 'New password (optional)' : 'Password' }}
                </span>
                <input
                    type="password"
                    name="password"
                    class="tools-input"
                    minlength="8"
                    @if (! $isEdit) required @endif
                    autocomplete="{{ $isEdit ? 'new-password' : 'new-password' }}"
                >
            </label>

            <label class="sp-field">
                <span data-i18n="accounts.shortName">Trigram</span>
                <input type="text" name="shortName" class="tools-input" maxlength="3" value="{{ old('shortName', $user['shortName'] ?? '') }}">
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
                <button type="submit" class="tools-btn tools-btn--primary" data-i18n="accounts.save">Save</button>
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
@endsection
