@extends('foundations.layouts.tools', [
    'mainClass' => 'tools-shell__main--overview',
])

@section('title', 'Sources — ' . config('app.name'))
@section('meta_description', 'Supplier library for analytics governance — core fields, dimensions, PII/DSDR and KPI templates per source (Salesforce, HubSpot, GA4). Start here, then adapt per customer.')

@section('content')
    <div class="tools-content tools-content--overview tools-content--suppliers" data-overview-filter-root>
        <div class="tools-overview-sticky-header supplier-hub-sticky">
            @php
                $initialProductCount = count($products);
            @endphp
            <div class="supplier-hub-sticky__heading">
                <h1 class="tools-page-title" data-i18n="suppliers.indexTitle">Sources</h1>
                <p
                    class="supplier-hub-sticky__count"
                    data-overview-result-count
                    data-overview-count-mode="items"
                    data-i18n="suppliers.visibleProductCount"
                    data-i18n-count="{{ $initialProductCount }}"
                >{{ $initialProductCount }} products</p>
            </div>
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
                <x-shared.ui.layout-toggle />
            </div>
        </div>

        <div class="tools-overview-scroll supplier-hub-scroll">
            <p class="tools-overview-empty" data-overview-empty hidden data-i18n="overview.noResults">
                No matches for your search.
            </p>

            <div class="supplier-hub-grid" role="list" data-overview-stories-grid>
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

                        $searchParts = [$labelEn, $labelDe, $purposeEn, $purposeDe, $domainEn, $domainDe, $id];
                        foreach (($product['measures'] ?? []) as $measure) {
                            if (! is_array($measure)) {
                                continue;
                            }
                            $searchParts[] = (string) ($measure['id'] ?? '');
                            $searchParts[] = (string) ($measure['label']['en'] ?? '');
                            $searchParts[] = (string) ($measure['label']['de'] ?? '');
                            $searchParts[] = (string) ($measure['formula'] ?? '');
                            $searchParts[] = (string) ($measure['question']['en'] ?? '');
                            $searchParts[] = (string) ($measure['question']['de'] ?? '');
                            $searchParts[] = (string) ($measure['sourceHints']['en'] ?? '');
                            $searchParts[] = (string) ($measure['sourceHints']['de'] ?? '');
                            foreach (($measure['fieldsUsed'] ?? []) as $fieldUsed) {
                                if (is_string($fieldUsed) && $fieldUsed !== '') {
                                    $searchParts[] = $fieldUsed;
                                }
                            }
                        }
                        foreach (($product['entities'] ?? []) as $entity) {
                            if (! is_array($entity)) {
                                continue;
                            }
                            $searchParts[] = (string) ($entity['id'] ?? '');
                            $searchParts[] = (string) ($entity['label']['en'] ?? '');
                            $searchParts[] = (string) ($entity['label']['de'] ?? '');
                        }
                        foreach (($product['fields'] ?? []) as $field) {
                            if (! is_array($field)) {
                                continue;
                            }
                            $searchParts[] = (string) ($field['entity'] ?? '');
                            $searchParts[] = (string) ($field['name'] ?? '');
                        }
                        foreach (($product['dimensions'] ?? []) as $dim) {
                            if (! is_array($dim)) {
                                continue;
                            }
                            $searchParts[] = (string) ($dim['id'] ?? '');
                            $searchParts[] = (string) ($dim['label']['en'] ?? '');
                            $searchParts[] = (string) ($dim['label']['de'] ?? '');
                        }
                        foreach (($product['pii'] ?? []) as $piiRow) {
                            if (! is_array($piiRow)) {
                                continue;
                            }
                            $searchParts[] = (string) ($piiRow['entity'] ?? '');
                            foreach (($piiRow['fields'] ?? []) as $piiField) {
                                if (is_string($piiField) && $piiField !== '') {
                                    $searchParts[] = $piiField;
                                }
                            }
                        }
                        foreach (($product['skip'] ?? []) as $skipRow) {
                            if (is_array($skipRow)) {
                                $searchParts[] = (string) ($skipRow['name'] ?? '');
                            }
                        }
                        foreach (($product['skipTables'] ?? []) as $skipTable) {
                            if (is_array($skipTable)) {
                                $searchParts[] = (string) ($skipTable['name'] ?? '');
                            }
                        }
                        $searchText = strtolower(implode(' ', array_filter(
                            $searchParts,
                            static fn ($v) => is_string($v) && trim($v) !== '',
                        )));
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
