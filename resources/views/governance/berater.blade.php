@extends('layouts.tools', ['viteEntries' => ['resources/js/governance/hub-advisor.js']])

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

@section('title', 'Data Governance Advisor - ' . config('app.name'))
@section('meta_description', 'Guided online advisor by Thomas Lindackers: answer six questions, get matching tools, suppliers, stacks, and next artifacts — no login required.')

@push('head')
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="Data Governance Advisor - {{ config('app.name') }}">
    <meta property="og:description" content="Answer a short situation form and get matching governance tools, suppliers, and playbooks.">
    <script type="application/ld+json">
        {!! json_encode([
            chr(64).'context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => 'Data Governance Advisor',
            'description' => 'Guided online advisor by Thomas Lindackers for data governance decisions without login.',
            'url' => url()->current(),
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
            'breadcrumb' => [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Governance', 'item' => locale_route('governance.index')],
                    ['@type' => 'ListItem', 'position' => 3, 'name' => 'Advisor', 'item' => url()->current()],
                ],
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
    <div class="tools-content governance-hub governance-landing" data-governance-advisor>
        <script type="application/json" data-governance-advisor-config>
            {!! json_encode(['links' => $advisorLinks], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>

        <x-governance.breadcrumbs :items="[
            ['label' => 'Governance', 'href' => locale_route('governance.index')],
            ['label' => 'Advisor', 'href' => locale_route('governance.advisor')],
        ]" />

        @include('governance.partials.www-nav')

        


        <header class="governance-hub__hero governance-landing__hero">
            <div class="governance-hub__hero-copy">
                <p class="governance-hub__eyebrow" data-text-de="Online-Berater von Thomas Lindackers" data-text-en="Online advisor by Thomas Lindackers">Online advisor by Thomas Lindackers</p>
                <h1
                    class="tools-page-title governance-hub__title"
                    data-text-de="Data Governance beraten: erst fragen, dann Tools und Nachweise"
                    data-text-en="Advise data governance: ask first, then tools and evidence"
                >Advise data governance: ask first, then tools and evidence</h1>
                <p
                    class="tools-page-lead governance-hub__lead"
                    data-hub-lead
                    data-text-de="Sechs kurze Fragen zu Ausgangslage, Ziel, Domain und Platform. Danach: empfohlene Workflows, Tools, Supplier und Playbooks — ohne Login. Danach optional Demo-Workspace und Report als Beispielartefakte."
                    data-text-en="Six short questions on starting point, goal, domain, and platform. Then: recommended workflows, tools, suppliers, and playbooks — no sign-in. Optionally open the demo workspace and report as sample artifacts."
                >Six short questions on starting point, goal, domain, and platform. Then: recommended workflows, tools, suppliers, and playbooks — no sign-in. Optionally open the demo workspace and report as sample artifacts.</p>
            </div>
            <x-governance.author-byline compact />
        </header>

        @include('governance.partials.stack-builder-modal')
        @include('governance.partials.advisor-panel', ['standalone' => true])

        <x-governance.seo-guide
            problem-de="Du brauchst einen klaren Start, bevor du Stack, Quelle oder KPI-Modell baust."
            problem-en="You need a clear starting point before you build stack, source, or KPI model."
            decision-de="Welche Entscheidung steht an und welche Artefakte solltest du als Nächstes erzeugen?"
            decision-en="Which decision is pending and which artifacts should you produce next?"
            :checklist="[
                ['de' => 'Ausgangslage und Ziel wählen', 'en' => 'Choose starting point and goal'],
                ['de' => 'Domain und Ziel-Stack eingrenzen', 'en' => 'Narrow domain and target stack'],
                ['de' => 'Empfehlungen als Arbeitsreihenfolge nutzen', 'en' => 'Use recommendations as working order'],
            ]"
            :artifacts="[
                ['de' => 'Empfohlene Tool-Kette', 'en' => 'Recommended tool chain'],
                ['de' => 'Supplier- und Resource-Links', 'en' => 'Supplier and resource links'],
            ]"
            :tools="[
                ['de' => 'Governance Hub', 'en' => 'Governance hub', 'href' => locale_route('governance.index')],
                ['de' => 'Discovery Canvas', 'en' => 'Discovery canvas', 'href' => locale_route('governance.discovery-canvas')],
            ]"
            :resources="[
                ['de' => 'Vendor Resources', 'en' => 'Vendor resources', 'href' => locale_route('resources.index')],
            ]"
            :playbooks="[
                ['de' => 'Playbooks', 'en' => 'Playbooks', 'href' => locale_route('playbooks.index')],
            ]"
            :next-steps="[
                ['de' => 'Stacks vergleichen', 'en' => 'Compare stacks', 'href' => locale_route('governance.stacks')],
                ['de' => 'Supplier Discovery', 'en' => 'Supplier discovery', 'href' => locale_route('governance.supplier-discovery')],
            ]"
            :faqs="$hubFaqs"
        />
    </div>
@endsection
