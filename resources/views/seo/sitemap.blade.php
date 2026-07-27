@extends('layouts.tools')

@section('title', 'Sitemap — ' . config('app.name'))
@section('meta_description', 'Browse the main sections of Binom Governance — hubs, governance, and legal pages.')

@push('head')
    <x-seo.hreflang />
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@section('content')
    <div class="tools-content tools-html-sitemap">
        <h1 class="tools-page-title" data-text-de="Sitemap" data-text-en="Sitemap">Sitemap</h1>
        <p
            class="tools-page-lead"
            data-text-de="Übersicht der wichtigsten Bereiche — zum Durchklicken, nicht die Maschinen-XML für Suchmaschinen."
            data-text-en="Overview of the main sections — for browsing, not the machine XML for search engines."
        >Overview of the main sections — for browsing, not the machine XML for search engines.</p>

        @foreach ($sections as $section)
            <section class="tools-section" aria-labelledby="sitemap-{{ $section['id'] }}">
                <h2
                    id="sitemap-{{ $section['id'] }}"
                    class="tools-section__title"
                    data-text-de="{{ $section['title']['de'] }}"
                    data-text-en="{{ $section['title']['en'] }}"
                >{{ $section['title']['en'] }}</h2>
                <ul class="tools-html-sitemap__list">
                    @foreach ($section['links'] as $link)
                        <li>
                            <a href="{{ $link['href'] }}">
                                <span data-text-de="{{ $link['label']['de'] }}" data-text-en="{{ $link['label']['en'] }}">{{ $link['label']['en'] }}</span>
                                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endforeach

        <section class="tools-section" aria-labelledby="sitemap-machines">
            <h2
                id="sitemap-machines"
                class="tools-section__title"
                data-text-de="Für Suchmaschinen"
                data-text-en="For search engines"
            >For search engines</h2>
            <p
                class="tools-about-body"
                data-text-de="Die XML-Sitemap ist für Crawler (Google, Bing). Menschen nutzen die Links oben."
                data-text-en="The XML sitemap is for crawlers (Google, Bing). People use the links above."
            >The XML sitemap is for crawlers (Google, Bing). People use the links above.</p>
            <div class="tools-about-actions">
                <a class="tools-btn tools-btn--ghost" href="{{ $xmlSitemapUrl }}">
                    <i class="fa-solid fa-code" aria-hidden="true"></i>
                    <span data-text-de="XML-Sitemap öffnen" data-text-en="Open XML sitemap">Open XML sitemap</span>
                </a>
            </div>
        </section>
    </div>
@endsection
