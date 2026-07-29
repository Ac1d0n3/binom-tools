<section class="tools-hero">
    <div class="tools-hero__artwork" aria-hidden="true">
        <div class="tools-hero-artwork-wrap">
            {!! file_get_contents(public_path('images/binom-tools-hero-artwork.svg')) !!}
        </div>
    </div>

    <div class="tools-hero__inner">
        <div class="tools-hero__content">
            <h1 class="tools-hero__headline">
                <span data-i18n="home.hero.headline">Data Governance Hub</span>
                <span class="tools-hero__headline-accent" data-i18n="home.hero.headlineAccent">orientation first — then decisions, artifacts, and evidence.</span>
            </h1>
            <p class="tools-hero__tagline" data-i18n="home.hero.tagline">
                Governance help hub: first the right question, then stories, glossary, learning paths, radar, and tools. Cloneable for your internal hub.
            </p>

            <div class="tools-hero__actions">
                <x-tools.repo-clone-link variant="primary" />
                <a
                    class="tools-btn tools-btn--ghost"
                    href="{{ locale_route('governance.index') }}"
                    data-i18n="home.hero.ctaGovernance"
                >Open Governance Hub</a>
                <a
                    class="tools-btn tools-btn--ghost"
                    href="{{ locale_route('learning-paths.show', ['slug' => 'trusted-metrics']) }}"
                    data-i18n="home.hero.ctaBi"
                >Trust BI metrics</a>
                <a
                    class="tools-btn tools-btn--ghost"
                    href="{{ locale_route('suppliers.index') }}"
                    data-i18n="home.hero.ctaSources"
                >Connect a source</a>
            </div>

            <p class="tools-hero__hint" data-i18n="home.hero.notice">
                Open source — cloneable as a starter for your internal hub.
            </p>

            <p class="tools-hero__attribution">
                <span data-i18n="home.hero.attribution">By</span>
                <a href="{{ config('playbooks.author_url', 'https://binom.net') }}" target="_blank" rel="noopener noreferrer author">Thomas Lindackers</a>
                <span class="tools-hero__attribution-role" data-i18n="home.hero.attributionRole">Senior Consultant</span>
            </p>
        </div>

        <div class="tools-hero__visual" aria-hidden="true">
            <div class="tools-hero-illustration-wrap">
                {!! file_get_contents(public_path('images/binom-tools-hero-illustration.svg')) !!}
            </div>
        </div>
    </div>
</section>
