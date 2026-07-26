@php
    use App\Support\Locale;

    $routeBase = Locale::routeBaseName(request()->route()?->getName());
    $showToolsPhoneGate = is_string($routeBase)
        && str_starts_with($routeBase, 'tools.')
        && $routeBase !== 'tools.landing';
@endphp

@if ($showToolsPhoneGate)
    <div
        class="tools-phone-gate"
        data-tools-phone-gate
        hidden
        role="region"
        aria-labelledby="tools-phone-gate-title"
    >
        <h1 class="tools-phone-gate__title" id="tools-phone-gate-title" data-i18n="tools.phoneGate.title">
            Tools from tablet onwards
        </h1>
        <p class="tools-phone-gate__lead" data-i18n="tools.phoneGate.lead">
            Interactive Binom-Tools are built for tablet and desktop screens. On phones, open Stories instead — they are designed to read well here.
        </p>
        <div class="tools-phone-gate__actions">
            <a class="tools-btn tools-btn--primary" href="{{ locale_route('playbooks.index') }}" data-i18n="tools.phoneGate.ctaStories">
                Open Stories
            </a>
            <a class="tools-btn" href="{{ locale_route('tools.landing') }}" data-i18n="tools.phoneGate.ctaHome">
                Back to home
            </a>
        </div>
    </div>
@endif
