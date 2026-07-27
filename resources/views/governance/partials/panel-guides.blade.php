<nav class="governance-hub__subtabs" aria-label="Guides Bereiche" data-governance-subtabs="guides" role="tablist">
    <button type="button" class="governance-hub__subtab governance-hub__subtab--active" data-governance-subtab-toggle="journeys" role="tab" aria-controls="guides-journeys" aria-selected="true">
        <span data-text-de="Pfade" data-text-en="Paths">Paths</span>
    </button>
    <button type="button" class="governance-hub__subtab" data-governance-subtab-toggle="decisions" role="tab" aria-controls="guides-decisions" aria-selected="false" tabindex="-1">
        <span data-text-de="Entscheidungen" data-text-en="Decisions">Decisions</span>
    </button>
    <button type="button" class="governance-hub__subtab" data-governance-subtab-toggle="stacks" role="tab" aria-controls="guides-stacks" aria-selected="false" tabindex="-1">
        <span data-text-de="Stacks" data-text-en="Stacks">Stacks</span>
    </button>
    <button type="button" class="governance-hub__subtab" data-governance-subtab-toggle="kpi" role="tab" aria-controls="guides-kpi" aria-selected="false" tabindex="-1">
        <span data-text-de="KPI" data-text-en="KPI">KPI</span>
    </button>
    <button type="button" class="governance-hub__subtab" data-governance-subtab-toggle="supplier" role="tab" aria-controls="guides-supplier" aria-selected="false" tabindex="-1">
        <span data-text-de="Supplier" data-text-en="Supplier">Supplier</span>
    </button>
</nav>

<section class="governance-hub__guides-block" id="guides-journeys" data-governance-subtab-panel="journeys" role="tabpanel">
    <p
        class="tools-section__lead"
        data-hub-lead
        data-text-de="Discovery-Einstiege in Hubs und Tools."
        data-text-en="Discovery entry points into hubs and tools."
    >Discovery entry points into hubs and tools.</p>

    <div class="governance-hub__journey-grid">
        @foreach ($journeys as $journey)
            @php
                $labelEn = $journey['label']['en'] ?? $journey['id'];
                $labelDe = $journey['label']['de'] ?? $labelEn;
                $leadEn = $journey['lead']['en'] ?? '';
                $leadDe = $journey['lead']['de'] ?? $leadEn;
                $persona = match ($journey['id'] ?? '') {
                    'stack' => 'architect custodian',
                    'kpi' => 'product-owner consumer',
                    'pii' => 'owner steward custodian',
                    'supplier' => 'architect owner steward custodian',
                    'collect' => 'product-owner architect owner steward consumer custodian',
                    default => 'architect product-owner owner steward custodian consumer',
                };
            @endphp
            <article class="governance-hub__journey" data-persona="{{ $persona }}">
                <div class="governance-hub__journey-icon">
                    <i class="fa-solid {{ $journey['icon'] }}" aria-hidden="true"></i>
                </div>
                <h3 data-text-de="{{ $labelDe }}" data-text-en="{{ $labelEn }}">{{ $labelEn }}</h3>
                <p data-text-de="{{ $leadDe }}" data-text-en="{{ $leadEn }}">{{ $leadEn }}</p>
                <ul class="governance-hub__link-list">
                    @foreach ($journey['links'] as $link)
                        @php
                            $linkLabelEn = $link['label']['en'] ?? $link['href'];
                            $linkLabelDe = $link['label']['de'] ?? $linkLabelEn;
                        @endphp
                        <li>
                            <a href="{{ $link['href'] }}">
                                <span data-text-de="{{ $linkLabelDe }}" data-text-en="{{ $linkLabelEn }}">{{ $linkLabelEn }}</span>
                                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </article>
        @endforeach
    </div>
</section>

