@extends('layouts.tools', ['viteEntries' => ['resources/js/governance/hub-advisor.js', 'resources/js/governance/discovery-canvas.js']])

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
            {!! json_encode(['links' => $advisorLinks], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>

        <header class="governance-hub__hero">
        <section class="governance-hub__top-controls" id="governance-header-drawer" aria-label="Governance Hub Hinweise und Session" data-governance-top-controls hidden>
            <nav class="governance-hub__panel-tabs" aria-label="Header Bereich" role="tablist">
                <button type="button" class="governance-hub__panel-tab governance-hub__panel-tab--active" id="governance-panel-tab-save" data-governance-panel-toggle="governance-save-panel" role="tab" aria-controls="governance-save-panel" aria-selected="true">
                    <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                    <span data-text-de="Speichern" data-text-en="Save">Save</span>
                </button>
                <button type="button" class="governance-hub__panel-tab" id="governance-panel-tab-help" data-governance-panel-toggle="governance-help-panel" role="tab" aria-controls="governance-help-panel" aria-selected="false" tabindex="-1">
                    <i class="fa-solid fa-circle-question" aria-hidden="true"></i>
                    <span data-text-de="Hilfe · FAQ" data-text-en="Help · FAQ">Help · FAQ</span>
                </button>
                <button type="button" class="governance-hub__panel-tab" id="governance-panel-tab-tool-info" data-governance-panel-toggle="governance-tool-info-panel" role="tab" aria-controls="governance-tool-info-panel" aria-selected="false" tabindex="-1">
                    <i class="fa-solid fa-diagram-project" aria-hidden="true"></i>
                    <span data-text-de="Tool-Info" data-text-en="Tool info">Tool info</span>
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
                                <span data-text-de="Sessions verwalten" data-text-en="Manage sessions">Manage sessions</span>
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

            <article class="governance-advisor__helpbox" id="governance-tool-info-panel" aria-labelledby="governance-panel-tab-tool-info" data-governance-panel role="tabpanel" hidden>
                <div class="governance-advisor__helpbox-head">
                    <span class="governance-advisor__helpbox-icon">
                        <i class="fa-solid fa-diagram-project" aria-hidden="true"></i>
                    </span>
                    <span>
                        <span class="governance-hub__eyebrow" data-text-de="Zusammenspiel der Tools" data-text-en="How the tools connect">How the tools connect</span>
                        <strong data-text-de="Jedes Tool kann allein genutzt werden, liefert aber einen Baustein für denselben Governance-Report." data-text-en="Each tool works standalone, but contributes a block to the same governance report.">Each tool works standalone, but contributes a block to the same governance report.</strong>
                    </span>
                </div>
                <div class="governance-advisor__helpbox-content">
                    <p
                        data-text-de="Die Empfehlungen rechts sind nicht zufällig: Sie zeigen die nächste sinnvolle Arbeitsfläche für deine Auswahl. KPI Intake erzeugt KPI-Karten, Source Scope erzeugt Ladeumfang und PII-Fragen, Mart Design macht daraus Tabellenentscheidungen, Data Quality ergänzt Regeln und Gates, Decision Brief fasst alles für Freigabe oder Change Request zusammen."
                        data-text-en="The recommendations on the right are not random: they show the next useful workspace for your selection. KPI Intake creates KPI cards, Source Scope creates load scope and PII questions, Mart Design turns this into table decisions, Data Quality adds rules and gates, and Decision Brief summarizes everything for approval or change request."
                    >The recommendations on the right are not random: they show the next useful workspace for your selection. KPI Intake creates KPI cards, Source Scope creates load scope and PII questions, Mart Design turns this into table decisions, Data Quality adds rules and gates, and Decision Brief summarizes everything for approval or change request.</p>
                    <ol>
                        <li data-text-de="Standalone: Du kannst ein Tool öffnen, Eingaben erfassen, den Report-Baustein ansehen und kopieren oder als Demo speichern." data-text-en="Standalone: open a tool, capture inputs, review the report block, then copy it or save a demo.">Standalone: open a tool, capture inputs, review the report block, then copy it or save a demo.</li>
                        <li data-text-de="Aus einem Plan: Der Tool-Report kann in die passende Aufgabe zurückgeschrieben werden, ohne dass die ganze Session ersetzt wird." data-text-en="From a plan: the tool report can be written back into the matching task without replacing the whole session.">From a plan: the tool report can be written back into the matching task without replacing the whole session.</li>
                        <li data-text-de="Mit Login: Die Governance Session bleibt dauerhaft verfügbar und kann später als Report, Workflow-Grundlage oder Change-Request-Ausgangspunkt weitergeführt werden." data-text-en="With sign-in: the governance session stays permanently available and can later continue as a report, workflow basis, or change request starting point.">With sign-in: the governance session stays permanently available and can later continue as a report, workflow basis, or change request starting point.</li>
                    </ol>
                </div>
            </article>
            </div>
        </section>

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
                    data-text-de="Dieser Hub verbindet Playbooks, Tools, Vendor Resources, Supplier Library und Compliance zu einem geführten Startpunkt: erst die richtige Frage, dann der passende Weg."
                    data-text-en="This hub connects playbooks, tools, vendor resources, supplier library, and compliance into one guided starting point: first the right question, then the right path."
                >This hub connects playbooks, tools, vendor resources, supplier library, and compliance into one guided starting point: first the right question, then the right path.</p>
                <div class="governance-hub__hero-actions" aria-label="Governance Hub Panels">
                    <button type="button" class="governance-hub__button" data-governance-drawer-toggle aria-controls="governance-header-drawer" aria-expanded="false">
                        <i class="fa-solid fa-sliders" aria-hidden="true"></i>
                        <span data-text-de="Speichern & Hilfe" data-text-en="Save & help">Save & help</span>
                    </button>
                    <a class="governance-hub__button governance-hub__button--primary" href="{{ locale_route('governance.sessions.demo-report') }}">
                        <i class="fa-solid fa-eye" aria-hidden="true"></i>
                        <span data-text-de="Workspace Report" data-text-en="Workspace report">Workspace report</span>
                    </a>
                    <a class="governance-hub__button" href="{{ locale_route('governance.radar') }}">
                        <i class="fa-solid fa-rss" aria-hidden="true"></i>
                        <span data-text-de="Radar" data-text-en="Radar">Radar</span>
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

        <nav class="governance-hub__tabs" aria-label="Governance Bereiche" data-governance-tabs role="tablist" data-governance-initial-tab="{{ $initialTab ?? 'advisor' }}" data-governance-initial-fragment="{{ $initialFragment ?? '' }}">
            <button type="button" class="governance-hub__tab" id="governance-tab-button-advisor" data-governance-tab-toggle="advisor" role="tab" aria-controls="governance-tab-advisor" aria-selected="false" tabindex="-1">
                <i class="fa-solid fa-compass" aria-hidden="true"></i>
                <span data-text-de="Advisor" data-text-en="Advisor">Advisor</span>
            </button>
            <button type="button" class="governance-hub__tab" id="governance-tab-button-guides" data-governance-tab-toggle="guides" role="tab" aria-controls="governance-tab-guides" aria-selected="false" tabindex="-1">
                <i class="fa-solid fa-route" aria-hidden="true"></i>
                <span data-text-de="Guides" data-text-en="Guides">Guides</span>
            </button>
            <button type="button" class="governance-hub__tab" id="governance-tab-button-canvas" data-governance-tab-toggle="canvas" role="tab" aria-controls="governance-tab-canvas" aria-selected="false" tabindex="-1">
                <i class="fa-solid fa-clipboard-list" aria-hidden="true"></i>
                <span data-text-de="Workshop" data-text-en="Workshop">Workshop</span>
            </button>
            <button type="button" class="governance-hub__tab phone-hide-tools" id="governance-tab-button-tools" data-phone-hide-tools data-governance-tab-toggle="tools" role="tab" aria-controls="governance-tab-tools" aria-selected="false" tabindex="-1">
                <i class="fa-solid fa-screwdriver-wrench" aria-hidden="true"></i>
                <span data-text-de="Tools" data-text-en="Tools">Tools</span>
            </button>
        </nav>

        @include('governance.partials.advisor-panel')

        <section class="governance-hub__section" id="governance-tab-guides" aria-labelledby="governance-tab-button-guides" data-governance-tab-panel="guides" role="tabpanel" hidden>
            @include('governance.partials.panel-guides')
        </section>

        <section class="governance-hub__section" id="governance-tab-canvas" aria-labelledby="governance-tab-button-canvas" data-governance-tab-panel="canvas" role="tabpanel" hidden>
            @include('governance.partials.panel-canvas')
        </section>

        <section class="governance-hub__section phone-hide-tools" id="governance-tab-tools" aria-labelledby="governance-tab-button-tools" data-phone-hide-tools data-governance-tab-panel="tools" role="tabpanel" hidden>
            @include('governance.partials.panel-tools')
        </section>

    </div>
@endsection
