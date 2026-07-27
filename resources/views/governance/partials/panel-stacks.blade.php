<p
    class="tools-section__lead"
    data-hub-lead
    data-text-de="Pro Stack typische Komponenten und drei Tools zum Start — kein Vendor-Verzeichnis."
    data-text-en="Typical components per stack and three start tools — not a vendor directory."
>Typical components per stack and three start tools — not a vendor directory.</p>

<div class="governance-landing__stack-grid">
    @foreach ($stackCards as $stack)
        @php
            $labelEn = $stack['label']['en'] ?? $stack['id'];
            $labelDe = $stack['label']['de'] ?? $labelEn;
            $descEn = $stack['description']['en'] ?? '';
            $descDe = $stack['description']['de'] ?? $descEn;
        @endphp
        <article class="governance-landing__stack-card" id="stack-{{ $stack['id'] }}">
            <h3 data-text-de="{{ $labelDe }}" data-text-en="{{ $labelEn }}">{{ $labelEn }}</h3>
            <p data-text-de="{{ $descDe }}" data-text-en="{{ $descEn }}">{{ $descEn }}</p>
            @if (! empty($stack['products']))
                <p class="governance-landing__stack-products">
                    <span data-text-de="Typische Tools" data-text-en="Typical tools">Typical tools</span>:
                    {{ implode(', ', $stack['products']) }}
                </p>
            @endif
            <p class="governance-hub__soft-label" data-text-de="Start mit diesen 3 Tools" data-text-en="Start with these 3 tools">Start with these 3 tools</p>
            <x-governance.tool-cards :tools="$stack['startTools']" />
        </article>
    @endforeach
</div>
