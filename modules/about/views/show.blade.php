@extends('foundations.layouts.tools')

@section('title', 'About — ' . config('app.name'))
@section('meta_description', 'About Binom Governance — open-source governance help hub by Thomas Lindackers: orientation, tools, and evidence. Not a commercial product.')

@push('head')
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="About — {{ config('app.name') }}">
    <meta property="og:description" content="Open-source governance help hub by Thomas Lindackers — orientation, tools, and evidence.">
    <script type="application/ld+json">
        {!! json_encode([
            chr(64).'context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => 'About Binom Governance',
            'description' => 'Open-source governance help hub by Thomas Lindackers: orientation, tools, and evidence.',
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
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <div class="tools-content">
        <h1 class="tools-page-title" data-i18n="about.title">About binom-tools</h1>
        <p class="tools-page-lead" data-hub-lead data-i18n="about.lead">
            Governance help hub for orientation, questions, tools, and evidence — by Thomas Lindackers.
        </p>

        <section class="tools-section">
            <h2 class="tools-section__title" data-i18n="about.project.title">What is binom-tools?</h2>
            <p class="tools-about-body" data-i18n="about.project.body">
                binom-tools is an open-source project by Thomas Lindackers: a usable governance help hub with Markdown stories and interactive reference workflows — not a commercial product and not legal advice.
            </p>
        </section>

        <section class="tools-section">
            <h2 class="tools-section__title" data-i18n="about.stories.title">Stories</h2>
            <p class="tools-about-body" data-i18n="about.stories.body">
                The playbooks offer a general introduction to governance and the worlds around it — from data platforms and BI to processes and the topics that matter in practice. It is knowledge collected over the years: experience, models, and ideas to explore, not a finished handbook.
            </p>
        </section>

        <section class="tools-section">
            <h2 class="tools-section__title" data-i18n="about.governanceWhy.title">Why governance?</h2>
            <p class="tools-about-body" data-i18n="about.governanceWhy.body">
                Data governance starts with practical decisions: which stack, which source, which KPI grain, which PII controls. Binom Governance is a help hub by Thomas Lindackers — orientation first, then tools and evidence — not a vendor directory and not legal advice.
            </p>
            <div class="tools-about-actions">
                <a class="tools-btn tools-btn--ghost" href="{{ locale_route('governance.index') }}">
                    <i class="fa-solid fa-compass" aria-hidden="true"></i>
                    <span data-i18n="about.governanceWhy.cta">Open Governance Hub</span>
                </a>
                <a class="tools-btn tools-btn--ghost" href="{{ config('playbooks.author_url', 'https://binom.net') }}" rel="author noopener noreferrer" target="_blank">
                    <span data-i18n="about.governanceWhy.author">Thomas Lindackers on binom.net</span>
                </a>
            </div>
        </section>

        <section class="tools-section">
            <h2 class="tools-section__title" data-i18n="about.signature.title">Signature models</h2>
            <h3 class="tools-section__subtitle" data-i18n="about.signature.ladderTitle">Decision Ladder</h3>
            <p class="tools-about-body" data-i18n="about.signature.ladderBody">
                Decision Ladder: orientation → questions → tools → evidence. Entry is the Governance Advisor — not another decision page.
            </p>
            <h3 class="tools-section__subtitle" data-i18n="about.signature.collectTitle">Collect Infos 8</h3>
            <p class="tools-about-body" data-i18n="about.signature.collectBody">
                Collect Infos 8 is the named scaffold for the eight Discovery Canvas steps: from stakeholders and KPI cards through source scope, PII and DQ to mart design brief and decision brief.
            </p>
            <h3 class="tools-section__subtitle" data-i18n="about.signature.evidenceTitle">Evidence Loop</h3>
            <p class="tools-about-body" data-i18n="about.signature.evidenceBody">
                Evidence Loop: decision → control → evidence. If a piece is missing, the metric stays untrusted — see Missing Pieces of Trusted Metrics.
            </p>
            <p class="tools-about-body" data-i18n="about.signature.oneLiners">
                Platform Starting Point: choose the entry by cloud/BI/catalog fit, not by feature list. Source Load First: load the source that carries owner, grain, and pilot outcome fastest.
            </p>
            <p class="tools-about-body" data-i18n="about.signature.pillars">
                The 8 Pillars stay the practical scaffold — DMBOK/CDMP are domain language, not the site structure.
            </p>
            <div class="tools-about-actions">
                <a class="tools-btn tools-btn--ghost" href="{{ locale_route('governance.discovery-canvas') }}">
                    <span data-text-de="Collect Infos 8 öffnen" data-text-en="Open Collect Infos 8">Open Collect Infos 8</span>
                </a>
                <a class="tools-btn tools-btn--ghost" href="{{ locale_route('playbooks.show', ['slug' => 'eight-pillars']) }}">
                    <span data-text-de="8 Pillars" data-text-en="8 Pillars">8 Pillars</span>
                </a>
                <a class="tools-btn tools-btn--ghost" href="{{ locale_route('playbooks.show', ['slug' => 'missing-pieces-trusted-metrics']) }}">
                    <span data-text-de="Evidence Loop / Missing Pieces" data-text-en="Evidence Loop / Missing Pieces">Evidence Loop / Missing Pieces</span>
                </a>
            </div>
        </section>

        <section class="tools-section">
            <h2 class="tools-section__title" data-i18n="about.tools.title">Governance</h2>
            <p class="tools-about-body" data-i18n="about.tools.body">
                Interactive reference workflows make ideas from the stories practical — step by step, copy-paste ready for your warehouse or governance setup. Example path: advisor → demo workspace → report.
            </p>
        </section>

        <section class="tools-section">
            <h2 class="tools-section__title" data-i18n="about.visuals.title">Visuals</h2>
            <p class="tools-about-body" data-i18n="about.visuals.body">
                Diagrams and illustrations for playbook examples are created with AI, aligned with a consistent corporate design so stories stay readable and comparable.
            </p>
        </section>

        <section class="tools-section">
            <h2 class="tools-section__title" data-i18n="about.feedback.title">Feedback</h2>
            <p class="tools-about-body" data-i18n="about.feedback.body">
                Feedback, corrections, and improvements are welcome via GitHub issues and pull requests.
            </p>
            <div class="tools-about-actions">
                @if ($repositoryUrl)
                    <a
                        href="{{ $repositoryUrl }}"
                        class="tools-btn tools-btn--ghost"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <i class="fa-brands fa-github" aria-hidden="true"></i>
                        <span data-i18n="about.feedback.github">Issues &amp; pull requests on GitHub</span>
                    </a>
                @endif
            </div>
        </section>

        <footer class="tools-about-footer">
            <x-tools.release-meta variant="inline" />
            <x-tools.repo-clone-link />
        </footer>
    </div>
@endsection
