@extends('layouts.tools')

@php
    $viteEntries = ['resources/js/tools/governance-advisory/index.js'];
    $titleEn = $tool['title']['en'] ?? $toolId;
    $titleDe = $tool['title']['de'] ?? $titleEn;
    $leadEn = $tool['lead']['en'] ?? '';
    $leadDe = $tool['lead']['de'] ?? $leadEn;
    $questionEn = $tool['question']['en'] ?? '';
    $questionDe = $tool['question']['de'] ?? $questionEn;
    $inputs = $tool['inputs'] ?? [];
    $outputs = $tool['outputs'] ?? [];
    $toolJson = [
        'id' => $toolId,
        'title' => $titleEn,
        'titleDe' => $titleDe,
        'outputs' => $outputs,
        'fieldLabels' => $fieldLabels ?? [],
        'template' => $tool['template'] ?? [],
        'templateGuides' => $templateGuides ?? [],
        'reportSummary' => $reportSummary ?? '',
        'demoPrefill' => $demoPrefill ?? null,
    ];
@endphp

@section('title', $titleEn . ' - ' . config('app.name'))
@section('meta_description', $leadEn)

@push('head')
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="{{ $titleEn }} - {{ config('app.name') }}">
    <meta property="og:description" content="{{ $leadEn }}">
    <script type="application/ld+json">
        {!! json_encode([
            chr(64).'context' => 'https://schema.org',
            '@type' => 'WebApplication',
            'name' => $titleEn,
            'description' => $leadEn,
            'url' => url()->current(),
            'applicationCategory' => 'BusinessApplication',
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
    <div
        class="tools-content governance-advisory-tool"
        data-governance-tool-root
        data-tool-config='@json($toolJson)'
    >
        <header class="governance-advisory-tool__header">
            <div class="governance-advisory-tool__header-main">
                <p class="governance-hub__eyebrow" data-text-de="Governance Tool" data-text-en="Governance tool">Governance tool</p>
                <h1 class="tools-page-title" data-text-de="{{ $titleDe }}" data-text-en="{{ $titleEn }}">{{ $titleEn }}</h1>
                <p class="tools-page-lead" data-hub-lead data-text-de="{{ $leadDe }}" data-text-en="{{ $leadEn }}">{{ $leadEn }}</p>
                <div class="governance-advisory-tool__header-actions">
                    <button type="button" class="governance-hub__button governance-advisory-tool__header-button governance-advisory-tool__header-button--neutral" data-governance-tool-drawer-toggle aria-controls="{{ $toolId }}-header-drawer" aria-expanded="false">
                        <i class="fa-solid fa-sliders" aria-hidden="true"></i>
                        <span data-text-de="Optionen" data-text-en="Options">Options</span>
                    </button>
                    @if ($toolId === 'kpi-requirements-intake')
                        <a class="governance-hub__button governance-advisory-tool__header-button governance-advisory-tool__header-button--neutral" href="#{{ $toolId }}-workbench">
                            <i class="fa-solid fa-eye" aria-hidden="true"></i>
                            <span data-text-de="Report ansehen" data-text-en="View report">View report</span>
                        </a>
                        <button type="button" class="governance-hub__button governance-advisory-tool__header-button governance-advisory-tool__header-button--primary" data-kpi-intake-save data-kpi-save-placement="header">
                            <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                            <span data-text-de="Intake speichern" data-text-en="Save intake">Save intake</span>
                        </button>
                    @else
                        <a class="governance-hub__button governance-advisory-tool__header-button governance-advisory-tool__header-button--neutral" href="#{{ $toolId }}-workbench">
                            <i class="fa-solid fa-eye" aria-hidden="true"></i>
                            <span data-text-de="Report ansehen" data-text-en="View report">View report</span>
                        </a>
                        <button type="button" class="governance-hub__button governance-advisory-tool__header-button governance-advisory-tool__header-button--primary" data-governance-record-save data-governance-record-save-placement="header">
                            <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                            <span data-text-de="Speichern" data-text-en="Save">Save</span>
                        </button>
                    @endif
                </div>
            </div>

            <div class="governance-advisory-tool__question">
                @if (! empty($tool['icon']))
                    <i class="fa-solid {{ $tool['icon'] }}" aria-hidden="true"></i>
                @endif
                <span data-text-de="Entscheidungsfrage" data-text-en="Decision question">Decision question</span>
                <strong data-text-de="{{ $questionDe }}" data-text-en="{{ $questionEn }}">{{ $questionEn }}</strong>
            </div>

            <section class="governance-advisory-tool__drawer" id="{{ $toolId }}-header-drawer" aria-label="Tool Hilfe, Überblick und Struktur" data-governance-tool-header-drawer hidden>
                <nav class="governance-hub__panel-tabs governance-advisory-tool__drawer-tabs" aria-label="Tool Header Bereich" role="tablist">
                    <button type="button" class="governance-hub__panel-tab governance-hub__panel-tab--active" id="{{ $toolId }}-drawer-tab-help" data-governance-tool-panel-toggle="{{ $toolId }}-drawer-help" role="tab" aria-controls="{{ $toolId }}-drawer-help" aria-selected="true">
                        <i class="fa-solid fa-circle-question" aria-hidden="true"></i>
                        <span data-text-de="Hilfe" data-text-en="Help">Help</span>
                    </button>
                    <button type="button" class="governance-hub__panel-tab" id="{{ $toolId }}-drawer-tab-overview" data-governance-tool-panel-toggle="{{ $toolId }}-drawer-overview" role="tab" aria-controls="{{ $toolId }}-drawer-overview" aria-selected="false" tabindex="-1">
                        <i class="fa-solid fa-table-columns" aria-hidden="true"></i>
                        <span data-text-de="Überblick" data-text-en="Overview">Overview</span>
                    </button>
                    <button type="button" class="governance-hub__panel-tab" id="{{ $toolId }}-drawer-tab-structure" data-governance-tool-panel-toggle="{{ $toolId }}-drawer-structure" role="tab" aria-controls="{{ $toolId }}-drawer-structure" aria-selected="false" tabindex="-1">
                        <i class="fa-solid fa-list-check" aria-hidden="true"></i>
                        <span data-text-de="Struktur" data-text-en="Structure">Structure</span>
                    </button>
                    <button type="button" class="governance-hub__panel-tab" id="{{ $toolId }}-drawer-tab-workspace" data-governance-tool-panel-toggle="{{ $toolId }}-drawer-workspace" role="tab" aria-controls="{{ $toolId }}-drawer-workspace" aria-selected="false" tabindex="-1">
                        <i class="fa-solid fa-folder-tree" aria-hidden="true"></i>
                        @if ($toolId === 'kpi-requirements-intake')
                            <span data-text-de="KPI Workspace" data-text-en="KPI workspace">KPI workspace</span>
                        @else
                            <span data-text-de="Workspace" data-text-en="Workspace">Workspace</span>
                        @endif
                    </button>
                </nav>

                <div class="governance-advisory-tool__drawer-stage">
                    <article class="governance-advisor__helpbox governance-advisory-tool__drawer-panel" id="{{ $toolId }}-drawer-help" aria-labelledby="{{ $toolId }}-drawer-tab-help" data-governance-tool-panel role="tabpanel">
                        <div class="governance-advisor__helpbox-head">
                            <span class="governance-advisor__helpbox-icon">
                                <i class="fa-solid fa-circle-question" aria-hidden="true"></i>
                            </span>
                            <span>
                                <span class="governance-hub__eyebrow" data-text-de="Wie du dieses Tool nutzt" data-text-en="How to use this tool">How to use this tool</span>
                                <strong data-text-de="Beratung, Eingaben, Report und Übergabe in einer Arbeitsfolge." data-text-en="Guidance, inputs, report, and handoff in one working flow.">Guidance, inputs, report, and handoff in one working flow.</strong>
                            </span>
                        </div>
                        <div class="governance-advisor__helpbox-content">
                            <p
                                data-text-de="Nutze das Tool wie einen Workshop-Assistenten: erst die Entscheidungsfrage verstehen, dann die wichtigsten Informationen erfassen, danach den Report-Baustein prüfen und erst dann in Plan, Workflow, Wiki oder Ticket übernehmen."
                                data-text-en="Use the tool like a workshop assistant: understand the decision question first, capture the most important information, review the report block, then move it into a plan, workflow, wiki, or ticket."
                            >Use the tool like a workshop assistant: understand the decision question first, capture the most important information, review the report block, then move it into a plan, workflow, wiki, or ticket.</p>
                            <ol>
                                <li data-text-de="Wenn du aus dem Governance Hub kommst, nutze die Empfehlungen dort als Reihenfolge und öffne nur die Tools, die zur aktuellen Entscheidung passen." data-text-en="If you came from the Governance Hub, use its recommendations as the working order and open only tools that match the current decision.">If you came from the Governance Hub, use its recommendations as the working order and open only tools that match the current decision.</li>
                                <li data-text-de="Fülle nicht alles perfekt aus. Wichtig sind konkrete Beispiele, Owner, offene Fragen und die Quelle der Information." data-text-en="Do not try to fill everything perfectly. Concrete examples, owners, open questions, and the source of the information matter most.">Do not try to fill everything perfectly. Concrete examples, owners, open questions, and the source of the information matter most.</li>
                                <li data-text-de="Die Ergebnisansicht ist der prüfbare Report-Baustein. Kopieren, Markdown laden, speichern oder aus dem Plan heraus direkt zurückschreiben." data-text-en="The result view is the reviewable report block. Copy it, download Markdown, save it, or write it back directly when you came from a plan.">The result view is the reviewable report block. Copy it, download Markdown, save it, or write it back directly when you came from a plan.</li>
                            </ol>
                        </div>
                    </article>

                    <article class="governance-advisory-tool__drawer-panel" id="{{ $toolId }}-drawer-overview" aria-labelledby="{{ $toolId }}-drawer-tab-overview" data-governance-tool-panel role="tabpanel" hidden>
                        <div class="governance-advisory-tool__grid governance-advisory-tool__grid--explained">
                            <section aria-labelledby="{{ $toolId }}-helps">
                                <h2 id="{{ $toolId }}-helps" data-text-de="Wobei hilft das Tool?" data-text-en="What does this tool help decide?">What does this tool help decide?</h2>
                                <ul>
                                    @foreach ($tool['helps'] as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </section>

                            <section aria-labelledby="{{ $toolId }}-inputs">
                                <h2 id="{{ $toolId }}-inputs" data-text-de="Warum diese Eingaben?" data-text-en="Why these inputs?">Why these inputs?</h2>
                                <div class="governance-advisory-tool__explain-list">
                                    @foreach ($inputs as $item)
                                        @php($label = $fieldLabels[$item] ?? ['de' => $item, 'en' => $item])
                                        <article>
                                            <strong data-text-de="{{ $label['de'] }}" data-text-en="{{ $label['en'] }}">{{ $label['en'] }}</strong>
                                            <p>{{ $inputExplanations[$item] ?? 'Hilft, Kontext, Owner, Risiko und Entscheidungsreife sauber einzuordnen.' }}</p>
                                        </article>
                                    @endforeach
                                </div>
                            </section>

                            <section aria-labelledby="{{ $toolId }}-outputs">
                                <h2 id="{{ $toolId }}-outputs" data-text-de="Was entsteht daraus?" data-text-en="What comes out?">What comes out?</h2>
                                <div class="governance-advisory-tool__explain-list governance-advisory-tool__explain-list--accent">
                                    @foreach ($outputs as $item)
                                        @php($label = $fieldLabels[$item] ?? ['de' => $item, 'en' => $item])
                                        <article>
                                            <strong data-text-de="{{ $label['de'] }}" data-text-en="{{ $label['en'] }}">{{ $label['en'] }}</strong>
                                            <p>{{ $outputExplanations[$item] ?? 'Wird als Report-Teil, Plan-Notiz oder nächster Workflow-Baustein nutzbar.' }}</p>
                                        </article>
                                    @endforeach
                                </div>
                            </section>
                        </div>
                    </article>

                    <article class="governance-advisory-tool__drawer-panel" id="{{ $toolId }}-drawer-structure" aria-labelledby="{{ $toolId }}-drawer-tab-structure" data-governance-tool-panel role="tabpanel" hidden>
                        <div class="governance-advisory-tool__template-content">
                            <div>
                                <span class="governance-hub__eyebrow" data-text-de="Erfassungsstruktur und Feldhilfe" data-text-en="Capture structure and field help">Capture structure and field help</span>
                                <p data-text-de="Diese Struktur ist die Checkliste für einen sauberen Arbeitsstand: Jedes Feld beantwortet eine Frage, die später für Entscheidung, Umsetzung, Review oder Change Request gebraucht wird." data-text-en="This structure is the checklist for a clean working state: each field answers a question needed later for decision, implementation, review, or change request.">This structure is the checklist for a clean working state: each field answers a question needed later for decision, implementation, review, or change request.</p>
                            </div>
                            <ol>
                                @foreach ($tool['template'] as $index => $item)
                                    @php($guide = $templateGuides[$index] ?? [])
                                    @php($label = $fieldLabels[$item] ?? ['de' => $item, 'en' => $item])
                                    <li>
                                        <strong data-text-de="{{ $label['de'] }}" data-text-en="{{ $label['en'] }}">{{ $label['en'] }}</strong>
                                        <span>{{ $guide['help'] ?? 'Erfasse den konkreten Stand, offene Fragen und die Quelle der Information.' }}</span>
                                    </li>
                                @endforeach
                            </ol>
                            <small data-text-de="Welche Informationen in den Report gehören" data-text-en="Which information belongs in the report">Which information belongs in the report</small>
                        </div>
                    </article>

                    <article class="governance-advisory-tool__drawer-panel" id="{{ $toolId }}-drawer-workspace" aria-labelledby="{{ $toolId }}-drawer-tab-workspace" data-governance-tool-panel role="tabpanel" hidden>
                        @if ($toolId === 'kpi-requirements-intake')
                            <section class="governance-advisory-tool__record-manager" data-kpi-intake-manager aria-label="KPI Intake Workspace">
                                <div class="governance-advisory-tool__kpi-manager-head">
                                    <div>
                                        <p class="governance-hub__eyebrow" data-text-de="KPI Workspace" data-text-en="KPI workspace">KPI workspace</p>
                                        <h3 data-text-de="KPI-Intakes verwalten" data-text-en="Manage KPI intakes">Manage KPI intakes</h3>
                                        <p data-text-de="Speichere mehrere Klärfälle, öffne sie wieder und übernimm fertige KPIs als Zeilen ins KPI-Register." data-text-en="Save several intake cases, reopen them, and accept finished KPIs as rows in the KPI register.">Save several intake cases, reopen them, and accept finished KPIs as rows in the KPI register.</p>
                                    </div>
                                    <div class="governance-advisory-tool__kpi-manager-actions">
                                        <button type="button" class="governance-hub__button" data-kpi-intake-new>
                                            <i class="fa-solid fa-plus" aria-hidden="true"></i>
                                            <span data-text-de="Neue KPI klären" data-text-en="New KPI intake">New KPI intake</span>
                                        </button>
                                        <button type="button" class="governance-hub__button governance-hub__button--primary" data-kpi-intake-save>
                                            <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                                            <span data-text-de="Intake speichern" data-text-en="Save intake">Save intake</span>
                                        </button>
                                        <button type="button" class="governance-hub__button" data-kpi-intake-accept>
                                            <i class="fa-solid fa-table-list" aria-hidden="true"></i>
                                            <span data-text-de="Ins Register übernehmen" data-text-en="Accept to register">Accept to register</span>
                                        </button>
                                        @if (\Illuminate\Support\Facades\Route::has('tools.kpi-definition'))
                                            <a class="governance-hub__button" href="{{ locale_route('tools.kpi-definition') }}">
                                                <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                                                <span data-text-de="KPI-Register öffnen" data-text-en="Open KPI register">Open KPI register</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="governance-advisory-tool__kpi-list" data-kpi-intake-list></div>
                                <p class="governance-advisor__save-status" data-kpi-intake-status>
                                    <span data-text-de="Noch kein Intake gespeichert. Beispielwerte kannst du speichern und danach ins Register übernehmen." data-text-en="No intake saved yet. You can save the example values and then accept them into the register.">No intake saved yet. You can save the example values and then accept them into the register.</span>
                                </p>
                            </section>
                        @else
                            <section class="governance-advisory-tool__record-manager" data-governance-record-manager aria-label="Governance Tool Workspace">
                                <div class="governance-advisory-tool__kpi-manager-head">
                                    <div>
                                        <p class="governance-hub__eyebrow" data-text-de="Workspace" data-text-en="Workspace">Workspace</p>
                                        <h3 data-text-de="Arbeitsstände verwalten" data-text-en="Manage saved items">Manage saved items</h3>
                                        <p data-text-de="Erstelle mehrere Arbeitsstände, öffne sie wieder, ändere sie gezielt und speichere den passenden Report-Baustein für Plan, Workflow oder Change Request." data-text-en="Create several work items, reopen them, edit them deliberately, and keep the matching report block for plan, workflow, or change request.">Create several work items, reopen them, edit them deliberately, and keep the matching report block for plan, workflow, or change request.</p>
                                    </div>
                                    <div class="governance-advisory-tool__kpi-manager-actions governance-advisory-tool__kpi-manager-actions--compact">
                                        <button type="button" class="governance-hub__button" data-governance-record-new>
                                            <i class="fa-solid fa-plus" aria-hidden="true"></i>
                                            <span data-text-de="Neu" data-text-en="New">New</span>
                                        </button>
                                        <button type="button" class="governance-hub__button governance-hub__button--primary" data-governance-record-save>
                                            <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                                            <span data-text-de="Speichern" data-text-en="Save">Save</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="governance-advisory-tool__kpi-list" data-governance-record-list></div>
                                <p class="governance-advisor__save-status" data-governance-record-status>
                                    <span data-text-de="Noch nichts gespeichert. Sobald Eingaben vorhanden sind, kannst du daraus einen wieder editierbaren Arbeitsstand machen." data-text-en="Nothing saved yet. Once inputs exist, you can turn them into a reusable editable work item.">Nothing saved yet. Once inputs exist, you can turn them into a reusable editable work item.</span>
                                </p>
                            </section>
                        @endif
                    </article>
                </div>
            </section>
        </header>

        <section
            class="governance-advisory-tool__panel governance-advisory-tool__workbench"
            aria-labelledby="{{ $toolId }}-workbench"
            data-governance-tool-workbench
        >
            <div class="governance-advisory-tool__panel-head">
                <div>
                    <p class="governance-hub__eyebrow" data-text-de="Arbeitsansicht" data-text-en="Workspace">Workspace</p>
                    <h2 id="{{ $toolId }}-workbench" data-text-de="Eingaben und Report" data-text-en="Inputs and report">Inputs and report</h2>
                </div>
                <div class="governance-advisory-tool__context-actions" data-plan-only hidden>
                    <button type="button" class="governance-hub__button governance-hub__button--primary" data-apply-to-plan>
                        <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                        <span data-text-de="Im Plan speichern" data-text-en="Save to plan">Save to plan</span>
                    </button>
                    <a class="governance-hub__button" href="{{ locale_route('governance.index') }}" data-return-to-plan>
                        <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                        <span data-text-de="Zurück zum Plan" data-text-en="Back to plan">Back to plan</span>
                    </a>
                </div>
            </div>

            <div class="governance-advisory-tool__workspace-grid">
                <form class="governance-advisory-tool__capture" data-governance-tool-form>
                    @if (! empty($reportSummary))
                        <div class="governance-advisory-tool__summary">
                            <span data-text-de="Report-Zusammenfassung" data-text-en="Report summary">Report summary</span>
                            <p>{{ $reportSummary }}</p>
                        </div>
                    @endif
                    @if (! empty($demoPrefill))
                        <div class="governance-advisory-tool__summary governance-advisory-tool__summary--demo">
                            <span data-text-de="Demo-Daten geladen" data-text-en="Demo data loaded">Demo data loaded</span>
                            <p data-text-de="Diese Ansicht ist mit Beispielwerten aus dem Finance Governance Workspace gefüllt. Du siehst dadurch direkt, wie Eingaben, Ergebnisreport und Plan-Übergabe zusammenarbeiten." data-text-en="This view is filled with example values from the Finance Governance Workspace. It shows how inputs, result report, and plan handoff work together.">This view is filled with example values from the Finance Governance Workspace. It shows how inputs, result report, and plan handoff work together.</p>
                        </div>
                    @endif
                    @if ($toolId === 'custom-stack-builder')
                        <div
                            class="stack-builder stack-builder--tool"
                            data-stack-builder-root
                            data-stack-builder-mode="tool"
                        ></div>
                    @endif
                    <label>
                        <span data-text-de="Kurznotiz" data-text-en="Short note">Short note</span>
                        <input type="text" class="tools-input" name="note" data-governance-tool-note placeholder="Workshop note, owner, decision context">
                    </label>
                    @foreach ($tool['template'] as $index => $item)
                        @php($guide = $templateGuides[$index] ?? [])
                        @php($label = $fieldLabels[$item] ?? ['de' => $item, 'en' => $item])
                        <label>
                            <span data-text-de="{{ $label['de'] }}" data-text-en="{{ $label['en'] }}">{{ $label['en'] }}</span>
                            <textarea
                                class="tools-input"
                                name="field_{{ $index }}"
                                rows="3"
                                data-governance-tool-field
                                data-field-label="{{ $label['en'] }}"
                                data-field-label-de="{{ $label['de'] }}"
                                data-field-label-en="{{ $label['en'] }}"
                                data-field-help="{{ $guide['help'] ?? 'Erfasse den konkreten Stand, offene Fragen und die Quelle der Information.' }}"
                                placeholder="{{ $guide['placeholder'] ?? $item }}"
                                aria-describedby="{{ $toolId }}-field-help-{{ $index }}"
                            ></textarea>
                            <small id="{{ $toolId }}-field-help-{{ $index }}" class="governance-advisory-tool__field-help">
                                {{ $guide['help'] ?? 'Erfasse den konkreten Stand, offene Fragen und die Quelle der Information.' }}
                            </small>
                        </label>
                    @endforeach
                </form>

                <aside class="governance-advisory-tool__report" aria-live="polite">
                    <div class="governance-advisory-tool__report-head">
                        <div>
                            <p class="governance-hub__eyebrow" data-text-de="Ergebnisansicht" data-text-en="Result view">Result view</p>
                            <h2 data-text-de="Report-Baustein" data-text-en="Report block">Report block</h2>
                        </div>
                        <strong data-governance-tool-score>0</strong>
                    </div>
                    <pre data-governance-tool-preview></pre>
                    <div class="governance-advisory-tool__links governance-advisory-tool__links--actions">
                        <button type="button" data-governance-tool-copy>
                            <i class="fa-solid fa-copy" aria-hidden="true"></i>
                            <span data-text-de="Report kopieren" data-text-en="Copy report">Copy report</span>
                        </button>
                        <button type="button" data-governance-tool-download>
                            <i class="fa-solid fa-download" aria-hidden="true"></i>
                            <span data-text-de="Markdown laden" data-text-en="Download Markdown">Download Markdown</span>
                        </button>
                        <button type="button" data-governance-tool-print>
                            <i class="fa-solid fa-print" aria-hidden="true"></i>
                            <span data-text-de="Drucken/PDF" data-text-en="Print/PDF">Print/PDF</span>
                        </button>
                    </div>
                    <p class="governance-advisor__save-status" data-governance-tool-status>
                        @if ($toolId === 'kpi-requirements-intake')
                            <span data-text-de="KPI-Intakes speicherst du im KPI Workspace. Der Report kann weiterhin kopiert, geladen oder gedruckt werden." data-text-en="Save KPI intakes in the KPI workspace. The report can still be copied, downloaded, or printed.">Save KPI intakes in the KPI workspace. The report can still be copied, downloaded, or printed.</span>
                        @else
                            <span data-text-de="Speichere Arbeitsstände im Workspace. Der Report kann weiterhin kopiert, geladen oder gedruckt werden." data-text-en="Save work items in the workspace. The report can still be copied, downloaded, or printed.">Save work items in the workspace. The report can still be copied, downloaded, or printed.</span>
                        @endif
                    </p>
                </aside>
            </div>
        </section>

        <section class="governance-advisory-tool__panel" aria-labelledby="{{ $toolId }}-next">
            <h2 id="{{ $toolId }}-next" data-text-de="Nächster Schritt" data-text-en="Next step">Next step</h2>
            <div class="governance-advisory-tool__links">
                @foreach ($tool['links'] as $link)
                    @if (\Illuminate\Support\Facades\Route::has($link['route']))
                        <a href="{{ locale_route($link['route']) }}">
                            <span>{{ $link['label'] }}</span>
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    @endif
                @endforeach
            </div>
        </section>

        <x-governance.seo-guide
            class="governance-advisory-tool__seo-guide"
            :problem-de="$questionDe !== '' ? $questionDe : $leadDe"
            :problem-en="$questionEn !== '' ? $questionEn : $leadEn"
            decision-de="Welche Inputs brauche ich und welches Artefakt entsteht?"
            decision-en="Which inputs do I need and which artifact is produced?"
            :checklist="collect($inputs)->map(static fn ($item): array => [
                'de' => is_string($item) ? $item : (string) ($item['de'] ?? ''),
                'en' => is_string($item) ? $item : (string) ($item['en'] ?? $item['de'] ?? ''),
            ])->all()"
            :artifacts="collect($outputs)->map(static fn ($item): array => [
                'de' => is_string($item) ? $item : (string) ($item['de'] ?? ''),
                'en' => is_string($item) ? $item : (string) ($item['en'] ?? $item['de'] ?? ''),
            ])->all()"
            :tools="[
                ['de' => 'Governance Hub', 'en' => 'Governance hub', 'href' => locale_route('governance.index')],
                ['de' => 'Discovery Canvas', 'en' => 'Discovery canvas', 'href' => locale_route('governance.discovery-canvas')],
            ]"
            :resources="[
                ['de' => 'Vendor Resources', 'en' => 'Vendor resources', 'href' => locale_route('resources.index')],
                ['de' => 'Supplier Library', 'en' => 'Supplier library', 'href' => locale_route('suppliers.index')],
            ]"
            :playbooks="[
                ['de' => 'Playbooks', 'en' => 'Playbooks', 'href' => locale_route('playbooks.index')],
            ]"
            :next-steps="collect($tool['links'] ?? [])->filter(static fn ($link): bool => \Illuminate\Support\Facades\Route::has($link['route'] ?? ''))->map(static fn ($link): array => [
                'de' => (string) ($link['label'] ?? $link['route']),
                'en' => (string) ($link['label'] ?? $link['route']),
                'href' => locale_route($link['route']),
            ])->take(3)->values()->all()"
        />
    </div>
@endsection
