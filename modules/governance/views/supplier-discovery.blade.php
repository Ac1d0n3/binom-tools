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

@section('title', 'Supplier Discovery for Analytics - ' . config('app.name'))
@section('meta_description', 'Start from Salesforce, HubSpot, SAP, Workday, ServiceNow and more: which entities to load, what to skip, where PII sits, and which KPIs are plausible.')

@push('head')
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="Supplier Discovery - {{ config('app.name') }}">
    <meta property="og:description" content="From source systems to supplier library, PII checks, and mart candidates.">
    <script type="application/ld+json">
        {!! json_encode([
            chr(64).'context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => 'Supplier Discovery for Analytics',
            'description' => 'Entry points from source systems into the supplier library and governance tools.',
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
            ['label' => 'Supplier discovery', 'href' => locale_route('governance.supplier-discovery')],
        ]" />

        @include('governance::partials.www-nav')

        


        <header class="governance-landing__hero">
            <p class="governance-hub__eyebrow" data-text-de="Quelle → Modell" data-text-en="Source → model">Source → model</p>
            <h1
                class="tools-page-title"
                data-text-de="Supplier Discovery: von Quellsystem zu Entitäten, PII und KPIs"
                data-text-en="Supplier discovery: from source system to entities, PII, and KPIs"
            >Supplier discovery: from source system to entities, PII, and KPIs</h1>
            <p
                class="tools-page-lead"
                data-hub-lead
                data-text-de="Einstieg über CRM, ERP, HCM und Collaboration. Die Supplier Library sagt: Welche Entitäten laden? Was skippen? Wo ist PII? Welche KPIs sind plausibel?"
                data-text-en="Entry via CRM, ERP, HCM, and collaboration. The supplier library answers: which entities to load, what to skip, where PII sits, and which KPIs are plausible."
            >Entry via CRM, ERP, HCM, and collaboration. The supplier library answers: which entities to load, what to skip, where PII sits, and which KPIs are plausible.</p>
            <x-governance.author-byline compact />
        </header>

        <section class="governance-landing__supplier-grid" aria-labelledby="featured-suppliers-title">
            <h2 id="featured-suppliers-title" data-text-de="Häufige Quellsysteme" data-text-en="Common source systems">Common source systems</h2>
            <div class="governance-landing__supplier-cards">
                @foreach ($featuredSuppliers as $supplier)
                    @php
                        $id = (string) ($supplier['id'] ?? '');
                        $nameEn = is_array($supplier['label'] ?? null)
                            ? (string) ($supplier['label']['en'] ?? $id)
                            : (string) ($supplier['label'] ?? $id);
                        $nameDe = is_array($supplier['label'] ?? null)
                            ? (string) ($supplier['label']['de'] ?? $nameEn)
                            : $nameEn;
                        $href = $id !== '' && \Illuminate\Support\Facades\Route::has('suppliers.show')
                            ? locale_route('suppliers.show', ['slug' => $id])
                            : locale_route('suppliers.index');
                    @endphp
                    <a class="governance-landing__supplier-card" href="{{ $href }}">
                        <strong data-text-de="{{ $nameDe }}" data-text-en="{{ $nameEn }}">{{ $nameEn }}</strong>
                        <span data-text-de="Kernobjekte, Skip, PII, Standard-KPIs" data-text-en="Core objects, skip, PII, standard KPIs">Core objects, skip, PII, standard KPIs</span>
                    </a>
                @endforeach
            </div>
            <p>
                <a class="governance-hub__button" href="{{ locale_route('suppliers.index') }}">
                    <span data-text-de="Gesamte Supplier Library" data-text-en="Full supplier library">Full supplier library</span>
                </a>
            </p>
        </section>

        <section class="governance-landing__tool-list" aria-labelledby="supplier-next-tools">
            <h2 id="supplier-next-tools" data-text-de="Nächste Tools" data-text-en="Next tools">Next tools</h2>
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
            problem-de="Eine SaaS-Quelle soll analytisch angebunden werden, aber Ladeumfang und Risiken sind unklar."
            problem-en="A SaaS source should be connected for analytics, but load scope and risks are unclear."
            decision-de="Welche Entitäten lohnen sich, was wird geskippt, wo sitzt PII, welche KPIs folgen?"
            decision-en="Which entities are worth loading, what is skipped, where is PII, which KPIs follow?"
            :checklist="[
                ['de' => 'Supplier-Seite und Kernobjekte lesen', 'en' => 'Read supplier page and core objects'],
                ['de' => 'Skip- und PII-Hinweise markieren', 'en' => 'Mark skip and PII notes'],
                ['de' => 'Source Scope und Readiness-Check öffnen', 'en' => 'Open source scope and readiness check'],
            ]"
            :artifacts="[
                ['de' => 'Source Scope', 'en' => 'Source scope'],
                ['de' => 'PII/DSDR Review', 'en' => 'PII/DSDR review'],
            ]"
            :tools="[
                ['de' => 'Source Scope Builder', 'en' => 'Source scope builder', 'href' => locale_route('tools.source-scope-builder')],
                ['de' => 'PII/DSDR Readiness', 'en' => 'PII/DSDR readiness', 'href' => locale_route('tools.pii-dsdr-readiness-checker')],
            ]"
            :resources="[
                ['de' => 'Supplier Library', 'en' => 'Supplier library', 'href' => locale_route('suppliers.index')],
            ]"
            :playbooks="[
                ['de' => 'Playbooks', 'en' => 'Playbooks', 'href' => locale_route('playbooks.index')],
            ]"
            :next-steps="[
                ['de' => 'Discovery Canvas', 'en' => 'Discovery canvas', 'href' => locale_route('governance.discovery-canvas')],
                ['de' => 'KPI-Anforderungen', 'en' => 'KPI requirements', 'href' => locale_route('governance.kpi-requirements')],
            ]"
            :faqs="$hubFaqs"
        />
    </div>
@endsection
