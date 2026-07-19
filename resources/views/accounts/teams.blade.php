@extends('layouts.tools', [
    'viteEntries' => ['resources/css/sprint-planner.css'],
])

@php
    use App\Support\AccentColors;
@endphp

@section('title', 'Teams — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide sp-app">
        <h1 class="tools-page-title" data-i18n="accounts.teamsTitle">Teams</h1>
        <p class="tools-page-lead" data-i18n="accounts.teamsLead">
            Create and edit teams one at a time. Membership syncs to user accounts.
        </p>

        <x-accounts.flash :status-map="[
            'team-created' => 'accounts.flash.teamCreated',
            'team-updated' => 'accounts.flash.teamUpdated',
            'team-deleted' => 'accounts.flash.teamDeleted',
        ]" />

        <section class="sp-section" aria-labelledby="accounts-teams-heading">
            <div class="sp-section__header">
                <h2 id="accounts-teams-heading" class="sp-section__title" data-i18n="accounts.existingTeams">Teams</h2>
                <a href="{{ locale_route('accounts.teams.create') }}" class="tools-btn tools-btn--primary" data-i18n="accounts.addTeam">
                    Add team
                </a>
            </div>

            <div class="sp-list">
                @forelse ($teams as $team)
                    @php
                        $label = $team['name']['en'] ?? $team['name']['de'] ?? $team['id'];
                        $icon = \App\Support\AvatarIcons::normalize($team['avatarIcon'] ?? '');
                        $short = trim((string) ($team['shortName'] ?? ''));
                        if ($short === '' && $icon === '') {
                            $short = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $label) ?: 'TM', 0, 3));
                            if (strlen($short) === 1) {
                                $short = str_pad($short, 2, 'X');
                            }
                        }
                        $showLabel = $short !== '';
                        $iconOnly = $icon !== '' && ! $showLabel;
                        $iconLabel = $icon !== '' && $showLabel;
                        $color = AccentColors::normalize($team['colorToken'] ?? null);
                        $roles = is_array($team['memberRoles'] ?? null) ? $team['memberRoles'] : [];
                        $managerCount = count(array_filter($roles, static fn ($r) => $r === 'manager'));
                        $ceoCount = count(array_filter($roles, static fn ($r) => $r === 'ceo'));
                        $memberCount = count($team['memberIds'] ?? []);
                        $avatarClasses = [
                            'sp-avatar',
                            'sp-avatar--'.$color,
                            'sp-avatar--team',
                        ];
                        if ($icon !== '') {
                            $avatarClasses[] = 'sp-avatar--icon';
                            $avatarClasses[] = $iconOnly ? 'sp-avatar--icon-only' : 'sp-avatar--icon-label';
                        } elseif (strlen($short) >= 3) {
                            $avatarClasses[] = 'sp-avatar--trigram-3';
                        }
                    @endphp
                    <div class="sp-list__row">
                        <div class="sp-list__identity">
                            <span
                                class="{{ implode(' ', $avatarClasses) }}"
                                style="{{ AccentColors::chipStyle($color) }}"
                                aria-hidden="true"
                            >
                                @if ($icon !== '')
                                    <span
                                        class="sp-avatar-icon-mask"
                                        style="mask-image:url('{{ asset('icons/avatar/'.$icon.'.svg') }}');-webkit-mask-image:url('{{ asset('icons/avatar/'.$icon.'.svg') }}')"
                                    ></span>
                                @endif
                                @if ($showLabel)
                                    @if ($icon !== '')
                                        <span class="sp-avatar__label">{{ $short }}</span>
                                    @else
                                        {{ $short }}
                                    @endif
                                @endif
                            </span>
                            <div>
                                <strong>{{ $label }}</strong>
                                <span class="sp-list__meta">
                                    {{ $team['id'] }}
                                    ·
                                    <span data-i18n="accounts.memberCount" data-i18n-count="{{ $memberCount }}">{{ $memberCount }} members</span>
                                    @if ($managerCount > 0)
                                        · <span data-i18n="accounts.role.manager">Manager</span> ×{{ $managerCount }}
                                    @endif
                                    @if ($ceoCount > 0)
                                        · <span data-i18n="accounts.role.ceo">CEO</span> ×{{ $ceoCount }}
                                    @endif
                                    @if (! empty($team['archived']))
                                        · <span data-i18n="accounts.archived">Archived</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <a
                            href="{{ locale_route('accounts.teams.edit', ['teamId' => $team['id']]) }}"
                            class="tools-btn tools-btn--secondary tools-btn--small"
                            data-i18n="accounts.edit"
                        >Edit</a>
                    </div>
                @empty
                    <p class="tools-page-lead" data-i18n="accounts.noTeams">No teams yet.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
