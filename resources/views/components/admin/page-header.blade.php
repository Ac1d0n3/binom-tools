@props([
    'title',
    'titleDe' => null,
    'titleEn' => null,
    'actions' => null,
])

<div class="sp-section__header admin-hub__toolbar">
    <h2 class="sp-section__title"
        @if ($titleDe && $titleEn)
            data-text-de="{{ $titleDe }}" data-text-en="{{ $titleEn }}"
        @endif
    >{{ $title }}</h2>
    @if ($actions)
        <div class="admin-hub__actions">{{ $actions }}</div>
    @endif
</div>
