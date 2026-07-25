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
    $sqlExamples = is_array($product['sqlExamples'] ?? null) ? $product['sqlExamples'] : [];
    $sqlRaw = array_values(array_filter($sqlExamples, static fn ($row) => ($row['stage'] ?? '') === 'raw'));
    $sqlCurated = array_values(array_filter($sqlExamples, static fn ($row) => ($row['stage'] ?? '') === 'curated'));
    $quality = is_array($product['quality'] ?? null) ? $product['quality'] : [];
    $qualityDq = is_array($quality['dq'] ?? null) ? $quality['dq'] : [];
    $qualityMdm = is_array($quality['mdm'] ?? null) ? $quality['mdm'] : [];
    $qualitySettings = is_array($quality['productSettings'] ?? null) ? $quality['productSettings'] : [];
    $qualityMetadata = is_array($quality['metadata'] ?? null) ? $quality['metadata'] : [];
    $hasQuality = $qualityDq !== [] || $qualityMdm !== [] || $qualitySettings !== [] || $qualityMetadata !== [];

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
    $classificationLabels = [
        'direct' => ['de' => 'Direkt', 'en' => 'Direct'],
        'quasi' => ['de' => 'Quasi', 'en' => 'Quasi'],
        'sensitive' => ['de' => 'Sensibel', 'en' => 'Sensitive'],
        'workforce' => ['de' => 'Workforce', 'en' => 'Workforce'],
    ];
    $priorityLabels = [
        'high' => ['de' => 'Hoch', 'en' => 'High'],
        'medium' => ['de' => 'Mittel', 'en' => 'Medium'],
        'low' => ['de' => 'Niedrig', 'en' => 'Low'],
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
            @if ($hasQuality)
                <button type="button" class="supplier-detail__tab" data-supplier-tab="quality" role="tab" aria-selected="false" aria-controls="supplier-panel-quality" id="supplier-tab-quality" data-i18n="suppliers.sectionQuality">Data Quality</button>
            @endif
            <button type="button" class="supplier-detail__tab" data-supplier-tab="tables" role="tab" aria-selected="false" aria-controls="supplier-panel-tables" id="supplier-tab-tables" data-i18n="suppliers.sectionTables">Tables</button>
            <button type="button" class="supplier-detail__tab" data-supplier-tab="fields" role="tab" aria-selected="false" aria-controls="supplier-panel-fields" id="supplier-tab-fields" data-i18n="suppliers.sectionFields">Core fields</button>
            <button type="button" class="supplier-detail__tab" data-supplier-tab="skip" role="tab" aria-selected="false" aria-controls="supplier-panel-skip" id="supplier-tab-skip" data-i18n="suppliers.sectionSkip">Do not load</button>
            @if ($sqlExamples !== [])
                <button type="button" class="supplier-detail__tab" data-supplier-tab="sql" role="tab" aria-selected="false" aria-controls="supplier-panel-sql" id="supplier-tab-sql" data-i18n="suppliers.sectionSql">SQL</button>
            @endif
            <button type="button" class="supplier-detail__tab" data-supplier-tab="tools" role="tab" aria-selected="false" aria-controls="supplier-panel-tools" id="supplier-tab-tools" data-i18n="suppliers.sectionTools">Tools &amp; playbooks</button>
        </div>

        <div class="supplier-detail__sections">
            <section id="supplier-panel-measures" class="supplier-detail__section is-active" data-supplier-panel="measures" role="tabpanel" aria-labelledby="supplier-tab-measures">
                <p class="supplier-detail__section-lead" data-i18n="suppliers.measuresLead">
                    Copy formulas into the KPI Definition Card, then adapt grain and filters.
                </p>
                <div class="supplier-detail__block">
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
                </div>

                @if ($measures !== [])
                    <div class="supplier-detail__block">
                    <h3 class="supplier-detail__subhead" data-i18n="suppliers.measuresTableTitle">Formula overview</h3>
                    <p class="supplier-detail__section-lead" data-i18n="suppliers.measuresTableLead">
                        Same formulas as a table — copy into KPI cards or SQL drafts.
                    </p>
                    <div class="supplier-table-wrap">
                        <table class="supplier-table supplier-table--measures">
                            <thead>
                                <tr>
                                    <th data-i18n="suppliers.colLabel">Label</th>
                                    <th data-i18n="suppliers.colFormula">Formula</th>
                                    <th data-i18n="suppliers.grain">Grain</th>
                                    <th data-i18n="suppliers.fieldsUsed">Fields</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($measures as $measure)
                                    @php
                                        $mId = (string) ($measure['id'] ?? '');
                                        $mLabelEn = $measure['label']['en'] ?? $mId;
                                        $mLabelDe = $measure['label']['de'] ?? $mLabelEn;
                                        $formula = (string) ($measure['formula'] ?? '');
                                        $grainEn = $measure['grain']['en'] ?? '';
                                        $grainDe = $measure['grain']['de'] ?? $grainEn;
                                        $fieldsUsed = is_array($measure['fieldsUsed'] ?? null) ? $measure['fieldsUsed'] : [];
                                    @endphp
                                    <tr>
                                        <td data-text-de="{{ $mLabelDe }}" data-text-en="{{ $mLabelEn }}">{{ $mLabelEn }}</td>
                                        <td>
                                            <code data-supplier-copy-source>{{ $formula }}</code>
                                            <button type="button" class="supplier-copy-btn supplier-copy-btn--inline" data-supplier-copy data-i18n="suppliers.copy" data-i18n-copied="suppliers.copied">Copy</button>
                                        </td>
                                        <td data-text-de="{{ $grainDe }}" data-text-en="{{ $grainEn }}">{{ $grainEn }}</td>
                                        <td>{{ implode(', ', $fieldsUsed) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    </div>
                @endif
            </section>

            <section id="supplier-panel-dimensions" class="supplier-detail__section" data-supplier-panel="dimensions" role="tabpanel" aria-labelledby="supplier-tab-dimensions" hidden>
                <div class="supplier-detail__block">
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
                </div>
            </section>

            <section id="supplier-panel-pii" class="supplier-detail__section" data-supplier-panel="pii" role="tabpanel" aria-labelledby="supplier-tab-pii" hidden>
                <div class="supplier-detail__block">
                <h3 class="supplier-detail__subhead" data-i18n="suppliers.piiTitle">PII hotspots</h3>
                <p class="supplier-detail__section-lead" data-i18n="suppliers.piiLead">
                    Inventory direct and quasi identifiers for this source — tag, restrict RAW, hash or drop before marts.
                </p>
                <div class="supplier-table-wrap">
                    <table class="supplier-table">
                        <thead>
                            <tr>
                                <th data-i18n="suppliers.colEntity">Entity</th>
                                <th data-i18n="suppliers.colFields">Fields</th>
                                <th data-i18n="suppliers.colClassification">Classification</th>
                                <th data-i18n="suppliers.colStageTreatment">Stage treatment</th>
                                <th data-i18n="suppliers.colTreatment">Treatment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pii as $row)
                                @php
                                    $entity = (string) ($row['entity'] ?? '');
                                    $piiFields = is_array($row['fields'] ?? null) ? $row['fields'] : [];
                                    $class = (string) ($row['classification'] ?? '');
                                    if ($class !== '' && ! isset($classificationLabels[$class])) {
                                        $class = 'direct';
                                    }
                                    $classEn = $class !== '' ? ($classificationLabels[$class]['en'] ?? $class) : '';
                                    $classDe = $class !== '' ? ($classificationLabels[$class]['de'] ?? $classEn) : '';
                                    $stageEn = $row['stage']['en'] ?? '';
                                    $stageDe = $row['stage']['de'] ?? $stageEn;
                                    $tEn = $row['treatment']['en'] ?? '';
                                    $tDe = $row['treatment']['de'] ?? $tEn;
                                @endphp
                                <tr>
                                    <td>{{ $entity }}</td>
                                    <td><code>{{ implode(', ', $piiFields) }}</code></td>
                                    <td>
                                        @if ($class !== '')
                                            <span
                                                class="supplier-pii-badge supplier-pii-badge--{{ $class }}"
                                                data-text-de="{{ $classDe }}"
                                                data-text-en="{{ $classEn }}"
                                            >{{ $classEn }}</span>
                                        @endif
                                    </td>
                                    <td data-text-de="{{ $stageDe }}" data-text-en="{{ $stageEn }}">{{ $stageEn }}</td>
                                    <td data-text-de="{{ $tDe }}" data-text-en="{{ $tEn }}">{{ $tEn }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>

                <div class="supplier-detail__block">
                <h3 class="supplier-detail__subhead" data-i18n="suppliers.dsdrTitle">DSDR discovery</h3>
                <p class="supplier-detail__section-lead" data-i18n="suppliers.dsdrLead">
                    Where personal data can hide beyond obvious contact fields — warehouse copies included.
                </p>
                <div class="supplier-table-wrap">
                    <table class="supplier-table">
                        <thead>
                            <tr>
                                <th data-i18n="suppliers.colFocus">Focus</th>
                                <th data-i18n="suppliers.colNotes">Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dsdr as $row)
                                @php
                                    $fEn = $row['focus']['en'] ?? '';
                                    $fDe = $row['focus']['de'] ?? $fEn;
                                    $nEn = $row['notes']['en'] ?? '';
                                    $nDe = $row['notes']['de'] ?? $nEn;
                                @endphp
                                <tr>
                                    <td data-text-de="{{ $fDe }}" data-text-en="{{ $fEn }}"><strong>{{ $fEn }}</strong></td>
                                    <td data-text-de="{{ $nDe }}" data-text-en="{{ $nEn }}">{{ $nEn }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
            </section>

            @if ($hasQuality)
                <section id="supplier-panel-quality" class="supplier-detail__section" data-supplier-panel="quality" role="tabpanel" aria-labelledby="supplier-tab-quality" hidden>
                    <p class="supplier-detail__section-lead" data-i18n="suppliers.qualityLead">
                        Source-specific guidance: what goes wrong, how to prevent it, what staff must know — plus where metadata lives and how to access it.
                    </p>

                    <div class="supplier-quality-stack">
                    @if ($qualityDq !== [])
                        <details class="supplier-quality-block">
                            <summary class="supplier-quality-block__summary">
                                <span class="supplier-quality-block__title" data-i18n="suppliers.dqTitle">Data quality guides</span>
                                <span class="supplier-quality-block__count">{{ count($qualityDq) }}</span>
                            </summary>
                            <div class="supplier-quality-block__body">
                                <p class="supplier-detail__section-lead" data-i18n="suppliers.dqLead">
                                    Prevent issues in the source first. Warehouse checks are a safety net — not a substitute for staff habits.
                                </p>
                                <div class="supplier-dq-list">
                                    @foreach ($qualityDq as $row)
                                        @php
                                            $prio = (string) ($row['priority'] ?? 'medium');
                                            if (! isset($priorityLabels[$prio])) {
                                                $prio = 'medium';
                                            }
                                            $pEn = $priorityLabels[$prio]['en'];
                                            $pDe = $priorityLabels[$prio]['de'];
                                            $tEn = $row['title']['en'] ?? '';
                                            $tDe = $row['title']['de'] ?? $tEn;
                                            $prEn = $row['problem']['en'] ?? '';
                                            $prDe = $row['problem']['de'] ?? $prEn;
                                            $prevEn = $row['prevent']['en'] ?? ($row['fixInSource']['en'] ?? '');
                                            $prevDe = $row['prevent']['de'] ?? ($row['fixInSource']['de'] ?? $prevEn);
                                            $staffEn = $row['staffNeedToKnow']['en'] ?? '';
                                            $staffDe = $row['staffNeedToKnow']['de'] ?? $staffEn;
                                            $cEn = $row['checks']['en'] ?? '';
                                            $cDe = $row['checks']['de'] ?? $cEn;
                                            $fsEn = $row['fixInSource']['en'] ?? '';
                                            $fsDe = $row['fixInSource']['de'] ?? $fsEn;
                                            $fwEn = $row['fixInWarehouse']['en'] ?? '';
                                            $fwDe = $row['fixInWarehouse']['de'] ?? $fwEn;
                                        @endphp
                                        <details class="supplier-dq-card">
                                            <summary class="supplier-dq-card__summary">
                                                <span
                                                    class="supplier-priority-badge supplier-priority-badge--{{ $prio }}"
                                                    data-text-de="{{ $pDe }}"
                                                    data-text-en="{{ $pEn }}"
                                                >{{ $pEn }}</span>
                                                <span class="supplier-dq-card__title" data-text-de="{{ $tDe }}" data-text-en="{{ $tEn }}">{{ $tEn }}</span>
                                            </summary>
                                            <dl class="supplier-dq-card__body">
                                                <div>
                                                    <dt data-i18n="suppliers.colProblem">Problem</dt>
                                                    <dd data-text-de="{{ $prDe }}" data-text-en="{{ $prEn }}">{{ $prEn }}</dd>
                                                </div>
                                                <div>
                                                    <dt data-i18n="suppliers.colPrevent">Prevent</dt>
                                                    <dd data-text-de="{{ $prevDe }}" data-text-en="{{ $prevEn }}">{{ $prevEn }}</dd>
                                                </div>
                                                <div class="supplier-dq-card__staff">
                                                    <dt data-i18n="suppliers.colStaff">Staff must know</dt>
                                                    <dd data-text-de="{{ $staffDe }}" data-text-en="{{ $staffEn }}">{{ $staffEn }}</dd>
                                                </div>
                                                <div>
                                                    <dt data-i18n="suppliers.colChecks">Checks</dt>
                                                    <dd><code data-text-de="{{ $cDe }}" data-text-en="{{ $cEn }}">{{ $cEn }}</code></dd>
                                                </div>
                                                <div>
                                                    <dt data-i18n="suppliers.colFixSource">Fix in source</dt>
                                                    <dd data-text-de="{{ $fsDe }}" data-text-en="{{ $fsEn }}">{{ $fsEn }}</dd>
                                                </div>
                                                <div>
                                                    <dt data-i18n="suppliers.colFixWarehouse">Warehouse safety net</dt>
                                                    <dd data-text-de="{{ $fwDe }}" data-text-en="{{ $fwEn }}">{{ $fwEn }}</dd>
                                                </div>
                                            </dl>
                                        </details>
                                    @endforeach
                                </div>
                            </div>
                        </details>
                    @endif

                    @if ($qualityMdm !== [])
                        <details class="supplier-quality-block">
                            <summary class="supplier-quality-block__summary">
                                <span class="supplier-quality-block__title" data-i18n="suppliers.mdmTitle">MDM / duplicates</span>
                                <span class="supplier-quality-block__count">{{ count($qualityMdm) }}</span>
                            </summary>
                            <div class="supplier-quality-block__body">
                                <div class="supplier-dq-list">
                                    @foreach ($qualityMdm as $row)
                                        @php
                                            $entity = (string) ($row['entity'] ?? '');
                                            $tEn = $row['title']['en'] ?? '';
                                            $tDe = $row['title']['de'] ?? $tEn;
                                            $keys = is_array($row['matchKeys'] ?? null) ? $row['matchKeys'] : [];
                                            $prevEn = $row['preventInSource']['en'] ?? '';
                                            $prevDe = $row['preventInSource']['de'] ?? $prevEn;
                                            $resEn = $row['resolveInWarehouse']['en'] ?? '';
                                            $resDe = $row['resolveInWarehouse']['de'] ?? $resEn;
                                            $survEn = $row['survivorship']['en'] ?? '';
                                            $survDe = $row['survivorship']['de'] ?? $survEn;
                                        @endphp
                                        <details class="supplier-dq-card">
                                            <summary class="supplier-dq-card__summary">
                                                <code class="supplier-dq-card__entity">{{ $entity }}</code>
                                                <span class="supplier-dq-card__title" data-text-de="{{ $tDe }}" data-text-en="{{ $tEn }}">{{ $tEn }}</span>
                                            </summary>
                                            <dl class="supplier-dq-card__body">
                                                <div>
                                                    <dt data-i18n="suppliers.colMatchKeys">Match keys</dt>
                                                    <dd><code>{{ implode(', ', $keys) }}</code></dd>
                                                </div>
                                                <div>
                                                    <dt data-i18n="suppliers.colPreventSource">Prevent in source</dt>
                                                    <dd data-text-de="{{ $prevDe }}" data-text-en="{{ $prevEn }}">{{ $prevEn }}</dd>
                                                </div>
                                                <div>
                                                    <dt data-i18n="suppliers.colResolveWarehouse">Resolve in warehouse</dt>
                                                    <dd data-text-de="{{ $resDe }}" data-text-en="{{ $resEn }}">{{ $resEn }}</dd>
                                                </div>
                                                <div>
                                                    <dt data-i18n="suppliers.colSurvivorship">Survivorship</dt>
                                                    <dd data-text-de="{{ $survDe }}" data-text-en="{{ $survEn }}">{{ $survEn }}</dd>
                                                </div>
                                            </dl>
                                        </details>
                                    @endforeach
                                </div>
                            </div>
                        </details>
                    @endif

                    @if ($qualitySettings !== [])
                        <details class="supplier-quality-block">
                            <summary class="supplier-quality-block__summary">
                                <span class="supplier-quality-block__title" data-i18n="suppliers.productSettingsTitle">Product settings to improve</span>
                                <span class="supplier-quality-block__count">{{ count($qualitySettings) }}</span>
                            </summary>
                            <div class="supplier-quality-block__body">
                                <div class="supplier-dq-list">
                                    @foreach ($qualitySettings as $row)
                                        @php
                                            $aEn = $row['area']['en'] ?? '';
                                            $aDe = $row['area']['de'] ?? $aEn;
                                            $sEn = $row['setting']['en'] ?? '';
                                            $sDe = $row['setting']['de'] ?? $sEn;
                                            $wEn = $row['why']['en'] ?? '';
                                            $wDe = $row['why']['de'] ?? $wEn;
                                            $hEn = $row['how']['en'] ?? '';
                                            $hDe = $row['how']['de'] ?? $hEn;
                                        @endphp
                                        <details class="supplier-dq-card">
                                            <summary class="supplier-dq-card__summary">
                                                <span
                                                    class="supplier-dq-card__area"
                                                    data-text-de="{{ $aDe }}"
                                                    data-text-en="{{ $aEn }}"
                                                >{{ $aEn }}</span>
                                                <span class="supplier-dq-card__title" data-text-de="{{ $sDe }}" data-text-en="{{ $sEn }}">{{ $sEn }}</span>
                                            </summary>
                                            <dl class="supplier-dq-card__body">
                                                <div>
                                                    <dt data-i18n="suppliers.colWhy">Why</dt>
                                                    <dd data-text-de="{{ $wDe }}" data-text-en="{{ $wEn }}">{{ $wEn }}</dd>
                                                </div>
                                                <div>
                                                    <dt data-i18n="suppliers.colHow">How</dt>
                                                    <dd data-text-de="{{ $hDe }}" data-text-en="{{ $hEn }}">{{ $hEn }}</dd>
                                                </div>
                                            </dl>
                                        </details>
                                    @endforeach
                                </div>
                            </div>
                        </details>
                    @endif

                    @if ($qualityMetadata !== [])
                        <details class="supplier-quality-block">
                            <summary class="supplier-quality-block__summary">
                                <span class="supplier-quality-block__title" data-i18n="suppliers.metadataTitle">Metadata: what exists &amp; how to get it</span>
                                <span class="supplier-quality-block__count">{{ count($qualityMetadata) }}</span>
                            </summary>
                            <div class="supplier-quality-block__body">
                                <p class="supplier-detail__section-lead" data-i18n="suppliers.metadataLead">
                                    Source-native catalogs for schema, picklists/properties and access paths — not a full dump.
                                </p>
                                <div class="supplier-dq-list">
                                    @foreach ($qualityMetadata as $row)
                                        @php
                                            $kEn = $row['kind']['en'] ?? '';
                                            $kDe = $row['kind']['de'] ?? $kEn;
                                            $wEn = $row['where']['en'] ?? '';
                                            $wDe = $row['where']['de'] ?? $wEn;
                                            $hEn = $row['how']['en'] ?? '';
                                            $hDe = $row['how']['de'] ?? $hEn;
                                            $uEn = $row['useFor']['en'] ?? '';
                                            $uDe = $row['useFor']['de'] ?? $uEn;
                                            $woEn = $row['watchouts']['en'] ?? '';
                                            $woDe = $row['watchouts']['de'] ?? $woEn;
                                        @endphp
                                        <details class="supplier-dq-card">
                                            <summary class="supplier-dq-card__summary">
                                                <span class="supplier-dq-card__title" data-text-de="{{ $kDe }}" data-text-en="{{ $kEn }}">{{ $kEn }}</span>
                                            </summary>
                                            <dl class="supplier-dq-card__body">
                                                <div>
                                                    <dt data-i18n="suppliers.colMetaWhere">Where in product</dt>
                                                    <dd data-text-de="{{ $wDe }}" data-text-en="{{ $wEn }}">{{ $wEn }}</dd>
                                                </div>
                                                <div>
                                                    <dt data-i18n="suppliers.colMetaHow">How to access</dt>
                                                    <dd data-text-de="{{ $hDe }}" data-text-en="{{ $hEn }}">{{ $hEn }}</dd>
                                                </div>
                                                <div>
                                                    <dt data-i18n="suppliers.colMetaUseFor">Use for</dt>
                                                    <dd data-text-de="{{ $uDe }}" data-text-en="{{ $uEn }}">{{ $uEn }}</dd>
                                                </div>
                                                <div>
                                                    <dt data-i18n="suppliers.colMetaWatchouts">Watchouts</dt>
                                                    <dd data-text-de="{{ $woDe }}" data-text-en="{{ $woEn }}">{{ $woEn }}</dd>
                                                </div>
                                            </dl>
                                        </details>
                                    @endforeach
                                </div>
                            </div>
                        </details>
                    @endif
                    </div>
                </section>
            @endif

            <section id="supplier-panel-tables" class="supplier-detail__section" data-supplier-panel="tables" role="tabpanel" aria-labelledby="supplier-tab-tables" hidden>
                <p class="supplier-detail__section-lead" data-i18n="suppliers.tablesLead">
                    Object overview with short descriptions and load guidance — not a full schema dump.
                </p>
                <div class="supplier-detail__block">
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
                </div>
            </section>

            <section id="supplier-panel-fields" class="supplier-detail__section" data-supplier-panel="fields" role="tabpanel" aria-labelledby="supplier-tab-fields" hidden>
                <p class="supplier-detail__section-lead" data-i18n="suppliers.fieldsLead">
                    Most important fields to load early — not a full schema dump.
                </p>
                <div class="supplier-detail__block">
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
                </div>
            </section>

            <section id="supplier-panel-skip" class="supplier-detail__section" data-supplier-panel="skip" role="tabpanel" aria-labelledby="supplier-tab-skip" hidden>
                <p class="supplier-detail__section-lead" data-i18n="suppliers.skipLead">
                    System and noise objects/fields you typically should not sync into the warehouse by default.
                </p>

                @if ($skipTables !== [])
                    <div class="supplier-detail__block">
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
                    </div>
                @endif

                @if ($skip !== [])
                    <div class="supplier-detail__block">
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
                    </div>
                @endif
            </section>

            @if ($sqlExamples !== [])
                <section id="supplier-panel-sql" class="supplier-detail__section" data-supplier-panel="sql" role="tabpanel" aria-labelledby="supplier-tab-sql" hidden>
                    <p class="supplier-detail__section-lead" data-i18n="suppliers.sqlLead">
                        Warehouse-neutral examples for landing (RAW) and curated models — copy and adapt to your dialect. Not vendor SOQL/API DDL.
                    </p>

                    @if ($sqlRaw !== [])
                        <details class="supplier-sql-group" open>
                            <summary class="supplier-sql-group__summary" data-i18n="suppliers.sqlStageRaw">Landing (RAW)</summary>
                            <div class="supplier-sql-group__body">
                                @foreach ($sqlRaw as $example)
                                    @include('suppliers.partials.sql-example', ['example' => $example])
                                @endforeach
                            </div>
                        </details>
                    @endif

                    @if ($sqlCurated !== [])
                        <details class="supplier-sql-group" open>
                            <summary class="supplier-sql-group__summary" data-i18n="suppliers.sqlStageCurated">Curated</summary>
                            <div class="supplier-sql-group__body">
                                @foreach ($sqlCurated as $example)
                                    @include('suppliers.partials.sql-example', ['example' => $example])
                                @endforeach
                            </div>
                        </details>
                    @endif
                </section>
            @endif

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

                <a class="supplier-resources-card" href="{{ $resourcesUrl }}">
                    <span class="supplier-resources-card__body">
                        <span class="supplier-resources-card__title" data-i18n="suppliers.resourcesCardTitle">
                            Vendor help on Resources
                        </span>
                        <span class="supplier-resources-card__lead" data-i18n="suppliers.resourcesCardLead">
                            Official docs, governance and learning paths for this source.
                        </span>
                    </span>
                    <span class="supplier-resources-card__cta" data-i18n="suppliers.resourcesCardCta">Open Resources →</span>
                </a>
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
