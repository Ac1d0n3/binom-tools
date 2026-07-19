@extends('layouts.tools')

@section('title', 'Teams — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide">
        <h1 class="tools-page-title" data-i18n="accounts.teamsTitle">Teams</h1>

        <section class="sp-section">
            <h2 class="sp-section__title">Add team</h2>
            <form method="post" action="{{ locale_route('accounts.teams.store') }}" class="sp-lock-form">
                @csrf
                <div class="sp-split">
                    <label class="sp-field"><span>Name (DE)</span><input type="text" name="name_de" class="tools-input" required></label>
                    <label class="sp-field"><span>Name (EN)</span><input type="text" name="name_en" class="tools-input"></label>
                </div>
                <fieldset class="sp-field">
                    <legend>Members</legend>
                    <div class="sp-checkbox-list">
                        @foreach ($users as $user)
                            <label class="sp-check">
                                <input type="checkbox" name="memberIds[]" value="{{ $user['id'] }}">
                                {{ $user['displayName'] }}
                            </label>
                        @endforeach
                    </div>
                </fieldset>
                <button type="submit" class="tools-btn tools-btn--primary">Create</button>
            </form>
        </section>

        <section class="sp-section">
            <h2 class="sp-section__title">Existing teams</h2>
            <div class="sp-list">
                @foreach ($teams as $team)
                    <div class="sp-list__row">
                        <form method="post" action="{{ locale_route('accounts.teams.update', ['teamId' => $team['id']]) }}" class="sp-lock-form" style="flex:1">
                            @csrf
                            @method('PUT')
                            <strong>{{ $team['id'] }}</strong>
                            <div class="sp-split">
                                <label class="sp-field"><span>Name (DE)</span><input type="text" name="name_de" class="tools-input" value="{{ $team['name']['de'] ?? '' }}" required></label>
                                <label class="sp-field"><span>Name (EN)</span><input type="text" name="name_en" class="tools-input" value="{{ $team['name']['en'] ?? '' }}"></label>
                            </div>
                            <div class="sp-checkbox-list">
                                @foreach ($users as $user)
                                    <label class="sp-check">
                                        <input type="checkbox" name="memberIds[]" value="{{ $user['id'] }}" @checked(in_array($user['id'], $team['memberIds'] ?? [], true))>
                                        {{ $user['displayName'] }}
                                    </label>
                                @endforeach
                            </div>
                            <label class="sp-check"><input type="checkbox" name="archived" value="1" @checked($team['archived'] ?? false)> Archived</label>
                            <button type="submit" class="tools-btn tools-btn--primary tools-btn--small">Save</button>
                        </form>
                        <form method="post" action="{{ locale_route('accounts.teams.destroy', ['teamId' => $team['id']]) }}" onsubmit="return confirm('Delete team?')">
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
