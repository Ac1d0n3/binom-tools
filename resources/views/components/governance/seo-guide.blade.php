@props([
    'problemDe' => '',
    'problemEn' => '',
    'decisionDe' => '',
    'decisionEn' => '',
    'checklist' => [],
    'artifacts' => [],
    'tools' => [],
    'resources' => [],
    'playbooks' => [],
    'nextSteps' => [],
    'faqs' => [],
])

@php
    /**
     * @var list<array{de: string, en: string}> $checklist
     * @var list<array{de: string, en: string}> $artifacts
     * @var list<array{de: string, en: string, href?: string}> $tools
     * @var list<array{de: string, en: string, href?: string}> $resources
     * @var list<array{de: string, en: string, href?: string}> $playbooks
     * @var list<array{de: string, en: string, href?: string}> $nextSteps
     * @var list<array{qDe: string, qEn: string, aDe: string, aEn: string}> $faqs
     */
@endphp

<section {{ $attributes->class(['governance-seo-guide']) }} aria-label="Governance guide">
    <div class="governance-seo-guide__grid">
        <article class="governance-seo-guide__card">
            <h2 data-text-de="Problem" data-text-en="Problem">Problem</h2>
            <p data-text-de="{{ $problemDe }}" data-text-en="{{ $problemEn }}">{{ $problemEn }}</p>
        </article>
        <article class="governance-seo-guide__card">
            <h2 data-text-de="Entscheidung" data-text-en="Decision">Decision</h2>
            <p data-text-de="{{ $decisionDe }}" data-text-en="{{ $decisionEn }}">{{ $decisionEn }}</p>
        </article>
    </div>

    @if (count($checklist) > 0)
        <article class="governance-seo-guide__block">
            <h2 data-text-de="Checkliste" data-text-en="Checklist">Checklist</h2>
            <ul>
                @foreach ($checklist as $item)
                    <li data-text-de="{{ $item['de'] }}" data-text-en="{{ $item['en'] }}">{{ $item['en'] }}</li>
                @endforeach
            </ul>
        </article>
    @endif

    @if (count($artifacts) > 0)
        <article class="governance-seo-guide__block">
            <h2 data-text-de="Artefakte" data-text-en="Artifacts">Artifacts</h2>
            <ul>
                @foreach ($artifacts as $item)
                    <li data-text-de="{{ $item['de'] }}" data-text-en="{{ $item['en'] }}">{{ $item['en'] }}</li>
                @endforeach
            </ul>
        </article>
    @endif

    @if (count($tools) > 0)
        <article class="governance-seo-guide__block">
            <h2 data-text-de="Tools" data-text-en="Tools">Tools</h2>
            <ul class="governance-seo-guide__links">
                @foreach ($tools as $item)
                    <li>
                        @if (! empty($item['href']))
                            <a href="{{ $item['href'] }}" data-text-de="{{ $item['de'] }}" data-text-en="{{ $item['en'] }}">{{ $item['en'] }}</a>
                        @else
                            <span data-text-de="{{ $item['de'] }}" data-text-en="{{ $item['en'] }}">{{ $item['en'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </article>
    @endif

    @if (count($resources) > 0)
        <article class="governance-seo-guide__block">
            <h2 data-text-de="Ressourcen" data-text-en="Resources">Resources</h2>
            <ul class="governance-seo-guide__links">
                @foreach ($resources as $item)
                    <li>
                        @if (! empty($item['href']))
                            <a href="{{ $item['href'] }}" data-text-de="{{ $item['de'] }}" data-text-en="{{ $item['en'] }}">{{ $item['en'] }}</a>
                        @else
                            <span data-text-de="{{ $item['de'] }}" data-text-en="{{ $item['en'] }}">{{ $item['en'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </article>
    @endif

    @if (count($playbooks) > 0)
        <article class="governance-seo-guide__block">
            <h2 data-text-de="Playbooks" data-text-en="Playbooks">Playbooks</h2>
            <ul class="governance-seo-guide__links">
                @foreach ($playbooks as $item)
                    <li>
                        @if (! empty($item['href']))
                            <a href="{{ $item['href'] }}" data-text-de="{{ $item['de'] }}" data-text-en="{{ $item['en'] }}">{{ $item['en'] }}</a>
                        @else
                            <span data-text-de="{{ $item['de'] }}" data-text-en="{{ $item['en'] }}">{{ $item['en'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </article>
    @endif

    @if (count($nextSteps) > 0)
        <article class="governance-seo-guide__block governance-seo-guide__block--next">
            <h2 data-text-de="Nächster Schritt" data-text-en="Next step">Next step</h2>
            <ul class="governance-seo-guide__links">
                @foreach ($nextSteps as $item)
                    <li>
                        @if (! empty($item['href']))
                            <a href="{{ $item['href'] }}" data-text-de="{{ $item['de'] }}" data-text-en="{{ $item['en'] }}">{{ $item['en'] }}</a>
                        @else
                            <span data-text-de="{{ $item['de'] }}" data-text-en="{{ $item['en'] }}">{{ $item['en'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </article>
    @endif

    @if (count($faqs) > 0)
        <article class="governance-seo-guide__block" id="governance-faq">
            <h2 data-text-de="Häufige Fragen" data-text-en="FAQ">FAQ</h2>
            <div class="governance-seo-guide__faq">
                @foreach ($faqs as $faq)
                    <details>
                        <summary data-text-de="{{ $faq['qDe'] }}" data-text-en="{{ $faq['qEn'] }}">{{ $faq['qEn'] }}</summary>
                        <p data-text-de="{{ $faq['aDe'] }}" data-text-en="{{ $faq['aEn'] }}">{{ $faq['aEn'] }}</p>
                    </details>
                @endforeach
            </div>
        </article>
    @endif
</section>
