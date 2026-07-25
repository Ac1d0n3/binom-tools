@extends('layouts.tools', [
    'mainClass' => 'tools-shell__main--overview',
])

@php
    $id = (string) ($product['id'] ?? '');
    $labelEn = $product['label']['en'] ?? $id;
    $labelDe = $product['label']['de'] ?? $labelEn;
    $purposeEn = $product['shortPurpose']['en'] ?? '';
    $purposeDe = $product['shortPurpose']['de'] ?? $purposeEn;
    $domainId = is_string($product['domain'] ?? null) ? $product['domain'] : '';
    $domainLabel = $domains[$domainId] ?? ['de' => $domainId, 'en' => $domainId];
    $domainEn = $domainLabel['en'] ?? $domainId;
    $domainDe = $domainLabel['de'] ?? $domainEn;

    $entities = is_array($product['entities'] ?? null) ? $product['entities'] : [];
    $fields = is_array($product['fields'] ?? null) ? $product['fields'] : [];
    $skip = is_array($product['skip'] ?? null) ? $product['skip'] : [];
    $skipTables = is_array($product['skipTables'] ?? null) ? $product['skipTables'] : [];
    $dimensions = is_array($product['dimensions'] ?? null) ? $product['dimensions'] : [];
    $pii = is_array($product['pii'] ?? null) ? $product['pii'] : [];
    $dsdr = is_array($product['dsdr'] ?? null) ? $product['dsdr'] : [];
    $measures = is_array($product['measures'] ?? null) ? $product['measures'] : [];

    $roleLabels = [
        'key' => ['de' => 'Key', 'en' => 'Key'],
        'measure' => ['de' => 'Measure', 'en' => 'Measure'],
        'dimension' => ['de' => 'Dimension', 'en' => 'Dimension'],
        'pii' => ['de' => 'PII', 'en' => 'PII'],
    ];
    $loadLabels = [
        'required' => ['de' => 'Laden', 'en' => 'Load'],
        'optional' => ['de' => 'Optional', 'en' => 'Optional'],
        'skip' => ['de' => 'Nicht laden', 'en' => 'Do not load'],
    ];
@endphp

@section('title', $labelEn . ' — Suppliers — ' . config('app.name'))
@section('meta_description', $purposeEn)

