@extends('layouts.tools')

@section('title', 'Stories — ' . config('app.name'))

@section('content')
    <div class="tools-content" data-overview-filter-root>
        <h1 class="tools-page-title" data-i18n="playbooks.indexTitle">Stories</h1>
        <p class="tools-page-lead" data-i18n="playbooks.indexLead">
            Schritt-für-Schritt-Governance-Guides — von der Idee bis zur Umsetzung.
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
                    placeholder="Search playbooks…"
                />
            </label>

            @if (count($tags) > 0)
                <div class="tools-overview-tags" role="group" aria-label="Tags">
                    <button
                        type="button"
                        class="tools-filter-chip tools-filter-chip--active"
                        data-overview-tag="all"
                        data-i18n="overview.tagAll"
                    >
                        All
                    </button>
                    @foreach ($tags as $tag)
                        <button
                            type="button"
                            class="tools-filter-chip"
                            data-overview-tag="{{ $tag }}"
                        >
                            {{ $tag }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <p class="tools-overview-empty" data-overview-empty hidden data-i18n="overview.noResults">
            No matches for your search.
        </p>

        @if (count($playbooks) === 0)
            <p class="tools-section__lead" data-i18n="playbooks.empty">No playbooks published yet.</p>
        @else
            <div class="tools-card-grid">
                @foreach ($playbooks as $item)
                    <x-playbooks.card :item="$item" />
                @endforeach
            </div>
        @endif
    </div>
@endsection
