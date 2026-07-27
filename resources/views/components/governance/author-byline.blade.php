@props([
    'compact' => false,
])

<aside {{ $attributes->class(['governance-author-byline', $compact ? 'governance-author-byline--compact' : null]) }}>
    <p class="governance-author-byline__label" data-text-de="Kuratiert von" data-text-en="Curated by">Curated by</p>
    <p class="governance-author-byline__name">
        <a href="{{ config('playbooks.author_url', 'https://binom.net') }}" rel="author">
            {{ config('playbooks.default_author', 'Thomas Lindackers') }}
        </a>
    </p>
    <p
        class="governance-author-byline__bio"
        data-text-de="Praktische Data-Governance-Discovery: Stacks vergleichen, Quellen verstehen, KPI-Anforderungen sammeln und Artefakte für Plan und Workflow erzeugen."
        data-text-en="Practical data governance discovery: compare stacks, understand sources, collect KPI requirements, and produce artifacts for plans and workflows."
    >Practical data governance discovery: compare stacks, understand sources, collect KPI requirements, and produce artifacts for plans and workflows.</p>
    @unless ($compact)
        <p class="governance-author-byline__cta">
            <a class="governance-hub__button" href="{{ locale_route('governance.advisor') }}">
                <i class="fa-solid fa-compass" aria-hidden="true"></i>
                <span data-text-de="Governance Discovery starten" data-text-en="Start governance discovery">Start governance discovery</span>
            </a>
        </p>
    @endunless
</aside>
