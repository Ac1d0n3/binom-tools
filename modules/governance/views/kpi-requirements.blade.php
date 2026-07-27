@extends('foundations.layouts.tools')

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

@section('title', 'KPI Requirements for Data Governance - ' . config('app.name'))
@section('meta_description', 'Public KPI requirements workflow: from stakeholder interviews and business questions to KPI cards, grain, source tables, and mart design.')

@push('head')
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="KPI Requirements - {{ config('app.name') }}">
    <meta property="og:description" content="From business question to KPI card, grain, sources, and mart design.">
    <script type="application/ld+json">
        {!! json_encode([
            chr(64).'context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => 'KPI Requirements for Data Governance',
            'description' => 'Public workflow from stakeholder interviews to KPI cards and mart design.',
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
    <div class="tools-content governance-landing">
        <x-governance.breadcrumbs :items="[
            ['label' => 'Governance', 'href' => locale_route('governance.index')],
            ['label' => 'KPI requirements', 'href' => locale_route('governance.kpi-requirements')],
        ]" />

        @include('governance::partials.www-nav')

        


        <header class="governance-landing__hero">
            <p class="governance-hub__eyebrow" data-text-de="KPI Workflow" data-text-en="KPI workflow">KPI workflow</p>
            <h1
                class="tools-page-title"
                data-text-de="KPI-Anforderungen sammeln: von der Geschäftsfrage zum Mart"
                data-text-en="Collect KPI requirements: from business question to mart"
            >Collect KPI requirements: from business question to mart</h1>
            <p
                class="tools-page-lead"
                data-hub-lead
                data-text-de="Öffentlicher Workflow für Stakeholder-Formulare und KPI-Definition: Grain, Owner, Quelle und nächste Tabellenentscheidungen — indexierbar erklärt, Arbeitsstände optional speicherbar."
                data-text-en="Public workflow for stakeholder forms and KPI definition: grain, owner, source, and next table decisions — explained for search, workstates optionally savable."
            >Public workflow for stakeholder forms and KPI definition: grain, owner, source, and next table decisions — explained for search, workstates optionally savable.</p>
            <div class="governance-hub__hero-actions">
                <a class="governance-hub__button governance-hub__button--primary" href="{{ locale_route('tools.kpi-requirements-intake') }}">
                    <i class="fa-solid fa-gauge-high" aria-hidden="true"></i>
                    <span data-text-de="KPI Intake öffnen" data-text-en="Open KPI intake">Open KPI intake</span>
                </a>
                <a class="governance-hub__button" href="{{ locale_route('governance.discovery-canvas') }}">
                    <i class="fa-solid fa-table-columns" aria-hidden="true"></i>
                    <span data-text-de="Discovery Canvas" data-text-en="Discovery canvas">Discovery canvas</span>
                </a>
            </div>
            <x-governance.author-byline compact />
        </header>

        <ol class="governance-landing__steps">
            <li>
                <strong data-text-de="Stakeholder & RACI" data-text-en="Stakeholders & RACI">Stakeholders & RACI</strong>
                <span data-text-de="Wer entscheidet, wer liefert Definitionen, wer konsumiert Reports?" data-text-en="Who decides, who supplies definitions, who consumes reports?">Who decides, who supplies definitions, who consumes reports?</span>
            </li>
            <li>
                <strong data-text-de="Business-Fragen & Report Inventory" data-text-en="Business questions & report inventory">Business questions & report inventory</strong>
                <span data-text-de="Welche Entscheidungen sollen besser werden, welche Reports existieren schon?" data-text-en="Which decisions should improve, which reports already exist?">Which decisions should improve, which reports already exist?</span>
            </li>
            <li>
                <strong data-text-de="KPI Cards" data-text-en="KPI cards">KPI cards</strong>
                <span data-text-de="Definition, Formel, Grain, Zeitlogik, Filter, Dimensionen, Owner, Beispiel." data-text-en="Definition, formula, grain, time logic, filters, dimensions, owner, example.">Definition, formula, grain, time logic, filters, dimensions, owner, example.</span>
            </li>
            <li>
                <strong data-text-de="Source Scope & Mart Design" data-text-en="Source scope & mart design">Source scope & mart design</strong>
                <span data-text-de="Tabellen, Skip-Hinweise, Facts/Dimensions und History-Bedarf ableiten." data-text-en="Derive tables, skip hints, facts/dimensions, and history needs.">Derive tables, skip hints, facts/dimensions, and history needs.</span>
            </li>
        </ol>

        <section class="governance-landing__tool-list" aria-labelledby="kpi-related-tools">
            <h2 id="kpi-related-tools" data-text-de="Passende Tools" data-text-en="Matching tools">Matching tools</h2>
            <ul class="governance-seo-guide__links">
                @foreach ($relatedTools as $tool)
                    @php
                        $labelEn = $tool['label']['en'] ?? ($tool['id'] ?? '');
                        $labelDe = $tool['label']['de'] ?? $labelEn;
                    @endphp
                    <li>
                        <a href="{{ locale_route($tool['route']) }}" data-text-de="{{ $labelDe }}" data-text-en="{{ $labelEn }}">{{ $labelEn }}</a>
                    </li>
                @endforeach
            </ul>
        </section>

        <x-governance.seo-guide
            problem-de="KPI-Definitionen sind unklar, Grain fehlt, Quellen und Marts werden zu früh gebaut."
            problem-en="KPI definitions are unclear, grain is missing, sources and marts are built too early."
            decision-de="Welche KPI-Karten sind freigabefähig und welche Tabellen folgen daraus?"
            decision-en="Which KPI cards are approval-ready and which tables follow?"
            :checklist="[
                ['de' => 'Owner und Akzeptanzbeispiel je KPI', 'en' => 'Owner and acceptance example per KPI'],
                ['de' => 'Grain und Zeitlogik festlegen', 'en' => 'Set grain and time logic'],
                ['de' => 'Quellen und Skip-Tabellen markieren', 'en' => 'Mark sources and skip tables'],
            ]"
            :artifacts="[
                ['de' => 'KPI Cards (CSV/Markdown)', 'en' => 'KPI cards (CSV/Markdown)'],
                ['de' => 'Mart Design Brief', 'en' => 'Mart design brief'],
            ]"
            :tools="[
                ['de' => 'KPI Requirements Intake', 'en' => 'KPI requirements intake', 'href' => locale_route('tools.kpi-requirements-intake')],
                ['de' => 'KPI Definition', 'en' => 'KPI definition', 'href' => locale_route('tools.kpi-definition')],
            ]"
            :resources="[
                ['de' => 'Supplier Library', 'en' => 'Supplier library', 'href' => locale_route('suppliers.index')],
            ]"
            :playbooks="[
                ['de' => 'Playbooks', 'en' => 'Playbooks', 'href' => locale_route('playbooks.index')],
            ]"
            :next-steps="[
                ['de' => 'KPI Intake starten', 'en' => 'Start KPI intake', 'href' => locale_route('tools.kpi-requirements-intake')],
                ['de' => 'Supplier Discovery', 'en' => 'Supplier discovery', 'href' => locale_route('governance.supplier-discovery')],
            ]"
            :faqs="$hubFaqs"
        />
    </div>
@endsection
