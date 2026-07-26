@extends('layouts.tools', [
    'mainClass' => 'tools-shell__main--overview',
])

@php
    $id = (string) ($item['id'] ?? '');
    $termEn = (string) ($item['term']['en'] ?? $id);
    $termDe = (string) ($item['term']['de'] ?? $termEn);
    $defEn = (string) ($item['definition']['en'] ?? '');
    $defDe = (string) ($item['definition']['de'] ?? $defEn);
    $categoryId = is_string($item['category'] ?? null) ? $item['category'] : '';
    $categoryLabel = $categories[$categoryId] ?? ['de' => $categoryId, 'en' => $categoryId];
    $catEn = $categoryLabel['en'] ?? $categoryId;
    $catDe = $categoryLabel['de'] ?? $catEn;
    $aliases = is_array($item['aliases'] ?? null) ? $item['aliases'] : [];
@endphp

@section('title', $termEn.' — Glossary — '.config('app.name'))
@section('meta_description', $defEn !== '' ? $defEn : 'Governance glossary term: '.$termEn)

@section('content')
    <div class="tools-content tools-content--glossary-detail">
        <nav class="glossary-detail__nav" aria-label="Breadcrumb">
            <a href="{{ locale_route('glossary.index') }}" data-i18n="glossary.backToIndex">← Glossary</a>
        </nav>

        <header class="glossary-detail__header">
            <p class="glossary-detail__category" data-text-de="{{ $catDe }}" data-text-en="{{ $catEn }}">{{ $catEn }}</p>
            <h1 class="tools-page-title" data-text-de="{{ $termDe }}" data-text-en="{{ $termEn }}">{{ $termEn }}</h1>
            @if (count($aliases) > 0)
                <p class="glossary-detail__aliases">
                    <span data-i18n="glossary.alsoKnownAs">Also known as</span>:
                    {{ implode(', ', $aliases) }}
                </p>
            @endif
        </header>

        <p class="glossary-detail__definition" data-text-de="{{ $defDe }}" data-text-en="{{ $defEn }}">{{ $defEn }}</p>

        @if (count($relatedLinks) > 0)
            <section class="glossary-detail__related" aria-labelledby="glossary-related-heading">
                <h2 id="glossary-related-heading" class="glossary-detail__related-title" data-i18n="glossary.relatedTitle">
                    Related
                </h2>
                <ul class="glossary-detail__related-list">
                    @foreach ($relatedLinks as $link)
                        <li>
                            <a href="{{ $link['href'] }}">
                                <span data-text-de="{{ $link['label']['de'] }}" data-text-en="{{ $link['label']['en'] }}">{{ $link['label']['en'] }}</span>
                                <span class="glossary-detail__related-kind" data-i18n="search.type.{{ $link['kind'] }}">{{ $link['kind'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
    </div>
@endsection
