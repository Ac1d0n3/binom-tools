@extends('admin::layouts.shell')

@php
    $pendingUsers = array_values(array_filter($users, static fn ($u) => ! empty($u['pendingApproval'])));
    $activeUsers = array_values(array_filter($users, static fn ($u) => empty($u['pendingApproval'])));
    $teamLabels = [];
    foreach ($teams as $team) {
        $teamLabels[$team['id']] = $team['name']['en'] ?? $team['name']['de'] ?? $team['id'];
    }
    $userMeta = static function (array $user) use ($teamLabels): array {
        $userTeams = array_values(array_filter(array_map(
            static fn ($id) => $teamLabels[$id] ?? null,
            $user['teamIds'] ?? []
        )));
        $flags = [];
        if (! empty($user['canManageUsers'])) {
            $flags[] = 'Users';
        }
        if (! empty($user['canManageTeams'])) {
            $flags[] = 'Teams';
        }
        if (! empty($user['canManageContent'])) {
            $flags[] = 'Content';
        }
        $searchText = strtolower(trim(implode(' ', array_filter([
            $user['displayName'] ?? '',
            $user['email'] ?? '',
            $user['id'] ?? '',
            $user['shortName'] ?? '',
            implode(' ', $userTeams),
            implode(' ', $flags),
        ]))));

        return compact('userTeams', 'flags', 'searchText');
    };
@endphp

@section('title', 'Users — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide sp-app admin-hub" data-overview-filter-root>
        <x-admin.sticky-header :count="count($users)">
            <x-slot:search>
                <input type="search" class="tools-input" data-overview-search placeholder="Search users…" aria-label="Search" data-i18n-placeholder="accounts.filterUsers">
            </x-slot:search>
            <x-slot:actions>
                <x-admin.layout-toggle />
                <a href="{{ locale_route('admin.users.create') }}" class="tools-btn tools-btn--primary" data-i18n="accounts.addUser">Add user</a>
            </x-slot:actions>
        </x-admin.sticky-header>

        <x-accounts.flash :status-map="[
            'user-created' => 'accounts.flash.userCreated',
            'user-created-invited' => 'accounts.flash.userCreatedInvited',
            'user-created-invite-failed' => 'accounts.flash.userCreatedInviteFailed',
            'user-created-with-password' => 'accounts.flash.userCreatedWithPassword',
            'user-updated' => 'accounts.flash.userUpdated',
            'user-updated-invited' => 'accounts.flash.userUpdatedInvited',
            'user-updated-invite-failed' => 'accounts.flash.userUpdatedInviteFailed',
            'user-updated-with-password' => 'accounts.flash.userUpdatedWithPassword',
            'user-deleted' => 'accounts.flash.userDeleted',
            'user-approved' => 'accounts.flash.userApproved',
            'user-rejected' => 'accounts.flash.userRejected',
        ]" />

        <p class="admin-hub__meta" data-overview-empty hidden data-i18n="accounts.noUsers">No matches.</p>

        <div class="admin-hub__overview" data-admin-overview-root data-layout="table">
            <div class="admin-hub__card-grid" data-admin-overview-panel="cards" hidden>
                @foreach (array_merge($pendingUsers, $activeUsers) as $user)
                    @php
                        $meta = $userMeta($user);
                        $pending = ! empty($user['pendingApproval']);
                    @endphp
                    <article class="admin-hub__card" data-overview-item data-search-text="{{ $meta['searchText'] }}">
                        <h3 class="admin-hub__card-title">{{ $user['displayName'] }}</h3>
                        <p class="admin-hub__card-meta">
                            {{ $user['email'] }}
                            @if ($pending)
                                · Awaiting approval
                            @elseif (! ($user['active'] ?? true))
                                · Inactive
                            @endif
                            @if ($meta['flags'] !== [])
                                · {{ implode(', ', $meta['flags']) }}
                            @endif
                        </p>
                        <div class="admin-hub__card-actions">
                            @if ($pending)
                                <form method="post" action="{{ locale_route('accounts.users.approve', ['userId' => $user['id']]) }}">
                                    @csrf
                                    <x-admin.icon-btn kind="approve" type="submit" />
                                </form>
                                <form method="post" action="{{ locale_route('accounts.users.reject', ['userId' => $user['id']]) }}" data-admin-confirm-delete data-confirm-message="Reject and delete this registration?">
                                    @csrf
                                    <x-admin.icon-btn kind="reject" type="submit" />
                                </form>
                            @else
                                <x-admin.icon-btn kind="edit" :href="locale_route('admin.users.edit', ['userId' => $user['id']])" />
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="supplier-table-wrap" data-admin-overview-panel="table">
                <table class="supplier-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Flags</th>
                            <th class="admin-hub__table-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse (array_merge($pendingUsers, $activeUsers) as $user)
                            @php
                                $meta = $userMeta($user);
                                $pending = ! empty($user['pendingApproval']);
                            @endphp
                            <tr data-overview-item data-search-text="{{ $meta['searchText'] }}">
                                <td>
                                    <strong>{{ $user['displayName'] }}</strong>
                                    @if ($pending)
                                        <div class="admin-hub__meta">Awaiting approval</div>
                                    @elseif (! ($user['active'] ?? true))
                                        <div class="admin-hub__meta">Inactive</div>
                                    @endif
                                </td>
                                <td>{{ $user['email'] }}</td>
                                <td>{{ $meta['flags'] !== [] ? implode(', ', $meta['flags']) : '—' }}</td>
                                <td class="admin-hub__table-actions">
                                    @if ($pending)
                                        <form method="post" action="{{ locale_route('accounts.users.approve', ['userId' => $user['id']]) }}" style="display:inline">
                                            @csrf
                                            <x-admin.icon-btn kind="approve" type="submit" />
                                        </form>
                                        <form method="post" action="{{ locale_route('accounts.users.reject', ['userId' => $user['id']]) }}" style="display:inline" data-admin-confirm-delete data-confirm-message="Reject and delete this registration?">
                                            @csrf
                                            <x-admin.icon-btn kind="reject" type="submit" />
                                        </form>
                                    @else
                                        <x-admin.icon-btn kind="edit" :href="locale_route('admin.users.edit', ['userId' => $user['id']])" />
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"><p class="tools-page-lead" data-i18n="accounts.noUsers">No users yet.</p></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
