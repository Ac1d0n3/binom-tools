@extends('foundations.layouts.tools')

@section('title', 'About — ' . config('app.name'))
@section('meta_description', 'About Binom Governance — public online advisor by Thomas Lindackers: orientation, tools, and evidence for data governance. Open source, not a commercial product.')

@section('content')
    <div class="tools-content">
        <h1 class="tools-page-title" data-i18n="about.title">About binom-tools</h1>
        <p class="tools-page-lead" data-hub-lead data-i18n="about.lead">
            Public online advisor for data governance — orientation, questions, tools, and evidence by Thomas Lindackers.
        </p>

        <section class="tools-section">
            <h2 class="tools-section__title" data-i18n="about.project.title">What is binom-tools?</h2>
            <p class="tools-about-body" data-i18n="about.project.body">
                binom-tools is an open-source project by Thomas Lindackers: a usable public online advisor and governance help hub with Markdown stories and interactive reference workflows — not a commercial product and not legal advice.
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
                Data governance starts with practical decisions: which stack, which source, which KPI grain, which PII controls. Binom Governance is a public online advisor by Thomas Lindackers — orientation first, then tools and evidence — not a vendor directory and not legal advice.
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
