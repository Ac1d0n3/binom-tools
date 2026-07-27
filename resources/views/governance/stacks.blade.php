@extends('layouts.tools')

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

@section('title', 'Data Governance Stacks - ' . config('app.name'))
@section('meta_description', 'Compare Modern Data Stack, Microsoft Fabric, Databricks Lakehouse, GCP Analytics, open source and EU sovereign paths — with governance questions and three start tools each.')

@push('head')
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="Data Governance Stacks - {{ config('app.name') }}">
    <meta property="og:description" content="Curated stack paths with governance questions and start tools.">
    <script type="application/ld+json">
        {!! json_encode([
            chr(64).'context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => 'Data Governance Stacks',
            'description' => 'Curated analytics stack paths with governance entry points.',
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
            ['label' => 'Stacks', 'href' => locale_route('governance.stacks')],
        ]" />

        @include('governance.partials.www-nav')

        


        <header class="governance-landing__hero">
            <p class="governance-hub__eyebrow" data-text-de="Stack-Vergleich" data-text-en="Stack comparison">Stack comparison</p>
            <h1
                class="tools-page-title"
                data-text-de="Data Governance Stacks: Pfade, Rollen und Start-Tools"
                data-text-en="Data governance stacks: paths, roles, and start tools"
            >Data governance stacks: paths, roles, and start tools</h1>
            <p
                class="tools-page-lead"
                data-hub-lead
                data-text-de="Kein Vendor-Verzeichnis. Pro Stack siehst du typische Komponenten, Governance-Fragen und drei Tools, mit denen du praktisch startest."
                data-text-en="Not a vendor directory. For each stack you see typical components, governance questions, and three tools to start practically."
            >Not a vendor directory. For each stack you see typical components, governance questions, and three tools to start practically.</p>
            <x-governance.author-byline compact />
        </header>

        <div class="governance-landing__stack-grid">
            @foreach ($stackCards as $stack)
                @php
                    $labelEn = $stack['label']['en'] ?? $stack['id'];
                    $labelDe = $stack['label']['de'] ?? $labelEn;
                    $descEn = $stack['description']['en'] ?? '';
                    $descDe = $stack['description']['de'] ?? $descEn;
                @endphp
                <article class="governance-landing__stack-card" id="stack-{{ $stack['id'] }}">
                    <h2 data-text-de="{{ $labelDe }}" data-text-en="{{ $labelEn }}">{{ $labelEn }}</h2>
                    <p data-text-de="{{ $descDe }}" data-text-en="{{ $descEn }}">{{ $descEn }}</p>
                    @if (! empty($stack['products']))
                        <p class="governance-landing__stack-products">
                            <strong data-text-de="Typische Tools" data-text-en="Typical tools">Typical tools</strong>:
                            {{ implode(', ', $stack['products']) }}
                        </p>
                    @endif
                    <h3 data-text-de="Start mit diesen 3 Tools" data-text-en="Start with these 3 tools">Start with these 3 tools</h3>
                    <ul class="governance-seo-guide__links">
                        @foreach ($stack['startTools'] as $tool)
                            @php
                                $tEn = $tool['label']['en'] ?? ($tool['id'] ?? '');
                                $tDe = $tool['label']['de'] ?? $tEn;
                            @endphp
                            <li>
                                <a href="{{ locale_route($tool['route']) }}" data-text-de="{{ $tDe }}" data-text-en="{{ $tEn }}">{{ $tEn }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <p>
                        <a class="governance-hub__button" href="{{ locale_route('resources.index') }}?stack={{ urlencode($stack['id']) }}">
                            <span data-text-de="Resources für diesen Stack" data-text-en="Resources for this stack">Resources for this stack</span>
                        </a>
                    </p>
                </article>
            @endforeach
        </div>

        <x-governance.seo-guide
            problem-de="Du musst einen Analytics-/Governance-Stack wählen oder vergleichen."
            problem-en="You need to choose or compare an analytics/governance stack."
            decision-de="Welcher Pfad passt zu Lizenz, Skills, Security und Governance-Reife?"
            decision-en="Which path fits license, skills, security, and governance maturity?"
            :checklist="[
                ['de' => 'Vorhandene Plattform und Skills notieren', 'en' => 'Note existing platform and skills'],
                ['de' => 'Governance-Fragen je Stack lesen', 'en' => 'Read governance questions per stack'],
                ['de' => 'Drei Start-Tools durchspielen', 'en' => 'Walk through three start tools'],
            ]"
            :artifacts="[
                ['de' => 'Stack-Entscheidungsskizze', 'en' => 'Stack decision sketch'],
                ['de' => 'Resource- und Zertifikatslinks', 'en' => 'Resource and certification links'],
            ]"
            :tools="[
                ['de' => 'Stack Advisor', 'en' => 'Stack advisor', 'href' => locale_route('tools.governance-stack-advisor')],
                ['de' => 'Online-Berater', 'en' => 'Online advisor', 'href' => locale_route('governance.advisor')],
            ]"
            :resources="[
                ['de' => 'Vendor Resources', 'en' => 'Vendor resources', 'href' => locale_route('resources.index')],
            ]"
            :playbooks="[
                ['de' => 'Playbooks', 'en' => 'Playbooks', 'href' => locale_route('playbooks.index')],
            ]"
            :next-steps="[
                ['de' => 'Berater mit Stack-Ziel öffnen', 'en' => 'Open advisor with stack goal', 'href' => locale_route('governance.advisor')],
                ['de' => 'Learning Paths', 'en' => 'Learning paths', 'href' => locale_route('learning-paths.index')],
            ]"
            :faqs="$hubFaqs"
        />
    </div>
@endsection
