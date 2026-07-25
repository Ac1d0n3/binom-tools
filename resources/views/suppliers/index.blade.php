@extends('layouts.tools', [
    'mainClass' => 'tools-shell__main--overview',
])

@section('title', 'Suppliers — ' . config('app.name'))
@section('meta_description', 'Supplier library — core fields, dimensions, PII/DSDR and standard measure templates for Salesforce, HubSpot and GA4.')

@section('content')
    <div class="tools-content tools-content--overview tools-content--suppliers" data-overview-filter-root>
        <div class="tools-overview-sticky-header supplier-hub-sticky">
            <h1 class="tools-page-title" data-i18n="suppliers.indexTitle">Suppliers</h1>
            <p class="tools-page-lead supplier-hub-sticky__lead" data-hub-lead data-i18n="suppliers.indexLead">
                Reusable core fields, dimensions, PII/DSDR hints and measure templates per source product — start here, then adapt per customer.
            </p>
            <p class="supplier-hub-disclaimer" data-i18n="suppliers.disclaimer">
                Templates only — grain, filters, custom fields and ownership are firm-specific.
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
                        data-i18n-placeholder="suppliers.searchPlaceholder"
                        placeholder="Search Salesforce, ARR, PII…"
                    />
                </label>
                <label class="tools-overview-product-filter">
                    <span class="sr-only" data-i18n="suppliers.domainLabel">Domain</span>
                    <span class="tools-overview-sort__field">
                        <select class="tools-overview-sort__select" data-overview-product>
                            <option value="all" data-i18n="suppliers.domainAll">All domains</option>
                            @foreach ($availableDomains as $domainId)
                                @php
                                    $domainLabel = $domains[$domainId] ?? ['de' => $domainId, 'en' => $domainId];
                                    $dLabelEn = $domainLabel['en'] ?? $domainId;
                                    $dLabelDe = $domainLabel['de'] ?? $dLabelEn;
                                @endphp
                                <option
                                    value="{{ $domainId }}"
                                    data-text-de="{{ $dLabelDe }}"
                                    data-text-en="{{ $dLabelEn }}"
                                >{{ $dLabelEn }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down tools-overview-sort__icon" aria-hidden="true"></i>
                    </span>
                </label>
            </div>
        </div>

        <div class="tools-overview-scroll supplier-hub-scroll">
            <p class="tools-overview-empty" data-overview-empty hidden data-i18n="overview.noResults">
                No matches for your search.
            </p>

            <div class="supplier-hub-grid" role="list">
                @foreach ($products as $product)
                    @php
                        $id = (string) ($product['id'] ?? '');
                        $domainId = is_string($product['domain'] ?? null) ? $product['domain'] : '';
                        $labelEn = $product['label']['en'] ?? $id;
                        $labelDe = $product['label']['de'] ?? $labelEn;
                        $purposeEn = $product['shortPurpose']['en'] ?? '';
                        $purposeDe = $product['shortPurpose']['de'] ?? $purposeEn;
                        $domainLabel = $domains[$domainId] ?? ['de' => $domainId, 'en' => $domainId];
                        $domainEn = $domainLabel['en'] ?? $domainId;
                        $domainDe = $domainLabel['de'] ?? $domainEn;
                        $searchText = strtolower($labelEn.' '.$labelDe.' '.$purposeEn.' '.$purposeDe.' '.$domainEn.' '.$domainDe.' '.$id);
                    @endphp
                    <a
                        href="{{ locale_route('suppliers.show', ['slug' => $id]) }}"
                        class="supplier-hub-card"
                        role="listitem"
                        data-overview-item
                        data-products="{{ $domainId }}"
                        data-search-text="{{ $searchText }}"
                        data-product-id="{{ $id }}"
                    >
                        <span class="supplier-hub-card__domain" data-text-de="{{ $domainDe }}" data-text-en="{{ $domainEn }}">{{ $domainEn }}</span>
                        <span class="supplier-hub-card__title" data-text-de="{{ $labelDe }}" data-text-en="{{ $labelEn }}">{{ $labelEn }}</span>
                        <span class="supplier-hub-card__purpose" data-text-de="{{ $purposeDe }}" data-text-en="{{ $purposeEn }}">{{ $purposeEn }}</span>
                        <span class="supplier-hub-card__cta" data-i18n="suppliers.openLibrary">Open library →</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
