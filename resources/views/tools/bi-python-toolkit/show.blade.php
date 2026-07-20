@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/bi-python-toolkit/index.js'],
])

@section('title', 'BI Python Toolkit — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        title-key="biPythonToolkit.pageTitle"
        lead-key="biPythonToolkit.pageLead"
        tool-id="bi-python-toolkit"
        app-id="bi-python-toolkit-app"
    >
        <x-tools.collapsible-info summary-key="discovery.howto.summary" :open="true">
            <p data-i18n="biPythonToolkit.howto.intro">
                Run BI exports locally, not in the browser. Download the script, place it in a Python project folder, run it against Qlik, Power BI or Tableau, then use the generated CSV, Markdown or plan JSON in your plan work.
            </p>
            <ol>
                <li data-i18n="biPythonToolkit.howto.step1">Prepare Python locally: create a folder, activate a virtual environment, install optional dependencies.</li>
                <li data-i18n="biPythonToolkit.howto.step2">Choose the script: KPI formulas or Qlik app/sheet inventory.</li>
                <li data-i18n="biPythonToolkit.howto.step3">Run the export and attach or copy the result into the relevant Sprint Planner task.</li>
            </ol>
            <p data-i18n="biPythonToolkit.howto.tip">
                The export is raw inventory, not the final governance decision. Review owners, business meaning, status and open questions before closing the task.
            </p>
            <p>
                <a href="{{ locale_route('playbooks.show', ['slug' => 'python-bi-export-setup']) }}" data-i18n="biPythonToolkit.setupStoryLink">
                    Python setup guide: install Python, initialize a folder, run the exports
                </a>
            </p>
        </x-tools.collapsible-info>

        <section class="tools-panel discovery-canvas" aria-labelledby="bi-python-purpose-title">
            <h2 id="bi-python-purpose-title" class="tools-panel__title" data-i18n="biPythonToolkit.purpose.title">
                Which script should I use?
            </h2>
            <div class="discovery-table-wrap">
                <table class="discovery-table">
                    <thead>
                        <tr>
                            <th data-i18n="biPythonToolkit.choice.script">Script</th>
                            <th data-i18n="biPythonToolkit.choice.use">Use it for</th>
                            <th data-i18n="biPythonToolkit.choice.output">Useful output</th>
                            <th data-i18n="biPythonToolkit.choice.next">Next step</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>bi_kpi_export.py</code></td>
                            <td data-i18n="biPythonToolkit.choice.kpi.use">KPI / measure formulas from Qlik, Power BI model.bim or Tableau workbook files.</td>
                            <td><code>plan-json</code>, <code>csv</code>, <code>markdown</code></td>
                            <td data-i18n="biPythonToolkit.choice.kpi.next">Load the result into the KPI Definition task and complete owner, grain, filters and status.</td>
                        </tr>
                        <tr>
                            <td><code>qlik_app_inventory.py</code></td>
                            <td data-i18n="biPythonToolkit.choice.inventory.use">Qlik Cloud app inventory and, optionally, sheet inventory including published/approved state.</td>
                            <td><code>csv</code>, <code>plan-json</code>, <code>markdown</code></td>
                            <td data-i18n="biPythonToolkit.choice.inventory.next">Use it in app-scope, impact or cleanup tasks to decide which apps/sheets need review.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="tools-panel discovery-canvas" aria-labelledby="bi-python-downloads-title">
            <h2 id="bi-python-downloads-title" class="tools-panel__title" data-i18n="biPythonToolkit.downloads.title">Downloads</h2>
            <p class="discovery-export__hint" data-i18n="biPythonToolkit.downloads.lead">
                Save the files into your local Python project folder. API keys stay on your machine.
            </p>
            <div class="discovery-toolbar">
                <a class="tools-btn tools-btn--primary" href="{{ locale_route('tools.bi-python-toolkit.download', ['file' => 'bi-kpi-export']) }}" data-i18n="biPythonToolkit.download.kpi">KPI export script</a>
                <a class="tools-btn tools-btn--primary" href="{{ locale_route('tools.bi-python-toolkit.download', ['file' => 'qlik-app-inventory']) }}" data-i18n="biPythonToolkit.download.inventory">Qlik app inventory script</a>
                <a class="tools-btn tools-btn--secondary" href="{{ locale_route('tools.bi-python-toolkit.download', ['file' => 'readme']) }}" data-i18n="biPythonToolkit.download.readme">Download README</a>
                <a class="tools-btn tools-btn--secondary" href="{{ locale_route('playbooks.show', ['slug' => 'python-bi-export-setup']) }}" data-i18n="biPythonToolkit.openSetupStory">Python Setup Story</a>
            </div>
        </section>

        <section class="tools-panel discovery-canvas" aria-labelledby="bi-python-commands-title">
            <h2 id="bi-python-commands-title" class="tools-panel__title" data-i18n="biPythonToolkit.commands.title">Example commands</h2>
            <p class="discovery-export__hint" data-i18n="biPythonToolkit.commands.lead">
                Adjust filenames, tenant and API key for your environment.
            </p>
            <pre class="discovery-export__preview"><code>python3 bi_kpi_export.py qlik --input qlik-measures.json --format plan-json --output kpis.plan.json
python3 bi_kpi_export.py powerbi --input model.bim --format csv --output powerbi-kpis.csv
python3 bi_kpi_export.py tableau --input workbook.twbx --format markdown --output tableau-kpis.md

QLIK_TENANT="https://tenant.eu.qlikcloud.com" QLIK_API_KEY="..." python3 qlik_app_inventory.py --include-sheets --format csv --output qlik-inventory.csv</code></pre>
        </section>

        <section class="tools-panel discovery-canvas" aria-labelledby="bi-python-export-title">
            <h2 id="bi-python-export-title" class="tools-panel__title" data-i18n="biPythonToolkit.export.title">
                What do I do with the export?
            </h2>
            <div class="bi-python-export-grid">
                <div class="bi-python-export-card">
                    <h3 data-i18n="biPythonToolkit.export.csv.title">CSV</h3>
                    <p data-i18n="biPythonToolkit.export.csv.body">
                        Use CSV for Excel, quick filtering, app inventories and manual cleanup lists.
                    </p>
                </div>
                <div class="bi-python-export-card">
                    <h3 data-i18n="biPythonToolkit.export.markdown.title">Markdown</h3>
                    <p data-i18n="biPythonToolkit.export.markdown.body">
                        Use Markdown for Sprint Planner notes, tickets, pull requests or wiki pages.
                    </p>
                </div>
                <div class="bi-python-export-card">
                    <h3 data-i18n="biPythonToolkit.export.planJson.title">Plan JSON</h3>
                    <p data-i18n="biPythonToolkit.export.planJson.body">
                        Use plan JSON when you want structured rows that match planner-style tables and later automation.
                    </p>
                </div>
            </div>
            <p class="discovery-export__hint" data-i18n="biPythonToolkit.export.review">
                After import, review each row: remove duplicates, assign an owner, mark status, and turn unclear formulas or unpublished sheets into follow-up tasks.
            </p>
        </section>

        <section class="tools-panel discovery-canvas" aria-labelledby="bi-python-scripts-title">
            <h2 id="bi-python-scripts-title" class="tools-panel__title" data-i18n="biPythonToolkit.scripts.title">View and copy scripts</h2>
            <p class="discovery-export__hint" data-i18n="biPythonToolkit.scripts.lead">
                Review the Python files directly here, copy them, or download them as files.
            </p>

            <div class="bi-python-scripts">
                @foreach ($scripts as $script)
                    <article class="bi-python-script" aria-labelledby="bi-python-script-{{ $script['id'] }}-title">
                        <div class="bi-python-script__header">
                            <h3 id="bi-python-script-{{ $script['id'] }}-title" class="bi-python-script__title">{{ $script['name'] }}</h3>
                            <div class="discovery-toolbar bi-python-script__actions">
                                <button
                                    type="button"
                                    class="tools-btn tools-btn--primary"
                                    data-copy-code="bi-python-code-{{ $script['id'] }}"
                                    data-i18n="biPythonToolkit.copyScript"
                                >Copy script</button>
                                <a
                                    class="tools-btn tools-btn--secondary"
                                    href="{{ locale_route('tools.bi-python-toolkit.download', ['file' => $script['download']]) }}"
                                    data-i18n="biPythonToolkit.downloadScript"
                                >Download</a>
                            </div>
                        </div>
                        <pre class="discovery-export__preview bi-python-script__code"><code id="bi-python-code-{{ $script['id'] }}">{{ $script['content'] }}</code></pre>
                    </article>
                @endforeach
            </div>
        </section>
    </x-tools.generator-page>
@endsection
