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
                        $memberCount = count($team['memberIds'] ?? []);
                        $chip = $team['shortName'] ?: strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $label) ?: 'TM', 0, 3));
                        if (strlen($chip) === 1) {
                            $chip = str_pad($chip, 2, 'X');
                        }
                        $color = AccentColors::normalize($team['colorToken'] ?? null);
                        $roles = is_array($team['memberRoles'] ?? null) ? $team['memberRoles'] : [];
                        $managerCount = count(array_filter($roles, static fn ($r) => $r === 'manager'));
                        $ceoCount = count(array_filter($roles, static fn ($r) => $r === 'ceo'));
                    @endphp
                    <div class="sp-list__row">
                        <div class="sp-list__identity">
                            <span
                                class="sp-avatar sp-avatar--{{ $color }} sp-avatar--team{{ strlen($chip) >= 3 ? ' sp-avatar--trigram-3' : '' }}"
                                style="{{ AccentColors::chipStyle($color) }}"
                                aria-hidden="true"
                            >{{ $chip }}</span>
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
