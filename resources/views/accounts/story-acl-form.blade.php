@extends('layouts.tools', [
    'viteEntries' => ['resources/css/sprint-planner.css'],
])

@section('title', 'Edit story access — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide sp-app">
        <p class="sp-action-row">
            <a href="{{ locale_route('accounts.story-acl') }}" class="tools-btn tools-btn--secondary" data-i18n="accounts.backToStoryAcl">
                Back to story access
            </a>
        </p>

        <h1 class="tools-page-title" data-i18n="accounts.editStoryAcl">Edit story access</h1>
        <p class="tools-page-lead">
            <strong>{{ $story['title'] }}</strong>
            · <code>{{ $story['slug'] }}</code>
        </p>

        <x-accounts.flash />

        <form
            method="post"
            action="{{ locale_route('accounts.story-acl.update', ['slug' => $story['slug']]) }}"
            class="sp-lock-form"
            style="max-width:40rem"
        >
            @csrf
            @method('PUT')

            <label class="sp-field">
                <span data-i18n="accounts.visibility">Visibility</span>
                <select name="visibility" class="tools-input" id="accounts-story-visibility">
                    <option value="public" @selected(old('visibility', $story['acl']['visibility'] ?? 'public') === 'public') data-i18n="accounts.visibility.public">
                        Public
                    </option>
                    <option value="restricted" @selected(old('visibility', $story['acl']['visibility'] ?? 'public') === 'restricted') data-i18n="accounts.visibility.restricted">
                        Restricted
                    </option>
                </select>
            </label>

            <p class="sp-password-note" data-i18n="accounts.storyAclRestrictedNote">
                User and team lists apply only when visibility is restricted.
            </p>

            <x-accounts.checkbox-filter
                :items="$users"
                name="userIds"
                :selected="old('userIds', $story['acl']['userIds'] ?? [])"
                label-key="displayName"
                legend-key="accounts.allowedUsers"
                search-placeholder-key="accounts.filterUsers"
            />

            <x-accounts.checkbox-filter
                :items="$teams"
                name="teamIds"
                :selected="old('teamIds', $story['acl']['teamIds'] ?? [])"
                label-key="name"
                legend-key="accounts.allowedTeams"
                search-placeholder-key="accounts.filterTeams"
            />

            <div class="sp-action-row">
                <a href="{{ locale_route('accounts.story-acl') }}" class="tools-btn tools-btn--secondary" data-i18n="accounts.cancel">Cancel</a>
                <button type="submit" class="tools-btn tools-btn--primary" data-i18n="accounts.save">Save</button>
            </div>
        </form>
    </div>
@endsection
