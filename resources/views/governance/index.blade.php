@extends('layouts.tools')

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
                <div class="governance-hub__actions" aria-label="Governance hub actions">
                    <a class="governance-hub__button governance-hub__button--primary" href="#governance-journeys">
                        <i class="fa-solid fa-compass" aria-hidden="true"></i>
                        <span data-text-de="Entscheidungspfad waehlen" data-text-en="Choose a decision path">Choose a decision path</span>
                    </a>
                    <a class="governance-hub__button" href="{{ locale_route('resources.index') }}">
                        <i class="fa-solid fa-link" aria-hidden="true"></i>
                        <span data-text-de="Resources oeffnen" data-text-en="Open resources">Open resources</span>
                    </a>
                </div>
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
                                ['href' => locale_route('resources.index'), 'label' => ['de' => 'Resources Stack-Filter', 'en' => 'Resources stack filter']],
                                ['href' => locale_route('tools.architecture-fit'), 'label' => ['de' => 'Architecture Fit', 'en' => 'Architecture fit']],
                            ],
                        ],
                        [
                            'icon' => 'fa-database',
                            'question' => ['de' => 'Welche Quelle zuerst laden?', 'en' => 'Which source should load first?'],
                            'helps' => ['de' => 'Zeigt pro Supplier Kernobjekte, Skip-Tabellen, PII/DSDR-Risiken und typische KPI-Kandidaten.', 'en' => 'Shows core entities, skip tables, PII/DSDR risks, and typical KPI candidates per supplier.'],
                            'outcome' => ['de' => 'Source Scope mit must-have, optional, skip und Review-Fragen.', 'en' => 'Source scope with must-have, optional, skip, and review questions.'],
                            'links' => [
                                ['href' => locale_route('suppliers.index'), 'label' => ['de' => 'Supplier Library', 'en' => 'Supplier library']],
                                ['href' => locale_route('tools.meta-export-generator'), 'label' => ['de' => 'Meta Export', 'en' => 'Meta export']],
                            ],
                        ],
                        [
                            'icon' => 'fa-gauge-high',
                            'question' => ['de' => 'Welche KPI wird zu welcher Tabelle?', 'en' => 'Which KPI becomes which table?'],
                            'helps' => ['de' => 'Klaert Geschaeftsfrage, Formel, Grain, Dimensionen, Owner, Akzeptanzbeispiel und BI-Verwendung.', 'en' => 'Clarifies business question, formula, grain, dimensions, owner, acceptance example, and BI usage.'],
                            'outcome' => ['de' => 'KPI Card plus erste Fact-/Dimension-Kandidaten.', 'en' => 'KPI card plus first fact/dimension candidates.'],
                            'links' => [
                                ['href' => locale_route('tools.kpi-definition'), 'label' => ['de' => 'KPI Definition', 'en' => 'KPI definition']],
                                ['href' => locale_route('tools.report-inventory'), 'label' => ['de' => 'Report Inventory', 'en' => 'Report inventory']],
                            ],
                        ],
                        [
                            'icon' => 'fa-shield-halved',
                            'question' => ['de' => 'Welche Risiken blockieren den Start?', 'en' => 'Which risks block the start?'],
                            'helps' => ['de' => 'Prueft PII, Freitext, DSDR-Suchkeys, Access, Retention, DQ-Gates und Compliance-Nachweise.', 'en' => 'Reviews PII, free text, DSDR search keys, access, retention, DQ gates, and compliance evidence.'],
                            'outcome' => ['de' => 'Risiko-Backlog, Policy-Entscheidungen und erste Governance Gates.', 'en' => 'Risk backlog, policy decisions, and first governance gates.'],
                            'links' => [
                                ['href' => locale_route('tools.pii-policy-generator'), 'label' => ['de' => 'PII Policy', 'en' => 'PII policy']],
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
                    data-text-de="Oeffne einen Supplier, wenn du vor dem ersten Load wissen musst: Welche Objekte sind Kernscope, welche Felder sind PII, welche Tabellen sind Ballast und welche KPIs sind plausibel?"
                    data-text-en="Open a supplier when you need to know before the first load: which objects are core scope, which fields are PII, which tables are ballast, and which KPIs are plausible?"
                >Open a supplier when you need to know before the first load: which objects are core scope, which fields are PII, which tables are ballast, and which KPIs are plausible?</p>
                <ul class="governance-hub__supplier-list">
                    @foreach ($featuredSuppliers as $supplier)
                        @php
                            $id = (string) ($supplier['id'] ?? '');
                            $labelEn = $supplier['label']['en'] ?? $id;
                            $labelDe = $supplier['label']['de'] ?? $labelEn;
                            $purposeEn = $supplier['shortPurpose']['en'] ?? '';
                            $purposeDe = $supplier['shortPurpose']['de'] ?? $purposeEn;
                        @endphp
                        <li>
                            <a href="{{ locale_route('suppliers.show', ['slug' => $id]) }}">
                                <strong data-text-de="{{ $labelDe }}" data-text-en="{{ $labelEn }}">{{ $labelEn }}</strong>
                                <span data-text-de="{{ $purposeDe }}" data-text-en="{{ $purposeEn }}">{{ $purposeEn }}</span>
                                <em data-text-de="Entscheidet: Load Scope, PII/DSDR, Skip, KPI-Kandidaten" data-text-en="Decides: load scope, PII/DSDR, skip, KPI candidates">Decides: load scope, PII/DSDR, skip, KPI candidates</em>
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
