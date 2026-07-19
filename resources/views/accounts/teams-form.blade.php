@extends('layouts.tools', [
    'viteEntries' => ['resources/css/sprint-planner.css'],
])

@php
    $isEdit = is_array($team);
    $titleKey = $isEdit ? 'accounts.editTeam' : 'accounts.addTeam';
    $action = $isEdit
        ? locale_route('accounts.teams.update', ['teamId' => $team['id']])
        : locale_route('accounts.teams.store');
@endphp

@section('title', ($isEdit ? 'Edit team' : 'Add team') . ' — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide sp-app">
        <p class="sp-action-row">
            <a href="{{ locale_route('accounts.teams') }}" class="tools-btn tools-btn--secondary" data-i18n="accounts.backToTeams">Back to teams</a>
        </p>

        <h1 class="tools-page-title" data-i18n="{{ $titleKey }}">{{ $isEdit ? 'Edit team' : 'Add team' }}</h1>
        @if ($isEdit)
            <p class="tools-page-lead"><code>{{ $team['id'] }}</code></p>
        @endif

        <x-accounts.flash />

        <form method="post" action="{{ $action }}" class="sp-lock-form" style="max-width:40rem">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="sp-split">
                <label class="sp-field">
                    <span data-i18n="accounts.nameDe">Name (DE)</span>
                    <input type="text" name="name_de" class="tools-input" value="{{ old('name_de', $team['name']['de'] ?? '') }}" required>
                </label>
                <label class="sp-field">
                    <span data-i18n="accounts.nameEn">Name (EN)</span>
                    <input type="text" name="name_en" class="tools-input" value="{{ old('name_en', $team['name']['en'] ?? '') }}">
                </label>
            </div>

            <label class="sp-field">
                <span data-i18n="accounts.shortName">Trigram</span>
                <input type="text" name="shortName" class="tools-input" maxlength="3" value="{{ old('shortName', $team['shortName'] ?? '') }}">
            </label>

            <label class="sp-field">
                <span data-i18n="accounts.descriptionDe">Description (DE)</span>
                <textarea name="description_de" class="tools-input" rows="2">{{ old('description_de', $team['description']['de'] ?? '') }}</textarea>
            </label>
            <label class="sp-field">
                <span data-i18n="accounts.descriptionEn">Description (EN)</span>
                <textarea name="description_en" class="tools-input" rows="2">{{ old('description_en', $team['description']['en'] ?? '') }}</textarea>
            </label>

            <x-accounts.color-swatches
                :selected="old('colorToken', $team['colorToken'] ?? ($defaultColorToken ?? 'accent-1'))"
                :tokens="\App\Support\AccentColors::TEAM_TOKENS"
            />

            <x-accounts.checkbox-filter
                :items="$users"
                name="memberIds"
                :selected="old('memberIds', $team['memberIds'] ?? [])"
                label-key="displayName"
                legend-key="accounts.members"
            />

            @if ($isEdit)
                <label class="sp-check">
                    <input type="checkbox" name="archived" value="1" @checked(old('archived', $team['archived'] ?? false))>
                    <span data-i18n="accounts.archived">Archived</span>
                </label>
            @endif

            <div class="sp-action-row">
                <a href="{{ locale_route('accounts.teams') }}" class="tools-btn tools-btn--secondary" data-i18n="accounts.cancel">Cancel</a>
                <button type="submit" class="tools-btn tools-btn--primary" data-i18n="accounts.save">Save</button>
            </div>
        </form>

        @if ($isEdit)
            <form
                method="post"
                action="{{ locale_route('accounts.teams.destroy', ['teamId' => $team['id']]) }}"
                class="sp-lock-form"
                style="max-width:40rem;margin-top:1.5rem"
                onsubmit="return confirm(@json(__('Delete this team?')));"
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="tools-btn tools-btn--danger" data-i18n="accounts.deleteTeam">Delete team</button>
            </form>
        @endif
    </div>
@endsection
