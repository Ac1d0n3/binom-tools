<p
    class="tools-section__lead"
    data-hub-lead
    data-text-de="Acht Workshop-Schritte bis zum Decision Brief — Notizen lokal, Export als Markdown/JSON."
    data-text-en="Eight workshop steps to the decision brief — notes stay local, export as Markdown/JSON."
>Eight workshop steps to the decision brief — notes stay local, export as Markdown/JSON.</p>

<div data-governance-discovery-canvas>
    <ol class="governance-discovery-steps" data-discovery-steps>
        @foreach ($discoverySteps as $index => $step)
            @php
                $titleEn = $step['title']['en'] ?? $step['id'];
                $titleDe = $step['title']['de'] ?? $titleEn;
                $leadEn = $step['lead']['en'] ?? '';
                $leadDe = $step['lead']['de'] ?? $leadEn;
                $outEn = $step['output']['en'] ?? '';
                $outDe = $step['output']['de'] ?? $outEn;
            @endphp
            <li class="governance-discovery-steps__item" data-discovery-step="{{ $step['id'] }}">
                <details class="governance-discovery-steps__details" @if ($index === 0) open @endif>
                    <summary class="governance-discovery-steps__summary">
                        <span class="governance-discovery-steps__num">{{ $index + 1 }}</span>
                        <span class="governance-discovery-steps__summary-copy">
                            <span
                                class="governance-discovery-steps__title"
                                data-discovery-title
                                data-text-de="{{ $titleDe }}"
                                data-text-en="{{ $titleEn }}"
                            >{{ $titleEn }}</span>
                            <span
                                class="governance-discovery-steps__summary-lead"
                                data-text-de="{{ $leadDe }}"
                                data-text-en="{{ $leadEn }}"
                            >{{ $leadEn }}</span>
                        </span>
                        <span class="governance-discovery-steps__chevron" aria-hidden="true">
                            <i class="fa-solid fa-chevron-down"></i>
                        </span>
                    </summary>

                    <div class="governance-discovery-steps__body">
                        <p class="governance-discovery-steps__output">
                            <strong data-text-de="Output" data-text-en="Output">Output</strong>:
                            <span data-text-de="{{ $outDe }}" data-text-en="{{ $outEn }}">{{ $outEn }}</span>
                        </p>
                        <label class="governance-discovery-steps__note">
                            <span data-text-de="Workshop-Notiz" data-text-en="Workshop note">Workshop note</span>
                            <textarea rows="2" data-discovery-note placeholder="…"></textarea>
                        </label>
                        <div class="governance-discovery-steps__footer">
                            <label class="governance-discovery-steps__done">
                                <input type="checkbox" data-discovery-done>
                                <span data-text-de="Schritt erledigt" data-text-en="Step done">Step done</span>
                            </label>
                            @if (! empty($step['href']))
                                <a class="governance-hub__button governance-hub__button--compact" href="{{ $step['href'] }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                                    <span data-text-de="Tool öffnen" data-text-en="Open tool">Open tool</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </details>
            </li>
        @endforeach
    </ol>

    <section class="governance-discovery-export" aria-labelledby="discovery-export-title">
        <h3 id="discovery-export-title" class="governance-hub__soft-label" data-text-de="Export" data-text-en="Export">Export</h3>
        <p
            data-text-de="Nur lokale Notizen — keine Server-Speicherung."
            data-text-en="Local notes only — no server storage."
        >Local notes only — no server storage.</p>
        <div class="governance-discovery-export__actions">
            <button type="button" class="governance-hub__button governance-hub__button--primary governance-hub__button--compact" data-discovery-copy-md>
                <span data-text-de="Markdown kopieren" data-text-en="Copy Markdown">Copy Markdown</span>
            </button>
            <button type="button" class="governance-hub__button governance-hub__button--compact" data-discovery-download-md>
                <span data-text-de="Markdown laden" data-text-en="Download Markdown">Download Markdown</span>
            </button>
            <button type="button" class="governance-hub__button governance-hub__button--compact" data-discovery-copy-json>
                <span data-text-de="JSON kopieren" data-text-en="Copy JSON">Copy JSON</span>
            </button>
            <button type="button" class="governance-hub__button governance-hub__button--compact" data-discovery-reset>
                <span data-text-de="Zurücksetzen" data-text-en="Reset">Reset</span>
            </button>
        </div>
        <pre class="governance-discovery-export__preview" data-discovery-preview></pre>
        <p class="governance-discovery-export__status" data-discovery-status hidden></p>
    </section>
</div>
