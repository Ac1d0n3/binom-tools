@extends('foundations.layouts.tools')

@section('title', 'Governance Demo Workspace - ' . config('app.name'))
@section('meta_description', 'Example governance path without login: filled demo workspace with session, plans, KPI cards, generator results — then open the sample report.')
@section('robots', 'noindex,nofollow')

@section('content')
    @php
        $mainPlan = $workspace['mainPlan'] ?? [];
        $learningPlan = $workspace['learningPlan'] ?? [];
        $kpiCards = $workspace['kpiCards'] ?? [];
        $toolRuns = $workspace['toolRuns'] ?? [];
        $toolRoutes = [
            'kpi-requirements-intake' => 'tools.kpi-requirements-intake',
            'source-scope-builder' => 'tools.source-scope-builder',
            'mart-design-brief-generator' => 'tools.mart-design-brief-generator',
            'governance-stack-advisor' => 'tools.governance-stack-advisor',
            'pii-dsdr-readiness-checker' => 'tools.pii-dsdr-readiness-checker',
            'decision-brief-generator' => 'tools.decision-brief-generator',
            'vendor-learning-path-builder' => 'tools.vendor-learning-path-builder',
        ];
    @endphp

    <div class="tools-content governance-demo-workspace">
        <header class="governance-demo-workspace__header">
            <div>
                <p class="governance-hub__eyebrow" data-text-de="Beweis-Pfad / Beispiel" data-text-en="Proof path / example">Proof path / example</p>
                <h1 class="tools-page-title" data-text-de="Governance Demo Workspace" data-text-en="Governance Demo Workspace">Governance Demo Workspace</h1>
                <p
                    class="tools-page-lead"
                    data-hub-lead
                    data-text-de="So sieht ein gefüllter Stand aus: Session, Hauptplan, Lernplan, KPI Cards und Generator-Ergebnisse greifen zusammen. Als Nächstes den Beispiel-Report öffnen — oder im Hub eine eigene Discovery starten."
                    data-text-en="What a filled state looks like: session, main plan, learning plan, KPI cards, and generator results work together. Next, open the sample report — or start your own discovery in the hub."
                >What a filled state looks like: session, main plan, learning plan, KPI cards, and generator results work together. Next, open the sample report — or start your own discovery in the hub.</p>
            </div>
            <div class="governance-demo-workspace__actions">
                <a class="governance-hub__button governance-hub__button--primary" href="{{ locale_route('governance.sessions.demo-report') }}">
                    <i class="fa-solid fa-eye" aria-hidden="true"></i>
                    <span data-text-de="Report ansehen" data-text-en="View report">View report</span>
                </a>
                <a class="governance-hub__button" href="{{ locale_route('sprint-planner.templates') }}">
                    <i class="fa-solid fa-list-check" aria-hidden="true"></i>
                    <span data-text-de="Planvorlagen öffnen" data-text-en="Open plan templates">Open plan templates</span>
                </a>
                <a class="governance-hub__button" href="{{ locale_route('governance.index') }}#governance-advisor">
                    <i class="fa-solid fa-compass" aria-hidden="true"></i>
                    <span data-text-de="Eigene Session starten" data-text-en="Start own session">Start own session</span>
                </a>
            </div>
        </header>

        <section class="governance-demo-workspace__band" aria-labelledby="demo-session-title">
            <div>
                <p class="governance-hub__eyebrow" data-text-de="Session" data-text-en="Session">Session</p>
                <h2 id="demo-session-title">{{ $session['title'] }}</h2>
                <p>{{ $session['companyName'] }} · {{ $session['projectName'] }} · {{ strtoupper((string) $session['status']) }}</p>
            </div>
            <strong>{{ $session['validationSummary']['score'] ?? 0 }}%</strong>
        </section>

        <section class="governance-demo-workspace__plans" aria-label="Governance plans">
            <article class="governance-demo-workspace__plan">
                <div class="governance-demo-workspace__plan-head">
                    <div>
                        <p class="governance-hub__eyebrow" data-text-de="Aktiver Hauptplan" data-text-en="Active main plan">Active main plan</p>
                        <h2>{{ $mainPlan['title'] }}</h2>
                        <p>{{ $mainPlan['summary'] }}</p>
                    </div>
                    <strong>{{ $mainPlan['progress'] }}%</strong>
                </div>
                <div class="governance-demo-workspace__progress" aria-hidden="true">
                    <span style="width: {{ (int) $mainPlan['progress'] }}%"></span>
                </div>
                <ul class="governance-demo-workspace__steps">
                    @foreach (($mainPlan['sprints'] ?? []) as $sprint)
                        <li>
                            <span>{{ $sprint['title'] }}</span>
                            <small>{{ $sprint['status'] }} · {{ $sprint['done'] }}/{{ $sprint['total'] }}</small>
                        </li>
                    @endforeach
                </ul>
                <div class="governance-demo-workspace__chips">
                    @foreach (($mainPlan['openDecisions'] ?? []) as $decision)
                        <span>{{ $decision }}</span>
                    @endforeach
                </div>
            </article>

            <article class="governance-demo-workspace__plan governance-demo-workspace__plan--learning">
                <div class="governance-demo-workspace__plan-head">
                    <div>
                        <p class="governance-hub__eyebrow" data-text-de="Paralleler Lernplan" data-text-en="Parallel learning plan">Parallel learning plan</p>
                        <h2>{{ $learningPlan['title'] }}</h2>
                        <p>{{ $learningPlan['summary'] }}</p>
                    </div>
                    <strong>{{ $learningPlan['progress'] }}%</strong>
                </div>
                <div class="governance-demo-workspace__progress" aria-hidden="true">
                    <span style="width: {{ (int) $learningPlan['progress'] }}%"></span>
                </div>
                <ul class="governance-demo-workspace__steps">
                    @foreach (($learningPlan['tracks'] ?? []) as $track)
                        <li>
                            <span>{{ $track['title'] }}</span>
                            <small>{{ $track['status'] }}</small>
                        </li>
                    @endforeach
                </ul>
                <div class="governance-demo-workspace__chips">
                    @foreach (($learningPlan['certificates'] ?? []) as $certificate)
                        <span>{{ $certificate }}</span>
                    @endforeach
                </div>
            </article>
        </section>

        <section class="governance-demo-workspace__section" aria-labelledby="demo-kpi-title">
            <div class="governance-demo-workspace__section-head">
                <div>
                    <p class="governance-hub__eyebrow" data-text-de="Generator-Ergebnis" data-text-en="Generator result">Generator result</p>
                    <h2 id="demo-kpi-title" data-text-de="KPI Cards mit echten Werten" data-text-en="KPI cards with values">KPI cards with values</h2>
                </div>
                <a class="governance-hub__button" href="{{ locale_route('tools.kpi-requirements-intake') }}?demo=finance">
                    <i class="fa-solid fa-gauge-high" aria-hidden="true"></i>
                    <span data-text-de="KPI Tool öffnen" data-text-en="Open KPI tool">Open KPI tool</span>
                </a>
            </div>
            <div class="governance-demo-workspace__kpis">
                @foreach ($kpiCards as $kpi)
                    <article>
                        <strong>{{ $kpi['name'] }}</strong>
                        <p>{{ $kpi['formula'] }}</p>
                        <dl>
                            <div><dt>Grain</dt><dd>{{ $kpi['grain'] }}</dd></div>
                            <div><dt>Owner</dt><dd>{{ $kpi['owner'] }}</dd></div>
                            <div><dt>Quelle</dt><dd>{{ $kpi['source'] }}</dd></div>
                            <div><dt>Status</dt><dd>{{ $kpi['status'] }}</dd></div>
                        </dl>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="governance-demo-workspace__section" aria-labelledby="demo-tools-title">
            <div class="governance-demo-workspace__section-head">
                <div>
                    <p class="governance-hub__eyebrow" data-text-de="Generator-Ansichten" data-text-en="Generator views">Generator views</p>
                    <h2 id="demo-tools-title" data-text-de="Gefüllte Tools öffnen" data-text-en="Open filled tools">Open filled tools</h2>
                </div>
                <a class="governance-hub__button" href="{{ locale_route('governance.sessions.demo-report') }}">
                    <i class="fa-solid fa-file-lines" aria-hidden="true"></i>
                    <span data-text-de="Gesamtreport ansehen" data-text-en="View full report">View full report</span>
                </a>
            </div>
            <div class="governance-demo-workspace__tools">
                @foreach ($toolRuns as $tool)
                    @php($route = $toolRoutes[$tool['id']] ?? null)
                    <article>
                        <div>
                            <span data-text-de="Gefüllt" data-text-en="Filled">Filled</span>
                            <h3>{{ $tool['title'] }}</h3>
                            <p>{{ $tool['output'] }}</p>
                        </div>
                        @if ($route !== null && \Illuminate\Support\Facades\Route::has($route))
                            <a href="{{ locale_route($route) }}?demo=finance">
                                <span data-text-de="Öffnen" data-text-en="Open">Open</span>
                                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        @endif
                    </article>
                @endforeach
            </div>
        </section>
    </div>
@endsection
