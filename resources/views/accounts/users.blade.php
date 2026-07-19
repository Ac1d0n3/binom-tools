@extends('layouts.tools')

@section('title', 'Users — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide">
        <h1 class="tools-page-title" data-i18n="accounts.usersTitle">Users</h1>
        <p class="tools-page-lead" data-i18n="accounts.usersLead">Managed in local users.json — passwords are hashed only.</p>

        <section class="sp-section">
            <h2 class="sp-section__title" data-i18n="accounts.addUser">Add user</h2>
            <form method="post" action="{{ locale_route('accounts.users.store') }}" class="sp-lock-form">
                @csrf
                <div class="sp-split">
                    <label class="sp-field"><span>Email</span><input type="email" name="email" class="tools-input" required></label>
                    <label class="sp-field"><span>Display name</span><input type="text" name="displayName" class="tools-input" required></label>
                    <label class="sp-field"><span>Password</span><input type="password" name="password" class="tools-input" required minlength="8"></label>
                </div>
                <fieldset class="sp-field">
                    <legend>Teams</legend>
                    <div class="sp-checkbox-list">
                        @foreach ($teams as $team)
                            <label class="sp-check">
                                <input type="checkbox" name="teamIds[]" value="{{ $team['id'] }}">
                                {{ $team['name']['en'] ?? $team['id'] }}
                            </label>
                        @endforeach
                    </div>
                </fieldset>
                <label class="sp-check"><input type="checkbox" name="canManageUsers" value="1"> Can manage users</label>
                <label class="sp-check"><input type="checkbox" name="canManageTeams" value="1"> Can manage teams</label>
                <label class="sp-check"><input type="checkbox" name="active" value="1" checked> Active</label>
                <button type="submit" class="tools-btn tools-btn--primary">Create</button>
            </form>
        </section>

        <section class="sp-section">
            <h2 class="sp-section__title">Existing users</h2>
            <div class="sp-list">
                @foreach ($users as $user)
                    <div class="sp-list__row">
                        <div>
                            <strong>{{ $user['displayName'] }}</strong>
                            <span class="sp-list__meta">{{ $user['email'] }} · {{ $user['id'] }}</span>
                        </div>
                        <form method="post" action="{{ locale_route('accounts.users.update', ['userId' => $user['id']]) }}" class="sp-lock-form" style="flex:1">
                            @csrf
                            @method('PUT')
                            <div class="sp-split">
                                <label class="sp-field"><span>Email</span><input type="email" name="email" class="tools-input" value="{{ $user['email'] }}" required></label>
                                <label class="sp-field"><span>Display name</span><input type="text" name="displayName" class="tools-input" value="{{ $user['displayName'] }}" required></label>
                                <label class="sp-field"><span>New password (optional)</span><input type="password" name="password" class="tools-input" minlength="8"></label>
                            </div>
                            <div class="sp-checkbox-list">
                                @foreach ($teams as $team)
                                    <label class="sp-check">
                                        <input type="checkbox" name="teamIds[]" value="{{ $team['id'] }}" @checked(in_array($team['id'], $user['teamIds'] ?? [], true))>
                                        {{ $team['name']['en'] ?? $team['id'] }}
                                    </label>
                                @endforeach
                            </div>
                            <label class="sp-check"><input type="checkbox" name="canManageUsers" value="1" @checked($user['canManageUsers'] ?? false)> Manage users</label>
                            <label class="sp-check"><input type="checkbox" name="canManageTeams" value="1" @checked($user['canManageTeams'] ?? false)> Manage teams</label>
                            <label class="sp-check"><input type="checkbox" name="active" value="1" @checked($user['active'] ?? true)> Active</label>
                            <div class="sp-action-row">
                                <button type="submit" class="tools-btn tools-btn--primary tools-btn--small">Save</button>
                            </div>
                        </form>
                        <form method="post" action="{{ locale_route('accounts.users.destroy', ['userId' => $user['id']]) }}" onsubmit="return confirm('Delete user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="tools-btn tools-btn--danger tools-btn--small">Delete</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
@endsection
