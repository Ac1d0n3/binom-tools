@extends('admin::layouts.shell')

@section('title', 'Plan templates — Admin — ' . config('app.name'))

@section('admin_content')
    <div class="tools-content tools-content--wide sp-app admin-hub" data-overview-filter-root>
        <x-admin.sticky-header :count="count($templates)">
            <x-slot:search>
                <input type="search" class="tools-input" data-overview-search placeholder="Search templates…" aria-label="Search">
            </x-slot:search>
            <x-slot:actions>
                <x-admin.layout-toggle />
                <a class="tools-btn tools-btn--primary" href="{{ locale_route('admin.plan-templates.create') }}">New template</a>
            </x-slot:actions>
        </x-admin.sticky-header>

        <p class="admin-hub__meta" data-overview-empty hidden>No matches.</p>

        <div class="admin-hub__overview" data-admin-overview-root data-layout="table">
            <div class="admin-hub__card-grid" data-admin-overview-panel="cards" hidden>
                @foreach ($templates as $tpl)
                    <article class="admin-hub__card" data-overview-item data-search-text="{{ $tpl['slug'] }}">
                        <h3 class="admin-hub__card-title">{{ $tpl['slug'] }}</h3>
                        <p class="admin-hub__card-meta">DE {{ $tpl['de'] ? '✓' : '—' }} · EN {{ $tpl['en'] ? '✓' : '—' }}</p>
                        <div class="admin-hub__card-actions">
                            <x-admin.icon-btn kind="edit" :href="locale_route('admin.plan-templates.edit', ['slug' => $tpl['slug']])" />
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
                        @foreach ($templates as $tpl)
                            <tr data-overview-item data-search-text="{{ $tpl['slug'] }}">
                                <td><strong>{{ $tpl['slug'] }}</strong></td>
                                <td>{{ $tpl['de'] ? '✓' : '—' }}</td>
                                <td>{{ $tpl['en'] ? '✓' : '—' }}</td>
                                <td class="admin-hub__table-actions">
                                    <x-admin.icon-btn kind="edit" :href="locale_route('admin.plan-templates.edit', ['slug' => $tpl['slug']])" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
