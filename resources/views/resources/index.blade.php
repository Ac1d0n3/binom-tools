@extends('layouts.tools', [
    'mainClass' => 'tools-shell__main--overview',
])

@section('title', 'Resources — ' . config('app.name'))
@section('meta_description', 'Curated vendor help, governance, learning and compliance links — not a sales directory. Filter by family, SaaS/OSS and cloud residency (GDPR).')

@section('content')
    <div class="tools-content tools-content--overview tools-content--resources" data-overview-filter-root>
        <div class="tools-overview-sticky-header vendor-resources-sticky">
            @php
                $initialVendorCount = count(array_unique(array_values(array_filter(array_map(
                    static fn ($product) => is_string($product['vendor'] ?? null) ? $product['vendor'] : '',
                    $products,
                )))));
            @endphp
            <div class="vendor-resources-sticky__heading">
                <h1 class="tools-page-title" data-i18n="resources.indexTitle">Vendor resources</h1>
                <p
                    class="vendor-resources-sticky__count"
                    data-overview-result-count
                    data-i18n="resources.visibleVendorCount"
                    data-i18n-count="{{ $initialVendorCount }}"
                >{{ $initialVendorCount }} vendors</p>
            </div>
            <p class="tools-page-lead vendor-resources-sticky__lead" data-hub-lead data-i18n="resources.indexLead">
                Official help, governance, learning paths, cloud residency (GDPR) and compliance — filter by vendor, family, SaaS/Open Source or residency.
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
                        data-i18n-placeholder="resources.searchPlaceholder"
                        placeholder="Search products and links…"
                    />
                </label>
                <label class="tools-overview-product-filter">
                    <span class="sr-only" data-i18n="resources.stackLabel">Stack</span>
                    <span class="tools-overview-sort__field">
                        <select class="tools-overview-sort__select" data-overview-stack>
                            <option value="all" data-i18n="resources.stackAll">All stacks</option>
                            @foreach ($stacks as $stackId => $stack)
                                @php
                                    $stackLabelEn = $stack['label']['en'] ?? $stackId;
                                    $stackLabelDe = $stack['label']['de'] ?? $stackLabelEn;
                                    $stackDescEn = $stack['description']['en'] ?? '';
                                    $stackDescDe = $stack['description']['de'] ?? $stackDescEn;
                                    $stackProducts = is_array($stack['products'] ?? null)
                                        ? array_values(array_filter($stack['products'], static fn ($id) => is_string($id) && $id !== ''))
                                        : [];
                                    $stackSlots = [];
                                    if (is_array($stack['slots'] ?? null)) {
                                        foreach ($stack['slots'] as $slot) {
                                            if (! is_array($slot)) {
                                                continue;
                                            }
                                            $slotProducts = is_array($slot['products'] ?? null)
                                                ? array_values(array_filter($slot['products'], static fn ($id) => is_string($id) && $id !== ''))
                                                : [];
                                            if ($slotProducts === []) {
                                                continue;
                                            }
                                            $roleEn = is_string($slot['role']['en'] ?? null) ? $slot['role']['en'] : '';
                                            $roleDe = is_string($slot['role']['de'] ?? null) ? $slot['role']['de'] : $roleEn;
                                            $stackSlots[] = [
                                                'role' => ['de' => $roleDe, 'en' => $roleEn],
                                                'products' => $slotProducts,
                                                'chooseOne' => (bool) ($slot['chooseOne'] ?? false),
                                            ];
                                        }
                                    }
                                @endphp
                                <option
                                    value="{{ $stackId }}"
                                    data-text-de="{{ $stackLabelDe }}"
                                    data-text-en="{{ $stackLabelEn }}"
                                    data-description-de="{{ $stackDescDe }}"
                                    data-description-en="{{ $stackDescEn }}"
                                    data-products="{{ implode(',', $stackProducts) }}"
                                    data-slots="{{ json_encode($stackSlots, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}"
                                >{{ $stackLabelEn }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down tools-overview-sort__icon" aria-hidden="true"></i>
                    </span>
                </label>
                <label class="tools-overview-product-filter">
                    <span class="sr-only" data-i18n="resources.familyLabel">Product family</span>
                    <span class="tools-overview-sort__field">
                        <select class="tools-overview-sort__select" data-overview-product>
                            <option value="all" data-i18n="resources.familyAll">All families</option>
                            @foreach ($availableFamilies as $familyId)
                                @php
                                    $familyLabel = $families[$familyId] ?? ['de' => $familyId, 'en' => $familyId];
                                    $labelEn = $familyLabel['en'] ?? $familyId;
                                    $labelDe = $familyLabel['de'] ?? $labelEn;
                                @endphp
                                <option
                                    value="{{ $familyId }}"
                                    data-text-de="{{ $labelDe }}"
                                    data-text-en="{{ $labelEn }}"
                                >{{ $labelEn }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down tools-overview-sort__icon" aria-hidden="true"></i>
                    </span>
                </label>
                <label class="tools-overview-product-filter">
                    <span class="sr-only" data-i18n="resources.modelLabel">Licensing model</span>
                    <span class="tools-overview-sort__field">
                        <select class="tools-overview-sort__select" data-overview-model>
                            <option value="all" data-i18n="resources.modelAll">All models</option>
                            <option value="opensource" data-i18n="resources.modelOpenSource">Open Source</option>
                            <option value="onprem" data-i18n="resources.modelOnPrem">On-prem</option>
                            <option value="saas" data-i18n="resources.modelSaas">SaaS</option>
                        </select>
                        <i class="fa-solid fa-chevron-down tools-overview-sort__icon" aria-hidden="true"></i>
                    </span>
                </label>
                <label class="tools-overview-product-filter">
                    <span class="sr-only" data-i18n="resources.residencyLabel">Data residency</span>
                    <span class="tools-overview-sort__field">
                        <select class="tools-overview-sort__select" data-overview-residency>
                            <option value="all" data-i18n="resources.residencyAll">All residencies</option>
                            <option value="eu" data-i18n="resources.residencyEu">EU</option>
                            <option value="de" data-i18n="resources.residencyDe">Germany</option>
                            <option value="us" data-i18n="resources.residencyUs">US</option>
                            <option value="global" data-i18n="resources.residencyGlobal">Global</option>
                        </select>
                        <i class="fa-solid fa-chevron-down tools-overview-sort__icon" aria-hidden="true"></i>
                    </span>
                </label>
                <label class="tools-overview-product-filter">
                    <span class="sr-only" data-i18n="resources.vendorLabel">Vendor</span>
                    <span class="tools-overview-sort__field">
                        <select class="tools-overview-sort__select" data-overview-vendor>
                            <option value="all" data-i18n="resources.vendorAll">All vendors</option>
                            @foreach ($availableVendors as $vendorId)
                                @php
                                    $vendorLabel = $vendors[$vendorId] ?? ['de' => $vendorId, 'en' => $vendorId];
                                    $vLabelEn = $vendorLabel['en'] ?? $vendorId;
                                    $vLabelDe = $vendorLabel['de'] ?? $vLabelEn;
                                @endphp
                                <option
                                    value="{{ $vendorId }}"
                                    data-text-de="{{ $vLabelDe }}"
                                    data-text-en="{{ $vLabelEn }}"
                                >{{ $vLabelEn }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down tools-overview-sort__icon" aria-hidden="true"></i>
                    </span>
                </label>
            </div>
        </div>

        <div class="tools-overview-scroll vendor-resources-scroll">
            <div class="vendor-resources-stack-banner" data-overview-stack-banner hidden>
                <div class="vendor-resources-stack-banner__hero">
                    <p class="vendor-resources-stack-banner__eyebrow" data-i18n="resources.stackBannerEyebrow">Suggested stack</p>
                    <h2 class="vendor-resources-stack-banner__title" data-overview-stack-banner-title></h2>
                    <p class="vendor-resources-stack-banner__desc" data-overview-stack-banner-desc></p>
                </div>
                <ul class="vendor-resources-stack-banner__chips" data-overview-stack-banner-chips aria-label="Stack products"></ul>
            </div>

            <p class="tools-overview-empty" data-overview-empty hidden data-i18n="overview.noResults">
                No matches for your search.
            </p>

            <div class="vendor-resources-grid" role="list">
                @foreach ($products as $product)
                    @php
                        $productId = (string) ($product['id'] ?? '');
                        $familyId = is_string($product['family'] ?? null) ? $product['family'] : '';
                        $vendorId = is_string($product['vendor'] ?? null) ? $product['vendor'] : '';
                        $labelEn = $product['label']['en'] ?? $productId;
                        $labelDe = $product['label']['de'] ?? $labelEn;
                        $purposeEn = $product['purpose']['en'] ?? '';
                        $purposeDe = $product['purpose']['de'] ?? $purposeEn;
                        $models = is_array($product['models'] ?? null) ? array_values(array_filter($product['models'], static fn ($m) => is_string($m) && $m !== '')) : [];
                        $bundles = is_array($product['bundles'] ?? null) ? array_values(array_filter($product['bundles'], static fn ($m) => is_string($m) && $m !== '')) : [];
                        $residency = is_array($product['residency'] ?? null) ? array_values(array_filter($product['residency'], static fn ($m) => is_string($m) && $m !== '')) : [];
                        $compliance = is_array($product['compliance'] ?? null) ? $product['compliance'] : [];
                        $ourTools = is_array($toolsByProduct[$productId] ?? null) ? $toolsByProduct[$productId] : [];
                        $supplierLibrary = is_array($supplierLibraryByProduct[$productId] ?? null) ? $supplierLibraryByProduct[$productId] : [];
                        $productStackIds = is_array($stacksByProduct[$productId] ?? null) ? $stacksByProduct[$productId] : [];
                        $brandColor = is_string($product['brandColor'] ?? null) ? $product['brandColor'] : null;
                        $logo = is_string($product['logo'] ?? null) ? $product['logo'] : null;
                        $useWordmark = $brandColor !== null || $logo !== null;
                        $help = is_array($product['help'] ?? null) ? $product['help'] : [];
                        $governance = is_array($product['governance'] ?? null) ? $product['governance'] : [];
                        $learning = is_array($product['learning'] ?? null) ? $product['learning'] : [];
                        $certifications = is_array($product['certifications'] ?? null) ? $product['certifications'] : [];
                        $vendorLabel = ($vendors[$vendorId] ?? null);
                        $vendorLabelEn = is_array($vendorLabel) ? ($vendorLabel['en'] ?? $vendorId) : $vendorId;
                        $vendorLabelDe = is_array($vendorLabel) ? ($vendorLabel['de'] ?? $vendorLabelEn) : $vendorId;
                        $modelMeta = [
                            'saas' => [
                                'i18n' => 'resources.modelSaas',
                                'fallback' => 'SaaS',
                                'search' => ['saas', 'cloud'],
                            ],
                            'opensource' => [
                                'i18n' => 'resources.modelOpenSource',
                                'fallback' => 'Open Source',
                                'search' => ['opensource', 'open source', 'oss', 'open-source'],
                            ],
                            'onprem' => [
                                'i18n' => 'resources.modelOnPrem',
                                'fallback' => 'On-prem',
                                'search' => ['onprem', 'on-prem', 'on premise', 'on-premise', 'self-hosted', 'self hosted', 'client-managed'],
                            ],
                        ];
                        $bundleMeta = [
                            'm365' => [
                                'i18n' => 'resources.bundleM365',
                                'fallback' => 'Microsoft 365',
                                'search' => ['m365', 'microsoft 365', 'office 365', 'o365', 'office365'],
                            ],
                        ];
                        $residencyMeta = [
                            'eu' => [
                                'i18n' => 'resources.residencyEu',
                                'fallback' => 'EU',
                                'search' => ['eu', 'europe', 'dsgvo', 'gdpr', 'schengen'],
                            ],
                            'de' => [
                                'i18n' => 'resources.residencyDe',
                                'fallback' => 'DE',
                                'search' => ['de', 'germany', 'deutschland', 'frankfurt', 'dsgvo'],
                            ],
                            'us' => [
                                'i18n' => 'resources.residencyUs',
                                'fallback' => 'US',
                                'search' => ['us', 'usa', 'united states'],
                            ],
                            'global' => [
                                'i18n' => 'resources.residencyGlobal',
                                'fallback' => 'Global',
                                'search' => ['global', 'multi-region', 'worldwide'],
                            ],
                        ];
                        $groups = [
                            [
                                'key' => 'help',
                                'icon' => 'fa-life-ring',
                                'i18n' => 'resources.helpTitle',
                                'fallback' => 'Help',
                                'links' => $help,
                            ],
                            [
                                'key' => 'governance',
                                'icon' => 'fa-shield-halved',
                                'i18n' => 'resources.governanceTitle',
                                'fallback' => 'Governance',
                                'links' => $governance,
                            ],
                            [
                                'key' => 'learning',
                                'icon' => 'fa-graduation-cap',
                                'i18n' => 'resources.learningTitle',
                                'fallback' => 'Learning paths',
                                'links' => $learning,
                            ],
                            [
                                'key' => 'certifications',
                                'icon' => 'fa-certificate',
                                'i18n' => 'resources.certificationsTitle',
                                'fallback' => 'Certifications',
                                'links' => $certifications,
                            ],
                            [
                                'key' => 'compliance',
                                'icon' => 'fa-building-columns',
                                'i18n' => 'resources.complianceTitle',
                                'fallback' => 'Compliance',
                                'links' => $compliance,
                                'span' => true,
                                'external' => true,
                            ],
                            [
                                'key' => 'ourTools',
                                'icon' => 'fa-screwdriver-wrench',
                                'i18n' => 'resources.ourToolsTitle',
                                'fallback' => 'Binom Tools',
                                'links' => $ourTools,
                                'span' => true,
                                'external' => false,
                            ],
                            [
                                'key' => 'supplierLibrary',
                                'icon' => 'fa-database',
                                'i18n' => 'resources.supplierLibraryTitle',
                                'fallback' => 'Supplier Library',
                                'links' => $supplierLibrary,
                                'span' => true,
                                'external' => false,
                            ],
                        ];
                        $searchParts = [
                            $productId,
                            $familyId,
                            $vendorId,
                            $vendorLabelDe,
                            $vendorLabelEn,
                            $labelDe,
                            $labelEn,
                            $purposeDe,
                            $purposeEn,
                            'help',
                            'governance',
                            'learning',
                            'certifications',
                            'compliance',
                            'binom tools',
                            'binom',
                            'hilfe',
                            'lernpfad',
                            'zertifizierung',
                            'dsgvo',
                            'gdpr',
                            'banken',
                            'behörden',
                            'banking',
                            'public sector',
                            'planen',
                            'planning',
                            'miro',
                            'talend',
                        ];
                        foreach ($ourTools as $toolLink) {
                            $searchParts[] = $toolLink['label']['de'] ?? '';
                            $searchParts[] = $toolLink['label']['en'] ?? '';
                            $searchParts[] = $toolLink['description']['de'] ?? '';
                            $searchParts[] = $toolLink['description']['en'] ?? '';
                        }
                        foreach ($supplierLibrary as $libraryLink) {
                            $searchParts[] = $libraryLink['label']['de'] ?? '';
                            $searchParts[] = $libraryLink['label']['en'] ?? '';
                            $searchParts[] = $libraryLink['description']['de'] ?? '';
                            $searchParts[] = $libraryLink['description']['en'] ?? '';
                            $searchParts[] = 'supplier library';
                            $searchParts[] = 'supplier';
                        }
                        foreach ($models as $modelId) {
                            $searchParts[] = $modelId;
                            foreach (($modelMeta[$modelId]['search'] ?? []) as $term) {
                                $searchParts[] = $term;
                            }
                        }
                        foreach ($bundles as $bundleId) {
                            $searchParts[] = $bundleId;
                            foreach (($bundleMeta[$bundleId]['search'] ?? []) as $term) {
                                $searchParts[] = $term;
                            }
                        }
                        foreach ($residency as $residencyId) {
                            $searchParts[] = $residencyId;
                            foreach (($residencyMeta[$residencyId]['search'] ?? []) as $term) {
                                $searchParts[] = $term;
                            }
                        }
                        foreach (array_merge($help, $governance, $learning, $certifications, $compliance) as $link) {
                            if (! is_array($link)) {
                                continue;
                            }
                            $searchParts[] = $link['id'] ?? '';
                            $searchParts[] = $link['label']['de'] ?? '';
                            $searchParts[] = $link['label']['en'] ?? '';
                            $searchParts[] = $link['description']['de'] ?? '';
                            $searchParts[] = $link['description']['en'] ?? '';
                            $searchParts[] = $link['href'] ?? '';
                        }
                        $searchText = strtolower(implode(' ', array_filter($searchParts, static fn ($v) => is_string($v) && $v !== '')));
                    @endphp
                    <article
                        class="vendor-resources-card"
                        role="listitem"
                        data-overview-item
                        data-product-id="{{ $productId }}"
                        data-products="{{ $familyId }}"
                        data-vendor="{{ $vendorId }}"
                        data-models="{{ implode(',', $models) }}"
                        data-residency="{{ implode(',', $residency) }}"
                        data-stacks="{{ implode(',', $productStackIds) }}"
                        data-search-text="{{ $searchText }}"
                        data-sort-title-en="{{ $labelEn }}"
                        data-sort-title-de="{{ $labelDe }}"
                    >
                        <aside class="vendor-resources-card__aside">
                            <div class="vendor-resources-card__mark" @if ($brandColor) style="--vendor-brand: {{ $brandColor }}" @endif>
                                @if ($useWordmark)
                                    <span
                                        class="vendor-resources-card__wordmark"
                                        data-text-de="{{ $labelDe }}"
                                        data-text-en="{{ $labelEn }}"
                                    >{{ $labelEn }}</span>
                                @else
                                    <span class="vendor-resources-card__initial" aria-hidden="true">{{ strtoupper(substr($labelEn, 0, 1)) }}</span>
                                @endif
                            </div>
                            {{-- Visible wordmark already shows the product name. --}}
                            <h2
                                @class([
                                    'vendor-resources-card__title',
                                    'sr-only' => $useWordmark,
                                ])
                                data-text-de="{{ $labelDe }}"
                                data-text-en="{{ $labelEn }}"
                            >{{ $labelEn }}</h2>
                            @if ($purposeEn !== '')
                                <p
                                    class="vendor-resources-card__purpose"
                                    data-text-de="{{ $purposeDe }}"
                                    data-text-en="{{ $purposeEn }}"
                                >{{ $purposeEn }}</p>
                            @endif
                            @if ($models !== [] || $residency !== [] || $bundles !== [])
                                <ul class="vendor-resources-card__models" aria-label="Models, bundles and residency">
                                    @foreach ($models as $modelId)
                                        @php
                                            $meta = $modelMeta[$modelId] ?? null;
                                        @endphp
                                        @if ($meta)
                                            <li class="vendor-resources-model vendor-resources-model--{{ $modelId }}">
                                                <span data-i18n="{{ $meta['i18n'] }}">{{ $meta['fallback'] }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                    @foreach ($bundles as $bundleId)
                                        @php
                                            $meta = $bundleMeta[$bundleId] ?? null;
                                        @endphp
                                        @if ($meta)
                                            <li class="vendor-resources-model vendor-resources-model--bundle vendor-resources-model--bundle-{{ $bundleId }}">
                                                <span data-i18n="{{ $meta['i18n'] }}">{{ $meta['fallback'] }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                    @foreach ($residency as $residencyId)
                                        @php
                                            $meta = $residencyMeta[$residencyId] ?? null;
                                        @endphp
                                        @if ($meta)
                                            <li class="vendor-resources-model vendor-resources-model--residency vendor-resources-model--residency-{{ $residencyId }}">
                                                <span data-i18n="{{ $meta['i18n'] }}">{{ $meta['fallback'] }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                            @if ($compliance !== [])
                                <ul class="vendor-resources-card__compliance" aria-label="Compliance">
                                    @foreach ($compliance as $item)
                                        @php
                                            if (! is_array($item)) {
                                                continue;
                                            }
                                            $compId = (string) ($item['id'] ?? '');
                                            $compLabelEn = $item['label']['en'] ?? ($compId !== '' ? strtoupper($compId) : 'Compliance');
                                            $compLabelDe = $item['label']['de'] ?? $compLabelEn;
                                            $compHref = is_string($item['href'] ?? null) ? $item['href'] : null;
                                        @endphp
                                        <li>
                                            @if ($compHref)
                                                <a
                                                    class="vendor-resources-compliance-chip"
                                                    href="{{ $compHref }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    data-text-de="{{ $compLabelDe }}"
                                                    data-text-en="{{ $compLabelEn }}"
                                                >{{ $compLabelEn }}</a>
                                            @else
                                                <span
                                                    class="vendor-resources-compliance-chip vendor-resources-compliance-chip--static"
                                                    data-text-de="{{ $compLabelDe }}"
                                                    data-text-en="{{ $compLabelEn }}"
                                                >{{ $compLabelEn }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </aside>

                        <div class="vendor-resources-card__content">
                            @foreach ($groups as $group)
                                @if (($group['key'] ?? '') === 'compliance' && $compliance === [])
                                    @continue
                                @endif
                                @if (($group['key'] ?? '') === 'ourTools' && $ourTools === [])
                                    @continue
                                @endif
                                @if (($group['key'] ?? '') === 'supplierLibrary' && $supplierLibrary === [])
                                    @continue
                                @endif
                                @php
                                    $isExternalGroup = ($group['external'] ?? true) !== false;
                                @endphp
                                <section
                                    @class([
                                        'vendor-resources-group',
                                        'vendor-resources-group--span' => ! empty($group['span']),
                                        'vendor-resources-group--our-tools' => ($group['key'] ?? '') === 'ourTools',
                                    ])
                                    aria-labelledby="vendor-{{ $group['key'] }}-{{ $productId }}"
                                >
                                    <h3 id="vendor-{{ $group['key'] }}-{{ $productId }}" class="vendor-resources-group__title">
                                        <i class="fa-solid {{ $group['icon'] }}" aria-hidden="true"></i>
                                        <span data-i18n="{{ $group['i18n'] }}">{{ $group['fallback'] }}</span>
                                    </h3>
                                    <ul class="vendor-resources-links">
                                        @forelse ($group['links'] as $link)
                                            @php
                                                if (! empty($link['href'])) {
                                                    $linkHref = (string) $link['href'];
                                                } elseif (! $isExternalGroup) {
                                                    $linkHref = locale_route((string) ($link['route'] ?? 'tools.landing'));
                                                } else {
                                                    $linkHref = (string) ($link['href'] ?? '#');
                                                }
                                                $linkLabelEn = $link['label']['en'] ?? ($isExternalGroup ? ($link['href'] ?? 'Link') : ($link['route'] ?? 'Tool'));
                                                $linkLabelDe = $link['label']['de'] ?? $linkLabelEn;
                                                $linkDescEn = $link['description']['en'] ?? '';
                                                $linkDescDe = $link['description']['de'] ?? $linkDescEn;
                                            @endphp
                                            <li>
                                                <a
                                                    @class([
                                                        'vendor-resources-link',
                                                        'vendor-resources-link--internal' => ! $isExternalGroup,
                                                    ])
                                                    href="{{ $linkHref }}"
                                                    @if ($isExternalGroup)
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                    @endif
                                                    @if ($linkDescEn !== '') title="{{ $linkDescEn }}" @endif
                                                >
                                                    <span
                                                        class="vendor-resources-link__label"
                                                        data-text-de="{{ $linkLabelDe }}"
                                                        data-text-en="{{ $linkLabelEn }}"
                                                    >{{ $linkLabelEn }}</span>
                                                    @if ($linkDescEn !== '')
                                                        <span
                                                            class="vendor-resources-link__desc"
                                                            data-text-de="{{ $linkDescDe }}"
                                                            data-text-en="{{ $linkDescEn }}"
                                                        >{{ $linkDescEn }}</span>
                                                    @endif
                                                    <i
                                                        @class([
                                                            'fa-solid',
                                                            'vendor-resources-link__icon',
                                                            'fa-arrow-up-right-from-square' => $isExternalGroup,
                                                            'fa-arrow-right' => ! $isExternalGroup,
                                                        ])
                                                        aria-hidden="true"
                                                    ></i>
                                                </a>
                                            </li>
                                        @empty
                                            <li class="vendor-resources-links__empty" data-i18n="resources.noLinks">No links yet.</li>
                                        @endforelse
                                    </ul>
                                </section>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
@endsection
