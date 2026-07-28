@props([
    'kind' => 'edit',
    'label' => null,
])

@php
    $map = [
        'edit' => ['icon' => 'fa-pen', 'label' => 'Edit', 'variant' => 'tools-btn--secondary'],
        'delete' => ['icon' => 'fa-trash-can', 'label' => 'Delete', 'variant' => 'tools-btn--danger'],
        'view' => ['icon' => 'fa-eye', 'label' => 'View', 'variant' => 'tools-btn--secondary'],
        'approve' => ['icon' => 'fa-check', 'label' => 'Approve', 'variant' => 'tools-btn--primary'],
        'reject' => ['icon' => 'fa-xmark', 'label' => 'Reject', 'variant' => 'tools-btn--secondary'],
    ];
    $cfg = $map[$kind] ?? $map['edit'];
    $resolvedLabel = $label ?? $cfg['label'];
    $isLink = filled($attributes->get('href'));
    $buttonType = $attributes->get('type', 'button') ?: 'button';
@endphp

@if ($isLink)
    <a
        {{ $attributes->class(['tools-btn', 'tools-btn--small', 'admin-hub__icon-btn', $cfg['variant']]) }}
        aria-label="{{ $resolvedLabel }}"
        title="{{ $resolvedLabel }}"
    >
        <i class="fa-solid {{ $cfg['icon'] }}" aria-hidden="true"></i>
        <span class="sr-only">{{ $resolvedLabel }}</span>
    </a>
@else
    <button
        {{ $attributes->except('type')->class(['tools-btn', 'tools-btn--small', 'admin-hub__icon-btn', $cfg['variant']]) }}
        type="{{ $buttonType }}"
        aria-label="{{ $resolvedLabel }}"
        title="{{ $resolvedLabel }}"
    >
        <i class="fa-solid {{ $cfg['icon'] }}" aria-hidden="true"></i>
        <span class="sr-only">{{ $resolvedLabel }}</span>
    </button>
@endif
