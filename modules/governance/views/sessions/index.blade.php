@extends('foundations.layouts.tools')

@section('title', 'Governance Sessions - ' . config('app.name'))
@section('meta_description', 'Manage saved Governance Discovery Sessions, reports, validation states and workflow handoff.')
@section('robots', 'noindex,nofollow')

@section('content')
    <div class="tools-content governance-sessions">
        <header class="governance-sessions__header">
            <div>
                <p class="governance-hub__eyebrow" data-text-de="Permanente Arbeitsstände" data-text-en="Persistent work sessions">Persistent work sessions</p>
                <h1 class="tools-page-title" data-text-de="Governance Sessions verwalten" data-text-en="Manage governance sessions">Manage governance sessions</h1>
                <p
                    class="tools-page-lead"
                    data-hub-lead
                    data-text-de="Gespeicherte Discovery-Ergebnisse bleiben mit Login dauerhaft verfügbar und können als Report, Kopie oder Workflow weiterverwendet werden."
                    data-text-en="Signed-in discovery results stay permanently available and can continue as reports, copies, or workflows."
                >Signed-in discovery results stay permanently available and can continue as reports, copies, or workflows.</p>
            </div>
            <div class="governance-sessions__header-actions">
                <a class="governance-hub__button governance-hub__button--primary" href="{{ locale_route('governance.index') }}#governance-advisor">
                    <i class="fa-solid fa-compass" aria-hidden="true"></i>
                    <span data-text-de="Neue Session starten" data-text-en="Start new session">Start new session</span>
                </a>
                <a class="governance-hub__button" href="{{ locale_route('governance.sessions.demo-report') }}">
                    <i class="fa-solid fa-eye" aria-hidden="true"></i>
                    <span data-text-de="Beispiel-Report ansehen" data-text-en="View example report">View example report</span>
                </a>
                <a class="governance-hub__button" href="{{ locale_route('governance.sessions.demo-workspace') }}">
                    <i class="fa-solid fa-diagram-project" aria-hidden="true"></i>
                    <span data-text-de="Demo Workspace ansehen" data-text-en="View demo workspace">View demo workspace</span>
                </a>
                <a class="governance-hub__button" href="{{ locale_route('governance.sessions.index', $showArchived ? [] : ['archived' => 1]) }}">
                    <i class="fa-solid fa-box-archive" aria-hidden="true"></i>
                    <span data-text-de="{{ $showArchived ? 'Aktive anzeigen' : 'Archiv anzeigen' }}" data-text-en="{{ $showArchived ? 'Show active' : 'Show archive' }}">{{ $showArchived ? 'Show active' : 'Show archive' }}</span>
                </a>
            </div>
        </header>

        @if (session('status'))
            <p class="governance-sessions__flash">{{ session('status') }}</p>
        @endif

        @if ($sessions === [])
            <section class="governance-sessions__empty">
                <i class="fa-solid fa-folder-open" aria-hidden="true"></i>
                <h2 data-text-de="Noch keine Sessions" data-text-en="No sessions yet">No sessions yet</h2>
                <p data-text-de="Starte im Governance Hub den Advisor und speichere die Ergebnisse als permanente Session." data-text-en="Start the advisor in the Governance Hub and save the results as a persistent session.">Start the advisor in the Governance Hub and save the results as a persistent session.</p>
                <a class="governance-hub__button governance-hub__button--primary" href="{{ locale_route('governance.sessions.demo-workspace') }}">
                    <i class="fa-solid fa-diagram-project" aria-hidden="true"></i>
                    <span data-text-de="Gefüllten Demo Workspace ansehen" data-text-en="View filled demo workspace">View filled demo workspace</span>
                </a>
            </section>
        @else
            <section class="governance-sessions__grid" aria-label="Governance sessions">
                @foreach ($sessions as $session)
                    @php
                        $validation = is_array($session['validationSummary'] ?? null) ? $session['validationSummary'] : [];
                        $warnings = is_array($validation['warnings'] ?? null) ? $validation['warnings'] : [];
                        $advisor = is_array($session['payload']['advisor'] ?? null) ? $session['payload']['advisor'] : [];
                    @endphp
                    <article class="governance-sessions__item">
                        <div class="governance-sessions__item-head">
                            <div>
                                <p class="governance-sessions__meta">
                                    <span>{{ strtoupper(str_replace('_', ' ', (string) ($session['status'] ?? 'draft'))) }}</span>
                                    <span>{{ strtoupper((string) ($session['scenario'] ?? 'new')) }}</span>
                                </p>
                                <h2>{{ $session['title'] }}</h2>
                            </div>
                            <strong class="governance-sessions__score">{{ $validation['score'] ?? 0 }}</strong>
                        </div>
                        <dl class="governance-sessions__facts">
                            <div>
                                <dt data-text-de="Firma" data-text-en="Company">Company</dt>
                                <dd>{{ $session['companyName'] ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt data-text-de="Projekt" data-text-en="Project">Project</dt>
                                <dd>{{ $session['projectName'] ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt data-text-de="Ziel" data-text-en="Goal">Goal</dt>
                                <dd>{{ $advisor['goal'] ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt data-text-de="Quelle / Stack" data-text-en="Source / stack">Source / stack</dt>
                                <dd>{{ ($advisor['domain'] ?? '-') }} / {{ ($advisor['platform'] ?? '-') }}</dd>
                            </div>
                        </dl>
                        @if ($warnings !== [])
                            <ul class="governance-sessions__warnings">
                                @foreach (array_slice($warnings, 0, 3) as $warning)
                                    <li>{{ $warning }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <div class="governance-sessions__actions">
                            <a href="{{ locale_route('governance.sessions.report', ['sessionId' => $session['id']]) }}">
                                <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                <span data-text-de="Report ansehen" data-text-en="View report">View report</span>
                            </a>
                            <form method="post" action="{{ locale_route('governance.sessions.duplicate', ['sessionId' => $session['id']]) }}">
                                @csrf
                                <button type="submit">
                                    <i class="fa-solid fa-copy" aria-hidden="true"></i>
                                    <span data-text-de="Kopieren" data-text-en="Copy">Copy</span>
                                </button>
                            </form>
                            @if (($session['status'] ?? '') !== 'archived')
                                <form method="post" action="{{ locale_route('governance.sessions.archive', ['sessionId' => $session['id']]) }}">
                                    @csrf
                                    <button type="submit">
                                        <i class="fa-solid fa-box-archive" aria-hidden="true"></i>
                                        <span data-text-de="Archivieren" data-text-en="Archive">Archive</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </article>
                @endforeach
            </section>
        @endif
    </div>
@endsection
