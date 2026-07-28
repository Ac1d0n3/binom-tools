@extends('admin::layouts.shell')

@section('title', 'Admin Hub — ' . config('app.name'))

@section('admin_content')
    @php
        $canContent = ! empty($canManageContent);
        $areas = is_array($contentAreas ?? null) ? $contentAreas : [];
        $canArea = static fn (string $key): bool => $canContent || ! empty($areas[$key]);
    @endphp
    <div class="tools-content tools-content--wide sp-app admin-hub">
        <section class="sp-section">
            <div class="sp-list">
                @if ($canArea('stories'))
                    <div class="sp-list__row">
                        <div class="sp-list__identity">
                            <strong data-text-de="Stories" data-text-en="Stories">Stories</strong>
                            <span class="admin-hub__meta">{{ (int) ($storyCount ?? 0) }}</span>
                        </div>
                        <div class="sp-list__actions">
                            <a class="tools-btn tools-btn--small" href="{{ locale_route('admin.stories.index') }}">{{ (int) ($storyCount ?? 0) }}</a>
                        </div>
                    </div>
                @endif
                @if ($canArea('stories') || $canArea('vendorsSources'))
                    <div class="sp-list__row">
                        <div class="sp-list__identity">
                            <strong data-text-de="Advisor" data-text-en="Advisor">Advisor</strong>
                            <span class="admin-hub__meta" data-text-de="Stories, Sources & Vendors" data-text-en="Stories, sources & vendors">Stories, sources & vendors</span>
                        </div>
                        <div class="sp-list__actions">
                            <a class="tools-btn tools-btn--small" href="{{ locale_route('admin.advisor.index') }}">Open</a>
                        </div>
                    </div>
                @endif
                @if ($canArea('planTemplates'))
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
                @if ($canArea('news'))
                    <div class="sp-list__row">
                        <div class="sp-list__identity">
                            <strong data-text-de="Radar / News" data-text-en="Radar / News">Radar / News</strong>
                        </div>
                        <div class="sp-list__actions">
                            <a class="tools-btn tools-btn--small" href="{{ locale_route('admin.radar.index') }}">Open</a>
                        </div>
                    </div>
                @endif
                @if ($canArea('vendorsSources'))
                    <div class="sp-list__row">
                        <div class="sp-list__identity">
                            <strong data-text-de="Vendors & Sources" data-text-en="Vendors & Sources">Vendors & Sources</strong>
                        </div>
                        <div class="sp-list__actions">
                            <a class="tools-btn tools-btn--small" href="{{ locale_route('admin.vendors.index') }}">Vendors</a>
                            <a class="tools-btn tools-btn--small" href="{{ locale_route('admin.suppliers.index') }}">Sources</a>
                        </div>
                    </div>
                @endif
                @if ($canArea('glossary'))
                    <div class="sp-list__row">
                        <div class="sp-list__identity">
                            <strong data-text-de="Glossary" data-text-en="Glossary">Glossary</strong>
                        </div>
                        <div class="sp-list__actions">
                            <a class="tools-btn tools-btn--small" href="{{ locale_route('admin.glossary.index') }}">Open</a>
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
                            @if (!empty($canManageContent)) · Content admin @endif
                        </span>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