<section class="governance-hub__guides-block governance-hub__decision-section" id="guides-decisions" data-governance-subtab-panel="decisions" role="tabpanel" hidden>
    <p
        class="tools-section__lead"
        data-hub-lead
        data-text-de="Welche Frage klärst du wo — und welches Artefakt entsteht danach?"
        data-text-en="Which question do you answer where — and which artifact comes next?"
    >Which question do you answer where — and which artifact comes next?</p>

    <div class="governance-hub__decision-layout">
        <div class="governance-hub__decision-list">
            @foreach ([
                [
                    'icon' => 'fa-layer-group',
                    'persona' => 'architect custodian',
                    'goal' => 'stack',
                    'question' => ['de' => 'Welcher Stack passt?', 'en' => 'Which stack fits?'],
                    'helps' => ['de' => 'Vergleicht Fabric, Databricks, Snowflake/dbt, GCP, SAP und Open Source nach Cloud, BI, Catalog, Residency und Zertifikaten.', 'en' => 'Compares Fabric, Databricks, Snowflake/dbt, GCP, SAP, and open source by cloud, BI, catalog, residency, and certifications.'],
                    'outcome' => ['de' => 'Shortlist, offene Architekturfragen und Lern-/Zertifikatspfad.', 'en' => 'Shortlist, open architecture questions, and learning/certification path.'],
                    'links' => [
                        ['href' => locale_route('tools.governance-stack-advisor'), 'label' => ['de' => 'Stack Advisor', 'en' => 'Stack advisor']],
                        ['href' => locale_route('tools.architecture-fit'), 'label' => ['de' => 'Architecture Fit', 'en' => 'Architecture fit']],
                    ],
                ],
                [
                    'icon' => 'fa-database',
                    'persona' => 'architect owner custodian',
                    'goal' => 'supplier',
                    'question' => ['de' => 'Welche Quelle zuerst laden?', 'en' => 'Which source should load first?'],
                    'helps' => ['de' => 'Zeigt pro Supplier Kernobjekte, Skip-Tabellen, PII/DSDR-Risiken und typische KPI-Kandidaten.', 'en' => 'Shows core entities, skip tables, PII/DSDR risks, and typical KPI candidates per supplier.'],
                    'outcome' => ['de' => 'Source Scope mit must-have, optional, skip und Review-Fragen.', 'en' => 'Source scope with must-have, optional, skip, and review questions.'],
                    'links' => [
                        ['href' => locale_route('tools.source-scope-builder'), 'label' => ['de' => 'Source Scope Builder', 'en' => 'Source Scope Builder']],
                        ['href' => locale_route('tools.meta-export-generator'), 'label' => ['de' => 'Meta Export', 'en' => 'Meta export']],
                    ],
                ],
                [
                    'icon' => 'fa-gauge-high',
                    'persona' => 'product-owner consumer',
                    'goal' => 'kpi',
                    'question' => ['de' => 'Welche KPI wird zu welcher Tabelle?', 'en' => 'Which KPI becomes which table?'],
                    'helps' => ['de' => 'Klärt Geschäftsfrage, Formel, Grain, Dimensionen, Owner, Akzeptanzbeispiel und BI-Verwendung.', 'en' => 'Clarifies business question, formula, grain, dimensions, owner, acceptance example, and BI usage.'],
                    'outcome' => ['de' => 'KPI Card plus erste Fact-/Dimension-Kandidaten.', 'en' => 'KPI card plus first fact/dimension candidates.'],
                    'links' => [
                        ['href' => locale_route('tools.kpi-requirements-intake'), 'label' => ['de' => 'KPI Intake', 'en' => 'KPI intake']],
                        ['href' => locale_route('tools.report-inventory'), 'label' => ['de' => 'Report Inventory', 'en' => 'Report inventory']],
                    ],
                ],
                [
                    'icon' => 'fa-shield-halved',
                    'persona' => 'owner steward custodian',
                    'goal' => 'pii',
                    'question' => ['de' => 'Welche Risiken blockieren den Start?', 'en' => 'Which risks block the start?'],
                    'helps' => ['de' => 'Prüft PII, Freitext, DSDR-Suchkeys, Access, Retention, DQ-Gates und Compliance-Nachweise.', 'en' => 'Reviews PII, free text, DSDR search keys, access, retention, DQ gates, and compliance evidence.'],
                    'outcome' => ['de' => 'Risiko-Backlog, Policy-Entscheidungen und erste Governance Gates.', 'en' => 'Risk backlog, policy decisions, and first governance gates.'],
                    'links' => [
                        ['href' => locale_route('tools.pii-dsdr-readiness-checker'), 'label' => ['de' => 'PII/DSDR Check', 'en' => 'PII/DSDR check']],
                        ['href' => locale_route('compliance.index'), 'label' => ['de' => 'Compliance Hub', 'en' => 'Compliance hub']],
                    ],
                ],
            ] as $decision)
                @php
                    $questionEn = $decision['question']['en'];
                    $questionDe = $decision['question']['de'];
                    $helpsEn = $decision['helps']['en'];
                    $helpsDe = $decision['helps']['de'];
                    $outcomeEn = $decision['outcome']['en'];
                    $outcomeDe = $decision['outcome']['de'];
                @endphp
                <article class="governance-hub__decision-card" data-persona="{{ $decision['persona'] }}" data-goal="{{ $decision['goal'] ?? '' }}">
                    <div class="governance-hub__decision-icon">
                        <i class="fa-solid {{ $decision['icon'] }}" aria-hidden="true"></i>
                    </div>
                    <div class="governance-hub__decision-copy">
                        <p class="governance-hub__decision-label" data-text-de="Entscheidungsfrage" data-text-en="Decision question">Decision question</p>
                        <h3 data-text-de="{{ $questionDe }}" data-text-en="{{ $questionEn }}">{{ $questionEn }}</h3>
                        <dl class="governance-hub__decision-facts">
                            <div>
                                <dt data-text-de="Hilft bei" data-text-en="Helps with">Helps with</dt>
                                <dd data-text-de="{{ $helpsDe }}" data-text-en="{{ $helpsEn }}">{{ $helpsEn }}</dd>
                            </div>
                            <div>
                                <dt data-text-de="Danach hast du" data-text-en="Afterwards you have">Afterwards you have</dt>
                                <dd data-text-de="{{ $outcomeDe }}" data-text-en="{{ $outcomeEn }}">{{ $outcomeEn }}</dd>
                            </div>
                        </dl>
                        <div class="governance-hub__decision-links">
                            @foreach ($decision['links'] as $link)
                                @php
                                    $linkLabelEn = $link['label']['en'];
                                    $linkLabelDe = $link['label']['de'];
                                @endphp
                                <a href="{{ $link['href'] }}">
                                    <span data-text-de="{{ $linkLabelDe }}" data-text-en="{{ $linkLabelEn }}">{{ $linkLabelEn }}</span>
                                    <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="governance-hub__panel governance-hub__supplier-panel">
            <h3 data-text-de="Supplier Library: wofür hilft sie?" data-text-en="Supplier library: what does it decide?">Supplier library: what does it decide?</h3>
            <p
                class="governance-hub__panel-lead"
                data-text-de="Die Supplier Library ist keine Favoritenliste. Du wählst erst den Quelltyp, dann das konkrete System, und klärst daraus Scope, PII, Skip und KPI-Nutzen."
                data-text-en="The supplier library is not a favorites list. Pick the source type first, then the concrete system, and derive scope, PII, skip, and KPI usefulness."
            >The supplier library is not a favorites list. Pick the source type first, then the concrete system, and derive scope, PII, skip, and KPI usefulness.</p>
            <ul class="governance-hub__supplier-list">
                @foreach ([
                    [
                        'label' => ['de' => 'CRM & Revenue', 'en' => 'CRM & revenue'],
                        'examples' => ['Salesforce', 'HubSpot', 'Dynamics 365'],
                        'decides' => ['de' => 'Accounts, Contacts, Deals, Campaigns, ARR/Pipeline, Formular-PII.', 'en' => 'Accounts, contacts, deals, campaigns, ARR/pipeline, form PII.'],
                    ],
                    [
                        'label' => ['de' => 'ERP, Finance & Procurement', 'en' => 'ERP, finance & procurement'],
                        'examples' => ['SAP S/4HANA', 'NetSuite', 'DATEV', 'Coupa'],
                        'decides' => ['de' => 'Buchungen, Kunden/Lieferanten, Kostenstellen, Belege, Finance-KPIs.', 'en' => 'Postings, customers/suppliers, cost centers, documents, finance KPIs.'],
                    ],
                    [
                        'label' => ['de' => 'HCM & Workforce', 'en' => 'HCM & workforce'],
                        'examples' => ['Workday', 'SuccessFactors', 'Personio'],
                        'decides' => ['de' => 'Mitarbeiterdaten, Organisation, Abwesenheit, Compensation, Workforce-PII.', 'en' => 'Employee data, organization, absence, compensation, workforce PII.'],
                    ],
                    [
                        'label' => ['de' => 'Collaboration & Service', 'en' => 'Collaboration & service'],
                        'examples' => ['SharePoint', 'Teams', 'Jira', 'ServiceNow'],
                        'decides' => ['de' => 'Tickets, Sites, Spaces, Sharing, Freitext, Attachments und Access-Governance.', 'en' => 'Tickets, sites, spaces, sharing, free text, attachments, and access governance.'],
                    ],
                ] as $sourceType)
                    @php
                        $labelEn = $sourceType['label']['en'];
                        $labelDe = $sourceType['label']['de'];
                        $decidesEn = $sourceType['decides']['en'];
                        $decidesDe = $sourceType['decides']['de'];
                        $examples = implode(', ', $sourceType['examples']);
                    @endphp
                    <li>
                        <a href="{{ locale_route('suppliers.index') }}">
                            <strong data-text-de="{{ $labelDe }}" data-text-en="{{ $labelEn }}">{{ $labelEn }}</strong>
                            <span>{{ $examples }}</span>
                            <em data-text-de="{{ $decidesDe }}" data-text-en="{{ $decidesEn }}">{{ $decidesEn }}</em>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

<section class="governance-hub__guides-block" id="guides-stacks" data-governance-subtab-panel="stacks" role="tabpanel" hidden>
    @include('governance.partials.panel-stacks')
</section>

<section class="governance-hub__guides-block" id="guides-kpi" data-governance-subtab-panel="kpi" role="tabpanel" hidden>
    @include('governance.partials.panel-kpi')
</section>

<section class="governance-hub__guides-block" id="guides-supplier" data-governance-subtab-panel="supplier" role="tabpanel" hidden>
    @include('governance.partials.panel-supplier')
</section>
