@php
    static $sectionArtIndex = 0;
    $sectionArtIndex++;
    $uid = 'section-art-'.$sectionArtIndex;
@endphp

<div class="tools-section__art" aria-hidden="true">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 520" preserveAspectRatio="xMidYMid slice" focusable="false">
        <defs>
            <linearGradient id="{{ $uid }}-glow-a" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" class="tools-section__art-glow-start" />
                <stop offset="100%" class="tools-section__art-glow-end" />
            </linearGradient>
            <linearGradient id="{{ $uid }}-glow-b" x1="100%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" class="tools-section__art-glow-accent" />
                <stop offset="100%" class="tools-section__art-glow-end" />
            </linearGradient>
        </defs>

        <rect class="tools-section__art-glow" width="1440" height="520" fill="url(#{{ $uid }}-glow-a)" opacity="0.7" />
        <rect class="tools-section__art-glow" width="1440" height="520" fill="url(#{{ $uid }}-glow-b)" opacity="0.45" />

        <g class="tools-section__art-lines" fill="none" stroke-linecap="round">
            <path class="tools-section__art-curve tools-section__art-curve--accent" d="M-40 70 C 280 10, 560 130, 900 55 S 1320 20, 1500 95" />
            <path class="tools-section__art-curve tools-section__art-curve--primary" d="M-60 230 C 260 170, 540 310, 900 235 S 1340 180, 1520 280" />
            <path class="tools-section__art-curve tools-section__art-curve--muted" d="M-20 390 C 300 340, 620 460, 980 380 S 1380 330, 1500 430" />
            <path class="tools-section__art-curve tools-section__art-curve--accent" d="M980 -10 C 1080 90, 1160 180, 1240 320 S 1360 470, 1320 540" />
        </g>

        <g class="tools-section__art-mesh">
            <path d="M0 120 L1440 30" />
            <path d="M0 280 L1440 190" />
            <path d="M0 440 L1440 350" />
            <path d="M240 0 L120 520" />
            <path d="M720 0 L600 520" />
            <path d="M1200 0 L1080 520" />
        </g>

        <g class="tools-section__art-nodes">
            <circle class="tools-section__art-node tools-section__art-node--accent" cx="900" cy="55" r="3.2" />
            <circle class="tools-section__art-node tools-section__art-node--primary" cx="900" cy="235" r="2.8" />
            <circle class="tools-section__art-node tools-section__art-node--accent" cx="1240" cy="320" r="2.4" />
            <circle class="tools-section__art-node tools-section__art-node--muted" cx="300" cy="340" r="2.2" />
        </g>
    </svg>
</div>
