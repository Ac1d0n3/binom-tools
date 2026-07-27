@extends('profile::layouts.shell')

@section('title', 'Profile Hub — ' . config('app.name'))

@section('profile_content')
    <div class="tools-content tools-content--wide sp-app admin-hub">
        <h1 class="tools-page-title" data-text-de="Profil Hub" data-text-en="Profile Hub">Profile Hub</h1>
        <p class="tools-page-lead" data-hub-lead data-text-de="Dein persönlicher Arbeitsbereich — Workspaces, Pläne und Lernfortschritt." data-text-en="Your personal area — workspaces, plans, and learning progress.">Your personal area — workspaces, plans, and learning progress.</p>

        <x-admin.help id="profile-hub" titleDe="Kurzhilfe" titleEn="Quick help">
            <p data-text-de="Workspaces trennen Kunden/Stacks. Navigation links führt zu den Detailseiten — hier nur der aktuelle Stand." data-text-en="Workspaces separate clients/stacks. Use the sidebar for detail pages — this dashboard shows status only.">Workspaces separate clients/stacks. Use the sidebar for detail pages — this dashboard shows status only.</p>
        </x-admin.help>

        <section class="sp-section">
            <x-admin.page-header title="Active workspace" titleDe="Aktiver Workspace" titleEn="Active workspace" />
            @if ($activeWorkspace)
                <div class="sp-list">
                    <div class="sp-list__row">
                        <div class="sp-list__identity">
                            <strong>{{ $activeWorkspace['name'] ?? '-' }}</strong>
                            <span class="admin-hub__meta">{{ $activeWorkspace['stack'] ?? 'unknown' }}@if (!empty($activeWorkspace['label'])) · {{ $activeWorkspace['label'] }}@endif</span>
                        </div>
                        <div class="sp-list__actions">
                            <a class="tools-btn tools-btn--small" href="{{ locale_route('profile.workspaces.edit', ['workspaceId' => $activeWorkspace['id']]) }}" data-text-de="Bearbeiten" data-text-en="Edit">Edit</a>
                        </div>
                    </div>
                </div>
            @elseif ($workspaceCount === 0)
                <p class="tools-page-lead" data-text-de="Noch kein Workspace — lege den ersten an." data-text-en="No workspace yet — create your first one.">No workspace yet — create your first one.</p>
                <p><a href="{{ locale_route('profile.workspaces.create') }}" class="tools-btn tools-btn--primary" data-text-de="Workspace anlegen" data-text-en="Create workspace">Create workspace</a></p>
            @else
                <p class="tools-page-lead" data-text-de="Kein aktiver Workspace — bitte unter My Workspaces aktivieren." data-text-en="No active workspace — activate one under My Workspaces.">No active workspace — activate one under My Workspaces.</p>
                <p><a href="{{ locale_route('profile.workspaces.index') }}" class="tools-btn" data-text-de="Zu My Workspaces" data-text-en="Go to My Workspaces">Go to My Workspaces</a></p>
            @endif
        </section>

        <section class="sp-section">
            <x-admin.page-header title="Overview" titleDe="Übersicht" titleEn="Overview" />
            <div class="sp-list">
                <div class="sp-list__row">
                    <div class="sp-list__identity">
                        <strong data-text-de="Workspaces" data-text-en="Workspaces">Workspaces</strong>
                        <span class="admin-hub__meta">{{ (int) $workspaceCount }}</span>
                    </div>
                    <div class="sp-list__actions">
                        <a class="tools-btn tools-btn--small" href="{{ locale_route('profile.workspaces.index') }}">{{ (int) $workspaceCount }}</a>
                    </div>
                </div>
                <div class="sp-list__row">
                    <div class="sp-list__identity">
                        <strong data-text-de="Pläne" data-text-en="Plans">Plans</strong>
                        <span class="admin-hub__meta">{{ (int) $planCount }}</span>
                    </div>
                    <div class="sp-list__actions">
                        <a class="tools-btn tools-btn--small" href="{{ locale_route('profile.plans.index') }}">{{ (int) $planCount }}</a>
                    </div>
                </div>
                <div class="sp-list__row">
                    <div class="sp-list__identity">
                        <strong data-text-de="Gelesene Stories" data-text-en="Read stories">Read stories</strong>
                        <span class="admin-hub__meta">{{ (int) $readCount }}</span>
                    </div>
                    <div class="sp-list__actions">
                        <a class="tools-btn tools-btn--small" href="{{ locale_route('profile.reads.index') }}">{{ (int) $readCount }}</a>
                    </div>
                </div>
                <div class="sp-list__row">
                    <div class="sp-list__identity">
                        <strong data-text-de="Quiz-Versuche" data-text-en="Quiz attempts">Quiz attempts</strong>
                        <span class="admin-hub__meta">{{ (int) $quizAttempts }}@if ($quizBestTotal > 0) · best {{ (int) $quizBestScore }}/{{ (int) $quizBestTotal }}@endif</span>
                    </div>
                    <div class="sp-list__actions">
                        <a class="tools-btn tools-btn--small" href="{{ locale_route('profile.quiz.index') }}">{{ (int) $quizAttempts }}</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
