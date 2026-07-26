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
                'dbt-dq-macro-generator' => locale_route('tools.dbt-dq-macro-generator'),
                'dbt-dq-rules-generator' => locale_route('tools.dbt-dq-rules-generator'),
                'dbt-dq-history-generator' => locale_route('tools.dbt-dq-history-generator'),
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
            'session' => [
                'accountsEnabled' => ! empty($accountsEnabled),
                'loggedIn' => ! empty($accountUser),
                'apiUrl' => ! empty($accountUser) ? url('/api/governance/sessions') : null,
                'sessionsUrl' => ! empty($accountUser) ? locale_route('governance.sessions.index') : null,
                'loginUrl' => ! empty($accountsEnabled) && empty($accountUser) ? locale_route('accounts.login') : null,
            ],
        ];
    @endphp

    <div class="tools-content governance-hub" data-governance-advisor>
        <script type="application/json" data-governance-advisor-config>
            {!! json_encode(['links' => $advisorLinks], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>

        <header class="governance-hub__hero">
        <section class="governance-hub__top-controls" id="governance-header-drawer" aria-label="Governance Hub Hinweise und Session" data-governance-top-controls hidden>
            <nav class="governance-hub__panel-tabs" aria-label="Header Bereich" role="tablist">
                <button type="button" class="governance-hub__panel-tab governance-hub__panel-tab--active" id="governance-panel-tab-help" data-governance-panel-toggle="governance-help-panel" role="tab" aria-controls="governance-help-panel" aria-selected="true">
                    <i class="fa-solid fa-circle-question" aria-hidden="true"></i>
                    <span data-text-de="Hilfe" data-text-en="Help">Help</span>
                </button>
                <button type="button" class="governance-hub__panel-tab" id="governance-panel-tab-save" data-governance-panel-toggle="governance-save-panel" role="tab" aria-controls="governance-save-panel" aria-selected="false" tabindex="-1">
                    <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                    <span data-text-de="Speichern" data-text-en="Save">Save</span>
                </button>
                <button type="button" class="governance-hub__panel-tab" id="governance-panel-tab-tools" data-governance-panel-toggle="governance-tools-panel" role="tab" aria-controls="governance-tools-panel" aria-selected="false" tabindex="-1">
                    <i class="fa-solid fa-diagram-project" aria-hidden="true"></i>
                    <span data-text-de="Tools" data-text-en="Tools">Tools</span>
                </button>
            </nav>

            <div class="governance-hub__panel-stage">
            <article class="governance-advisor__helpbox" id="governance-help-panel" aria-labelledby="governance-panel-tab-help" data-governance-panel role="tabpanel">
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
                        <li data-text-de="Wähle zuerst die Ausgangslage: neu aufbauen, vorhandene Umgebung ergänzen oder Hilfe in einer bestehenden Landschaft finden." data-text-en="First choose the starting point: build new, extend an existing environment, or find help in an existing landscape.">First choose the starting point: build new, extend an existing environment, or find help in an existing landscape.</li>
                        <li data-text-de="Lege fest, was entschieden werden soll. Bei Datenqualität kannst du zusätzlich Ziel, Schicht und Fehlerklasse eingrenzen." data-text-en="Define what needs a decision. For data quality, you can also narrow down goal, layer, and issue class.">Define what needs a decision. For data quality, you can also narrow down goal, layer, and issue class.</li>
                        <li data-text-de="Nutze die Empfehlungen rechts als Arbeitsreihenfolge: erst die passenden Tools, dann Supplier/Resources, dann Nachweise und Stories." data-text-en="Use the recommendations on the right as a working order: matching tools first, then suppliers/resources, then evidence and stories.">Use the recommendations on the right as a working order: matching tools first, then suppliers/resources, then evidence and stories.</li>
                        <li data-text-de="Speichere die Session, wenn daraus ein Report, eine Plan-Aufgabe, ein Workflow-Schritt oder später ein Change Request entstehen soll." data-text-en="Save the session when it should become a report, plan task, workflow step, or later change request.">Save the session when it should become a report, plan task, workflow step, or later change request.</li>
                    </ol>
                </div>
            </article>

            <article class="governance-advisor__helpbox" id="governance-tools-panel" aria-labelledby="governance-panel-tab-tools" data-governance-panel role="tabpanel" hidden>
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

            <section class="governance-advisor__save-disclosure governance-advisor__save-disclosure--hub" id="governance-save-panel" aria-labelledby="governance-panel-tab-save" data-governance-save-panel data-governance-panel role="tabpanel" hidden>
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
                        <span data-text-de="Hilfe, Speichern, Tools" data-text-en="Help, save, tools">Help, save, tools</span>
                    </button>
                    <a class="governance-hub__button governance-hub__button--primary" href="{{ locale_route('governance.sessions.demo-report') }}">
                        <i class="fa-solid fa-eye" aria-hidden="true"></i>
                        <span data-text-de="Workspace Report" data-text-en="Workspace report">Workspace report</span>
                    </a>
                    <a class="governance-hub__button" href="{{ locale_route('governance.radar') }}">
                        <i class="fa-solid fa-rss" aria-hidden="true"></i>
                        <span data-text-de="Governance Radar" data-text-en="Governance Radar">Governance Radar</span>
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

        <nav class="governance-hub__tabs" aria-label="Governance Hub Bereiche" data-governance-tabs role="tablist">
            <button type="button" class="governance-hub__tab governance-hub__tab--active" id="governance-tab-button-advisor" data-governance-tab-toggle="advisor" role="tab" aria-controls="governance-tab-advisor" aria-selected="true">
                <i class="fa-solid fa-compass" aria-hidden="true"></i>
                <span data-text-de="Advisor" data-text-en="Advisor">Advisor</span>
            </button>
            <button type="button" class="governance-hub__tab" id="governance-tab-button-workflows" data-governance-tab-toggle="workflows" role="tab" aria-controls="governance-tab-workflows" aria-selected="false" tabindex="-1">
                <i class="fa-solid fa-route" aria-hidden="true"></i>
                <span data-text-de="Workflows" data-text-en="Workflows">Workflows</span>
            </button>
            <button type="button" class="governance-hub__tab" id="governance-tab-button-decisions" data-governance-tab-toggle="decisions" role="tab" aria-controls="governance-tab-decisions" aria-selected="false" tabindex="-1">
                <i class="fa-solid fa-scale-balanced" aria-hidden="true"></i>
                <span data-text-de="Entscheidungen" data-text-en="Decisions">Decisions</span>
            </button>
            <button type="button" class="governance-hub__tab" id="governance-tab-button-tools" data-governance-tab-toggle="tools" role="tab" aria-controls="governance-tab-tools" aria-selected="false" tabindex="-1">
                <i class="fa-solid fa-screwdriver-wrench" aria-hidden="true"></i>
                <span data-text-de="Tools" data-text-en="Tools">Tools</span>
            </button>
        </nav>

        <section class="governance-hub__section governance-advisor" id="governance-tab-advisor" aria-labelledby="governance-tab-button-advisor" data-governance-tab-panel="advisor" role="tabpanel">

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
                    data-text-de="Gedacht für drei echte Startpunkte: neu bauen, bestehende Umgebung ergänzen oder Orientierung in einer vorhandenen Landschaft finden."
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
                                    <small data-text-de="Stack, Quellen, erste KPIs und Governance Gates klären." data-text-en="Clarify stack, sources, first KPIs, and governance gates.">Clarify stack, sources, first KPIs, and governance gates.</small>
                                </span>
                            </label>
                            <label class="governance-advisor__option">
                                <input type="radio" name="scenario" value="extend">
                                <span>
                                    <strong data-text-de="Ich ergänze Bestehendes" data-text-en="I extend existing">I extend existing</strong>
                                    <small data-text-de="Fit, Impact, neue Quelle und Abhängigkeiten prüfen." data-text-en="Check fit, impact, new source, and dependencies.">Check fit, impact, new source, and dependencies.</small>
                                </span>
                            </label>
                            <label class="governance-advisor__option">
                                <input type="radio" name="scenario" value="help">
                                <span>
                                    <strong data-text-de="Alles ist da, ich brauche Hilfe" data-text-en="Everything exists, I need help">Everything exists, I need help</strong>
                                    <small data-text-de="Stories, Ressourcen, Zertifikate und nächste Schritte finden." data-text-en="Find stories, resources, certifications, and next steps.">Find stories, resources, certifications, and next steps.</small>
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
                                ['value' => 'dq', 'icon' => 'fa-circle-check', 'de' => 'Datenqualität', 'en' => 'Data quality'],
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

                    <div class="governance-advisor__followup" data-governance-followup>
                        <i class="fa-solid fa-route" aria-hidden="true"></i>
                        <div>
                            <span data-governance-followup-label data-text-de="Nächste Frage" data-text-en="Next question">Next question</span>
                            <strong data-governance-followup-copy>Which stack and source context defines the decision?</strong>
                        </div>
                    </div>

                    <div class="governance-advisor__select-grid">
                        <label>
                            <span
                                data-governance-domain-label
                                data-text-de="Quelltyp"
                                data-text-en="Source type"
                            >Source type</span>
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
                            <span
                                data-governance-platform-label
                                data-text-de="Ziel-Stack"
                                data-text-en="Target stack"
                            >Target stack</span>
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

                    <div class="governance-advisor__dq" data-governance-dq-panel>
                        <div class="governance-advisor__dq-heading">
                            <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                            <span data-text-de="DQ-Entscheidung vertiefen" data-text-en="Refine DQ decision">Refine DQ decision</span>
                        </div>
                        <p
                            class="governance-advisor__dq-copy"
                            data-text-de="Data Quality ist hier ein Governance-Pfad: erst Problem und Schicht eingrenzen, dann passende Regeln, Monitoring und Gates ableiten."
                            data-text-en="Data quality is a governance path here: narrow down problem and layer first, then derive rules, monitoring, and gates."
                        >Data quality is a governance path here: narrow down problem and layer first, then derive rules, monitoring, and gates.</p>
                        <div class="governance-advisor__select-grid">
                            <label>
                                <span data-text-de="DQ Ziel" data-text-en="DQ goal">DQ goal</span>
                                <select name="dqMode">
                                    <option value="health_check" data-text-de="Health Check" data-text-en="Health check">Health check</option>
                                    <option value="known_issue" data-text-de="Bekanntes Problem" data-text-en="Known issue">Known issue</option>
                                    <option value="new_source_gate" data-text-de="Neue Quelle absichern" data-text-en="Gate a new source">Gate a new source</option>
                                    <option value="report_stabilization" data-text-de="Report stabilisieren" data-text-en="Stabilize a report">Stabilize a report</option>
                                    <option value="mart_quality_gate" data-text-de="Mart Quality Gate" data-text-en="Mart quality gate">Mart quality gate</option>
                                </select>
                            </label>
                            <label>
                                <span data-text-de="DQ Schicht" data-text-en="DQ layer">DQ layer</span>
                                <select name="dqLayer">
                                    <option value="source" data-text-de="Source/API" data-text-en="Source/API">Source/API</option>
                                    <option value="raw" data-text-de="Raw/Ingestion" data-text-en="Raw/ingestion">Raw/ingestion</option>
                                    <option value="transform" data-text-de="Transformation" data-text-en="Transformation">Transformation</option>
                                    <option value="mart" data-text-de="Mart" data-text-en="Mart">Mart</option>
                                    <option value="semantic" data-text-de="Semantic Layer" data-text-en="Semantic layer">Semantic layer</option>
                                    <option value="bi" data-text-de="BI Report" data-text-en="BI report">BI report</option>
                                    <option value="master_data" data-text-de="Stammdaten" data-text-en="Master data">Master data</option>
                                </select>
                            </label>
                        </div>
                        <fieldset class="governance-advisor__fieldset">
                            <legend data-text-de="Fehlerklasse" data-text-en="Issue class">Issue class</legend>
                            <div class="governance-advisor__options">
                                @foreach ([
                                    ['value' => 'completeness', 'de' => 'Vollständigkeit', 'en' => 'Completeness'],
                                    ['value' => 'duplicates', 'de' => 'Duplikate', 'en' => 'Duplicates'],
                                    ['value' => 'freshness', 'de' => 'Aktualität', 'en' => 'Freshness'],
                                    ['value' => 'value_range', 'de' => 'Wertebereich', 'en' => 'Value range'],
                                    ['value' => 'referential_integrity', 'de' => 'Referenzen', 'en' => 'References'],
                                    ['value' => 'business_rule', 'de' => 'Business-Regel', 'en' => 'Business rule'],
                                    ['value' => 'pii_access', 'de' => 'PII/Access', 'en' => 'PII/access'],
                                    ['value' => 'unknown', 'de' => 'Unbekannt', 'en' => 'Unknown'],
                                ] as $issue)
                                    <label class="governance-advisor__pill governance-advisor__pill--compact">
                                        <input type="checkbox" name="dqIssues[]" value="{{ $issue['value'] }}" @checked($issue['value'] === 'completeness')>
                                        <span>
                                            <strong data-text-de="{{ $issue['de'] }}" data-text-en="{{ $issue['en'] }}">{{ $issue['en'] }}</strong>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>
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

        <section class="governance-hub__section" id="governance-tab-workflows" aria-labelledby="governance-tab-button-workflows" data-governance-tab-panel="workflows" role="tabpanel" hidden>
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
                    data-text-de="Die Karten sind keine neuen Silos. Sie führen in vorhandene Hubs und Tools und setzen die neue Beratungslogik darüber."
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

        <section class="governance-hub__section governance-hub__workflow" aria-labelledby="governance-tab-button-workflows" data-governance-tab-panel="workflows" role="tabpanel" hidden>
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

        <section class="governance-hub__section governance-hub__decision-section" id="governance-tab-decisions" aria-labelledby="governance-tab-button-decisions" data-governance-tab-panel="decisions" role="tabpanel" hidden>
            <div class="governance-hub__decision-main">
                <h2
                    id="governance-stacks-title"
                    class="tools-section__title"
                    data-text-de="Entscheidungshilfen: welche Frage klärst du wo?"
                    data-text-en="Decision aids: which question do you answer where?"
                >Decision aids: which question do you answer where?</h2>
                <p
                    class="tools-section__lead"
                    data-hub-lead
                    data-text-de="Jede Karte sagt dir, bei welcher Entscheidung sie hilft, welches vorhandene Hub oder Tool du öffnest und welches Artefakt danach entstehen soll."
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
                            'helps' => ['de' => 'Klärt Geschäftsfrage, Formel, Grain, Dimensionen, Owner, Akzeptanzbeispiel und BI-Verwendung.', 'en' => 'Clarifies business question, formula, grain, dimensions, owner, acceptance example, and BI usage.'],
                            'outcome' => ['de' => 'KPI Card plus erste Fact-/Dimension-Kandidaten.', 'en' => 'KPI card plus first fact/dimension candidates.'],
                            'links' => [
                                ['href' => locale_route('tools.kpi-requirements-intake'), 'label' => ['de' => 'KPI Intake', 'en' => 'KPI intake']],
                                ['href' => locale_route('tools.report-inventory'), 'label' => ['de' => 'Report Inventory', 'en' => 'Report inventory']],
                            ],
                        ],
                        [
                            'icon' => 'fa-shield-halved',
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
        </section>

        <section class="governance-hub__section" id="governance-tab-tools" aria-labelledby="governance-tab-button-tools" data-governance-tab-panel="tools" role="tabpanel" hidden>
            <div class="governance-hub__section-heading">
                <h2
                    id="governance-tools-title"
                    class="tools-section__title"
                    data-text-de="Werkbank für die nächste Aktion"
                    data-text-en="Workbench for the next action"
                >Workbench for the next action</h2>
                <p
                    class="tools-section__lead"
                    data-hub-lead
                    data-text-de="Diese bestehenden Tools sind die ersten Bausteine für den neuen Governance Discovery Canvas."
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
