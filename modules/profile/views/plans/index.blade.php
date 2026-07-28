@extends('profile::layouts.shell')

@section('title', 'My Plans — ' . config('app.name'))

@section('profile_content')
    <div class="tools-content tools-content--wide sp-app admin-hub">
        <h1 class="tools-page-title">My Plans</h1>
        <p class="admin-hub__meta">Active workspace: {{ $activeWorkspaceId ?? 'none' }}</p>
        <div class="sp-list">
            @forelse ($plans as $plan)
                @php
                    $title = $plan['translations']['en']['title'] ?? $plan['translations']['de']['title'] ?? $plan['id'] ?? 'Plan';
                @endphp
                <div class="sp-list__row">
                    <div class="sp-list__identity">
                        <strong>{{ $title }}</strong>
                        <span class="admin-hub__meta">{{ $plan['id'] }} · workspace {{ $plan['workspaceId'] ?? '—' }}</span>
                    </div>
                    <div class="sp-list__actions">
                        <form method="post" action="{{ locale_route('profile.plans.assign', ['planId' => $plan['id']]) }}" style="display:inline-flex;gap:.35rem;align-items:center">
                            @csrf
                            <select name="workspaceId">
                                <option value="">—</option>
                                @foreach ($workspaces as $ws)
                                    <option value="{{ $ws['id'] }}" @selected(($plan['workspaceId'] ?? null) === $ws['id'])>{{ $ws['name'] }}</option>
                                @endforeach
                            </select>
                            <button class="tools-btn tools-btn--small" type="submit">Assign</button>
                        </form>
                        <form method="post" action="{{ locale_route('profile.plans.duplicate', ['planId' => $plan['id']]) }}" style="display:inline-flex;gap:.35rem;align-items:center">
                            @csrf
                            <select name="workspaceId">
                                @foreach ($workspaces as $ws)
                                    <option value="{{ $ws['id'] }}" @selected(($activeWorkspaceId ?? null) === $ws['id'])>{{ $ws['name'] }}</option>
                                @endforeach
                            </select>
                            <button class="tools-btn tools-btn--small" type="submit">Duplicate</button>
                        </form>
                        <a class="tools-btn tools-btn--small" href="{{ locale_route('sprint-planner.show', ['instanceId' => $plan['id']]) }}">Open</a>
                    </div>
                </div>
            @empty
                <p class="tools-page-lead">No plans yet.</p>
            @endforelse
        </div>
    </div>
@endsection
