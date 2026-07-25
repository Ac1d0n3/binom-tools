@extends('layouts.tools')

@section('title', config('app.name'))
@section('meta_description', 'Governance help hub with Markdown stories, interactive tools, and i18n — cloneable and CMS-free.')

@section('content')
    <div class="tools-home">
        <x-tools.hero
            :hero-pills="$heroPills"
            :tools-overview-url="locale_route('tools.overview')"
        />

        <div class="tools-content">
            <section class="tools-section">
                <h2 class="tools-section__title" data-i18n="home.hubsTitle">Hubs</h2>
                <p class="tools-section__lead" data-i18n="home.hubsLead">
                    Entry points for stories, vendor resources, compliance, sprint planning, and interactive governance workflows.
                </p>
                <div class="tools-card-grid">
                    <x-tools.card
                        :href="locale_route('playbooks.index')"
                        title="Stories"
                        description="Step-by-step governance playbooks — from idea to implementation."
                        title-key="home.hub.stories.title"
                        description-key="home.hub.stories.description"
                        icon="fa-book-open"
                        accent="primary"
                        card-id="hub-stories"
                    />
                    <x-tools.card
                        :href="locale_route('resources.index')"
                        title="Resources"
                        description="Official help, governance, learning paths, cloud residency (GDPR) and compliance — filter by vendor, family, SaaS/Open Source or residency."
                        title-key="home.hub.resources.title"
                        description-key="home.hub.resources.description"
                        icon="fa-link"
                        accent="accent"
                        card-id="hub-resources"
                    />
                    <x-tools.card
                        :href="locale_route('suppliers.index')"
                        title="Suppliers"
                        description="Core fields, dimensions, PII/DSDR and standard measure templates per source — Salesforce, HubSpot, GA4."
                        title-key="home.hub.suppliers.title"
                        description-key="home.hub.suppliers.description"
                        icon="fa-database"
                        accent="primary"
                        card-id="hub-suppliers"
                    />
                    <x-tools.card
                        :href="locale_route('compliance.index')"
                        title="Compliance"
                        description="Frameworks and regulations for data, privacy, security and AI — plus a consultant certification roadmap."
                        title-key="home.hub.compliance.title"
                        description-key="home.hub.compliance.description"
                        icon="fa-scale-balanced"
                        accent="accent"
                        card-id="hub-compliance"
                    />
                    <x-tools.card
                        :href="locale_route('sprint-planner.index')"
                        title="Sprint Planner"
                        description="Plan BI and governance work from templates, attach tool exports, and turn findings into trackable tasks."
                        title-key="home.hub.sprintPlanner.title"
                        description-key="home.hub.sprintPlanner.description"
                        icon="fa-list-check"
                        accent="primary"
                        card-id="hub-sprint-planner"
                    />
                    <x-tools.card
                        :href="locale_route('tools.overview')"
                        title="Binom-Tools"
                        description="Interactive reference workflows — step by step, copy-paste ready."
                        title-key="home.hub.tools.title"
                        description-key="home.hub.tools.description"
                        icon="fa-screwdriver-wrench"
                        accent="primary"
                        card-id="hub-tools"
                    />
                </div>
            </section>

            <section class="tools-section">
                <h2 class="tools-section__title" data-i18n="home.storiesTitle">Governance stories</h2>
                <p class="tools-section__lead" data-i18n="home.storiesLead">
                    Playbooks on data governance topics — step by step, from idea to implementation.
                </p>
                <div class="tools-card-grid">
                    @foreach ($latestStories as $item)
                        <x-playbooks.card :item="$item" />
                    @endforeach
                    <x-tools.overview-card
                        :href="locale_route('playbooks.index')"
                        title-key="home.viewAllStories.title"
                        description-key="home.viewAllStories.description"
                        :count="$storyCount"
                        icon="fa-book-open"
                    />
                </div>
            </section>

            @if (count($featuredAiTools) > 0)
                <section class="tools-section">
                    <h2 class="tools-section__title" data-i18n="home.aiTitle">AI tools</h2>
                    <p class="tools-section__lead" data-i18n="home.aiLead">
                        Build prompts and sanitize them before sending to external AI tools.
                    </p>
                    <div class="tools-card-grid">
                        @foreach ($featuredAiTools as $item)
                            <x-tools.card
                                :href="locale_route($item['route'])"
                                :title="$item['label']['en']"
                                :description="$item['description']['en']"
                                :icon="$item['icon']"
                                :accent="$item['accent']"
                                :card-id="$item['id']"
                                :example="$item['example'] ?? false"
                                :dbt-badge="\App\Support\ToolsNav::showsDbtBadge($item)"
                                :platform-marks="\App\Support\ToolsNav::platformMarks($item)"
                            />
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="tools-section">
                <h2 class="tools-section__title" data-i18n="home.toolsTitle">Binom-Tools</h2>
                <p class="tools-section__lead" data-i18n="home.workflowsLead">
                    Interaktive Referenz-Workflows — Schritt für Schritt, copy-paste-fähig.
                </p>
                <div class="tools-card-grid">
                    @foreach ($latestTools as $item)
                        <x-tools.card
                            :href="locale_route($item['route'])"
                            :title="$item['label']['en']"
                            :description="$item['description']['en']"
                            :icon="$item['icon']"
                            :accent="$item['accent']"
                            :card-id="$item['id']"
                            :example="$item['example'] ?? false"
                            :dbt-badge="\App\Support\ToolsNav::showsDbtBadge($item)"
                            :platform-marks="\App\Support\ToolsNav::platformMarks($item)"
                        />
                    @endforeach
                    <x-tools.overview-card
                        :href="locale_route('tools.overview')"
                        title-key="home.viewAllTools.title"
                        description-key="home.viewAllTools.description"
                        :count="$toolCount"
                        icon="fa-screwdriver-wrench"
                    />
                </div>
            </section>

            @if (count($ecosystemItems) > 0)
                <section class="tools-section">
                    <h2 class="tools-section__title" data-i18n="home.ecosystemTitle">Ecosystem</h2>
                    <div class="tools-card-grid">
                        @foreach ($ecosystemItems as $item)
                            @php
                                $ecosystemHref = $item['id'] === 'binom-ngx'
                                    ? \App\Support\ToolLinks::BINOM_NGX_DOCS
                                    : ($item['href'] ?? $links[$item['href_key'] ?? ''] ?? '#');
                            @endphp
                            <x-tools.card
                                :href="$ecosystemHref"
                                :title="$item['title']"
                                :description="$item['description']['en']"
                                :meta="$item['meta']['en']"
                                :icon="$item['icon']"
                                :accent="$item['accent']"
                                :featured="$item['featured'] ?? false"
                                :external="$item['external'] ?? false"
                                :card-id="$item['id']"
                            />
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection
