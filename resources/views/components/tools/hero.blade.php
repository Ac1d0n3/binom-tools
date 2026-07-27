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
                Public online advisor for data governance: first the right question, then stories, glossary, learning paths, radar, and tools. Cloneable for your internal hub.
            </p>

            <div class="tools-hero__actions">
                <x-tools.repo-clone-link variant="primary" />
            </div>

            <p class="tools-hero__hint" data-i18n="home.hero.notice">
                Open source — cloneable as a starter for your internal hub.
            </p>

            <p class="tools-hero__attribution">
                <span data-i18n="home.hero.attribution">Public advisor by</span>
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
