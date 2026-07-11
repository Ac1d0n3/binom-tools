@props([
    'id',
])

<div {{ $attributes->merge(['id' => $id, 'class' => 'tools-validation-banner', 'hidden' => true, 'role' => 'alert']) }}></div>
