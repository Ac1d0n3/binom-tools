@extends('admin::layouts.shell')

@section('title', 'Admin Hub — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide sp-app admin-hub">
        <section class="sp-section">
            <div class="sp-list">
                @if (!empty($canManageUsers))
                    <div class="sp-list__row">
                        <div class="sp-list__identity">
                            <strong data-text-de="Stories" data-text-en="Stories">Stories</strong>
                            <span class="admin-hub__meta">{{ (int) ($storyCount ?? 0) }}</span>
                        </div>
                        <div class="sp-list__actions">
                            <a class="tools-btn tools-btn--small" href="{{ locale_route('admin.stories.index') }}">{{ (int) ($storyCount ?? 0) }}</a>
                        </div>
                    </div>
                    <div class="sp-list__row">
                        <div class="sp-list__identity">
                            <strong data-text-de="Plan-Templates" data-text-en="Plan templates">Plan templates</strong>
                            <span class="admin-hub__meta">{{ (int) ($templateCount ?? 0) }}</span>
                        </div>
                        <div class="sp-list__actions">
                            <a class="tools-btn tools-btn--small" href="{{ locale_route('admin.plan-templates.index') }}">{{ (int) ($templateCount ?? 0) }}</a>
                        </div>
                    </div>
                @endif
                <div class="sp-list__row">
                    <div class="sp-list__identity">
                        <strong data-text-de="Rechte" data-text-en="Permissions">Permissions</strong>
                        <span class="admin-hub__meta">
                            @if (!empty($canManageUsers)) Users @endif
                            @if (!empty($canManageUsers) && !empty($canManageTeams)) · @endif
                            @if (!empty($canManageTeams)) Teams @endif
                        </span>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
