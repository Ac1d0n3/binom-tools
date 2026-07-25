@extends('layouts.tools', [
    'viteEntries' => ['resources/css/sprint-planner.css'],
])

@php
    use App\Support\AccentColors;
    $pendingUsers = array_values(array_filter($users, static fn ($u) => ! empty($u['pendingApproval'])));
    $activeUsers = array_values(array_filter($users, static fn ($u) => empty($u['pendingApproval'])));
@endphp

@section('title', 'Users — ' . config('app.name'))

@section('content')
    <div class="tools-content tools-content--wide sp-app">
        <h1 class="tools-page-title" data-i18n="accounts.usersTitle">Users</h1>
        <p class="tools-page-lead" data-i18n="accounts.usersLead">
            Managed accounts — passwords are hashed only.
        </p>

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

        @if ($pendingUsers !== [])
            <section class="sp-section" aria-labelledby="accounts-pending-heading">
                <div class="sp-section__header">
                    <h2 id="accounts-pending-heading" class="sp-section__title" data-i18n="accounts.pendingUsers">Pending approval</h2>
                </div>
                <div class="sp-list">
                    @foreach ($pendingUsers as $user)
                        @include('accounts.partials.user-row', ['user' => $user, 'teams' => $teams, 'pending' => true])
                    @endforeach
                </div>
            </section>
        @endif

        <section class="sp-section" aria-labelledby="accounts-users-heading">
            <div class="sp-section__header">
                <h2 id="accounts-users-heading" class="sp-section__title" data-i18n="accounts.existingUsers">Users</h2>
                <a href="{{ locale_route('accounts.users.create') }}" class="tools-btn tools-btn--primary" data-i18n="accounts.addUser">
                    Add user
                </a>
            </div>

            <div class="sp-list">
                @forelse ($activeUsers as $user)
                    @include('accounts.partials.user-row', ['user' => $user, 'teams' => $teams, 'pending' => false])
                @empty
                    <p class="tools-page-lead" data-i18n="accounts.noUsers">No users yet.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
