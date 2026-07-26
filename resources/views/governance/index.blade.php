@extends('layouts.tools', ['viteEntries' => ['resources/js/governance/hub-advisor.js']])

@section('title', 'Governance Hub - ' . config('app.name'))
@section('meta_description', 'Data Governance Hub: connect tools, vendor resources, suppliers, compliance, KPI requirements, PII/DSDR checks and stack decisions.')

@push('head')
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="Governance Hub - {{ config('app.name') }}">
    <meta property="og:description" content="Connect governance tools, vendor resources, supplier discovery, compliance, KPI requirements and stack decisions.">
    <script type="application/ld+json">
        {!! json_encode([
            chr(64).'context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => 'Governance Hub',
            'description' => 'A guided hub for data governance decisions, KPI requirements, supplier discovery, PII checks, and stack selection.',
            'url' => url()->current(),
            'author' => [
                '@type' => 'Person',
                'name' => 'Thomas Lindackers',
                'url' => config('playbooks.author_url', 'https://binom.net'),
            ],
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => config('app.name'),
                'url' => url('/'),
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <div class="tools-content governance-hub">
        <header class="governance-hub__hero">
            <div class="governance-hub__hero-copy">
                <p class="governance-hub__eyebrow" data-text-de="Governance Schaltzentrale" data-text-en="Governance control hub">Governance control hub</p>
                <h1
                    class="tools-page-title governance-hub__title"
                    data-text-de="Data Governance entscheiden, sammeln und umsetzen"
                    data-text-en="Decide, collect, and ship data governance"
                >Decide, collect, and ship data governance</h1>
                <p
                    class="tools-page-lead governance-hub__lead"
                    data-hub-lead
                    data-text-de="Dieser Hub verbindet Playbooks, Tools, Vendor Resources, Supplier Library und Compliance zu einem gefuehrten Startpunkt: erst die richtige Frage, dann der passende Weg."
                    data-text-en="This hub connects playbooks, tools, vendor resources, supplier library, and compliance into one guided starting point: first the right question, then the right path."
                >This hub connects playbooks, tools, vendor resources, supplier library, and compliance into one guided starting point: first the right question, then the right path.</p>
            </div>

            <aside class="governance-hub__stats" aria-label="Hub coverage">
                <div class="governance-hub__stat">
                    <strong>{{ $counts['tools'] }}</strong>
                    <span data-text-de="Tools" data-text-en="tools">tools</span>
                </div>
                <div class="governance-hub__stat">
                    <strong>{{ $counts['resources'] }}</strong>
                    <span data-text-de="Resource Karten" data-text-en="resource cards">resource cards</span>
                </div>
                <div class="governance-hub__stat">
                    <strong>{{ $counts['suppliers'] }}</strong>
                    <span data-text-de="Supplier" data-text-en="suppliers">suppliers</span>
                </div>
                <div class="governance-hub__stat">
                    <strong>{{ $counts['stacks'] }}</strong>
                    <span data-text-de="Stack Pfade" data-text-en="stack paths">stack paths</span>
                </div>
            </aside>
        </header>

        @php
            $advisorLinks = [
                'tools' => [
                    'governance-stack-advisor' => locale_route('tools.governance-stack-advisor'),
                    'source-scope-builder' => locale_route('tools.source-scope-builder'),
                    'kpi-requirements-intake' => locale_route('tools.kpi-requirements-intake'),
                    'mart-design-brief-generator' => locale_route('tools.mart-design-brief-generator'),
                    'pii-dsdr-readiness-checker' => locale_route('tools.pii-dsdr-readiness-checker'),
                    'decision-brief-generator' => locale_route('tools.decision-brief-generator'),
                    'vendor-learning-path-builder' => locale_route('tools.vendor-learning-path-builder'),
                    'architecture-fit' => locale_route('tools.architecture-fit'),
                    'impact-effort' => locale_route('tools.impact-effort'),
                    'meta-export-generator' => locale_route('tools.meta-export-generator'),
                    'report-inventory' => locale_route('tools.report-inventory'),
                    'kpi-definition' => locale_route('tools.kpi-definition'),
                    'pii-policy-generator' => locale_route('tools.pii-policy-generator'),
                    'pii-recommend-generator' => locale_route('tools.pii-recommend-generator'),
                    'schema-yml-editor' => locale_route('tools.schema-yml-editor'),
                    'dbt-dq-rules-generator' => locale_route('tools.dbt-dq-rules-generator'),
                    'fabric-pii-governance-pattern-generator' => locale_route('tools.fabric-pii-governance-pattern-generator'),
                    'databricks-pii-governance-pattern-generator' => locale_route('tools.databricks-pii-governance-pattern-generator'),
                    'unity-catalog-governance-generator' => locale_route('tools.unity-catalog-governance-generator'),
                ],
                'hubs' => [
                    'resources' => locale_route('resources.index'),
                    'suppliers' => locale_route('suppliers.index'),
                    'compliance' => locale_route('compliance.index'),
                    'playbooks' => locale_route('playbooks.index'),
                ],
            ];
        @endphp

        <section class="governance-hub__section governance-advisor" id="governance-advisor" data-governance-advisor aria-labelledby="governance-advisor-title">
            <script type="application/json" data-governance-advisor-config>
                {!! json_encode(['links' => $advisorLinks], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
            </script>

            <div class="governance-hub__section-heading governance-advisor__heading">
                <div>
                    <p class="governance-hub__eyebrow" data-text-de="Interaktive Entscheidungshilfe" data-text-en="Interactive decision aid">Interactive decision aid</p>
                    <h2
                        id="governance-advisor-title"
                        class="tools-section__title"
                        data-text-de="Beantworte kurz die Lage, dann bekommst du passende Tools, Supplier und Links"
                        data-text-en="Answer the situation, then get matching tools, suppliers, and links"
                    >Answer the situation, then get matching tools, suppliers, and links</h2>
                </div>
                <p
                    class="tools-section__lead"
                    data-hub-lead
                    data-text-de="Gedacht fuer drei echte Startpunkte: neu bauen, bestehende Umgebung ergaenzen oder Orientierung in einer vorhandenen Landschaft finden."
                    data-text-en="Built for three real starting points: build new, extend an existing environment, or find orientation in a landscape that already exists."
                >Built for three real starting points: build new, extend an existing environment, or find orientation in a landscape that already exists.</p>
            </div>

            <div class="governance-advisor__layout">
                <form class="governance-advisor__form" aria-label="Governance advisor questions" data-governance-advisor-form>
                    <fieldset class="governance-advisor__fieldset">
                        <legend data-text-de="Ausgangslage" data-text-en="Starting point">Starting point</legend>
                        <div class="governance-advisor__options governance-advisor__options--three">
                            <label class="governance-advisor__option">
                                <input type="radio" name="scenario" value="new" checked>
                                <span>
                                    <strong data-text-de="Ich baue neu auf" data-text-en="I build new">I build new</strong>
                                    <small data-text-de="Stack, Quellen, erste KPIs und Governance Gates klaeren." data-text-en="Clarify stack, sources, first KPIs, and governance gates.">Clarify stack, sources, first KPIs, and governance gates.</small>
                                </span>
                            </label>
                            <label class="governance-advisor__option">
                                <input type="radio" name="scenario" value="extend">
                                <span>
                                    <strong data-text-de="Ich ergaenze Bestehendes" data-text-en="I extend existing">I extend existing</strong>
                                    <small data-text-de="Fit, Impact, neue Quelle und Abhaengigkeiten pruefen." data-text-en="Check fit, impact, new source, and dependencies.">Check fit, impact, new source, and dependencies.</small>
                                </span>
                            </label>
                            <label class="governance-advisor__option">
                                <input type="radio" name="scenario" value="help">
                                <span>
                                    <strong data-text-de="Alles ist da, ich brauche Hilfe" data-text-en="Everything exists, I need help">Everything exists, I need help</strong>
                                    <small data-text-de="Stories, Ressourcen, Zertifikate und naechste Schritte finden." data-text-en="Find stories, resources, certifications, and next steps.">Find stories, resources, certifications, and next steps.</small>
                                </span>
                            </label>
                        </div>
                    </fieldset>

                    <fieldset class="governance-advisor__fieldset">
                        <legend data-text-de="Was soll entschieden werden?" data-text-en="What needs a decision?">What needs a decision?</legend>
                        <div class="governance-advisor__options">
                            @foreach ([
                                ['value' => 'stack', 'icon' => 'fa-layer-group', 'de' => 'Stack', 'en' => 'Stack'],
                                ['value' => 'supplier', 'icon' => 'fa-database', 'de' => 'Quelle/Supplier', 'en' => 'Source/supplier'],
                                ['value' => 'kpi', 'icon' => 'fa-gauge-high', 'de' => 'KPI & Mart', 'en' => 'KPI & mart'],
                                ['value' => 'pii', 'icon' => 'fa-shield-halved', 'de' => 'PII/DSDR', 'en' => 'PII/DSDR'],
                                ['value' => 'dq', 'icon' => 'fa-circle-check', 'de' => 'Datenqualitaet', 'en' => 'Data quality'],
                                ['value' => 'learning', 'icon' => 'fa-graduation-cap', 'de' => 'Lernen/Zertifikate', 'en' => 'Learning/certs'],
                            ] as $index => $goal)
                                <label class="governance-advisor__pill">
                                    <input type="radio" name="goal" value="{{ $goal['value'] }}" @checked($index === 0)>
                                    <span>
                                        <i class="fa-solid {{ $goal['icon'] }}" aria-hidden="true"></i>
                                        <strong data-text-de="{{ $goal['de'] }}" data-text-en="{{ $goal['en'] }}">{{ $goal['en'] }}</strong>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>

                    <div class="governance-advisor__select-grid">
                        <label>
                            <span data-text-de="Quelltyp" data-text-en="Source type">Source type</span>
                            <select name="domain">
                                <option value="unknown" data-text-de="Noch offen / gemischt" data-text-en="Open / mixed">Open / mixed</option>
                                <option value="crm" data-text-de="CRM & Revenue" data-text-en="CRM & revenue">CRM & revenue</option>
                                <option value="erp" data-text-de="ERP, Finance & Procurement" data-text-en="ERP, finance & procurement">ERP, finance & procurement</option>
                                <option value="hcm" data-text-de="HCM & Workforce" data-text-en="HCM & workforce">HCM & workforce</option>
                                <option value="collab" data-text-de="Collaboration & Service" data-text-en="Collaboration & service">Collaboration & service</option>
                                <option value="finance" data-text-de="Reguliertes Finance Reporting" data-text-en="Regulated finance reporting">Regulated finance reporting</option>
                            </select>
                        </label>
                        <label>
                            <span data-text-de="Ziel-Stack" data-text-en="Target stack">Target stack</span>
                            <select name="platform">
                                <option value="unknown" data-text-de="Noch offen / mehrere" data-text-en="Open / multiple">Open / multiple</option>
                                <option value="fabric" data-text-de="Microsoft Fabric / Power BI" data-text-en="Microsoft Fabric / Power BI">Microsoft Fabric / Power BI</option>
                                <option value="databricks" data-text-de="Databricks Lakehouse" data-text-en="Databricks Lakehouse">Databricks Lakehouse</option>
                                <option value="snowflake-dbt" data-text-de="Snowflake / dbt" data-text-en="Snowflake / dbt">Snowflake / dbt</option>
                                <option value="sap" data-text-de="SAP-nahe Landschaft" data-text-en="SAP-near landscape">SAP-near landscape</option>
                                <option value="opensource" data-text-de="Open Source / leichtgewichtig" data-text-en="Open source / lightweight">Open source / lightweight</option>
                            </select>
                        </label>
                    </div>
                </form>

                <aside class="governance-advisor__results" aria-live="polite">
                    <div class="governance-advisor__result-header">
                        <p class="governance-hub__eyebrow" data-text-de="Empfehlung" data-text-en="Recommendation">Recommendation</p>
                        <h3 data-governance-advisor-summary>Start with the stack decision, then validate sources and governance gates.</h3>
                    </div>
                    <div class="governance-advisor__result-grid" data-governance-advisor-results></div>
                </aside>
            </div>
        </section>

        <section class="governance-hub__section" id="governance-journeys" aria-labelledby="governance-journeys-title">
            <div class="governance-hub__section-heading">
                <h2
                    id="governance-journeys-title"
                    class="tools-section__title"
                    data-text-de="Was willst du gerade vorbereiten?"
                    data-text-en="What are you preparing right now?"
                >What are you preparing right now?</h2>
                <p
                    class="tools-section__lead"
                    data-hub-lead
                    data-text-de="Die Karten sind keine neuen Silos. Sie fuehren in vorhandene Hubs und Tools und setzen die neue Beratungslogik darueber."
                    data-text-en="These cards are not new silos. They route into existing hubs and tools and add the advisory logic above them."
                >These cards are not new silos. They route into existing hubs and tools and add the advisory logic above them.</p>
            </div>

            <div class="governance-hub__journey-grid">
                @foreach ($journeys as $journey)
                    @php
                        $labelEn = $journey['label']['en'] ?? $journey['id'];
                        $labelDe = $journey['label']['de'] ?? $labelEn;
                        $leadEn = $journey['lead']['en'] ?? '';
                        $leadDe = $journey['lead']['de'] ?? $leadEn;
                    @endphp
                    <article class="governance-hub__journey">
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

        <section class="governance-hub__section governance-hub__workflow" aria-labelledby="governance-workflow-title">
            <div>
                <p class="governance-hub__eyebrow" data-text-de="Collect Infos Workflow" data-text-en="Collect infos workflow">Collect infos workflow</p>
                <h2
                    id="governance-workflow-title"
                    class="tools-section__title"
                    data-text-de="Von Workshop-Fragen zum Tabellen- und Entscheidungsbrief"
                    data-text-en="From workshop questions to table and decision brief"
                >From workshop questions to table and decision brief</h2>
                <p
                    class="tools-section__lead"
                    data-hub-lead
                    data-text-de="Der Hub ordnet die vorhandenen Werkzeuge als Beratungsfolge: Stakeholder, Business-Fragen, KPI Cards, Source Scope, PII/DSDR, DQ-Regeln, Mart Design, Decision Brief."
                    data-text-en="The hub orders the existing tools as an advisory sequence: stakeholders, business questions, KPI cards, source scope, PII/DSDR, DQ rules, mart design, decision brief."
                >The hub orders the existing tools as an advisory sequence: stakeholders, business questions, KPI cards, source scope, PII/DSDR, DQ rules, mart design, decision brief.</p>
            </div>

            <ol class="governance-hub__steps">
                @foreach ([
                    ['de' => 'Stakeholder', 'en' => 'Stakeholders'],
                    ['de' => 'Business-Fragen', 'en' => 'Business questions'],
                    ['de' => 'KPI Cards', 'en' => 'KPI cards'],
                    ['de' => 'Source Scope', 'en' => 'Source scope'],
                    ['de' => 'PII/DSDR', 'en' => 'PII/DSDR'],
                    ['de' => 'DQ-Regeln', 'en' => 'DQ rules'],
                    ['de' => 'Mart Design', 'en' => 'Mart design'],
                    ['de' => 'Decision Brief', 'en' => 'Decision brief'],
                ] as $index => $step)
                    <li>
                        <span>{{ $index + 1 }}</span>
                        <strong data-text-de="{{ $step['de'] }}" data-text-en="{{ $step['en'] }}">{{ $step['en'] }}</strong>
                    </li>
                @endforeach
            </ol>
        </section>

        <section class="governance-hub__section governance-hub__decision-section" aria-labelledby="governance-stacks-title">
            <div class="governance-hub__decision-main">
                <h2
                    id="governance-stacks-title"
                    class="tools-section__title"
                    data-text-de="Entscheidungshilfen: welche Frage klaerst du wo?"
                    data-text-en="Decision aids: which question do you answer where?"
                >Decision aids: which question do you answer where?</h2>
                <p
                    class="tools-section__lead"
                    data-hub-lead
                    data-text-de="Jede Karte sagt dir, bei welcher Entscheidung sie hilft, welches vorhandene Hub oder Tool du oeffnest und welches Artefakt danach entstehen soll."
                    data-text-en="Each card tells you which decision it helps with, which existing hub or tool to open, and which artifact should exist afterwards."
                >Each card tells you which decision it helps with, which existing hub or tool to open, and which artifact should exist afterwards.</p>

                <div class="governance-hub__decision-list">
                    @foreach ([
                        [
                            'icon' => 'fa-layer-group',
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
                            'question' => ['de' => 'Welche KPI wird zu welcher Tabelle?', 'en' => 'Which KPI becomes which table?'],
                            'helps' => ['de' => 'Klaert Geschaeftsfrage, Formel, Grain, Dimensionen, Owner, Akzeptanzbeispiel und BI-Verwendung.', 'en' => 'Clarifies business question, formula, grain, dimensions, owner, acceptance example, and BI usage.'],
                            'outcome' => ['de' => 'KPI Card plus erste Fact-/Dimension-Kandidaten.', 'en' => 'KPI card plus first fact/dimension candidates.'],
                            'links' => [
                                ['href' => locale_route('tools.kpi-requirements-intake'), 'label' => ['de' => 'KPI Intake', 'en' => 'KPI intake']],
                                ['href' => locale_route('tools.report-inventory'), 'label' => ['de' => 'Report Inventory', 'en' => 'Report inventory']],
                            ],
                        ],
                        [
                            'icon' => 'fa-shield-halved',
                            'question' => ['de' => 'Welche Risiken blockieren den Start?', 'en' => 'Which risks block the start?'],
                            'helps' => ['de' => 'Prueft PII, Freitext, DSDR-Suchkeys, Access, Retention, DQ-Gates und Compliance-Nachweise.', 'en' => 'Reviews PII, free text, DSDR search keys, access, retention, DQ gates, and compliance evidence.'],
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
                        <article class="governance-hub__decision-card">
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
            </div>

            <div class="governance-hub__panel governance-hub__supplier-panel">
                <h3 data-text-de="Supplier Library: wofuer hilft sie?" data-text-en="Supplier library: what does it decide?">Supplier library: what does it decide?</h3>
                <p
                    class="governance-hub__panel-lead"
                    data-text-de="Die Supplier Library ist keine Favoritenliste. Du waehlst erst den Quelltyp, dann das konkrete System, und klaerst daraus Scope, PII, Skip und KPI-Nutzen."
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
        </section>

        <section class="governance-hub__section" aria-labelledby="governance-tools-title">
            <div class="governance-hub__section-heading">
                <h2
                    id="governance-tools-title"
                    class="tools-section__title"
                    data-text-de="Werkbank fuer die naechste Aktion"
                    data-text-en="Workbench for the next action"
                >Workbench for the next action</h2>
                <p
                    class="tools-section__lead"
                    data-hub-lead
                    data-text-de="Diese bestehenden Tools sind die ersten Bausteine fuer den neuen Governance Discovery Canvas."
                    data-text-en="These existing tools are the first building blocks for the new Governance Discovery Canvas."
                >These existing tools are the first building blocks for the new Governance Discovery Canvas.</p>
            </div>

            <div class="governance-hub__tool-grid">
                @foreach ($featuredTools as $tool)
                    @php
                        $labelEn = $tool['label']['en'] ?? ($tool['id'] ?? '');
                        $labelDe = $tool['label']['de'] ?? $labelEn;
                        $descEn = $tool['description']['en'] ?? '';
                        $descDe = $tool['description']['de'] ?? $descEn;
                    @endphp
                    <a class="governance-hub__tool" href="{{ locale_route($tool['route']) }}">
                        @if (! empty($tool['icon']))
                            <i class="fa-solid {{ $tool['icon'] }}" aria-hidden="true"></i>
                        @endif
                        <span>
                            <strong data-text-de="{{ $labelDe }}" data-text-en="{{ $labelEn }}">{{ $labelEn }}</strong>
                            <small data-text-de="{{ $descDe }}" data-text-en="{{ $descEn }}">{{ $descEn }}</small>
                        </span>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
@endsection
