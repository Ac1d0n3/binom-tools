@props([
    'title',
    'titleKey' => null,
    'titleDe' => null,
])

@php
    $titleEn = (string) $title;
    $titleDeResolved = trim((string) ($titleDe ?? '')) !== '' ? (string) $titleDe : $titleEn;

    $toBinary = static function (string $text): string {
        $bytes = unpack('C*', $text);
        if ($bytes === false || $bytes === []) {
            return '';
        }

        $chunks = [];
        foreach ($bytes as $byte) {
            $chunks[] = str_pad(decbin((int) $byte), 8, '0', STR_PAD_LEFT);
        }

        return implode(' ', $chunks);
    };

    $binaryEn = $toBinary($titleEn);
    $binaryDe = $toBinary($titleDeResolved);
@endphp

<div class="tools-section__title-row">
    <h2
        class="tools-section__title"
        @if ($titleKey)
            data-i18n="{{ $titleKey }}"
        @endif
    >{{ $titleEn }}</h2>
    <span
        class="tools-section__title-binary"
        aria-hidden="true"
        data-text-de="{{ $binaryDe }}"
        data-text-en="{{ $binaryEn }}"
    >{{ $binaryEn }}</span>
</div>
