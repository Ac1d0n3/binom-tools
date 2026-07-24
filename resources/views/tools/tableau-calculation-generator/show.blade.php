@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/tableau-calculation-generator/index.js'],
])

@section('title', 'Tableau Calculation Generator — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        title-key="tableauCalc.pageTitle"
        lead-key="tableauCalc.pageLead"
        tool-id="tableau-calculation-generator"
        app-id="tableau-calculation-generator-app"
        title-badge="V1"
    >
        <div class="tableau-calc" data-tableau-calc-root>
            <section class="tools-panel tableau-calc__help">
                <details open>
                    <summary data-tableau-i18n="tableauCalc.help.summary">Kurzlogik</summary>
                    <ol>
                        <li data-tableau-i18n="tableauCalc.help.step1"></li>
                        <li data-tableau-i18n="tableauCalc.help.step2"></li>
                        <li data-tableau-i18n="tableauCalc.help.step3"></li>
                        <li data-tableau-i18n="tableauCalc.help.step4"></li>
                    </ol>
                </details>
            </section>

            <div class="tableau-calc__actions">
                <button type="button" class="tools-btn tools-btn--primary" data-tableau-save-app data-tableau-i18n="tableauCalc.apps.save">App speichern</button>
                <button type="button" class="tools-btn tools-btn--secondary" data-tableau-load-app data-tableau-i18n="tableauCalc.apps.load">App laden</button>
            </div>

            <div class="tableau-calc__grid">
                <aside class="tableau-calc__column">
                    <section class="tableau-calc__panel">
                        <h3 data-tableau-i18n="tableauCalc.catalog.title">App-Katalog</h3>
                        <p class="tableau-calc__small" data-tableau-i18n="tableauCalc.catalog.hint"></p>
                        <details open>
                            <summary data-tableau-i18n="tableauCalc.catalog.fields"></summary>
                            <textarea class="pii-policy-input" rows="10" data-tableau-fields>field,type,tags
Region,dimension,geo
Country,dimension,geo
City,dimension,geo
Segment,dimension,customer
Category,dimension,product
Order Date,date,calendar
Sales,measure,currency
Costs,measure,currency
Margin,measure,currency
Quantity,measure,number</textarea>
                        </details>
                        <details>
                            <summary data-tableau-i18n="tableauCalc.catalog.values"></summary>
                            <textarea class="pii-policy-input" rows="10" data-tableau-values>dimension,value,label
Region,DACH,DACH
Region,North America,North America
Country,DE,Germany
Country,AT,Austria
Country,CH,Switzerland
Country,US,United States
City,Berlin,Berlin
City,Vienna,Vienna
City,Zurich,Zurich
Segment,Enterprise,Enterprise
Segment,SMB,SMB
Category,Software,Software
Category,Services,Services</textarea>
                        </details>
                    </section>
                </aside>

                <main class="tableau-calc__column">
                    <section class="tableau-calc__panel">
                        <h3 data-tableau-i18n="tableauCalc.definitions.title">Definitionen</h3>
                        <div class="tableau-calc__stack">
                            <x-tools.field label-key="tableauCalc.definitions.dimension">
                                <select class="pii-policy-input" data-tableau-definition-dimension></select>
                            </x-tools.field>
                            <x-tools.field label-key="tableauCalc.definitions.values">
                                <select class="pii-policy-input" data-tableau-definition-values multiple size="6"></select>
                            </x-tools.field>
                            <x-tools.field label-key="tableauCalc.definitions.name">
                                <input class="pii-policy-input" type="text" value="Region DACH" data-tableau-definition-name>
                            </x-tools.field>
                            <div class="tableau-calc__preview">
                                <div class="tableau-calc__preview-label" data-tableau-i18n="tableauCalc.definitions.preview"></div>
                                <pre class="tableau-calc__preview-code" data-tableau-definition-preview></pre>
                            </div>
                            <button type="button" class="tools-btn tools-btn--primary" data-tableau-add-definition data-tableau-i18n="tableauCalc.definitions.add"></button>
                            <p class="tableau-calc__message" data-tableau-message hidden></p>
                            <details>
                                <summary data-tableau-i18n="tableauCalc.definitions.raw"></summary>
                                <textarea class="pii-policy-input" rows="8" data-tableau-definitions>name,dimensions,values,expression,description
Region DACH,Region,DACH,[Region] = 'DACH',DACH market
Current Year,Order Date,,YEAR([Order Date]) = YEAR(TODAY()),Current year</textarea>
                            </details>
                        </div>
                    </section>

                    <section class="tableau-calc__panel">
                        <h3 data-tableau-i18n="tableauCalc.base.title">Base Measures</h3>
                        <x-tools.field label-key="tableauCalc.base.raw">
                            <textarea class="pii-policy-input" rows="8" data-tableau-base-measures>name,expression,description_de,description_en
Sales,SUM([Sales]),Umsatz,Sales
Costs,SUM([Costs]),Kosten,Costs
Margin,SUM([Margin]),Marge,Margin
Quantity,SUM([Quantity]),Menge,Quantity</textarea>
                        </x-tools.field>
                    </section>

                    <section class="tableau-calc__panel">
                        <h3 data-tableau-i18n="tableauCalc.description.title">Beschreibung</h3>
                        <x-tools.field label-key="tableauCalc.description.de">
                            <textarea class="pii-policy-input" rows="3" data-tableau-description-de>{baseDescription} Erweiterung: {definition}. Bedingung: {condition}.</textarea>
                        </x-tools.field>
                        <x-tools.field label-key="tableauCalc.description.en">
                            <textarea class="pii-policy-input" rows="3" data-tableau-description-en>{baseDescription} Extension: {definition}. Condition: {condition}.</textarea>
                        </x-tools.field>
                    </section>
                </main>

                <aside class="tableau-calc__column">
                    <section class="tableau-calc__panel">
                        <div class="tableau-calc__tabs" role="tablist" aria-label="Tableau output">
                            <button type="button" class="tableau-calc__tab is-active" data-tableau-tab="calculations" aria-selected="true" data-tableau-i18n="tableauCalc.output.calcs"></button>
                            <button type="button" class="tableau-calc__tab" data-tableau-tab="lod" aria-selected="false" data-tableau-i18n="tableauCalc.output.lod"></button>
                            <button type="button" class="tableau-calc__tab" data-tableau-tab="definitions" aria-selected="false" data-tableau-i18n="tableauCalc.output.defs"></button>
                            <button type="button" class="tableau-calc__tab" data-tableau-tab="csv" aria-selected="false" data-tableau-i18n="tableauCalc.output.csv"></button>
                        </div>
                        <div class="tableau-calc__output-actions">
                            <button type="button" class="tools-btn tools-btn--secondary" data-tableau-copy data-tableau-i18n="tableauCalc.copy"></button>
                            <button type="button" class="tools-btn tools-btn--secondary" data-tableau-download data-tableau-i18n="tableauCalc.downloadCsv"></button>
                        </div>
                        <pre class="pii-policy-code tableau-calc__code" data-tableau-output></pre>
                    </section>
                </aside>
            </div>
        </div>
    </x-tools.generator-page>
@endsection
