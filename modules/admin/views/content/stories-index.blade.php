@extends('admin::layouts.shell')

@section('title', 'Stories — Admin — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide sp-app admin-hub" data-overview-filter-root>
        <x-admin.sticky-header>
            <x-slot:search>
                <input type="search" class="tools-input" data-overview-search placeholder="Search stories…" aria-label="Search">
            </x-slot:search>
            <x-slot:actions>
                <a class="tools-btn tools-btn--primary" href="{{ locale_route('admin.stories.create') }}">New story</a>
            </x-slot:actions>
        </x-admin.sticky-header>

        <p class="admin-hub__meta" data-overview-empty hidden>No matches.</p>

        <div class="sp-list">
            @foreach ($stories as $story)
                <div class="sp-list__row" data-overview-item data-search-text="{{ $story['slug'] }}">
                    <div class="sp-list__identity">
                        <strong>{{ $story['slug'] }}</strong>
                        <span class="admin-hub__meta">DE {{ $story['de'] ? '✓' : '—' }} · EN {{ $story['en'] ? '✓' : '—' }}</span>
                    </div>
                    <div class="sp-list__actions">
                        <a class="tools-btn tools-btn--small" href="{{ locale_route('admin.stories.edit', ['slug' => $story['slug']]) }}">Edit</a>
                        <a class="tools-btn tools-btn--small" href="{{ locale_route('playbooks.show', ['slug' => $story['slug']]) }}">View</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
