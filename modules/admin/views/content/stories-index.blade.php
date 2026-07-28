@extends('admin::layouts.shell')

@section('title', 'Stories — Admin — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide sp-app admin-hub" data-overview-filter-root>
        <x-admin.sticky-header :count="count($stories)">
            <x-slot:search>
                <input type="search" class="tools-input" data-overview-search placeholder="Search stories…" aria-label="Search">
            </x-slot:search>
            <x-slot:actions>
                <x-admin.layout-toggle />
                <a class="tools-btn tools-btn--primary" href="{{ locale_route('admin.stories.create') }}">New story</a>
            </x-slot:actions>
        </x-admin.sticky-header>

        <p class="admin-hub__meta" data-overview-empty hidden>No matches.</p>

        <div class="admin-hub__overview" data-admin-overview-root data-layout="table">
            <div class="admin-hub__card-grid" data-admin-overview-panel="cards" hidden>
                @foreach ($stories as $story)
                    <article class="admin-hub__card" data-overview-item data-search-text="{{ $story['slug'] }}">
                        <h3 class="admin-hub__card-title">{{ $story['slug'] }}</h3>
                        <p class="admin-hub__card-meta">DE {{ $story['de'] ? '✓' : '—' }} · EN {{ $story['en'] ? '✓' : '—' }}</p>
                        <div class="admin-hub__card-actions">
                            <x-admin.icon-btn kind="edit" :href="locale_route('admin.stories.edit', ['slug' => $story['slug']])" />
                            <x-admin.icon-btn kind="view" :href="locale_route('playbooks.show', ['slug' => $story['slug']])" />
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="supplier-table-wrap" data-admin-overview-panel="table">
                <table class="supplier-table">
                    <thead>
                        <tr>
                            <th>Slug</th>
                            <th>DE</th>
                            <th>EN</th>
                            <th class="admin-hub__table-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stories as $story)
                            <tr data-overview-item data-search-text="{{ $story['slug'] }}">
                                <td><strong>{{ $story['slug'] }}</strong></td>
                                <td>{{ $story['de'] ? '✓' : '—' }}</td>
                                <td>{{ $story['en'] ? '✓' : '—' }}</td>
                                <td class="admin-hub__table-actions">
                                    <x-admin.icon-btn kind="edit" :href="locale_route('admin.stories.edit', ['slug' => $story['slug']])" />
                                    <x-admin.icon-btn kind="view" :href="locale_route('playbooks.show', ['slug' => $story['slug']])" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
