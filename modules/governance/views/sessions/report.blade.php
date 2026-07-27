@extends('foundations.layouts.tools')

@section('title', $session['title'] . ' - Governance Report')
@section('meta_description', ! empty($isDemo)
    ? 'Sample governance discovery report: advisor inputs, recommendations, and validation findings — example path without login.'
    : 'Governance Discovery report view with saved advisor inputs, recommendations and validation findings.')

@section('content')
    @php
        $advisor = $report['advisor'] ?? [];
        $guidance = $report['guidance'] ?? [];
        $dataQuality = $report['dataQuality'] ?? [];
        $kpis = $report['kpis'] ?? [];
        $sourceScope = $report['sourceScope'] ?? [];
        $pii = $report['pii'] ?? [];
        $decisionBrief = $report['decisionBrief'] ?? [];
        $recommendations = $report['recommendations'] ?? [];
        $validation = $report['validation'] ?? [];
        $warnings = is_array($validation['warnings'] ?? null) ? $validation['warnings'] : [];
        $guidanceCerts = is_array($guidance['certs'] ?? null) ? $guidance['certs'] : [];
        $guidanceGaps = is_array($guidance['gaps'] ?? null) ? $guidance['gaps'] : [];
        $guidanceItems = array_values(array_filter([...$guidanceCerts, ...$guidanceGaps]));
    @endphp
    <div class="tools-content governance-report">
        <header class="governance-report__header">
            <div>
                <p class="governance-hub__eyebrow" data-text-de="{{ ! empty($isDemo) ? 'Beispiel-Report / Beweis-Pfad' : 'Report Ansicht' }}" data-text-en="{{ ! empty($isDemo) ? 'Sample report / proof path' : 'Report view' }}">{{ ! empty($isDemo) ? 'Sample report / proof path' : 'Report view' }}</p>
                <h1 class="tools-page-title">{{ $session['title'] }}</h1>
                <p class="tools-page-lead" data-hub-lead>
                    @if (! empty($isDemo))
                        <span data-text-de="Anonymisiertes Beispielartefakt aus einer geführten Discovery — danach eigene Session im Governance Hub starten." data-text-en="Anonymized sample artifact from a guided discovery — then start your own session in the Governance Hub.">Anonymized sample artifact from a guided discovery — then start your own session in the Governance Hub.</span>
                    @else
                        {{ $session['companyName'] ?: 'Governance Discovery' }}
                        @if (! empty($session['projectName']))
                            - {{ $session['projectName'] }}
                        @endif
                    @endif
                </p>
            </div>
            <div class="governance-report__actions">
                <a class="governance-hub__button" href="{{ locale_route('governance.index') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                    <span data-text-de="Zurück zum Hub" data-text-en="Back to hub">Back to hub</span>
                </a>
                @if (empty($isDemo))
                    <button
                        type="button"
                        class="governance-hub__button governance-hub__button--primary"
                        data-governance-create-plan
                        data-session-id="{{ $session['id'] }}"
                        data-create-plan-url="{{ url('/api/governance/sessions/'.$session['id'].'/create-plan') }}"
                    >
                        <i class="fa-solid fa-diagram-project" aria-hidden="true"></i>
                        <span data-text-de="In Workflow übernehmen" data-text-en="Create workflow">Create workflow</span>
                    </button>
                @else
                    <a class="governance-hub__button governance-hub__button--primary" href="{{ locale_route('governance.index') }}#governance-advisor">
                        <i class="fa-solid fa-compass" aria-hidden="true"></i>
                        <span data-text-de="Eigene Session starten" data-text-en="Start own session">Start own session</span>
                    </a>
                @endif
                <button type="button" class="governance-hub__button" onclick="window.print()">
                    <i class="fa-solid fa-print" aria-hidden="true"></i>
                    <span data-text-de="Drucken/PDF" data-text-en="Print/PDF">Print/PDF</span>
                </button>
                <a class="governance-hub__button" href="{{ locale_route('sprint-planner.index') }}?list=1">
                    <i class="fa-solid fa-folder-open" aria-hidden="true"></i>
                    <span data-text-de="Aktive Pläne weiterführen" data-text-en="Continue active plans">Continue active plans</span>
                </a>
                <a class="governance-hub__button" href="{{ locale_route('governance.sessions.index') }}">
                    <i class="fa-solid fa-table-list" aria-hidden="true"></i>
                    <span data-text-de="Sessions" data-text-en="Sessions">Sessions</span>
                </a>
            </div>
        </header>

        <section class="governance-report__summary">
            <div>
                <span data-text-de="Status" data-text-en="Status">Status</span>
                <strong>{{ str_replace('_', ' ', (string) ($session['status'] ?? 'draft')) }}</strong>
            </div>
            <div>
                <span data-text-de="Validierung" data-text-en="Validation">Validation</span>
                <strong>{{ $validation['state'] ?? 'incomplete' }} / {{ $validation['score'] ?? 0 }}</strong>
            </div>
            <div>
                <span data-text-de="Aktualisiert" data-text-en="Updated">Updated</span>
                <strong>{{ $session['updatedAt'] ?? '-' }}</strong>
            </div>
        </section>

        <section class="governance-report__section">
            <h2 data-text-de="Eingaben" data-text-en="Inputs">Inputs</h2>
            <dl class="governance-report__facts">
                <div><dt>Scenario</dt><dd>{{ $advisor['scenario'] ?? $session['scenario'] ?? '-' }}</dd></div>
                <div><dt>Goal</dt><dd>{{ $advisor['goal'] ?? '-' }}</dd></div>
                <div><dt>Source type</dt><dd>{{ $advisor['domain'] ?? '-' }}</dd></div>
                <div><dt>Target stack</dt><dd>{{ $advisor['platform'] ?? '-' }}</dd></div>
                <div><dt data-text-de="Organisationskontext" data-text-en="Organisation context">Organisation context</dt><dd>{{ $advisor['orgContext'] ?? '-' }}</dd></div>
            </dl>
        </section>

        <section class="governance-report__section">
            <h2 data-text-de="Empfehlungen" data-text-en="Recommendations">Recommendations</h2>
            <div class="governance-report__recommendations">
                @forelse ($recommendations as $item)
                    <a href="{{ $item['url'] ?? '#' }}">
                        <strong>{{ $item['title'] ?? '-' }}</strong>
                        <span>{{ $item['group'] ?? 'tool' }}</span>
                        <em>{{ $item['reason'] ?? '' }}</em>
                    </a>
                @empty
                    <p data-text-de="Noch keine Empfehlungen gespeichert." data-text-en="No recommendations saved yet.">No recommendations saved yet.</p>
                @endforelse
            </div>
        </section>

        @if ($guidanceItems !== [])
            <section class="governance-report__section">
                <h2 data-text-de="Nachweise &amp; Lücken" data-text-en="Evidence &amp; gaps">Evidence &amp; gaps</h2>
                <div class="governance-report__recommendations">
                    @foreach ($guidanceItems as $item)
                        <a href="{{ $item['url'] ?? '#' }}">
                            <strong>{{ $item['title'] ?? '-' }}</strong>
                            <span>{{ $item['group'] ?? 'guidance' }}</span>
                            <em>{{ $item['reason'] ?? '' }}</em>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($kpis !== [])
            <section class="governance-report__section">
                <h2 data-text-de="KPI-Karten" data-text-en="KPI cards">KPI cards</h2>
                <div class="governance-report__recommendations">
                    @foreach ($kpis as $kpi)
                        <article>
                            <strong>{{ $kpi['name'] ?? '-' }}</strong>
                            <span>{{ $kpi['status'] ?? 'draft' }}</span>
                            <em>
                                {{ $kpi['formula'] ?? '-' }}
                                @if (! empty($kpi['grain']))
                                    · Grain: {{ $kpi['grain'] }}
                                @endif
                                @if (! empty($kpi['owner']))
                                    · Owner: {{ $kpi['owner'] }}
                                @endif
                            </em>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($sourceScope !== [])
            <section class="governance-report__section">
                <h2 data-text-de="Source Scope" data-text-en="Source scope">Source scope</h2>
                <dl class="governance-report__facts">
                    <div><dt>Supplier</dt><dd>{{ $sourceScope['supplier'] ?? '-' }}</dd></div>
                    <div><dt>Must-have</dt><dd>{{ implode(', ', array_map('strval', $sourceScope['mustHave'] ?? [])) ?: '-' }}</dd></div>
                    <div><dt>Optional</dt><dd>{{ implode(', ', array_map('strval', $sourceScope['optional'] ?? [])) ?: '-' }}</dd></div>
                    <div><dt>Skip</dt><dd>{{ implode(', ', array_map('strval', $sourceScope['skip'] ?? [])) ?: '-' }}</dd></div>
                    <div><dt>Owner</dt><dd>{{ implode(', ', array_map('strval', $sourceScope['owners'] ?? [])) ?: '-' }}</dd></div>
                </dl>
            </section>
        @endif

        @if ($pii !== [])
            <section class="governance-report__section">
                <h2 data-text-de="PII/DSDR" data-text-en="PII/DSDR">PII/DSDR</h2>
                <dl class="governance-report__facts">
                    <div><dt>Fields</dt><dd>{{ implode(', ', array_map('strval', $pii['fields'] ?? [])) ?: '-' }}</dd></div>
                    <div><dt>DSDR keys</dt><dd>{{ implode(', ', array_map('strval', $pii['dsdrKeys'] ?? [])) ?: '-' }}</dd></div>
                    <div><dt>Controls</dt><dd>{{ implode(', ', array_map('strval', $pii['controls'] ?? [])) ?: '-' }}</dd></div>
                </dl>
            </section>
        @endif

        @if (($advisor['goal'] ?? '') === 'dq' || $dataQuality !== [])
            <section class="governance-report__section">
                <h2 data-text-de="Data Quality" data-text-en="Data quality">Data quality</h2>
                <dl class="governance-report__facts">
                    <div><dt>Mode</dt><dd>{{ $dataQuality['mode'] ?? $advisor['dqMode'] ?? '-' }}</dd></div>
                    <div><dt>Layer</dt><dd>{{ $dataQuality['layer'] ?? $advisor['dqLayer'] ?? '-' }}</dd></div>
                    <div>
                        <dt>Issue classes</dt>
                        <dd>{{ implode(', ', array_map('strval', $dataQuality['issueTypes'] ?? $advisor['dqIssues'] ?? [])) ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt>Decision status</dt>
                        <dd>{{ $dataQuality['decisionStatus'] ?? 'draft' }}</dd>
                    </div>
                    <div>
                        <dt>Affected context</dt>
                        <dd>
                            {{ implode(', ', array_filter([
                                implode(', ', array_map('strval', $dataQuality['affectedSources'] ?? [])),
                                implode(', ', array_map('strval', $dataQuality['affectedKpis'] ?? [])),
                                implode(', ', array_map('strval', $dataQuality['affectedReports'] ?? [])),
                            ])) ?: '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt>Proposed rules</dt>
                        <dd>{{ implode(', ', array_map('strval', $dataQuality['proposedRules'] ?? [])) ?: '-' }}</dd>
                    </div>
                </dl>
                <p data-text-de="DQ ist Teil der Governance-Entscheidung: Regeln, Monitoring und Gates müssen mit Source, KPI, Mart und PII zusammen bewertet werden." data-text-en="DQ is part of the governance decision: rules, monitoring, and gates must be assessed together with source, KPI, mart, and PII.">DQ is part of the governance decision: rules, monitoring, and gates must be assessed together with source, KPI, mart, and PII.</p>
            </section>
        @endif

        @if ($decisionBrief !== [])
            <section class="governance-report__section">
                <h2 data-text-de="Decision Brief" data-text-en="Decision brief">Decision brief</h2>
                <dl class="governance-report__facts">
                    <div><dt>Recommendation</dt><dd>{{ $decisionBrief['recommendation'] ?? '-' }}</dd></div>
                    <div><dt>Open questions</dt><dd>{{ implode(', ', array_map('strval', $decisionBrief['openQuestions'] ?? [])) ?: '-' }}</dd></div>
                    <div><dt>Next sprint</dt><dd>{{ implode(', ', array_map('strval', $decisionBrief['nextSprint'] ?? [])) ?: '-' }}</dd></div>
                </dl>
            </section>
        @endif

        <section class="governance-report__section">
            <h2 data-text-de="Validierung" data-text-en="Validation">Validation</h2>
            @if ($warnings === [])
                <p data-text-de="Keine offenen Warnungen. Die Session ist als Entscheidungsgrundlage nutzbar." data-text-en="No open warnings. The session can be used as a decision basis.">No open warnings. The session can be used as a decision basis.</p>
            @else
                <ul>
                    @foreach ($warnings as $warning)
                        <li>{{ $warning }}</li>
                    @endforeach
                </ul>
            @endif
        </section>

        <section class="governance-report__section">
            <h2 data-text-de="Nächste Workflow-Schritte" data-text-en="Next workflow steps">Next workflow steps</h2>
            <ol>
                <li data-text-de="Eingaben und Empfehlungen fachlich prüfen." data-text-en="Review inputs and recommendations with stakeholders.">Review inputs and recommendations with stakeholders.</li>
                <li data-text-de="Source Scope, KPI/Mart und PII/DSDR-Lücken schließen." data-text-en="Close source scope, KPI/mart and PII/DSDR gaps.">Close source scope, KPI/mart and PII/DSDR gaps.</li>
                <li data-text-de="Decision Brief finalisieren und Change Requests für spätere Änderungen nutzen." data-text-en="Finalize the decision brief and use change requests for later changes.">Finalize the decision brief and use change requests for later changes.</li>
            </ol>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-governance-create-plan]').forEach(function (button) {
            button.addEventListener('click', function () {
                button.disabled = true;
                fetch(button.dataset.createPlanUrl, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                }).then(function (response) {
                    if (! response.ok) {
                        throw new Error('workflow-create-failed');
                    }
                    return response.json();
                }).then(function (data) {
                    if (data.url) {
                        window.location.href = data.url;
                    }
                }).catch(function () {
                    button.disabled = false;
                    alert('Workflow could not be created.');
                });
            });
        });
    </script>
@endpush
