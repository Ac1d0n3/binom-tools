@php
    $bridgeId = (string) ($bridge['id'] ?? '');
    $kind = (string) ($bridge['kind'] ?? 'bridge');
    $tone = (string) ($bridge['tone'] ?? 'recommended');
    $titleEn = (string) ($bridge['title']['en'] ?? $bridgeId);
    $titleDe = (string) ($bridge['title']['de'] ?? $titleEn);
    $leadEn = (string) ($bridge['lead']['en'] ?? '');
    $leadDe = (string) ($bridge['lead']['de'] ?? $leadEn);
    $spans = is_array($bridge['spans'] ?? null) ? $bridge['spans'] : [];
    $whenEn = is_array($bridge['when']['en'] ?? null) ? $bridge['when']['en'] : [];
    $whenDe = is_array($bridge['when']['de'] ?? null) ? $bridge['when']['de'] : $whenEn;
    $keepEn = is_array($bridge['keepsSeparate']['en'] ?? null) ? $bridge['keepsSeparate']['en'] : [];
    $keepDe = is_array($bridge['keepsSeparate']['de'] ?? null) ? $bridge['keepsSeparate']['de'] : $keepEn;
    $toneI18n = match ($tone) {
        'caution' => 'roles.bridgeToneCaution',
        'accountability' => 'roles.bridgeToneAccountability',
        default => 'roles.bridgeToneRecommended',
    };
    $toneFallback = match ($tone) {
        'caution' => 'Caution',
        'accountability' => 'Accountability',
        default => 'Recommended',
    };
    $compact = (bool) ($compact ?? false);
@endphp
<article
    class="roles-bridge-card roles-bridge-card--{{ $tone }}{{ $kind === 'accountability' ? ' roles-bridge-card--accountability' : '' }}{{ $compact ? ' roles-bridge-card--compact' : '' }}"
    data-bridge-id="{{ $bridgeId }}"
    data-bridge-tone="{{ $tone }}"
>
    <header class="roles-bridge-card__header">
        <span class="roles-bridge-card__tone" data-i18n="{{ $toneI18n }}">{{ $toneFallback }}</span>
        <h3 class="roles-bridge-card__title" data-text-de="{{ $titleDe }}" data-text-en="{{ $titleEn }}">{{ $titleEn }}</h3>
    </header>
    @if ($leadEn !== '' || $leadDe !== '')
        <p class="roles-bridge-card__lead" data-text-de="{{ $leadDe }}" data-text-en="{{ $leadEn }}">{{ $leadEn }}</p>
    @endif
    @if (count($spans) > 0)
        <div class="roles-bridge-card__spans">
            <span class="roles-bridge-card__spans-label" data-i18n="roles.bridgeSpansLabel">Spans</span>
            <ul class="roles-bridge-card__span-list">
                @foreach ($spans as $span)
                    <li>
                        <a href="{{ $span['href'] }}">
                            <span data-text-de="{{ $span['label']['de'] }}" data-text-en="{{ $span['label']['en'] }}">{{ $span['label']['en'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (! $compact && (count($whenEn) > 0 || count($whenDe) > 0))
        <div class="roles-bridge-card__list-block">
            <h4 class="roles-bridge-card__list-title" data-i18n="roles.bridgeWhenTitle">When it fits</h4>
            <ul class="roles-bridge-card__bullets">
                @foreach ($whenEn as $i => $lineEn)
                    @php $lineDe = (string) ($whenDe[$i] ?? $lineEn); @endphp
                    <li data-text-de="{{ $lineDe }}" data-text-en="{{ $lineEn }}">{{ $lineEn }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (! $compact && (count($keepEn) > 0 || count($keepDe) > 0))
        <div class="roles-bridge-card__list-block">
            <h4 class="roles-bridge-card__list-title" data-i18n="roles.bridgeKeepsSeparateTitle">Keep separate</h4>
            <ul class="roles-bridge-card__bullets">
                @foreach ($keepEn as $i => $lineEn)
                    @php $lineDe = (string) ($keepDe[$i] ?? $lineEn); @endphp
                    <li data-text-de="{{ $lineDe }}" data-text-en="{{ $lineEn }}">{{ $lineEn }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</article>
