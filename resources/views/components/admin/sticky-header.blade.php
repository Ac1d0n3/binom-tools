@props([
    'title' => null,
    'titleDe' => null,
    'titleEn' => null,
    'count' => null,
])

{{-- Page titles are intentionally omitted; browser tab + sidebar provide context. --}}
<div {{ $attributes->class(['admin-hub__sticky']) }}>
    @isset($search)
        <div class="admin-hub__sticky-search">{{ $search }}</div>
    @endisset
    @isset($actions)
        <div class="admin-hub__sticky-actions">{{ $actions }}</div>
    @endisset
    @if ($count !== null)
        <span
            class="admin-hub__count-badge"
            data-overview-result-count
            data-overview-count-mode="items"
            data-overview-count-badge
            aria-live="polite"
        >{{ (int) $count }}</span>
    @endif
</div>
