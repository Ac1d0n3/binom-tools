@extends('admin::layouts.shell')

@php
    use App\Support\AccentColors;
    $pendingUsers = array_values(array_filter($users, static fn ($u) => ! empty($u['pendingApproval'])));
    $activeUsers = array_values(array_filter($users, static fn ($u) => empty($u['pendingApproval'])));
@endphp

@section('title', 'Users — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide sp-app admin-hub" data-overview-filter-root>
        <x-admin.sticky-header>
            <x-slot:search>
                <input type="search" class="tools-input" data-overview-search placeholder="Search users…" aria-label="Search" data-i18n-placeholder="accounts.filterUsers">
            </x-slot:search>
            <x-slot:actions>
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

        @if ($pendingUsers !== [])
            <section class="sp-section">
                <div class="sp-list">
                    @foreach ($pendingUsers as $user)
                        @include('accounts::partials.user-row', ['user' => $user, 'teams' => $teams, 'pending' => true])
                    @endforeach
                </div>
            </section>
        @endif

        <section class="sp-section">
            <div class="sp-list">
                @forelse ($activeUsers as $user)
                    @include('accounts::partials.user-row', ['user' => $user, 'teams' => $teams, 'pending' => false])
                @empty
                    @if ($pendingUsers === [])
                        <p class="tools-page-lead" data-i18n="accounts.noUsers">No users yet.</p>
                    @endif
                @endforelse
            </div>
        </section>
    </div>
@endsection
