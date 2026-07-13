@props([
    'heroPills' => [],
    'toolsOverviewUrl' => '#',
])

<section class="tools-hero">
    <div class="tools-hero__artwork" aria-hidden="true">
        <div class="tools-hero-artwork-wrap">
            {!! file_get_contents(public_path('images/binom-tools-hero-artwork.svg')) !!}
        </div>
    </div>

    <div class="tools-hero__inner">
        <div class="tools-hero__content">
            <h1 class="tools-hero__headline">
                <span data-i18n="home.hero.headline">Governance Help Hub</span>
                <span class="tools-hero__headline-accent" data-i18n="home.hero.headlineAccent">für Data-, BI- und Analytics-Teams.</span>
            </h1>
            <p class="tools-hero__tagline" data-i18n="home.hero.tagline">
                Stories zu Data Governance — von PII, Qualität und Lineage bis KPIs und Ownership. Plus interaktive Referenz-Tools, versionierbar wie Code.
            </p>
            <p class="tools-hero__notice" data-i18n="home.hero.notice">
                Klonbar als Starter-Template: eigene Stories, Tools und Branding für euren internen Help Hub.
            </p>

            <div class="tools-hero__actions">
                <a href="{{ $toolsOverviewUrl }}" class="tools-btn tools-btn--primary" data-i18n="home.hero.ctaWorkflows">
                    Interaktive Tools
                </a>
                <a
                    href="{{ \App\Support\ToolLinks::BINOM_NGX_DOCS }}"
                    class="tools-btn tools-btn--accent"
                    target="_blank"
                    rel="noopener noreferrer"
                    data-i18n="home.hero.ctaSdk"
                >
                    binom-ngx SDKs
                </a>
                <x-tools.repo-clone-link />
            </div>

            @if (count($heroPills) > 0)
                <ul class="tools-hero__pills" aria-label="Features">
                    @foreach ($heroPills as $pill)
                        <li class="tools-hero__pill">{{ $pill }}</li>
                    @endforeach
                </ul>
            @endif

            <p class="tools-hero__attribution">
                <span data-i18n="home.hero.attribution">Design concept by</span>
                <a href="https://binom.net" target="_blank" rel="noopener noreferrer">Thomas Lindackers</a>
            </p>
        </div>

        <div class="tools-hero__visual" aria-hidden="true">
            <div class="tools-hero-illustration-wrap">
                {!! file_get_contents(public_path('images/binom-tools-hero-illustration.svg')) !!}
            </div>
        </div>
    </div>
</section>
