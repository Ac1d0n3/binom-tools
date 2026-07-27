@extends('admin::layouts.shell')

@section('title', 'Admin Hub — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide sp-app admin-hub">
        <h1 class="tools-page-title" data-text-de="Admin Hub" data-text-en="Admin Hub">Admin Hub</h1>
        <p class="tools-page-lead" data-hub-lead data-text-de="Persönlicher Arbeitsbereich und Content-Administration — eigene Sidebar, Speichern ohne Git-Commit." data-text-en="Personal workspace and content admin — dedicated sidebar, save without git commit.">Personal workspace and content admin — dedicated sidebar, save without git commit.</p>

        <x-admin.help id="hub" titleDe="Kurzhilfe" titleEn="Quick help">
            <p data-text-de="My Workspaces trennen Kunden/Stacks. Administration (rechts in der Sidebar) ist nur mit Manage-Rechten sichtbar. Stories und Templates bleiben Markdown-Dateien unter content/." data-text-en="My Workspaces separate clients/stacks. Administration (sidebar) needs manage flags. Stories and templates stay markdown files under content/.">My Workspaces separate clients/stacks. Administration (sidebar) needs manage flags. Stories and templates stay markdown files under content/.</p>
        </x-admin.help>

        <section class="sp-section">
            <x-admin.page-header title="My Workspaces" titleDe="My Workspaces" titleEn="My Workspaces">
                <x-slot:actions>
                    <a href="{{ locale_route('admin.workspaces.create') }}" class="tools-btn tools-btn--primary" data-text-de="Workspace anlegen" data-text-en="Create workspace">Create workspace</a>
                </x-slot:actions>
            </x-admin.page-header>
            <div class="sp-list">
                @forelse ($workspaces as $ws)
                    <div class="sp-list__row">
                        <div class="sp-list__identity">
                            <strong>{{ $ws['name'] ?? '-' }}</strong>
                            <span class="admin-hub__meta">{{ $ws['stack'] ?? 'unknown' }}@if (!empty($ws['label'])) · {{ $ws['label'] }}@endif</span>
                        </div>
                        <div class="sp-list__actions">
                            @if (($activeWorkspaceId ?? null) === ($ws['id'] ?? null))
                                <span class="admin-hub__meta" data-text-de="Aktiv" data-text-en="Active">Active</span>
                            @else
                                <form method="post" action="{{ locale_route('admin.workspaces.activate', ['workspaceId' => $ws['id']]) }}" style="display:inline">@csrf
                                    <button type="submit" class="tools-btn tools-btn--small" data-text-de="Aktivieren" data-text-en="Activate">Activate</button>
                                </form>
                            @endif
                            <a class="tools-btn tools-btn--small" href="{{ locale_route('admin.workspaces.edit', ['workspaceId' => $ws['id']]) }}" data-text-de="Bearbeiten" data-text-en="Edit">Edit</a>
                        </div>
                    </div>
                @empty
                    <p class="tools-page-lead" data-text-de="Noch keine Workspaces." data-text-en="No workspaces yet.">No workspaces yet.</p>
                @endforelse
            </div>
        </section>

        <section class="sp-section">
            <x-admin.page-header title="Shortcuts" titleDe="Shortcuts" titleEn="Shortcuts" />
            <div class="sp-list">
                <div class="sp-list__row">
                    <div class="sp-list__identity"><strong data-text-de="My Plans" data-text-en="My Plans">My Plans</strong></div>
                    <div class="sp-list__actions"><a class="tools-btn tools-btn--small" href="{{ locale_route('admin.plans.index') }}">Open</a></div>
                </div>
                <div class="sp-list__row">
                    <div class="sp-list__identity"><strong data-text-de="Gelesene Stories" data-text-en="Read stories">Read stories</strong></div>
                    <div class="sp-list__actions"><a class="tools-btn tools-btn--small" href="{{ locale_route('admin.reads.index') }}">Open</a></div>
                </div>
                @if (!empty($canManageUsers))
                    <div class="sp-list__row">
                        <div class="sp-list__identity"><strong data-text-de="Stories bearbeiten" data-text-en="Edit stories">Edit stories</strong></div>
                        <div class="sp-list__actions"><a class="tools-btn tools-btn--small" href="{{ locale_route('admin.stories.index') }}">Open</a></div>
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection
