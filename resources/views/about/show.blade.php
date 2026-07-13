@extends('layouts.tools')

@section('title', 'About — ' . config('app.name'))

@section('content')
    <div class="tools-content">
        <h1 class="tools-page-title" data-i18n="about.title">About binom-tools</h1>
        <p class="tools-page-lead" data-i18n="about.lead">
            Background on the project — what it is and where content and visuals come from.
        </p>

        <section class="tools-section">
            <h2 class="tools-section__title" data-i18n="about.project.title">What is binom-tools?</h2>
            <p class="tools-about-body" data-i18n="about.project.body">
                binom-tools is an open-source hobby project: a governance help hub with Markdown stories and interactive reference tools — not a commercial product.
            </p>
        </section>

        <section class="tools-section">
            <h2 class="tools-section__title" data-i18n="about.stories.title">Stories</h2>
            <p class="tools-about-body" data-i18n="about.stories.body">
                The playbooks offer a general introduction to governance and the worlds around it — from data platforms and BI to processes and the topics that matter in practice. It is knowledge collected over the years: experience, models, and ideas to explore, not a finished handbook.
            </p>
        </section>

        <section class="tools-section">
            <h2 class="tools-section__title" data-i18n="about.tools.title">Tools</h2>
            <p class="tools-about-body" data-i18n="about.tools.body">
                Interactive reference workflows make ideas from the stories practical — step by step, copy-paste ready for your warehouse or governance setup.
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
                The project benefits from exchange. Feedback, suggestions, and improvements are welcome on GitHub.
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
