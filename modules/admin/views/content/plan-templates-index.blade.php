@extends('admin::layouts.shell')

@section('title', 'Plan templates — Admin — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide sp-app admin-hub" data-overview-filter-root>
        <x-admin.sticky-header>
            <x-slot:search>
                <input type="search" class="tools-input" data-overview-search placeholder="Search templates…" aria-label="Search">
            </x-slot:search>
            <x-slot:actions>
                <a class="tools-btn tools-btn--primary" href="{{ locale_route('admin.plan-templates.create') }}">New template</a>
            </x-slot:actions>
        </x-admin.sticky-header>

        <p class="admin-hub__meta" data-overview-empty hidden>No matches.</p>

        <div class="sp-list">
            @foreach ($templates as $tpl)
                <div class="sp-list__row" data-overview-item data-search-text="{{ $tpl['slug'] }}">
                    <div class="sp-list__identity">
                        <strong>{{ $tpl['slug'] }}</strong>
                        <span class="admin-hub__meta">DE {{ $tpl['de'] ? '✓' : '—' }} · EN {{ $tpl['en'] ? '✓' : '—' }}</span>
                    </div>
                    <div class="sp-list__actions">
                        <a class="tools-btn tools-btn--small" href="{{ locale_route('admin.plan-templates.edit', ['slug' => $tpl['slug']]) }}">Edit</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
