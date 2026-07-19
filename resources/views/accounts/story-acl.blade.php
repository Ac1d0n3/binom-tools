@extends('layouts.tools')

@section('title', 'Story access — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide">
        <h1 class="tools-page-title">Story visibility</h1>
        <p class="tools-page-lead">Public by default. Restricted stories require listed users or teams.</p>

        <div class="sp-list">
            @foreach ($stories as $story)
                <div class="sp-list__row">
                    <form method="post" action="{{ locale_route('accounts.story-acl.update', ['slug' => $story['slug']]) }}" class="sp-lock-form" style="flex:1">
                        @csrf
                        @method('PUT')
                        <strong>{{ $story['title'] }}</strong>
                        <span class="sp-list__meta">{{ $story['slug'] }}</span>
                        <label class="sp-field">
                            <span>Visibility</span>
                            <select name="visibility" class="tools-input">
                                <option value="public" @selected(($story['acl']['visibility'] ?? '') === 'public')>Public</option>
                                <option value="restricted" @selected(($story['acl']['visibility'] ?? '') === 'restricted')>Restricted</option>
                            </select>
                        </label>
                        <fieldset class="sp-field">
                            <legend>Users</legend>
                            <div class="sp-checkbox-list">
                                @foreach ($users as $user)
                                    <label class="sp-check">
                                        <input type="checkbox" name="userIds[]" value="{{ $user['id'] }}" @checked(in_array($user['id'], $story['acl']['userIds'] ?? [], true))>
                                        {{ $user['displayName'] }}
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>
                        <fieldset class="sp-field">
                            <legend>Teams</legend>
                            <div class="sp-checkbox-list">
                                @foreach ($teams as $team)
                                    <label class="sp-check">
                                        <input type="checkbox" name="teamIds[]" value="{{ $team['id'] }}" @checked(in_array($team['id'], $story['acl']['teamIds'] ?? [], true))>
                                        {{ $team['name']['en'] ?? $team['id'] }}
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>
                        <button type="submit" class="tools-btn tools-btn--primary tools-btn--small">Save</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endsection
