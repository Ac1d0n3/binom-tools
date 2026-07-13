@php
    $links = config('tools.links', []);
@endphp

<footer class="tools-site-bar">
    <div class="tools-site-bar__inner">
        <div class="tools-site-bar__meta">
            <span class="tools-site-bar__copyright" data-i18n="footer.copyright">
                © {{ date('Y') }} Binom Governance
            </span>
            <x-tools.release-meta variant="footer" />
        </div>
        <nav class="tools-site-bar__links" aria-label="Meta navigation">
            <a
                class="tools-site-bar__link"
                href="{{ locale_route('about.show') }}"
                data-i18n="footer.about"
            >
                About
            </a>
            <span class="tools-site-bar__sep" aria-hidden="true">·</span>
            <a
                class="tools-site-bar__link"
                href="{{ locale_route('legal.impressum') }}"
                data-i18n="footer.impressum"
            >
                Legal Notice
            </a>
            <span class="tools-site-bar__sep" aria-hidden="true">·</span>
            <a
                class="tools-site-bar__link"
                href="{{ locale_route('legal.privacy') }}"
                data-i18n="footer.privacy"
            >
                Privacy
            </a>
            <span class="tools-site-bar__sep" aria-hidden="true">·</span>
            <a
                class="tools-site-bar__link"
                href="{{ $links['website'] ?? 'https://binom.net' }}"
                target="_blank"
                rel="noopener noreferrer"
                data-i18n="footer.website"
            >
                binom.net
            </a>
            <span class="tools-site-bar__sep" aria-hidden="true">·</span>
            <a
                class="tools-site-bar__link"
                href="{{ \App\Support\ToolLinks::BINOM_NGX_DOCS }}"
                target="_blank"
                rel="noopener noreferrer"
                data-i18n="footer.binomNgx"
            >
                binom-ngx Docs
            </a>
        </nav>
    </div>
</footer>
