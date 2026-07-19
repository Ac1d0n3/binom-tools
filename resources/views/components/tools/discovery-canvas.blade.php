@props([
    'copyCsv' => true,
])

<div class="discovery-canvas">
    <x-tools.discovery-ephemeral-banner />

    <div class="discovery-toolbar">
        <button type="button" class="tools-btn" data-add-row data-i18n="discovery.addRow">Add row</button>
    </div>

    <div data-discovery-table></div>

    <div data-discovery-extra></div>

    <section class="tools-panel discovery-export" aria-labelledby="discovery-export-title">
        <h2 id="discovery-export-title" class="discovery-check-section__title" data-i18n="discovery.exportPreview">
            Export preview
        </h2>
        <p class="discovery-export__hint" data-i18n="discovery.exportHint">
            Copy or download now — content is lost when you leave this page.
        </p>
        <div class="discovery-export__actions">
            <button type="button" class="tools-btn tools-btn--primary" data-copy-md data-i18n="discovery.copyMarkdown">Copy Markdown</button>
            <button type="button" class="tools-btn" data-download-md data-i18n="discovery.downloadMarkdown">Download .md</button>
            @if ($copyCsv)
                <button type="button" class="tools-btn" data-copy-csv data-i18n="discovery.copyCsv">Copy CSV</button>
                <button type="button" class="tools-btn" data-download-csv data-i18n="discovery.downloadCsv">Download .csv</button>
            @endif
            <button type="button" class="tools-btn tools-btn--ghost" data-clear data-i18n="discovery.clear">Reset</button>
        </div>
        <pre class="discovery-export__preview" data-export-preview></pre>
    </section>
</div>
