@extends('profile::layouts.shell')

@section('title', 'Saved discoveries — ' . config('app.name'))
@section('meta_description', 'Saved Governance Hub discoveries — reports, copies, and archives.')
@section('robots', 'noindex,nofollow')

@section('profile_content')
    <div class="tools-content governance-sessions">
        <header class="governance-sessions__header">
            <div>
                <p class="governance-hub__eyebrow" data-text-de="Gespeicherte Discoveries" data-text-en="Saved discoveries">Saved discoveries</p>
                <h1 class="tools-page-title" data-text-de="Gespeicherte Discoveries" data-text-en="Saved discoveries">Saved discoveries</h1>
                <p
                    class="tools-page-lead"
                    data-hub-lead
                    data-text-de="Ergebnisse, die du im Governance Hub dauerhaft gespeichert hast — Reports, Kopien und Archiv. Neu starten und Speichern passiert im Hub, nicht hier."
                    data-text-en="Results you permanently saved from the Governance Hub — reports, copies, and archive. Starting and saving happens in the Hub, not here."
                >Results you permanently saved from the Governance Hub — reports, copies, and archive. Starting and saving happens in the Hub, not here.</p>
                @if (! empty($activeWorkspace))
                    <p class="tools-page-lead" style="margin-top:.35rem">
                        <span data-text-de="Aktiver Workspace:" data-text-en="Active workspace:">Active workspace:</span>
                        <strong>{{ $activeWorkspace['name'] ?? '' }}</strong>
                        <span> · {{ $activeWorkspace['stack'] ?? 'unknown' }}</span>
                    </p>
                @endif
            </div>
            <div class="governance-sessions__header-actions">
                <a class="governance-hub__button governance-hub__button--primary" href="{{ locale_route('governance.index') }}#governance-advisor">
                    <i class="fa-solid fa-compass" aria-hidden="true"></i>
                    <span data-text-de="Im Governance Hub weiterarbeiten" data-text-en="Continue in Governance Hub">Continue in Governance Hub</span>
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
                <h2 data-text-de="Noch nichts gespeichert" data-text-en="Nothing saved yet">Nothing saved yet</h2>
                <p data-text-de="Öffne den Governance Hub, nutze den Advisor und speichere dort dein Ergebnis. Hier erscheinen danach deine dauerhaften Discoveries." data-text-en="Open the Governance Hub, use the advisor, and save your result there. Your permanent discoveries will show up here.">Open the Governance Hub, use the advisor, and save your result there. Your permanent discoveries will show up here.</p>
                <a class="governance-hub__button governance-hub__button--primary" href="{{ locale_route('governance.index') }}#governance-advisor">
                    <i class="fa-solid fa-compass" aria-hidden="true"></i>
                    <span data-text-de="Zum Governance Hub" data-text-en="Go to Governance Hub">Go to Governance Hub</span>
                </a>
                <p style="margin-top:1rem">
                    <a class="tools-btn tools-btn--ghost" href="{{ locale_route('governance.sessions.demo-workspace') }}">
                        <span data-text-de="Nur Demo ansehen (ohne Speichern)" data-text-en="View demo only (no save)">View demo only (no save)</span>
                    </a>
                </p>
            </section>
        @else
            <section class="governance-sessions__grid" aria-label="Saved discoveries">
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
