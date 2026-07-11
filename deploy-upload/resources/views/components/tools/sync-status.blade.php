@props([
    'id',
])

<p {{ $attributes->merge(['id' => $id, 'class' => 'pii-policy-sync-status', 'aria-live' => 'polite']) }}></p>
