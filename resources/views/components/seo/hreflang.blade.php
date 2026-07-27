@php
    use App\Support\LocaleSwitch;

    $urls = LocaleSwitch::urls();
@endphp

<link rel="alternate" hreflang="en" href="{{ $urls['en'] }}">
<link rel="alternate" hreflang="de" href="{{ $urls['de'] }}">
<link rel="alternate" hreflang="x-default" href="{{ $urls['en'] }}">
