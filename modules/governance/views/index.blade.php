@extends('foundations.layouts.tools', [
    'viteEntries' => ['modules/governance/js/hub-advisor.js', 'modules/governance/js/discovery-canvas.js'],
    'mainClass' => 'tools-shell__main--overview',
])

@php
    $faqEntities = collect($hubFaqs ?? [])->map(static fn (array $faq): array => [
        '@type' => 'Question',
        'name' => $faq['qEn'],
        'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => $faq['aEn'],
        ],
    ])->all();
@endphp

@section('title', 'Governance Hub - ' . config('app.name'))
@section('meta_description', 'Data governance help hub by Thomas Lindackers: clarify decisions, stacks, sources, and risks — then open matching tools, suppliers, playbooks, and evidence.')

@push('head')
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="Governance Hub - {{ config('app.name') }}">
    <meta property="og:description" content="Data governance help hub: orientation first, then tools, suppliers, and evidence — by Thomas Lindackers.">
    <script type="application/ld+json">
        {!! json_encode([
            chr(64).'context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => 'Governance Hub',
            'description' => 'Data governance help hub by Thomas Lindackers for decisions, KPI requirements, supplier discovery, PII checks, and stack selection.',
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
    @if (! empty($faqEntities))
        <script type="application/ld+json">
            {!! json_encode([
                chr(64).'context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $faqEntities,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
    @endif
@endpush

@section('content')
    <div class="tools-content governance-hub" data-governance-advisor>
        <script type="application/json" data-governance-advisor-config>
            {!! json_encode([
                'links' => $advisorLinks,
                'preferredRole' => $preferredRole ?? '',
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>

        <header class="governance-hub__hero">
        <section class="governance-hub__top-controls" id="governance-header-drawer" aria-label="Governance Hub Hinweise und Session" data-governance-top-controls hidden>
            <nav class="governance-hub__panel-tabs" aria-label="Header Bereich" role="tablist">
                <button type="button" class="governance-hub__panel-tab governance-hub__panel-tab--active" id="governance-panel-tab-save" data-governance-panel-toggle="governance-save-panel" role="tab" aria-controls="governance-save-panel" aria-selected="true">
                    <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                    <span data-text-de="Speichern" data-text-en="Save">Save</span>
                </button>
                <button type="button" class="governance-hub__panel-tab" id="governance-panel-tab-filter" data-governance-panel-toggle="governance-filter-panel" role="tab" aria-controls="governance-filter-panel" aria-selected="false" tabindex="-1">
                    <i class="fa-solid fa-filter" aria-hidden="true"></i>
                    <span data-text-de="Filter" data-text-en="Filter">Filter</span>
                </button>
                <button type="button" class="governance-hub__panel-tab" id="governance-panel-tab-help" data-governance-panel-toggle="governance-help-panel" role="tab" aria-controls="governance-help-panel" aria-selected="false" tabindex="-1">
                    <i class="fa-solid fa-circle-question" aria-hidden="true"></i>
                    <span data-text-de="Hilfe" data-text-en="Help">Help</span>
                </button>
            </nav>

            <div class="governance-hub__panel-stage">
            <section class="governance-advisor__save-disclosure governance-advisor__save-disclosure--hub" id="governance-save-panel" aria-labelledby="governance-panel-tab-save" data-governance-save-panel data-governance-panel role="tabpanel">
                <div class="governance-advisor__save-head">
                    <span>
                        <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                        <strong data-text-de="Session speichern oder Demo anlegen" data-text-en="Save session or create demo">Save session or create demo</strong>
                    </span>
                    <small data-text-de="Gilt für den gesamten Governance Hub: Report, Workflow, Demo Workspace und spätere Änderungen vorbereiten." data-text-en="Applies to the whole Governance Hub: prepare report, workflow, demo workspace, and later changes.">Applies to the whole Governance Hub: prepare report, workflow, demo workspace, and later changes.</small>
                </div>
                <div class="governance-advisor__save">
                    <label>
                        <span data-text-de="Session Titel" data-text-en="Session title">Session title</span>
                        <input type="text" name="title" value="Governance Discovery" data-governance-session-title>
                    </label>
                    <div class="governance-advisor__save-grid">
                        <label>
                            <span data-text-de="Firma" data-text-en="Company">Company</span>
                            <input type="text" name="companyName" placeholder="Acme GmbH" data-governance-session-company>
                        </label>
                        <label>
                            <span data-text-de="Projekt" data-text-en="Project">Project</span>
                            <input type="text" name="projectName" placeholder="Data Platform 2026" data-governance-session-project>
                        </label>
                    </div>
                    <div class="governance-advisor__save-actions">
                        <div class="governance-advisor__save-primary">
                            <button type="button" class="governance-hub__button governance-hub__button--primary" data-governance-save-session>
                                <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                                <span data-text-de="Session speichern" data-text-en="Save session">Save session</span>
                            </button>
                        </div>
                        <div class="governance-advisor__save-secondary">
                            <a class="governance-hub__button" href="{{ locale_route('governance.sessions.demo-workspace') }}">
                                <i class="fa-solid fa-diagram-project" aria-hidden="true"></i>
                                <span data-text-de="Demo Workspace ansehen" data-text-en="View demo workspace">View demo workspace</span>
                            </a>
                            <a class="governance-hub__button" href="{{ locale_route('governance.sessions.demo-report') }}">
                                <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                <span data-text-de="Beispiel-Report ansehen" data-text-en="View example report">View example report</span>
                            </a>
                            <a class="governance-hub__button" href="{{ locale_route('sprint-planner.index') }}?list=1">
                                <i class="fa-solid fa-folder-open" aria-hidden="true"></i>
                                <span data-text-de="Aktive Pläne weiterführen" data-text-en="Continue active plans">Continue active plans</span>
                            </a>
                            <button type="button" class="governance-hub__button" data-governance-save-demo>
                                <i class="fa-solid fa-vial" aria-hidden="true"></i>
                                <span data-text-de="Demo speichern" data-text-en="Save demo">Save demo</span>
                            </button>
                            <a class="governance-hub__button" href="#" data-governance-view-report hidden>
                                <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                <span data-text-de="Report ansehen" data-text-en="View report">View report</span>
                            </a>
                        @if (! empty($accountUser))
                            <a class="governance-hub__button" href="{{ locale_route('governance.sessions.index') }}">
                                <i class="fa-solid fa-table-list" aria-hidden="true"></i>
                                <span data-text-de="Gespeicherte Discoveries" data-text-en="Saved discoveries">Saved discoveries</span>
                            </a>
                        @elseif (! empty($accountsEnabled))
                            <a class="governance-hub__button" href="{{ locale_route('accounts.login') }}">
                                <i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i>
                                <span data-text-de="Login für permanent" data-text-en="Sign in for permanent">Sign in for permanent</span>
                            </a>
                        @endif
                        </div>
                    </div>
                    <p class="governance-advisor__save-status" data-governance-save-status>
                        @if (! empty($accountUser))
                            <span data-text-de="Eingeloggt: Sessions werden dauerhaft gespeichert." data-text-en="Signed in: sessions are stored permanently.">Signed in: sessions are stored permanently.</span>
                        @elseif (! empty($accountsEnabled))
                            <span data-text-de="Demo-Sessions bleiben nur in dieser Browser-Sitzung. Login speichert permanent." data-text-en="Demo sessions stay in this browser session only. Sign in to store permanently.">Demo sessions stay in this browser session only. Sign in to store permanently.</span>
                        @else
                            <span data-text-de="Accounts sind deaktiviert. Demo-Sessions bleiben lokal in dieser Sitzung." data-text-en="Accounts are disabled. Demo sessions stay local to this session.">Accounts are disabled. Demo sessions stay local to this session.</span>
                        @endif
                    </p>
                </div>
            </section>

            <article class="governance-advisor__helpbox" id="governance-filter-panel" aria-labelledby="governance-panel-tab-filter" data-governance-panel role="tabpanel" hidden>
                <div class="governance-advisor__helpbox-head">
                    <span class="governance-advisor__helpbox-icon">
                        <i class="fa-solid fa-filter" aria-hidden="true"></i>
                    </span>
                    <span>
                        <span class="governance-hub__eyebrow" data-text-de="Hub-Filter" data-text-en="Hub filter">Hub filter</span>
                        <strong data-text-de="Rolle, Goal, Domain und Stack steuern Advisor, Guides, Workshop und Tools." data-text-en="Role, goal, domain, and stack steer advisor, guides, workshop, and tools.">Role, goal, domain, and stack steer advisor, guides, workshop, and tools.</strong>
                    </span>
                </div>
                <div class="governance-advisor__helpbox-content">
                    <p
                        data-governance-hub-context-label
                        data-text-de="Kein Filter aktiv — Rolle „Alle“ und offene Domain/Stack."
                        data-text-en="No filter active — role “All” and open domain/stack."
                    >No filter active — role “All” and open domain/stack.</p>
                    <ol>
                        <li data-text-de="Rolle im Advisor wählen: Goals werden priorisiert, Guides/Tools gefiltert." data-text-en="Pick a role in the advisor: goals are prioritized, guides/tools are filtered.">Pick a role in the advisor: goals are prioritized, guides/tools are filtered.</li>
                        <li data-text-de="Domain oder Stack setzen: Empfehlungen und Karten werden enger." data-text-en="Set domain or stack: recommendations and cards get narrower.">Set domain or stack: recommendations and cards get narrower.</li>
                        <li data-text-de="Eigener Stack: Platform „Custom“ öffnet den Stack Builder." data-text-en="Custom stack: platform “Custom” opens the Stack Builder.">Custom stack: platform “Custom” opens the Stack Builder.</li>
                    </ol>
                    <div class="governance-hub__filter-panel-actions">
                        <button type="button" class="governance-hub__button governance-hub__button--primary" data-governance-hub-filter-clear>
                            <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                            <span data-text-de="Filter zurücksetzen" data-text-en="Reset filter">Reset filter</span>
                        </button>
                        <button type="button" class="governance-hub__button" data-governance-open-panel="governance-save-panel" data-text-de="Zum Speichern" data-text-en="Go to save">
                            <span data-text-de="Zum Speichern" data-text-en="Go to save">Go to save</span>
                        </button>
                    </div>
                </div>
            </article>

            <article class="governance-advisor__helpbox" id="governance-help-panel" aria-labelledby="governance-panel-tab-help" data-governance-panel role="tabpanel" hidden>
                <div class="governance-advisor__helpbox-head">
                    <span class="governance-advisor__helpbox-icon">
                        <i class="fa-solid fa-circle-question" aria-hidden="true"></i>
                    </span>
                    <span>
                        <span class="governance-hub__eyebrow" data-text-de="So nutzt du den Advisor" data-text-en="How to use the advisor">How to use the advisor</span>
                        <strong data-text-de="Erst die Lage klären, dann passende Tools, Supplier und Nachweise öffnen." data-text-en="Clarify the situation first, then open matching tools, suppliers, and evidence.">Clarify the situation first, then open matching tools, suppliers, and evidence.</strong>
                    </span>
                </div>
                <div class="governance-advisor__helpbox-content">
                    <p
                        data-text-de="Der Governance Hub ist keine Linkliste. Er soll dich durch eine Discovery führen: Welche Entscheidung steht an, welcher Stack ist betroffen, welche Quelle liefert Daten, welche Risiken müssen vor Umsetzung geprüft werden und welche Artefakte sollen später in Plan, Workflow oder Report landen?"
                        data-text-en="The Governance Hub is not just a link list. It guides a discovery: what decision is pending, which stack is affected, which source supplies data, which risks must be checked before implementation, and which artifacts should later move into a plan, workflow, or report?"
                    >The Governance Hub is not just a link list. It guides a discovery: what decision is pending, which stack is affected, which source supplies data, which risks must be checked before implementation, and which artifacts should later move into a plan, workflow, or report?</p>
                    <ol>
                        <li data-text-de="Advisor: Lage in wenigen Fragen klären und passende Tools/Supplier bekommen." data-text-en="Advisor: clarify the situation in a few questions and get matching tools/suppliers.">Advisor: clarify the situation in a few questions and get matching tools/suppliers.</li>
                        <li data-text-de="Guides: Pfade, Entscheidungen, Stacks, KPI und Supplier über die Unter-Tabs." data-text-en="Guides: paths, decisions, stacks, KPI, and suppliers via the sub-tabs.">Guides: paths, decisions, stacks, KPI, and suppliers via the sub-tabs.</li>
                        <li data-text-de="Workshop: Notizen lokal erfassen und exportieren." data-text-en="Workshop: capture notes locally and export them.">Workshop: capture notes locally and export them.</li>
                        <li data-text-de="Tools: Featured Generatoren oder Setup-Workflows öffnen." data-text-en="Tools: open featured generators or setup workflows.">Tools: open featured generators or setup workflows.</li>
                        <li data-text-de="Speichere die Session, wenn daraus Report, Plan-Aufgabe oder Change Request entstehen soll." data-text-en="Save the session when it should become a report, plan task, or change request.">Save the session when it should become a report, plan task, or change request.</li>
                    </ol>

                    <h3 data-text-de="Decision Ladder" data-text-en="Decision Ladder">Decision Ladder</h3>
                    <p
                        data-text-de="Decision Ladder: Orientierung → Fragen → Tools → Nachweise. Einstieg ist der Governance Advisor."
                        data-text-en="Decision Ladder: orientation → questions → tools → evidence. Entry is the Governance Advisor."
                    >Decision Ladder: orientation → questions → tools → evidence. Entry is the Governance Advisor.</p>
                    <h3 data-text-de="Collect Infos 8 &amp; Evidence Loop" data-text-en="Collect Infos 8 &amp; Evidence Loop">Collect Infos 8 &amp; Evidence Loop</h3>
                    <p
                        data-text-de="Collect Infos 8 benennt die acht Canvas-Schritte. Evidence Loop: Entscheidung → Control → Nachweis. Platform Starting Point und Source Load First sind je ein Satz aus den bestehenden Serien — die 8 Pillars bleiben das Praxisgerüst."
                        data-text-en="Collect Infos 8 names the eight canvas steps. Evidence Loop: decision → control → evidence. Platform Starting Point and Source Load First are one-liners from the existing series — the 8 Pillars stay the practical scaffold."
                    >Collect Infos 8 names the eight canvas steps. Evidence Loop: decision → control → evidence. Platform Starting Point and Source Load First are one-liners from the existing series — the 8 Pillars stay the practical scaffold.</p>

                    <h3 data-text-de="Zusammenspiel der Tools" data-text-en="How the tools connect">How the tools connect</h3>
                    <p
                        data-text-de="Jedes Tool kann allein genutzt werden, liefert aber einen Baustein für denselben Governance-Report. KPI Intake erzeugt KPI-Karten, Source Scope den Ladeumfang, Mart Design Tabellenentscheidungen, Starting-Point Decision den Plattform-Start, Data Quality Regeln/Gates, Decision Brief die Freigabe."
                        data-text-en="Each tool works standalone, but contributes a block to the same governance report. KPI Intake creates KPI cards, Source Scope the load scope, Mart Design table decisions, Starting-Point Decision the platform start, Data Quality rules/gates, Decision Brief the approval."
                    >Each tool works standalone, but contributes a block to the same governance report. KPI Intake creates KPI cards, Source Scope the load scope, Mart Design table decisions, Starting-Point Decision the platform start, Data Quality rules/gates, Decision Brief the approval.</p>

                    @if (! empty($hubFaqs))
                        <div class="governance-hub__faq" id="governance-faq" aria-labelledby="governance-faq-title">
                            <h3 id="governance-faq-title" data-text-de="Häufige Fragen" data-text-en="FAQ">FAQ</h3>
                            <div class="governance-hub__faq-list">
                                @foreach ($hubFaqs as $faq)
                                    <details>
                                        <summary data-text-de="{{ $faq['qDe'] }}" data-text-en="{{ $faq['qEn'] }}">{{ $faq['qEn'] }}</summary>
                                        <p data-text-de="{{ $faq['aDe'] }}" data-text-en="{{ $faq['aEn'] }}">{{ $faq['aEn'] }}</p>
                                    </details>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </article>
            </div>
        </section>

            <div class="governance-hub__hero-copy">
                <p class="governance-hub__eyebrow" data-text-de="Governance Help Hub" data-text-en="Governance Help Hub">Governance Help Hub</p>
                <h1
                    class="tools-page-title governance-hub__title"
                    data-text-de="Data Governance entscheiden, sammeln und umsetzen"
                    data-text-en="Decide, collect, and ship data governance"
                >Decide, collect, and ship data governance</h1>
                <p
                    class="tools-page-lead governance-hub__lead"
                    data-hub-lead
                    data-text-de="Von Thomas Lindackers: erst die richtige Frage, dann Playbooks, Tools, Vendor Resources, Supplier Library und Compliance. Beispielweg ohne Login: Demo-Workspace und Beispiel-Report."
                    data-text-en="By Thomas Lindackers: first the right question, then playbooks, tools, vendor resources, supplier library, and compliance. Example path without login: demo workspace and sample report."
                >By Thomas Lindackers: first the right question, then playbooks, tools, vendor resources, supplier library, and compliance. Example path without login: demo workspace and sample report.</p>
                <nav class="governance-hub__explore" aria-label="Related hubs">
                    <a href="{{ locale_route('playbooks.index') }}" data-text-de="Playbooks" data-text-en="Playbooks">Playbooks</a>
                    <a href="{{ locale_route('learning-paths.index') }}" data-text-de="Learning Paths" data-text-en="Learning Paths">Learning Paths</a>
                    <a href="{{ locale_route('roles.index') }}" data-text-de="Rollen" data-text-en="Roles">Roles</a>
                    <a href="{{ locale_route('tools.overview') }}" data-text-de="Tools" data-text-en="Tools">Tools</a>
                    <a href="{{ locale_route('resources.index') }}" data-text-de="Resources" data-text-en="Resources">Resources</a>
                    <a href="{{ locale_route('suppliers.index') }}" data-text-de="Sources" data-text-en="Sources">Sources</a>
                    <a href="{{ locale_route('compliance.index') }}" data-text-de="Compliance" data-text-en="Compliance">Compliance</a>
                </nav>
                <div class="governance-hub__hero-actions" aria-label="Governance Hub Panels">
                    <button type="button" class="governance-hub__button" data-governance-drawer-toggle aria-controls="governance-header-drawer" aria-expanded="false">
                        <i class="fa-solid fa-table-columns" aria-hidden="true"></i>
                        <span data-text-de="Speichern / Filter / Hilfe" data-text-en="Save / Filter / Help">Save / Filter / Help</span>
                    </button>
                    <button type="button" class="governance-hub__button" data-governance-hub-filter-clear data-governance-header-filter-reset hidden>
                        <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                        <span data-text-de="Filter zurücksetzen" data-text-en="Reset filter">Reset filter</span>
                    </button>
                    <a class="governance-hub__button governance-hub__button--primary" href="{{ locale_route('governance.sessions.demo-report') }}">
                        <i class="fa-solid fa-eye" aria-hidden="true"></i>
                        <span data-text-de="Workspace Report" data-text-en="Workspace report">Workspace report</span>
                    </a>
                    <a class="governance-hub__button" href="{{ locale_route('governance.radar') }}">
                        <i class="fa-solid fa-rss" aria-hidden="true"></i>
                        <span data-text-de="Radar" data-text-en="Radar">Radar</span>
                    </a>
                    <button
                        type="button"
                        class="governance-hub__sidebar-toggle tools-btn tools-btn--ghost"
                        data-shell-sidebar-button
                        aria-pressed="false"
                        data-i18n-aria="settings.hideNavigation"
                        title="Hide navigation"
                    >
                        <i class="fa-solid fa-arrows-left-right-to-line" aria-hidden="true"></i>
                        <span class="sr-only" data-shell-sidebar-label data-i18n="settings.hideNavigation">Hide navigation</span>
                    </button>
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

        <aside class="governance-hub__phone-hint" data-phone-only role="note">
            <i class="fa-solid fa-tablet-screen-button" aria-hidden="true"></i>
            <div class="governance-hub__phone-hint-body">
                <strong data-i18n="governance.phoneHintTitle">Advisor &amp; Tools ab Tablet</strong>
                <p data-i18n="governance.phoneHintLead">
                    Am Phone sind Guides und Workshop verfügbar. Advisor und Tools brauchen mehr Platz — bitte auf ein Tablet oder den Desktop wechseln.
                </p>
            </div>
        </aside>

        <x-shared.ui.tabs
            variant="folder"
            aria-label="Governance Bereiche"
            class="governance-hub__tabs"
            data-governance-tabs
            data-governance-initial-tab="{{ $initialTab ?? 'advisor' }}"
            data-governance-initial-fragment="{{ $initialFragment ?? '' }}"
        >
            <button type="button" class="governance-hub__tab phone-hide-advisor" id="governance-tab-button-advisor" data-phone-hide-advisor data-governance-tab-toggle="advisor" role="tab" aria-controls="governance-tab-advisor" aria-selected="false" tabindex="-1">
                <i class="fa-solid fa-compass" aria-hidden="true"></i>
                <span data-text-de="Advisor" data-text-en="Advisor">Advisor</span>
            </button>
            <button type="button" class="governance-hub__tab" id="governance-tab-button-guides" data-governance-tab-toggle="guides" role="tab" aria-controls="governance-tab-guides" aria-selected="false" tabindex="-1">
                <i class="fa-solid fa-route" aria-hidden="true"></i>
                <span data-text-de="Guides" data-text-en="Guides">Guides</span>
                <span class="governance-hub__tab-filter" data-governance-hub-filter-badge hidden aria-hidden="true" title="Clear filter">
                    <i class="fa-solid fa-filter" aria-hidden="true"></i>
                    <b data-governance-hub-filter-count></b>
                </span>
            </button>
            <button type="button" class="governance-hub__tab" id="governance-tab-button-canvas" data-governance-tab-toggle="canvas" role="tab" aria-controls="governance-tab-canvas" aria-selected="false" tabindex="-1">
                <i class="fa-solid fa-clipboard-list" aria-hidden="true"></i>
                <span data-text-de="Workshop" data-text-en="Workshop">Workshop</span>
                <span class="governance-hub__tab-filter" data-governance-hub-filter-badge hidden aria-hidden="true" title="Clear filter">
                    <i class="fa-solid fa-filter" aria-hidden="true"></i>
                    <b data-governance-hub-filter-count></b>
                </span>
            </button>
            <button type="button" class="governance-hub__tab phone-hide-tools" id="governance-tab-button-tools" data-phone-hide-tools data-governance-tab-toggle="tools" role="tab" aria-controls="governance-tab-tools" aria-selected="false" tabindex="-1">
                <i class="fa-solid fa-screwdriver-wrench" aria-hidden="true"></i>
                <span data-text-de="Tools" data-text-en="Tools">Tools</span>
                <span class="governance-hub__tab-filter" data-governance-hub-filter-badge hidden aria-hidden="true" title="Clear filter">
                    <i class="fa-solid fa-filter" aria-hidden="true"></i>
                    <b data-governance-hub-filter-count></b>
                </span>
            </button>
        </x-shared.ui.tabs>

        @include('governance::partials.stack-builder-modal')

        @include('governance::partials.advisor-panel')

        <section class="governance-hub__section" id="governance-tab-guides" aria-labelledby="governance-tab-button-guides" data-governance-tab-panel="guides" role="tabpanel" hidden>
            @include('governance::partials.panel-guides')
        </section>

        <section class="governance-hub__section" id="governance-tab-canvas" aria-labelledby="governance-tab-button-canvas" data-governance-tab-panel="canvas" role="tabpanel" hidden>
            @include('governance::partials.panel-canvas')
        </section>

        <section class="governance-hub__section phone-hide-tools" id="governance-tab-tools" aria-labelledby="governance-tab-button-tools" data-phone-hide-tools data-governance-tab-panel="tools" role="tabpanel" hidden>
            @include('governance::partials.panel-tools')
        </section>

    </div>
@endsection
