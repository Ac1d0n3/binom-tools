@props([
    'title' => null,
    'titleDe' => null,
    'titleEn' => null,
])

{{-- Page titles are intentionally omitted; browser tab + sidebar provide context. --}}
<div {{ $attributes->class(['admin-hub__sticky']) }}>
    @isset($search)
        <div class="admin-hub__sticky-search">{{ $search }}</div>
    @endisset
    @isset($actions)
        <div class="admin-hub__sticky-actions">{{ $actions }}</div>
    @endisset
</div>
