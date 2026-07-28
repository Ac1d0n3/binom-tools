@extends('foundations.layouts.tools', ['viteEntries' => ['modules/governance/js/discovery-canvas.js']])

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

@section('title', 'Governance Discovery Canvas - ' . config('app.name'))
@section('meta_description', 'Collect infos workflow: eight crawlable steps from stakeholders and KPIs to source scope, PII, DQ, mart design, and decision brief — with Markdown export.')

@push('head')
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="Governance Discovery Canvas - {{ config('app.name') }}">
    <meta property="og:description" content="Eight-step collect infos workflow with tool links and Markdown export.">
    <script type="application/ld+json">
        {!! json_encode([
            chr(64).'context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => 'Governance Discovery Canvas',
            'description' => 'Guided collect infos workflow for governance discovery workshops.',
            'url' => url()->current(),
            'author' => [
                '@type' => 'Person',
                'name' => 'Thomas Lindackers',
                'url' => config('playbooks.author_url', 'https://binom.net'),
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
    <div class="tools-content governance-landing" data-governance-discovery-canvas>
        <x-governance.breadcrumbs :items="[
            ['label' => 'Governance', 'href' => locale_route('governance.index')],
            ['label' => 'Discovery canvas', 'href' => locale_route('governance.discovery-canvas')],
        ]" />

        @include('governance::partials.www-nav')

        


        <header class="governance-landing__hero">
            <p class="governance-hub__eyebrow" data-text-de="Collect Infos Workflow" data-text-en="Collect infos workflow">Collect infos workflow</p>
            <h1
                class="tools-page-title"
                data-text-de="Governance Discovery Canvas: Infos für Stack, Quelle, KPI und Mart sammeln"
                data-text-en="Governance discovery canvas: collect infos for stack, source, KPI, and mart"
            >Governance discovery canvas: collect infos for stack, source, KPI, and mart</h1>
            <p
                class="tools-page-lead"
                data-hub-lead
                data-text-de="Acht Schritte als crawlbarer HTML-Inhalt. Jeder Schritt verlinkt bestehende Tools. Exportiere eine Markdown-Checkliste für Workshops — individuelle Arbeitsstände bleiben optional und nicht indexiert."
                data-text-en="Eight steps as crawlable HTML. Each step links existing tools. Export a Markdown checklist for workshops — individual workstates stay optional and non-indexed."
            >Eight steps as crawlable HTML. Each step links existing tools. Export a Markdown checklist for workshops — individual workstates stay optional and non-indexed.</p>
        </header>

        <ol class="governance-discovery-steps" data-discovery-steps>
            @foreach ($steps as $index => $step)
                @php
                    $titleEn = $step['title']['en'] ?? $step['id'];
                    $titleDe = $step['title']['de'] ?? $titleEn;
                    $leadEn = $step['lead']['en'] ?? '';
                    $leadDe = $step['lead']['de'] ?? $leadEn;
                    $outEn = $step['output']['en'] ?? '';
                    $outDe = $step['output']['de'] ?? $outEn;
                @endphp
                <li class="governance-discovery-steps__item" data-discovery-step="{{ $step['id'] }}">
                    <details class="governance-discovery-steps__details" @if ($index === 0) open @endif>
                        <summary class="governance-discovery-steps__summary">
                            <span class="governance-discovery-steps__num">{{ $index + 1 }}</span>
                            <span class="governance-discovery-steps__summary-copy">
                                <span
                                    class="governance-discovery-steps__title"
                                    data-discovery-title
                                    data-text-de="{{ $titleDe }}"
                                    data-text-en="{{ $titleEn }}"
                                >{{ $titleEn }}</span>
                                <span
                                    class="governance-discovery-steps__summary-lead"
                                    data-text-de="{{ $leadDe }}"
                                    data-text-en="{{ $leadEn }}"
                                >{{ $leadEn }}</span>
                            </span>
                            <span class="governance-discovery-steps__chevron" aria-hidden="true">
                                <i class="fa-solid fa-chevron-down"></i>
                            </span>
                        </summary>

                        <div class="governance-discovery-steps__body">
                            <p class="governance-discovery-steps__output">
                                <strong data-text-de="Output" data-text-en="Output">Output</strong>:
                                <span data-text-de="{{ $outDe }}" data-text-en="{{ $outEn }}">{{ $outEn }}</span>
                            </p>
                            <label class="governance-discovery-steps__note">
                                <span data-text-de="Workshop-Notiz" data-text-en="Workshop note">Workshop note</span>
                                <textarea rows="2" data-discovery-note placeholder="…"></textarea>
                            </label>
                            <div class="governance-discovery-steps__footer">
                                <label class="governance-discovery-steps__done">
                                    <input type="checkbox" data-discovery-done>
                                    <span data-text-de="Schritt erledigt" data-text-en="Step done">Step done</span>
                                </label>
                                @if (! empty($step['href']))
                                    <a class="governance-hub__button governance-hub__button--compact" href="{{ $step['href'] }}">
                                        <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                                        <span data-text-de="Tool öffnen" data-text-en="Open tool">Open tool</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </details>
                </li>
            @endforeach
        </ol>

        <section class="governance-discovery-export" aria-labelledby="discovery-export-title">
            <h2 id="discovery-export-title" data-text-de="Export" data-text-en="Export">Export</h2>
            <p
                data-text-de="Kopiere Markdown oder JSON für Workshop-Handouts. Exporte enthalten nur deine lokalen Notizen — keine Server-Speicherung."
                data-text-en="Copy Markdown or JSON for workshop handouts. Exports contain only your local notes — no server storage."
            >Copy Markdown or JSON for workshop handouts. Exports contain only your local notes — no server storage.</p>
            <div class="governance-discovery-export__actions">
                <button type="button" class="governance-hub__button governance-hub__button--primary governance-hub__button--compact" data-discovery-copy-md>
                    <span data-text-de="Markdown kopieren" data-text-en="Copy Markdown">Copy Markdown</span>
                </button>
                <button type="button" class="governance-hub__button governance-hub__button--compact" data-discovery-download-md>
                    <span data-text-de="Markdown laden" data-text-en="Download Markdown">Download Markdown</span>
                </button>
                <button type="button" class="governance-hub__button governance-hub__button--compact" data-discovery-copy-json>
                    <span data-text-de="JSON kopieren" data-text-en="Copy JSON">Copy JSON</span>
                </button>
                <button type="button" class="governance-hub__button governance-hub__button--compact" data-discovery-reset>
                    <span data-text-de="Zurücksetzen" data-text-en="Reset">Reset</span>
                </button>
            </div>
            <pre class="governance-discovery-export__preview" data-discovery-preview></pre>
            <p class="governance-discovery-export__status" data-discovery-status hidden></p>
        </section>

        <x-governance.seo-guide
            problem-de="Workshop-Infos für Governance-Entscheidungen liegen verstreut und werden nicht zu Artefakten."
            problem-en="Workshop infos for governance decisions are scattered and never become artifacts."
            decision-de="Welche acht Bausteine brauche ich, bevor Stack, Load und Mart entschieden werden?"
            decision-en="Which eight building blocks do I need before deciding stack, load, and mart?"
            :checklist="[
                ['de' => 'Schritte der Reihe nach abhaken', 'en' => 'Check off steps in order'],
                ['de' => 'Je Schritt das passende Tool öffnen', 'en' => 'Open the matching tool per step'],
                ['de' => 'Markdown für das Workshop-Handout exportieren', 'en' => 'Export Markdown for the workshop handout'],
            ]"
            :artifacts="[
                ['de' => 'governance-discovery.md', 'en' => 'governance-discovery.md'],
                ['de' => 'Notizen + erledigte Schritte als JSON', 'en' => 'Notes + completed steps as JSON'],
            ]"
            :tools="[
                ['de' => 'Online-Berater', 'en' => 'Online advisor', 'href' => locale_route('governance.advisor')],
                ['de' => 'KPI-Anforderungen', 'en' => 'KPI requirements', 'href' => locale_route('governance.kpi-requirements')],
            ]"
            :resources="[
                ['de' => 'Supplier Library', 'en' => 'Supplier library', 'href' => locale_route('suppliers.index')],
                ['de' => 'Vendor Resources', 'en' => 'Vendor resources', 'href' => locale_route('resources.index')],
            ]"
            :playbooks="[
                ['de' => 'Playbooks', 'en' => 'Playbooks', 'href' => locale_route('playbooks.index')],
            ]"
            :next-steps="[
                ['de' => 'Session im Hub speichern', 'en' => 'Save a session in the hub', 'href' => locale_route('governance.index')],
                ['de' => 'Demo Report ansehen', 'en' => 'View demo report', 'href' => locale_route('governance.sessions.demo-report')],
            ]"
            :faqs="$hubFaqs"
        />
    </div>
@endsection
