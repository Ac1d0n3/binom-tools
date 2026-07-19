@extends('layouts.tools', [
    'viteEntries' => ['resources/css/sprint-planner.css'],
])

@php
    use App\Support\AccentColors;
@endphp

@section('title', 'Users — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide sp-app">
        <h1 class="tools-page-title" data-i18n="accounts.usersTitle">Users</h1>
        <p class="tools-page-lead" data-i18n="accounts.usersLead">
            Managed in local users.json — passwords are hashed only.
        </p>

        <x-accounts.flash :status-map="[
            'user-created' => 'accounts.flash.userCreated',
            'user-updated' => 'accounts.flash.userUpdated',
            'user-deleted' => 'accounts.flash.userDeleted',
        ]" />

        <section class="sp-section" aria-labelledby="accounts-users-heading">
            <div class="sp-section__header">
                <h2 id="accounts-users-heading" class="sp-section__title" data-i18n="accounts.existingUsers">Users</h2>
                <a href="{{ locale_route('accounts.users.create') }}" class="tools-btn tools-btn--primary" data-i18n="accounts.addUser">
                    Add user
                </a>
            </div>

            @php
                $teamLabels = [];
                foreach ($teams as $team) {
                    $teamLabels[$team['id']] = $team['name']['en'] ?? $team['name']['de'] ?? $team['id'];
                }
            @endphp

            <div class="sp-list">
                @forelse ($users as $user)
                    @php
                        $chip = $user['shortName'] ?: strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $user['displayName'] ?? '') ?: 'U', 0, 3));
                        if (strlen($chip) === 1) {
                            $chip .= 'X';
                        }
                        $color = AccentColors::normalize($user['colorToken'] ?? null);
                        $iconSvg = \App\Support\AvatarIcons::svgMarkup($user['avatarIcon'] ?? '');
                        $userTeams = array_values(array_filter(array_map(
                            static fn ($id) => $teamLabels[$id] ?? null,
                            $user['teamIds'] ?? []
                        )));
                    @endphp
                    <div class="sp-list__row">
                        <div class="sp-list__identity">
                            <span
                                class="sp-avatar sp-avatar--{{ $color }} sp-avatar--person{{ $iconSvg !== '' ? ' sp-avatar--icon' : '' }}{{ $iconSvg === '' && strlen($chip) >= 3 ? ' sp-avatar--trigram-3' : '' }}"
                                style="{{ AccentColors::chipStyle($color) }}"
                                aria-hidden="true"
                            >
                                @if ($iconSvg !== '')
                                    {!! $iconSvg !!}
                                @else
                                    {{ $chip }}
                                @endif
                            </span>
                            <div>
                                <strong>{{ $user['displayName'] }}</strong>
                                <span class="sp-list__meta">
                                    {{ $user['email'] }}
                                    @if (! ($user['active'] ?? true))
                                        · <span data-i18n="accounts.inactive">Inactive</span>
                                    @endif
                                    @if ($userTeams !== [])
                                        · {{ implode(', ', array_slice($userTeams, 0, 3)) }}{{ count($userTeams) > 3 ? '…' : '' }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <a
                            href="{{ locale_route('accounts.users.edit', ['userId' => $user['id']]) }}"
                            class="tools-btn tools-btn--secondary tools-btn--small"
                            data-i18n="accounts.edit"
                        >Edit</a>
                    </div>
                @empty
                    <p class="tools-page-lead" data-i18n="accounts.noUsers">No users yet.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
