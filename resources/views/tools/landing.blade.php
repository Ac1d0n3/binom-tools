@extends('layouts.tools')

@section('title', config('app.name'))
@section('meta_description', 'Governance help hub with hub advisor, radar, Markdown stories, resources, suppliers, compliance, sprint planner, and interactive tools — cloneable and CMS-free.')

@push('head')
    @if (count($metaKeywords) > 0)
        <meta name="keywords" content="{{ implode(', ', $metaKeywords) }}">
    @endif
@endpush

@section('content')
    <div class="tools-home">
        <x-tools.hero />

        <div class="tools-content">
            <section class="tools-section tools-section--band">
                <x-tools.section-band-art />
                <div class="tools-section__body">
                    <header class="tools-section__head">
                        <x-tools.section-title
                            title="Hubs"
                            title-key="home.hubsTitle"
                            title-de="Hubs"
                        />
                        <p class="tools-section__lead" data-hub-lead data-i18n="home.hubsLead">
                            Entry points for governance, stories, vendor resources, compliance, sprint planning, and the news radar.
                        </p>
                    </header>
                    <div class="tools-card-grid">
                        <x-tools.card
                            :href="locale_route('governance.index')"
                            title="Governance Hub"
                            description="Connects the other hubs into a guided discovery: clarify the decision, stack, sources, and risks — then turn them into plan, workflow, or report."
                            title-key="home.featuredGovernance.title"
                            description-key="home.featuredGovernance.description"
                            icon="fa-shield-halved"
                            accent="primary"
                            card-id="featured-governance"
                            :featured="true"
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
                            :hub="true"
                            :count="$hubCounts['tools']"
                            count-label-key="home.hub.tools.countLabel"
                        />
                        <x-tools.card
                            :href="locale_route('playbooks.index')"
                            title="Stories"
                            description="Step-by-step governance playbooks — from idea to implementation."
                            title-key="home.hub.stories.title"
                            description-key="home.hub.stories.description"
                            icon="fa-book-open"
                            accent="primary"
                            card-id="hub-stories"
                            :hub="true"
                            :count="$hubCounts['stories']"
                            count-label-key="home.hub.stories.countLabel"
                        />
                        <x-tools.card
                            :href="locale_route('governance.radar')"
                            title="Radar"
                            description="Curated monitor for governance news, policy changes, standards, and vendor updates."
                            title-key="home.hub.radar.title"
                            description-key="home.hub.radar.description"
                            icon="fa-satellite-dish"
                            accent="primary"
                            card-id="hub-radar"
                            :hub="true"
                            :count="$hubCounts['radar']"
                            count-label-key="home.hub.radar.countLabel"
                            :title-badge-en="data_get($radarUpdatedBadge, 'en')"
                            :title-badge-de="data_get($radarUpdatedBadge, 'de')"
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
                            :hub="true"
                            :count="$hubCounts['resources']"
                            count-label-key="home.hub.resources.countLabel"
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
                            :hub="true"
                            :count="$hubCounts['suppliers']"
                            count-label-key="home.hub.suppliers.countLabel"
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
                            :hub="true"
                            :count="$hubCounts['compliance']"
                            count-label-key="home.hub.compliance.countLabel"
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
                            :hub="true"
                            :count="$hubCounts['sprintPlanner']"
                            count-label-key="home.hub.sprintPlanner.countLabel"
                        />
                    </div>
                </div>
            </section>

            <section class="tools-section tools-section--band">
                <x-tools.section-band-art />
                <div class="tools-section__body">
                    <header class="tools-section__head">
                        <x-tools.section-title
                            title="Governance stories"
                            title-key="home.storiesTitle"
                            title-de="Governance-Stories"
                        />
                        <p class="tools-section__lead" data-hub-lead data-i18n="home.storiesLead">
                            Playbooks on data governance topics — step by step, from idea to implementation.
                        </p>
                    </header>
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
                        <x-tools.top-stories-card :stories="$topStories" />
                    </div>
                </div>
            </section>

            @if (count($featuredAiTools) > 0)
                <section class="tools-section tools-section--band">
                    <x-tools.section-band-art />
                    <div class="tools-section__body">
                        <header class="tools-section__head">
                            <x-tools.section-title
                                title="AI tools"
                                title-key="home.aiTitle"
                                title-de="AI-Tools"
                            />
                            <p class="tools-section__lead" data-hub-lead data-i18n="home.aiLead">
                                Build prompts and sanitize them before sending to external AI tools.
                            </p>
                        </header>
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
                            @if (! empty($landingQuote))
                                <x-tools.quote-card
                                    :quote="$landingQuote['quote']"
                                    :attribution="$landingQuote['attribution']"
                                />
                            @endif
                        </div>
                    </div>
                </section>
            @endif

            <section class="tools-section tools-section--band">
                <x-tools.section-band-art />
                <div class="tools-section__body">
                    <header class="tools-section__head">
                        <x-tools.section-title
                            title="Binom-Tools"
                            title-key="home.toolsTitle"
                            title-de="Binom-Tools"
                        />
                        <p class="tools-section__lead" data-hub-lead data-i18n="home.workflowsLead">
                            Interaktive Referenz-Workflows — Schritt für Schritt, copy-paste-fähig.
                        </p>
                    </header>
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
                </div>
            </section>

            @if (count($ecosystemItems) > 0)
                <section class="tools-section tools-section--band">
                    <x-tools.section-band-art />
                    <div class="tools-section__body">
                        <header class="tools-section__head">
                            <x-tools.section-title
                                title="Ecosystem"
                                title-key="home.ecosystemTitle"
                                title-de="Ökosystem"
                            />
                        </header>
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
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection
