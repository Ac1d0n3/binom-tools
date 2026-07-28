@extends('admin::layouts.shell')

@section('title', 'Teams — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide sp-app admin-hub" data-overview-filter-root>
        <x-admin.sticky-header :count="count($teams)">
            <x-slot:search>
                <input type="search" class="tools-input" data-overview-search placeholder="Search teams…" aria-label="Search">
            </x-slot:search>
            <x-slot:actions>
                <x-admin.layout-toggle />
                <a href="{{ locale_route('admin.teams.create') }}" class="tools-btn tools-btn--primary" data-i18n="accounts.addTeam">Add team</a>
            </x-slot:actions>
        </x-admin.sticky-header>

        <x-accounts.flash :status-map="[
            'team-created' => 'accounts.flash.teamCreated',
            'team-updated' => 'accounts.flash.teamUpdated',
            'team-deleted' => 'accounts.flash.teamDeleted',
        ]" />

        <p class="admin-hub__meta" data-overview-empty hidden data-i18n="accounts.noTeams">No matches.</p>

        <div class="admin-hub__overview" data-admin-overview-root data-layout="table">
            <div class="admin-hub__card-grid" data-admin-overview-panel="cards" hidden>
                @foreach ($teams as $team)
                    @php
                        $label = $team['name']['en'] ?? $team['name']['de'] ?? $team['id'];
                        $memberCount = count($team['memberIds'] ?? []);
                        $roles = is_array($team['memberRoles'] ?? null) ? $team['memberRoles'] : [];
                        $managerCount = count(array_filter($roles, static fn ($r) => $r === 'manager'));
                        $searchText = strtolower(trim(implode(' ', array_filter([
                            $label,
                            $team['id'] ?? '',
                            $team['name']['de'] ?? '',
                            $team['name']['en'] ?? '',
                        ]))));
                    @endphp
                    <article class="admin-hub__card" data-overview-item data-search-text="{{ $searchText }}">
                        <h3 class="admin-hub__card-title">{{ $label }}</h3>
                        <p class="admin-hub__card-meta">
                            {{ $team['id'] }} · {{ $memberCount }} members
                            @if ($managerCount > 0)
                                · Manager ×{{ $managerCount }}
                            @endif
                            @if (! empty($team['archived']))
                                · Archived
                            @endif
                        </p>
                        <div class="admin-hub__card-actions">
                            <x-admin.icon-btn kind="edit" :href="locale_route('admin.teams.edit', ['teamId' => $team['id']])" />
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="supplier-table-wrap" data-admin-overview-panel="table">
                <table class="supplier-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Id</th>
                            <th>Members</th>
                            <th class="admin-hub__table-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teams as $team)
                            @php
                                $label = $team['name']['en'] ?? $team['name']['de'] ?? $team['id'];
                                $memberCount = count($team['memberIds'] ?? []);
                                $searchText = strtolower(trim(implode(' ', array_filter([
                                    $label,
                                    $team['id'] ?? '',
                                    $team['name']['de'] ?? '',
                                    $team['name']['en'] ?? '',
                                ]))));
                            @endphp
                            <tr data-overview-item data-search-text="{{ $searchText }}">
                                <td>
                                    <strong>{{ $label }}</strong>
                                    @if (! empty($team['archived']))
                                        <div class="admin-hub__meta">Archived</div>
                                    @endif
                                </td>
                                <td><code>{{ $team['id'] }}</code></td>
                                <td>{{ $memberCount }}</td>
                                <td class="admin-hub__table-actions">
                                    <x-admin.icon-btn kind="edit" :href="locale_route('admin.teams.edit', ['teamId' => $team['id']])" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"><p class="tools-page-lead" data-i18n="accounts.noTeams">No teams yet.</p></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
