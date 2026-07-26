@extends('layouts.tools')

@section('title', $session['title'] . ' - Governance Report')
@section('meta_description', 'Printable Governance Discovery report with saved advisor inputs, recommendations and validation findings.')

@section('content')
    @php
        $advisor = $report['advisor'] ?? [];
        $recommendations = $report['recommendations'] ?? [];
        $validation = $report['validation'] ?? [];
        $warnings = is_array($validation['warnings'] ?? null) ? $validation['warnings'] : [];
    @endphp
    <div class="tools-content governance-report">
        <header class="governance-report__header">
            <div>
                <p class="governance-hub__eyebrow" data-text-de="Druckbarer Report" data-text-en="Printable report">Printable report</p>
                <h1 class="tools-page-title">{{ $session['title'] }}</h1>
                <p class="tools-page-lead" data-hub-lead>
                    {{ $session['companyName'] ?: 'Governance Discovery' }}
                    @if (! empty($session['projectName']))
                        - {{ $session['projectName'] }}
                    @endif
                </p>
            </div>
            <div class="governance-report__actions">
                <button type="button" class="governance-hub__button governance-hub__button--primary" onclick="window.print()">
                    <i class="fa-solid fa-print" aria-hidden="true"></i>
                    <span data-text-de="Drucken" data-text-en="Print">Print</span>
                </button>
                <button
                    type="button"
                    class="governance-hub__button"
                    data-governance-create-plan
                    data-session-id="{{ $session['id'] }}"
                    data-create-plan-url="{{ url('/api/governance/sessions/'.$session['id'].'/create-plan') }}"
                >
                    <i class="fa-solid fa-diagram-project" aria-hidden="true"></i>
                    <span data-text-de="In Workflow uebernehmen" data-text-en="Create workflow">Create workflow</span>
                </button>
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
            <h2 data-text-de="Naechste Workflow-Schritte" data-text-en="Next workflow steps">Next workflow steps</h2>
            <ol>
                <li data-text-de="Eingaben und Empfehlungen fachlich pruefen." data-text-en="Review inputs and recommendations with stakeholders.">Review inputs and recommendations with stakeholders.</li>
                <li data-text-de="Source Scope, KPI/Mart und PII/DSDR-Luecken schliessen." data-text-en="Close source scope, KPI/mart and PII/DSDR gaps.">Close source scope, KPI/mart and PII/DSDR gaps.</li>
                <li data-text-de="Decision Brief finalisieren und Change Requests fuer spaetere Aenderungen nutzen." data-text-en="Finalize the decision brief and use change requests for later changes.">Finalize the decision brief and use change requests for later changes.</li>
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
