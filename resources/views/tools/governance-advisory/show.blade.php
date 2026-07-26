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
@endpush

@section('content')
    <div
        class="tools-content governance-advisory-tool"
        data-governance-tool-root
        data-tool-config='@json($toolJson)'
    >
        <header class="governance-advisory-tool__header">
            <div>
                <p class="governance-hub__eyebrow" data-text-de="Governance Tool" data-text-en="Governance tool">Governance tool</p>
                <h1 class="tools-page-title" data-text-de="{{ $titleDe }}" data-text-en="{{ $titleEn }}">{{ $titleEn }}</h1>
                <p class="tools-page-lead" data-hub-lead data-text-de="{{ $leadDe }}" data-text-en="{{ $leadEn }}">{{ $leadEn }}</p>
            </div>
            <div class="governance-advisory-tool__header-side">
                <div class="governance-advisory-tool__question">
                    @if (! empty($tool['icon']))
                        <i class="fa-solid {{ $tool['icon'] }}" aria-hidden="true"></i>
                    @endif
                    <span data-text-de="Entscheidungsfrage" data-text-en="Decision question">Decision question</span>
                    <strong data-text-de="{{ $questionDe }}" data-text-en="{{ $questionEn }}">{{ $questionEn }}</strong>
                </div>
                <div class="governance-advisory-tool__header-actions">
                    <a class="governance-hub__button governance-hub__button--primary" href="#{{ $toolId }}-workbench">
                        <i class="fa-solid fa-eye" aria-hidden="true"></i>
                        <span data-text-de="Report ansehen" data-text-en="View report">View report</span>
                    </a>
                    <button type="button" class="governance-hub__button" data-governance-tool-copy>
                        <i class="fa-solid fa-copy" aria-hidden="true"></i>
                        <span data-text-de="Report kopieren" data-text-en="Copy report">Copy report</span>
                    </button>
                    <button type="button" class="governance-hub__button" data-governance-tool-save-demo>
                        <i class="fa-solid fa-vial" aria-hidden="true"></i>
                        <span data-text-de="Demo speichern" data-text-en="Save demo">Save demo</span>
                    </button>
                </div>
            </div>
        </header>

        <details class="governance-advisory-tool__help" open>
            <summary>
                <span>
                    <i class="fa-solid fa-circle-question" aria-hidden="true"></i>
                    <strong data-text-de="Wie du dieses Tool nutzt" data-text-en="How to use this tool">How to use this tool</strong>
                </span>
                <small data-text-de="Beratung, Eingaben, Report und Übergabe" data-text-en="Guidance, inputs, report, and handoff">Guidance, inputs, report, and handoff</small>
            </summary>
            <div class="governance-advisory-tool__help-content">
                <p
                    data-text-de="Nutze das Tool wie einen Workshop-Assistenten: erst die Entscheidungsfrage verstehen, dann die wichtigsten Informationen erfassen, danach den Report-Baustein prüfen und erst dann in Plan, Workflow, Wiki oder Ticket übernehmen."
                    data-text-en="Use the tool like a workshop assistant: understand the decision question first, capture the most important information, review the report block, then move it into a plan, workflow, wiki, or ticket."
                >Use the tool like a workshop assistant: understand the decision question first, capture the most important information, review the report block, then move it into a plan, workflow, wiki, or ticket.</p>
                <ol>
                    <li data-text-de="Wenn du aus dem Governance Hub kommst, nutze die Empfehlungen dort als Reihenfolge und öffne nur die Tools, die zur aktuellen Entscheidung passen." data-text-en="If you came from the Governance Hub, use its recommendations as the working order and open only tools that match the current decision.">If you came from the Governance Hub, use its recommendations as the working order and open only tools that match the current decision.</li>
                    <li data-text-de="Fülle nicht alles perfekt aus. Wichtig sind konkrete Beispiele, Owner, offene Fragen und die Quelle der Information." data-text-en="Do not try to fill everything perfectly. Concrete examples, owners, open questions, and the source of the information matter most.">Do not try to fill everything perfectly. Concrete examples, owners, open questions, and the source of the information matter most.</li>
                    <li data-text-de="Die Ergebnisansicht ist der prüfbare Report-Baustein. Kopieren, Markdown laden, Demo speichern oder aus dem Plan heraus direkt zurückschreiben." data-text-en="The result view is the reviewable report block. Copy it, download Markdown, save a demo, or write it back directly when you came from a plan.">The result view is the reviewable report block. Copy it, download Markdown, save a demo, or write it back directly when you came from a plan.</li>
                </ol>
            </div>
        </details>

        <details class="governance-advisory-tool__overview" open>
            <summary>
                <span>
                    <i class="fa-solid fa-table-columns" aria-hidden="true"></i>
                    <strong data-text-de="Entscheidung, Inputs und Outputs" data-text-en="Decision, inputs, and outputs">Decision, inputs, and outputs</strong>
                </span>
                <small data-text-de="Kurzüberblick ein- oder ausblenden" data-text-en="Show or hide the overview">Show or hide the overview</small>
            </summary>
            <div class="governance-advisory-tool__grid governance-advisory-tool__grid--explained">
                <section class="governance-advisory-tool__panel" aria-labelledby="{{ $toolId }}-helps">
                    <h2 id="{{ $toolId }}-helps" data-text-de="Wobei hilft das Tool?" data-text-en="What does this tool help decide?">What does this tool help decide?</h2>
                    <ul>
                        @foreach ($tool['helps'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </section>

                <section class="governance-advisory-tool__panel" aria-labelledby="{{ $toolId }}-inputs">
                    <h2 id="{{ $toolId }}-inputs" data-text-de="Warum diese Eingaben?" data-text-en="Why these inputs?">Why these inputs?</h2>
                    <div class="governance-advisory-tool__explain-list">
                        @foreach ($inputs as $item)
                            <article>
                                <strong>{{ $item }}</strong>
                                <p>{{ $inputExplanations[$item] ?? 'Hilft, Kontext, Owner, Risiko und Entscheidungsreife sauber einzuordnen.' }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section class="governance-advisory-tool__panel" aria-labelledby="{{ $toolId }}-outputs">
                    <h2 id="{{ $toolId }}-outputs" data-text-de="Was entsteht daraus?" data-text-en="What comes out?">What comes out?</h2>
                    <div class="governance-advisory-tool__explain-list governance-advisory-tool__explain-list--accent">
                        @foreach ($outputs as $item)
                            <article>
                                <strong>{{ $item }}</strong>
                                <p>{{ $outputExplanations[$item] ?? 'Wird als Report-Teil, Plan-Notiz oder nächster Workflow-Baustein nutzbar.' }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>
            </div>
        </details>

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
                    <label>
                        <span data-text-de="Kurznotiz" data-text-en="Short note">Short note</span>
                        <input type="text" class="tools-input" name="note" data-governance-tool-note placeholder="Workshop note, owner, decision context">
                    </label>
                    @foreach ($tool['template'] as $index => $item)
                        @php($guide = $templateGuides[$index] ?? [])
                        <label>
                            <span>{{ $item }}</span>
                            <textarea
                                class="tools-input"
                                name="field_{{ $index }}"
                                rows="3"
                                data-governance-tool-field
                                data-field-label="{{ $item }}"
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
                            <span data-text-de="Report kopieren" data-text-en="Copy report">Copy report</span>
                            <i class="fa-solid fa-copy" aria-hidden="true"></i>
                        </button>
                        <button type="button" data-governance-tool-download>
                            <span data-text-de="Markdown laden" data-text-en="Download Markdown">Download Markdown</span>
                            <i class="fa-solid fa-download" aria-hidden="true"></i>
                        </button>
                        <button type="button" data-governance-tool-print>
                            <span data-text-de="Drucken/PDF" data-text-en="Print/PDF">Print/PDF</span>
                            <i class="fa-solid fa-print" aria-hidden="true"></i>
                        </button>
                        <button type="button" data-governance-tool-save-demo>
                            <span data-text-de="Demo speichern" data-text-en="Save demo">Save demo</span>
                            <i class="fa-solid fa-vial" aria-hidden="true"></i>
                        </button>
                    </div>
                    <p class="governance-advisor__save-status" data-governance-tool-status>
                        <span data-text-de="Standalone speichert als Demo in dieser Browser-Sitzung. Aus einem Plan heraus kann der Report direkt in die Aufgabe geschrieben werden." data-text-en="Standalone stores a demo in this browser session. From a plan, the report can be written back to the item.">Standalone stores a demo in this browser session. From a plan, the report can be written back to the item.</span>
                    </p>
                </aside>
            </div>
        </section>

        <details class="governance-advisory-tool__panel governance-advisory-tool__template" aria-labelledby="{{ $toolId }}-template">
            <summary>
                <span>
                    <i class="fa-solid fa-list-check" aria-hidden="true"></i>
                    <strong id="{{ $toolId }}-template" data-text-de="Erfassungsstruktur und Feldhilfe" data-text-en="Capture structure and field help">Capture structure and field help</strong>
                </span>
                <small data-text-de="Welche Informationen in den Report gehören" data-text-en="Which information belongs in the report">Which information belongs in the report</small>
            </summary>
            <div class="governance-advisory-tool__template-content">
                <p data-text-de="Diese Struktur ist die Checkliste für einen sauberen Arbeitsstand: Jedes Feld beantwortet eine Frage, die später für Entscheidung, Umsetzung, Review oder Change Request gebraucht wird." data-text-en="This structure is the checklist for a clean working state: each field answers a question needed later for decision, implementation, review, or change request.">This structure is the checklist for a clean working state: each field answers a question needed later for decision, implementation, review, or change request.</p>
                <ol>
                    @foreach ($tool['template'] as $index => $item)
                        @php($guide = $templateGuides[$index] ?? [])
                        <li>
                            <strong>{{ $item }}</strong>
                            <span>{{ $guide['help'] ?? 'Erfasse den konkreten Stand, offene Fragen und die Quelle der Information.' }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </details>

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
                <a href="{{ locale_route('governance.index') }}">
                    <span data-text-de="Zurück zum Governance Hub" data-text-en="Back to Governance Hub">Back to Governance Hub</span>
                    <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </section>
    </div>
@endsection
