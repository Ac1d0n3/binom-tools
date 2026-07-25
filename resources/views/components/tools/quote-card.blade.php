@props([
    'quote',
    'attribution' => null,
])

@php
    $quoteDe = is_array($quote) ? (string) ($quote['de'] ?? '') : (string) $quote;
    $quoteEn = is_array($quote) ? (string) ($quote['en'] ?? $quoteDe) : (string) $quote;
    $attrDe = is_array($attribution) ? (string) ($attribution['de'] ?? '') : (string) ($attribution ?? '');
    $attrEn = is_array($attribution) ? (string) ($attribution['en'] ?? $attrDe) : (string) ($attribution ?? '');

    // Dense binary field so the wallpaper fills any card size.
    $seed = [
        '01001001 00100111 01101101 00100000 01100100 01100001 01110100 01100001',
        '00100000 01100111 01101111 01110110 01100101 01110010 00100000 01101101',
        '01100101 00100000 01110111 01100101 01101100 01101100 00101110 00100000',
        '01000111 01001111 01010110 01000101 01010010 01001110 00100000 01001101',
        '01000101 01010100 01000001 00100000 01000100 01000001 01010100 01000001',
        '00110000 00110001 00110000 00110001 00110000 00110001 00110000 00110001',
        '00110001 00110000 00110001 00110000 00110001 00110000 00110001 00110000',
        '01101101 01100101 01110100 01100001 00100000 01100111 01101111 01110110',
    ];
    $binaryRows = [];
    for ($i = 0; $i < 24; $i++) {
        $binaryRows[] = $seed[$i % count($seed)];
    }
    $binaryText = implode("\n", $binaryRows)."\n".implode("\n", $binaryRows);
@endphp

<aside class="tools-card tools-card--quote" aria-label="Quote">
    <div class="tools-card__quote-binary" aria-hidden="true">
        <pre class="tools-card__quote-binary-text">{{ $binaryText }}</pre>
    </div>

    <div class="tools-card__quote-body">
        <div class="tools-card__quote-mark-col" aria-hidden="true">
            <i class="fa-solid fa-quote-left tools-card__quote-mark"></i>
        </div>
        <div class="tools-card__quote-content">
            <blockquote
                class="tools-card__quote-text"
                data-text-de="{{ $quoteDe }}"
                data-text-en="{{ $quoteEn }}"
            >{{ $quoteEn }}</blockquote>

            @if ($attrDe !== '' || $attrEn !== '')
                <p
                    class="tools-card__quote-attribution"
                    data-text-de="{{ $attrDe }}"
                    data-text-en="{{ $attrEn }}"
                >{{ $attrEn }}</p>
            @endif
        </div>
    </div>
</aside>
