@extends('layouts.tools')

@section('title', 'Impressum — ' . config('app.name'))

@section('content')
    <div class="tools-content">
        <h1 class="tools-page-title" data-i18n="legal.impressum.title">Impressum</h1>
        <p class="tools-page-lead" data-i18n="legal.impressum.summary">Angaben gemäß § 5 TMG</p>

        <section class="tools-section">
            <h2 class="tools-section__title" data-i18n="legal.impressum.provider.title">Anbieter</h2>
            <p class="tools-impressum-body" data-i18n="legal.impressum.provider.body">Thomas Lindackers
Vollckmarstr 28
45219 Essen
Deutschland

E-Mail: support@governance.binom.net</p>
        </section>

        <section class="tools-section">
            <h2 class="tools-section__title" data-i18n="legal.impressum.responsible.title">Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV</h2>
            <p class="tools-impressum-body" data-i18n="legal.impressum.responsible.body">Thomas Lindackers
Vollckmarstr 28
45219 Essen</p>
        </section>
    </div>
@endsection
