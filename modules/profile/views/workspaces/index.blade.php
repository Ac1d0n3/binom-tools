@extends('profile::layouts.shell')

@section('title', 'My Workspaces — ' . config('app.name'))

@section('profile_content')
    <div class="tools-content tools-content--wide sp-app admin-hub">
        <h1 class="tools-page-title">My Workspaces</h1>
        <x-accounts.flash :status-map="[
            'workspace-saved' => 'Saved',
            'workspace-active' => 'Active workspace updated',
            'workspace-duplicated' => 'Workspace duplicated',
            'workspace-archived' => 'Workspace archived',
        ]" />
        <section class="sp-section">
            <x-admin.page-header title="Workspaces" titleDe="Workspaces" titleEn="Workspaces">
                <x-slot:actions>
                    <a href="{{ locale_route('profile.workspaces.create') }}" class="tools-btn tools-btn--primary">Create</a>
                </x-slot:actions>
            </x-admin.page-header>
            <div class="sp-list">
                @foreach ($workspaces as $ws)
                    <div class="sp-list__row">
                        <div class="sp-list__identity">
                            <strong>{{ $ws['name'] }}</strong>
                            <span class="admin-hub__meta">
                                {{ $ws['stack'] ?? 'unknown' }}
                                @if (!empty($ws['archived'])) · archived @endif
                                @if (($activeWorkspaceId ?? null) === $ws['id']) · active @endif
                            </span>
                        </div>
                        <div class="sp-list__actions">
                            @if (empty($ws['archived']))
                                <form method="post" action="{{ locale_route('profile.workspaces.activate', ['workspaceId' => $ws['id']]) }}" style="display:inline">@csrf
                                    <button class="tools-btn tools-btn--small" type="submit">Activate</button>
                                </form>
                                <form method="post" action="{{ locale_route('profile.workspaces.duplicate', ['workspaceId' => $ws['id']]) }}" style="display:inline">@csrf
                                    <button class="tools-btn tools-btn--small" type="submit">Duplicate</button>
                                </form>
                                <a class="tools-btn tools-btn--small" href="{{ locale_route('profile.workspaces.edit', ['workspaceId' => $ws['id']]) }}">Edit</a>
                                <form method="post" action="{{ locale_route('profile.workspaces.archive', ['workspaceId' => $ws['id']]) }}" style="display:inline">@csrf
                                    <button class="tools-btn tools-btn--small tools-btn--danger" type="submit">Archive</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
@endsection
