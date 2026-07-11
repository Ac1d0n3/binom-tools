@extends('layouts.tools')

@section('title', 'Tools — ' . config('app.name'))

@section('content')
    <div class="tools-content" data-overview-filter-root>
        <h1 class="tools-page-title" data-i18n="tools.overviewTitle">Tools</h1>
        <p class="tools-page-lead" data-i18n="tools.overviewLead">
            Interaktive Referenz-Workflows — Schritt für Schritt, copy-paste-fähig.
        </p>

        <div class="tools-overview-toolbar">
            <label class="tools-overview-search">
                <span class="sr-only" data-i18n="overview.searchLabel">Search</span>
                <i class="fa-solid fa-magnifying-glass tools-overview-search__icon" aria-hidden="true"></i>
                <input
                    type="search"
                    class="tools-overview-search__input"
                    data-overview-search
                    autocomplete="off"
                    data-i18n-placeholder="overview.searchPlaceholder"
                    placeholder="Search tools…"
                />
            </label>
        </div>

        <p class="tools-overview-empty" data-overview-empty hidden data-i18n="overview.noResults">
            No matches for your search.
        </p>

        <div class="tools-card-grid">
            @foreach ($navItems as $item)
                @php
                    $searchText = strtolower(implode(' ', [
                        $item['label']['de'] ?? '',
                        $item['label']['en'] ?? '',
                        $item['description']['de'] ?? '',
                        $item['description']['en'] ?? '',
                        $item['id'] ?? '',
                    ]));
                @endphp
                <x-tools.card
                    :href="route($item['route'])"
                    :title="$item['label']['en']"
                    :description="$item['description']['en']"
                    :icon="$item['icon']"
                    :accent="$item['accent']"
                    :card-id="$item['id']"
                    :example="$item['example'] ?? false"
                    :overview-item="true"
                    :search-text="$searchText"
                />
            @endforeach
        </div>
    </div>
@endsection
