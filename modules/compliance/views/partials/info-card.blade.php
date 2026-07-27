@php
    $variant = $variant ?? 'default';
    $titleDe = $titleDe ?? ($titleEn ?? '');
    $titleEn = $titleEn ?? $titleDe;
    $detailDe = $detailDe ?? ($detailEn ?? '');
    $detailEn = $detailEn ?? $detailDe;
    $refDe = $refDe ?? ($refEn ?? '');
    $refEn = $refEn ?? $refDe;
    $href = is_string($href ?? null) ? $href : '';
    $activeLocale = current_locale();
    $tag = $href !== '' ? 'a' : 'article';
    $extraAttrs = $href !== ''
        ? ' href="'.e($href).'" target="_blank" rel="noopener noreferrer"'
        : '';
@endphp
<{{ $tag }}
    class="compliance-detail-card compliance-detail-card--{{ $variant }}{{ $href !== '' ? ' compliance-detail-card--link' : '' }}"
    {!! $extraAttrs !!}
>
    @if ($titleEn !== '' || $titleDe !== '')
        <h3
            class="compliance-detail-card__title"
            @if (! empty($titleI18n))
                data-i18n="{{ $titleI18n }}"
            @else
                data-text-de="{{ $titleDe }}"
                data-text-en="{{ $titleEn }}"
            @endif
        >{{ $activeLocale === 'de' ? $titleDe : $titleEn }}</h3>
    @endif

    @if ($refEn !== '' || $refDe !== '')
        <p
            class="compliance-detail-card__ref"
            data-text-de="{{ $refDe }}"
            data-text-en="{{ $refEn }}"
        >{{ $activeLocale === 'de' ? $refDe : $refEn }}</p>
    @endif

    @if ($detailEn !== '' || $detailDe !== '')
        @php
            $paragraphsDe = preg_split("/\n\s*\n/", trim((string) $detailDe)) ?: [];
            $paragraphsEn = preg_split("/\n\s*\n/", trim((string) $detailEn)) ?: [];
            $paragraphs = $activeLocale === 'de' ? $paragraphsDe : $paragraphsEn;
        @endphp
        <div class="compliance-detail-card__detail">
            @foreach ($paragraphs as $pi => $paragraph)
                @php
                    $pDe = $paragraphsDe[$pi] ?? $paragraph;
                    $pEn = $paragraphsEn[$pi] ?? $paragraph;
                @endphp
                <p data-text-de="{{ $pDe }}" data-text-en="{{ $pEn }}">
                    {{ $activeLocale === 'de' ? $pDe : $pEn }}
                </p>
            @endforeach
        </div>
    @endif

    @if ($href !== '')
        <span class="compliance-detail-card__arrow" aria-hidden="true">
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
        </span>
    @endif
</{{ $tag }}>