@section('content')
    <div
        class="tools-content tools-content--supplier-detail"
        data-page-title-root
        data-title-de="{{ $labelDe }}"
        data-title-en="{{ $labelEn }}"
        data-title-suffix=" — Suppliers — {{ config('app.name') }}"
        data-supplier-library
    >
        <p class="supplier-detail__back">
            <a href="{{ locale_route('suppliers.index') }}" data-i18n="suppliers.backToIndex">
                ← All suppliers
            </a>
        </p>

        <header class="supplier-detail__header">
            <p class="supplier-detail__domain" data-text-de="{{ $domainDe }}" data-text-en="{{ $domainEn }}">{{ $domainEn }}</p>
            <h1 class="tools-page-title" data-text-de="{{ $labelDe }}" data-text-en="{{ $labelEn }}">{{ $labelEn }}</h1>
            <p class="tools-page-lead" data-text-de="{{ $purposeDe }}" data-text-en="{{ $purposeEn }}">{{ $purposeEn }}</p>
            <p class="supplier-hub-disclaimer" data-i18n="suppliers.disclaimer">
                Templates only — grain, filters, custom fields and ownership are firm-specific.
            </p>
        </header>

        <div class="supplier-detail__tabs" role="tablist" data-i18n-aria="suppliers.tabsLabel">
            <button type="button" class="supplier-detail__tab is-active" data-supplier-tab="measures" role="tab" aria-selected="true" aria-controls="supplier-panel-measures" id="supplier-tab-measures" data-i18n="suppliers.sectionMeasures">Measures</button>
            <button type="button" class="supplier-detail__tab" data-supplier-tab="dimensions" role="tab" aria-selected="false" aria-controls="supplier-panel-dimensions" id="supplier-tab-dimensions" data-i18n="suppliers.sectionDimensions">Dimensions</button>
            <button type="button" class="supplier-detail__tab" data-supplier-tab="pii" role="tab" aria-selected="false" aria-controls="supplier-panel-pii" id="supplier-tab-pii" data-i18n="suppliers.sectionPii">PII &amp; DSDR</button>
            <button type="button" class="supplier-detail__tab" data-supplier-tab="tables" role="tab" aria-selected="false" aria-controls="supplier-panel-tables" id="supplier-tab-tables" data-i18n="suppliers.sectionTables">Tables</button>
            <button type="button" class="supplier-detail__tab" data-supplier-tab="fields" role="tab" aria-selected="false" aria-controls="supplier-panel-fields" id="supplier-tab-fields" data-i18n="suppliers.sectionFields">Core fields</button>
            <button type="button" class="supplier-detail__tab" data-supplier-tab="skip" role="tab" aria-selected="false" aria-controls="supplier-panel-skip" id="supplier-tab-skip" data-i18n="suppliers.sectionSkip">Do not load</button>
            <button type="button" class="supplier-detail__tab" data-supplier-tab="tools" role="tab" aria-selected="false" aria-controls="supplier-panel-tools" id="supplier-tab-tools" data-i18n="suppliers.sectionTools">Tools &amp; playbooks</button>
        </div>

        <div class="supplier-detail__sections">
            <section id="supplier-panel-measures" class="supplier-detail__section is-active" data-supplier-panel="measures" role="tabpanel" aria-labelledby="supplier-tab-measures">
                <p class="supplier-detail__section-lead" data-i18n="suppliers.measuresLead">
                    Copy formulas into the KPI Definition Card, then adapt grain and filters.
                </p>
                <div class="supplier-measure-grid">
                    @foreach ($measures as $measure)
                        @php
                            $mId = (string) ($measure['id'] ?? '');
                            $isExample = (bool) ($measure['example'] ?? false);
                            $mLabelEn = $measure['label']['en'] ?? $mId;
                            $mLabelDe = $measure['label']['de'] ?? $mLabelEn;
                            $qEn = $measure['question']['en'] ?? '';
                            $qDe = $measure['question']['de'] ?? $qEn;
                            $formula = (string) ($measure['formula'] ?? '');
                            $grainEn = $measure['grain']['en'] ?? '';
                            $grainDe = $measure['grain']['de'] ?? $grainEn;
                            $adaptEn = $measure['adapt']['en'] ?? '';
                            $adaptDe = $measure['adapt']['de'] ?? $adaptEn;
                            $hintsEn = $measure['sourceHints']['en'] ?? '';
                            $hintsDe = $measure['sourceHints']['de'] ?? $hintsEn;
                            $fieldsUsed = is_array($measure['fieldsUsed'] ?? null) ? $measure['fieldsUsed'] : [];
                            $dimIds = is_array($measure['dimensions'] ?? null) ? $measure['dimensions'] : [];
                        @endphp
                        <article class="supplier-measure-card {{ $isExample ? 'supplier-measure-card--example' : '' }}">
                            @if ($isExample)
                                <span class="supplier-measure-card__badge" data-i18n="suppliers.exampleBadge">Example</span>
                            @endif
                            <h3 data-text-de="{{ $mLabelDe }}" data-text-en="{{ $mLabelEn }}">{{ $mLabelEn }}</h3>
                            <p class="supplier-measure-card__question" data-text-de="{{ $qDe }}" data-text-en="{{ $qEn }}">{{ $qEn }}</p>
                            <div class="supplier-measure-card__formula-row">
                                <code class="supplier-measure-card__formula" data-supplier-copy-source>{{ $formula }}</code>
                                <button
                                    type="button"
                                    class="supplier-copy-btn"
                                    data-supplier-copy
                                    data-i18n="suppliers.copy"
                                    data-i18n-copied="suppliers.copied"
                                >Copy</button>
                            </div>
                            <dl class="supplier-measure-card__meta">
                                <div>
                                    <dt data-i18n="suppliers.grain">Grain</dt>
                                    <dd data-text-de="{{ $grainDe }}" data-text-en="{{ $grainEn }}">{{ $grainEn }}</dd>
                                </div>
                                @if ($fieldsUsed !== [])
                                    <div>
                                        <dt data-i18n="suppliers.fieldsUsed">Fields</dt>
                                        <dd>{{ implode(', ', $fieldsUsed) }}</dd>
                                    </div>
                                @endif
                                @if ($dimIds !== [])
                                    <div>
                                        <dt data-i18n="suppliers.dimensionsUsed">Dimensions</dt>
                                        <dd>{{ implode(', ', $dimIds) }}</dd>
                                    </div>
                                @endif
                            </dl>
                            @if ($hintsEn !== '' || $hintsDe !== '')
                                <p class="supplier-measure-card__hint" data-text-de="{{ $hintsDe }}" data-text-en="{{ $hintsEn }}">{{ $hintsEn }}</p>
                            @endif
                            @if ($adaptEn !== '' || $adaptDe !== '')
                                <p class="supplier-measure-card__adapt">
                                    <strong data-i18n="suppliers.adapt">Adapt:</strong>
                                    <span data-text-de="{{ $adaptDe }}" data-text-en="{{ $adaptEn }}">{{ $adaptEn }}</span>
                                </p>
                            @endif
                        </article>
                    @endforeach
                </div>
            </section>

            <section id="supplier-panel-dimensions" class="supplier-detail__section" data-supplier-panel="dimensions" role="tabpanel" aria-labelledby="supplier-tab-dimensions" hidden>
                <div class="supplier-table-wrap">
                    <table class="supplier-table">
                        <thead>
                            <tr>
                                <th data-i18n="suppliers.colId">Id</th>
                                <th data-i18n="suppliers.colLabel">Label</th>
                                <th data-i18n="suppliers.grain">Grain</th>
                                <th data-i18n="suppliers.colNotes">Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dimensions as $dim)
                                @php
                                    $dId = (string) ($dim['id'] ?? '');
                                    $dLabelEn = $dim['label']['en'] ?? $dId;
                                    $dLabelDe = $dim['label']['de'] ?? $dLabelEn;
                                    $dGrainEn = $dim['grain']['en'] ?? '';
                                    $dGrainDe = $dim['grain']['de'] ?? $dGrainEn;
                                    $dNotesEn = $dim['notes']['en'] ?? '';
                                    $dNotesDe = $dim['notes']['de'] ?? $dNotesEn;
                                @endphp
                                <tr>
                                    <td><code>{{ $dId }}</code></td>
                                    <td data-text-de="{{ $dLabelDe }}" data-text-en="{{ $dLabelEn }}">{{ $dLabelEn }}</td>
                                    <td data-text-de="{{ $dGrainDe }}" data-text-en="{{ $dGrainEn }}">{{ $dGrainEn }}</td>
                                    <td data-text-de="{{ $dNotesDe }}" data-text-en="{{ $dNotesEn }}">{{ $dNotesEn }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="supplier-panel-pii" class="supplier-detail__section" data-supplier-panel="pii" role="tabpanel" aria-labelledby="supplier-tab-pii" hidden>
                <h3 class="supplier-detail__subhead" data-i18n="suppliers.piiTitle">PII hotspots</h3>
                <div class="supplier-table-wrap">
                    <table class="supplier-table">
                        <thead>
                            <tr>
                                <th data-i18n="suppliers.colEntity">Entity</th>
                                <th data-i18n="suppliers.colFields">Fields</th>
                                <th data-i18n="suppliers.colTreatment">Treatment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pii as $row)
                                @php
                                    $entity = (string) ($row['entity'] ?? '');
                                    $piiFields = is_array($row['fields'] ?? null) ? $row['fields'] : [];
                                    $tEn = $row['treatment']['en'] ?? '';
                                    $tDe = $row['treatment']['de'] ?? $tEn;
                                @endphp
                                <tr>
                                    <td>{{ $entity }}</td>
                                    <td><code>{{ implode(', ', $piiFields) }}</code></td>
                                    <td data-text-de="{{ $tDe }}" data-text-en="{{ $tEn }}">{{ $tEn }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <h3 class="supplier-detail__subhead" data-i18n="suppliers.dsdrTitle">DSDR discovery</h3>
                <ul class="supplier-dsdr-list">
                    @foreach ($dsdr as $row)
                        @php
                            $fEn = $row['focus']['en'] ?? '';
                            $fDe = $row['focus']['de'] ?? $fEn;
                            $nEn = $row['notes']['en'] ?? '';
                            $nDe = $row['notes']['de'] ?? $nEn;
                        @endphp
                        <li>
                            <strong data-text-de="{{ $fDe }}" data-text-en="{{ $fEn }}">{{ $fEn }}</strong>
                            — <span data-text-de="{{ $nDe }}" data-text-en="{{ $nEn }}">{{ $nEn }}</span>
                        </li>
                    @endforeach
                </ul>
            </section>

            <section id="supplier-panel-tables" class="supplier-detail__section" data-supplier-panel="tables" role="tabpanel" aria-labelledby="supplier-tab-tables" hidden>
                <p class="supplier-detail__section-lead" data-i18n="suppliers.tablesLead">
                    Object overview with short descriptions and load guidance — not a full schema dump.
                </p>
                <div class="supplier-table-wrap">
                    <table class="supplier-table">
                        <thead>
                            <tr>
                                <th data-i18n="suppliers.colTable">Table</th>
                                <th data-i18n="suppliers.colDescription">Description</th>
                                <th data-i18n="suppliers.grain">Grain</th>
                                <th data-i18n="suppliers.colLoad">Load</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($entities as $entity)
                                @php
                                    $eId = (string) ($entity['id'] ?? '');
                                    $eLabelEn = $entity['label']['en'] ?? $eId;
                                    $eLabelDe = $entity['label']['de'] ?? $eLabelEn;
                                    $eDescEn = $entity['description']['en'] ?? '';
                                    $eDescDe = $entity['description']['de'] ?? $eDescEn;
                                    $eGrainEn = $entity['grain']['en'] ?? '';
                                    $eGrainDe = $entity['grain']['de'] ?? $eGrainEn;
                                    $load = (string) ($entity['load'] ?? 'optional');
                                    if (! isset($loadLabels[$load])) {
                                        $load = 'optional';
                                    }
                                    $loadEn = $loadLabels[$load]['en'];
                                    $loadDe = $loadLabels[$load]['de'];
                                @endphp
                                <tr>
                                    <td>
                                        <code>{{ $eId }}</code>
                                        <div class="supplier-table__sublabel" data-text-de="{{ $eLabelDe }}" data-text-en="{{ $eLabelEn }}">{{ $eLabelEn }}</div>
                                    </td>
                                    <td data-text-de="{{ $eDescDe }}" data-text-en="{{ $eDescEn }}">{{ $eDescEn }}</td>
                                    <td data-text-de="{{ $eGrainDe }}" data-text-en="{{ $eGrainEn }}">{{ $eGrainEn }}</td>
                                    <td>
                                        <span
                                            class="supplier-load-badge supplier-load-badge--{{ $load }}"
                                            data-text-de="{{ $loadDe }}"
                                            data-text-en="{{ $loadEn }}"
                                        >{{ $loadEn }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="supplier-panel-fields" class="supplier-detail__section" data-supplier-panel="fields" role="tabpanel" aria-labelledby="supplier-tab-fields" hidden>
                <p class="supplier-detail__section-lead" data-i18n="suppliers.fieldsLead">
                    Most important fields to load early — not a full schema dump.
                </p>
                <div class="supplier-table-wrap">
                    <table class="supplier-table">
                        <thead>
                            <tr>
                                <th data-i18n="suppliers.colEntity">Entity</th>
                                <th data-i18n="suppliers.colField">Field</th>
                                <th data-i18n="suppliers.colRole">Role</th>
                                <th data-i18n="suppliers.colWhy">Why</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fields as $field)
                                @php
                                    $entity = (string) ($field['entity'] ?? '');
                                    $name = (string) ($field['name'] ?? '');
                                    $role = (string) ($field['role'] ?? '');
                                    $roleEn = $roleLabels[$role]['en'] ?? $role;
                                    $roleDe = $roleLabels[$role]['de'] ?? $roleEn;
                                    $whyEn = $field['why']['en'] ?? '';
                                    $whyDe = $field['why']['de'] ?? $whyEn;
                                @endphp
                                <tr>
                                    <td>{{ $entity }}</td>
                                    <td>
                                        <code data-supplier-copy-source>{{ $entity }}.{{ $name }}</code>
                                        <button type="button" class="supplier-copy-btn supplier-copy-btn--inline" data-supplier-copy data-i18n="suppliers.copy" data-i18n-copied="suppliers.copied">Copy</button>
                                    </td>
                                    <td data-text-de="{{ $roleDe }}" data-text-en="{{ $roleEn }}">{{ $roleEn }}</td>
                                    <td data-text-de="{{ $whyDe }}" data-text-en="{{ $whyEn }}">{{ $whyEn }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="supplier-panel-skip" class="supplier-detail__section" data-supplier-panel="skip" role="tabpanel" aria-labelledby="supplier-tab-skip" hidden>
                <p class="supplier-detail__section-lead" data-i18n="suppliers.skipLead">
                    System and noise objects/fields you typically should not sync into the warehouse by default.
                </p>

                @if ($skipTables !== [])
                    <h3 class="supplier-detail__subhead" data-i18n="suppliers.skipTablesTitle">Skip tables / objects</h3>
                    <div class="supplier-table-wrap">
                        <table class="supplier-table">
                            <thead>
                                <tr>
                                    <th data-i18n="suppliers.colTable">Table</th>
                                    <th data-i18n="suppliers.colCategory">Category</th>
                                    <th data-i18n="suppliers.colWhy">Why</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($skipTables as $row)
                                    @php
                                        $sName = (string) ($row['name'] ?? '');
                                        $rEn = $row['reason']['en'] ?? '';
                                        $rDe = $row['reason']['de'] ?? $rEn;
                                    @endphp
                                    <tr>
                                        <td><code>{{ $sName }}</code></td>
                                        <td data-i18n="suppliers.categorySystem">System</td>
                                        <td data-text-de="{{ $rDe }}" data-text-en="{{ $rEn }}">{{ $rEn }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if ($skip !== [])
                    <h3 class="supplier-detail__subhead" data-i18n="suppliers.skipFieldsTitle">Skip fields / content</h3>
                    <ul class="supplier-dsdr-list">
                        @foreach ($skip as $row)
                            @php
                                $sName = (string) ($row['name'] ?? '');
                                $rEn = $row['reason']['en'] ?? '';
                                $rDe = $row['reason']['de'] ?? $rEn;
                            @endphp
                            <li>
                                <strong>{{ $sName }}</strong>
                                — <span data-text-de="{{ $rDe }}" data-text-en="{{ $rEn }}">{{ $rEn }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <section id="supplier-panel-tools" class="supplier-detail__section" data-supplier-panel="tools" role="tabpanel" aria-labelledby="supplier-tab-tools" hidden>
                <div class="supplier-detail__block">
                    <h3 class="supplier-detail__subhead" data-i18n="suppliers.toolsBlockTitle">Tools</h3>
                    <p class="supplier-detail__section-lead" data-i18n="suppliers.toolsLead">
                        Open related Binom-Tools workflows to adapt formulas, PII and measures for this source.
                    </p>
                    @if ($toolLinks !== [])
                        <ul class="supplier-link-list">
                            @foreach ($toolLinks as $tool)
                                @php
                                    $tEn = $tool['label']['en'] ?? '';
                                    $tDe = $tool['label']['de'] ?? $tEn;
                                @endphp
                                <li>
                                    <a
                                        href="{{ locale_route($tool['route']) }}"
                                        class="supplier-link-list__item"
                                        data-text-de="{{ $tDe }}"
                                        data-text-en="{{ $tEn }}"
                                    >{{ $tEn }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <p class="supplier-detail__resources">
                        <a href="{{ locale_route('resources.index') }}" data-i18n="suppliers.resourcesLink">
                            Vendor help links on Resources →
                        </a>
                    </p>
                </div>

                <div class="supplier-detail__block">
                    <h3 class="supplier-detail__subhead" data-i18n="suppliers.playbooksBlockTitle">Playbooks</h3>
                    <p class="supplier-detail__section-lead" data-i18n="suppliers.playbooksLead">
                        Related governance stories for modelling, PII and measure design around this source.
                    </p>
                    @if ($relatedPlaybooks !== [])
                        <ul class="supplier-link-list">
                            @foreach ($relatedPlaybooks as $pb)
                                <li>
                                    <a
                                        href="{{ locale_route('playbooks.show', ['slug' => $pb['slug']]) }}"
                                        class="supplier-link-list__item"
                                        data-text-de="{{ $pb['titleDe'] }}"
                                        data-text-en="{{ $pb['titleEn'] }}"
                                    >{{ $pb['titleEn'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </section>
        </div>

        <nav class="supplier-detail__pager" aria-label="Supplier navigation">
            @if ($prev)
                <a class="supplier-detail__pager-link" href="{{ locale_route('suppliers.show', ['slug' => $prev['id']]) }}">
                    <span class="supplier-detail__pager-label" data-i18n="suppliers.prev">Previous</span>
                    <span data-text-de="{{ $prev['label']['de'] }}" data-text-en="{{ $prev['label']['en'] }}">{{ $prev['label']['en'] }}</span>
                </a>
            @else
                <span></span>
            @endif
            @if ($next)
                <a class="supplier-detail__pager-link supplier-detail__pager-link--next" href="{{ locale_route('suppliers.show', ['slug' => $next['id']]) }}">
                    <span class="supplier-detail__pager-label" data-i18n="suppliers.next">Next</span>
                    <span data-text-de="{{ $next['label']['de'] }}" data-text-en="{{ $next['label']['en'] }}">{{ $next['label']['en'] }}</span>
                </a>
            @endif
        </nav>
    </div>
@endsection
