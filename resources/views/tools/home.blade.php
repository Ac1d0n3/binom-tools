@extends('layouts.tools')

@section('title', config('app.name'))

@section('content')
    <div class="tools-home">
        <x-tools.hero
            :hero-pills="$heroPills"
            :binom-ngx-docs-url="$links['binom_ngx_docs'] ?? '#'"
        />

        <div class="tools-content">
            <section id="workflow-examples" class="tools-section">
                <h2 class="tools-section__title" data-i18n="home.workflowsTitle">Workflow examples</h2>
                <p class="tools-section__lead" data-i18n="home.workflowsLead"></p>
                <div class="tools-card-grid">
                    @foreach ($navItems as $item)
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
                </div>
            </section>

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
        </div>
    </div>
@endsection
