@extends('layouts.tools')

@section('title', config('app.name'))

@section('content')
    <div class="tools-home">
        <x-tools.hero
            :hero-pills="$heroPills"
            :tools-overview-url="route('tools.overview')"
            :binom-ngx-docs-url="$links['binom_ngx_docs'] ?? 'https://ngx-docs.binom.net'"
        />

        <div class="tools-content">
            <section class="tools-section">
                <h2 class="tools-section__title" data-i18n="home.toolsTitle">Tools</h2>
                <p class="tools-section__lead" data-i18n="home.workflowsLead">
                    Interaktive Referenz-Workflows — Schritt für Schritt, copy-paste-fähig.
                </p>
                <div class="tools-card-grid">
                    @foreach ($latestTools as $item)
                        <x-tools.card
                            :href="route($item['route'])"
                            :title="$item['label']['en']"
                            :description="$item['description']['en']"
                            :icon="$item['icon']"
                            :accent="$item['accent']"
                            :card-id="$item['id']"
                            :example="$item['example'] ?? false"
                        />
                    @endforeach
                    <x-tools.overview-card
                        :href="route('tools.overview')"
                        title-key="home.viewAllTools.title"
                        description-key="home.viewAllTools.description"
                        :count="$toolCount"
                        icon="fa-screwdriver-wrench"
                    />
                </div>
            </section>

            <section class="tools-section">
                <h2 class="tools-section__title" data-i18n="home.storiesTitle">Stories</h2>
                <p class="tools-section__lead" data-i18n="home.storiesLead">
                    Schritt-für-Schritt-Governance-Guides — von der Idee bis zur Umsetzung.
                </p>
                <div class="tools-card-grid">
                    @foreach ($latestStories as $item)
                        <x-playbooks.card :item="$item" />
                    @endforeach
                    <x-tools.overview-card
                        :href="route('playbooks.index')"
                        title-key="home.viewAllStories.title"
                        description-key="home.viewAllStories.description"
                        :count="$storyCount"
                        icon="fa-book-open"
                    />
                </div>
            </section>

            @if (count($ecosystemItems) > 0)
                <section class="tools-section">
                    <h2 class="tools-section__title" data-i18n="home.ecosystemTitle">Ecosystem</h2>
                    <div class="tools-card-grid">
                        @foreach ($ecosystemItems as $item)
                            <x-tools.card
                                :href="$links[$item['href_key']] ?? '#'"
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
