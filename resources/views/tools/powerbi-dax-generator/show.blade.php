@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/powerbi-dax-generator/index.js'],
])

@section('title', 'Power BI DAX Measure Generator — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        title-key="powerbiDax.pageTitle"
        lead-key="powerbiDax.pageLead"
        tool-id="powerbi-dax-generator"
        app-id="powerbi-dax-generator-app"
        title-badge="V1"
    >
        <div class="tableau-calc" data-powerbi-dax-root>
            <section class="tools-panel qlik-set-help is-collapsed" aria-labelledby="powerbi-dax-help-title" data-powerbi-help>
                <div class="qlik-set-help__header">
                    <div>
                        <h2 id="powerbi-dax-help-title" class="tools-panel__title" data-powerbi-i18n="powerbiDax.help.title">Power BI DAX Hilfe</h2>
                        <p class="qlik-set-help__lead" data-powerbi-i18n="powerbiDax.help.lead"></p>
                        <div class="qlik-set-help__links" aria-label="Power BI product links">
                            <a class="qlik-set-help__link" href="https://powerbi.microsoft.com/" target="_blank" rel="noopener noreferrer" data-powerbi-i18n="powerbiDax.help.productLink">Power BI Produktseite</a>
                            <a class="qlik-set-help__link" href="https://learn.microsoft.com/power-bi/" target="_blank" rel="noopener noreferrer" data-powerbi-i18n="powerbiDax.help.productHelpLink">Power BI Hilfe-Portal</a>
                            <a class="qlik-set-help__link" href="https://learn.microsoft.com/en-us/dax/calculate-function-dax" target="_blank" rel="noopener noreferrer" data-powerbi-i18n="powerbiDax.help.calculateLink">CALCULATE Dokumentation</a>
                            <a class="qlik-set-help__link" href="https://learn.microsoft.com/en-us/power-bi/transform-model/desktop-measures" target="_blank" rel="noopener noreferrer" data-powerbi-i18n="powerbiDax.help.measuresLink">Measures Dokumentation</a>
                        </div>
                    </div>
                    <button type="button" class="tools-btn tools-btn--secondary qlik-set-help__toggle" aria-expanded="false" data-powerbi-help-toggle data-powerbi-i18n="powerbiDax.help.show">Hilfe anzeigen</button>
                </div>
                <div class="qlik-set-help__body" data-powerbi-help-body hidden>
                    <div class="tableau-calc__help-grid">
                        <article class="tableau-calc__help-card">
                            <strong data-powerbi-i18n="powerbiDax.help.catalogTitle"></strong>
                            <p data-powerbi-i18n="powerbiDax.help.step1"></p>
                        </article>
                        <article class="tableau-calc__help-card">
                            <strong data-powerbi-i18n="powerbiDax.help.definitionsTitle"></strong>
                            <p data-powerbi-i18n="powerbiDax.help.step2"></p>
                        </article>
                        <article class="tableau-calc__help-card">
                            <strong data-powerbi-i18n="powerbiDax.help.baseTitle"></strong>
                            <p data-powerbi-i18n="powerbiDax.help.step3"></p>
                        </article>
                        <article class="tableau-calc__help-card">
                            <strong data-powerbi-i18n="powerbiDax.help.outputTitle"></strong>
                            <p data-powerbi-i18n="powerbiDax.help.step4"></p>
                        </article>
                    </div>
                    <div class="tableau-calc__help-note">
                        <strong data-powerbi-i18n="powerbiDax.help.whereTitle"></strong>
                        <p data-powerbi-i18n="powerbiDax.help.whereBody"></p>
                    </div>
                </div>
            </section>

            <x-tools.panel heading-id="powerbi-dax-workbench-title" title-key="powerbiDax.workbench.title" description-key="powerbiDax.workbench.description">
                <div class="qlik-set-workbench-shell">
                    <div class="qlik-set-workbench-control">
                        <div>
                            <strong data-powerbi-i18n="powerbiDax.workbench.controlTitle"></strong>
                            <span data-powerbi-i18n="powerbiDax.workbench.controlHint"></span>
                        </div>
                        <button type="button" class="tools-btn tools-btn--secondary" aria-expanded="true" data-powerbi-workbench-toggle data-powerbi-i18n="powerbiDax.workbench.hide"></button>
                    </div>
                    <div class="qlik-set-workbench-body" data-powerbi-workbench-body>
                        <div class="qlik-set-appbar" aria-label="Saved Power BI apps">
                            <x-tools.field label-key="powerbiDax.apps.name">
                                <input class="pii-policy-input" type="text" value="Demo Power BI Model" data-powerbi-app-name>
                            </x-tools.field>
                            <x-tools.field label-key="powerbiDax.apps.saved">
                                <select class="pii-policy-input" data-powerbi-saved-apps>
                                    <option value="" data-powerbi-i18n="powerbiDax.apps.none"></option>
                                </select>
                            </x-tools.field>
                            <div class="qlik-set-appbar__actions">
                                <button type="button" class="tools-btn tools-btn--primary" data-powerbi-save-app data-powerbi-i18n="powerbiDax.apps.save">App speichern</button>
                                <button type="button" class="tools-btn tools-btn--secondary" data-powerbi-load-app data-powerbi-i18n="powerbiDax.apps.load">App laden</button>
                                <button type="button" class="tools-btn tools-btn--secondary" data-powerbi-delete-app data-powerbi-i18n="powerbiDax.apps.delete">App löschen</button>
                            </div>
                            <p class="qlik-set-builder-message" data-powerbi-app-message aria-live="polite" hidden></p>
                        </div>
                        <div class="qlik-set-workbench-toolbar" aria-label="Workbench layout">
                            <button type="button" class="tools-btn tools-btn--secondary qlik-set-layout-button is-active" data-powerbi-layout-toggle="catalog" data-powerbi-i18n="powerbiDax.layout.catalog"></button>
                            <button type="button" class="tools-btn tools-btn--secondary qlik-set-layout-button is-active" data-powerbi-layout-toggle="composer" data-powerbi-i18n="powerbiDax.layout.formula"></button>
                            <button type="button" class="tools-btn tools-btn--secondary qlik-set-layout-button is-active" data-powerbi-layout-toggle="builder" data-powerbi-i18n="powerbiDax.layout.builder"></button>
                            <button type="button" class="tools-btn tools-btn--secondary" data-powerbi-layout-preset="focus-formula" data-powerbi-i18n="powerbiDax.layout.focusFormula"></button>
                            <button type="button" class="tools-btn tools-btn--secondary" data-powerbi-layout-preset="all" data-powerbi-i18n="powerbiDax.layout.showAll"></button>
                        </div>

                        <div class="tableau-calc__grid qlik-set-workbench" data-layout-catalog="open" data-layout-composer="open" data-layout-builder="open">
                            <aside class="tableau-calc__column tableau-calc__column--catalog qlik-set-rail" data-powerbi-column="catalog" data-qlik-column="catalog">
                                <h3 data-powerbi-i18n="powerbiDax.catalog.title">Modell-Katalog</h3>
                                <p class="tableau-calc__small" data-powerbi-i18n="powerbiDax.catalog.hint"></p>
                                <div class="qlik-set-catalog-tabs" role="tablist" aria-label="Power BI catalog">
                                    <button type="button" class="qlik-set-catalog-tab is-active" data-powerbi-catalog-tab="dimensions" aria-selected="true"><span class="qlik-set-catalog-tab__icon" aria-hidden="true">D</span><span class="qlik-set-catalog-tab__label" data-powerbi-i18n="powerbiDax.catalog.tabs.dimensions"></span></button>
                                    <button type="button" class="qlik-set-catalog-tab" data-powerbi-catalog-tab="measures" aria-selected="false"><span class="qlik-set-catalog-tab__icon" aria-hidden="true">#</span><span class="qlik-set-catalog-tab__label" data-powerbi-i18n="powerbiDax.catalog.tabs.measures"></span></button>
                                </div>
                                <div class="qlik-set-catalog-panel is-active" data-powerbi-catalog-panel="dimensions">
                                    <div class="qlik-set-chip-row qlik-set-chip-row--stacked" data-powerbi-dimension-chips></div>
                                </div>
                                <div class="qlik-set-catalog-panel" data-powerbi-catalog-panel="measures" hidden>
                                    <div class="qlik-set-chip-row qlik-set-chip-row--stacked" data-powerbi-measure-chips></div>
                                </div>
                                <button type="button" class="tools-btn tools-btn--secondary" data-powerbi-import-modal-open data-powerbi-i18n="powerbiDax.catalog.rawData"></button>
                                <dialog class="qlik-set-modal" data-powerbi-import-modal>
                                    <div class="qlik-set-modal__form">
                                        <div class="qlik-set-modal__header">
                                            <div>
                                                <h3 class="qlik-set-modal__title" data-powerbi-i18n="powerbiDax.catalog.rawData"></h3>
                                                <p class="qlik-set-small" data-powerbi-i18n="powerbiDax.catalog.rawDataHint"></p>
                                            </div>
                                            <button type="button" class="tools-btn tools-btn--secondary" data-powerbi-import-modal-close data-powerbi-i18n="powerbiDax.modal.close"></button>
                                        </div>
                                        <div class="qlik-set-modal-tabs" role="tablist" aria-label="Edit Power BI catalog data">
                                            <button type="button" class="qlik-set-modal-tab is-active" data-powerbi-import-tab="fields" aria-selected="true" data-powerbi-i18n="powerbiDax.catalog.fields"></button>
                                            <button type="button" class="qlik-set-modal-tab" data-powerbi-import-tab="values" aria-selected="false" data-powerbi-i18n="powerbiDax.catalog.values"></button>
                                        </div>
                                        <div class="qlik-set-modal-panel is-active" data-powerbi-import-panel="fields">
                                            <label class="tools-btn tools-btn--secondary qlik-set-file-button">
                                                <span data-powerbi-i18n="powerbiDax.catalog.fieldsUpload"></span>
                                                <input type="file" accept=".csv,text/csv,text/plain" data-powerbi-fields-file>
                                            </label>
                                            <x-tools.field label-key="powerbiDax.catalog.fields">
                                                <textarea class="pii-policy-input" rows="12" data-powerbi-fields>table,field,type,tags
Sales,Region,dimension,geo
Sales,Country,dimension,geo
Sales,City,dimension,geo
Sales,Segment,dimension,customer
Sales,Category,dimension,product
Date,Date,date,calendar
Sales,Sales,measure,currency
Sales,Costs,measure,currency
Sales,Margin,measure,currency
Sales,Quantity,measure,number</textarea>
                                            </x-tools.field>
                                        </div>
                                        <div class="qlik-set-modal-panel" data-powerbi-import-panel="values" hidden>
                                            <label class="tools-btn tools-btn--secondary qlik-set-file-button">
                                                <span data-powerbi-i18n="powerbiDax.catalog.valuesUpload"></span>
                                                <input type="file" accept=".csv,text/csv,text/plain" data-powerbi-values-file>
                                            </label>
                                            <x-tools.field label-key="powerbiDax.catalog.values">
                                                <textarea class="pii-policy-input" rows="12" data-powerbi-values>table,dimension,value,label
Sales,Region,DACH,DACH
Sales,Region,North America,North America
Sales,Country,DE,Germany
Sales,Country,AT,Austria
Sales,Country,CH,Switzerland
Sales,Country,US,United States
Sales,City,Berlin,Berlin
Sales,City,Vienna,Vienna
Sales,City,Zurich,Zurich
Sales,Segment,Enterprise,Enterprise
Sales,Segment,SMB,SMB
Sales,Category,Software,Software
Sales,Category,Services,Services</textarea>
                                            </x-tools.field>
                                        </div>
                                    </div>
                                </dialog>
                            </aside>

                            <main class="tableau-calc__column tableau-calc__column--formula qlik-set-composer" data-powerbi-column="composer" data-qlik-column="composer">
                                <div class="qlik-set-section-title">
                                    <h3 data-powerbi-i18n="powerbiDax.formula.title">Formel</h3>
                                    <p data-powerbi-i18n="powerbiDax.formula.hint"></p>
                                </div>
                                <textarea class="pii-policy-input tableau-calc__formula-dropzone" rows="5" data-powerbi-base-expression>SUM(Sales[Sales])</textarea>
                                <div class="qlik-set-history-bar" aria-label="Formula history">
                                    <button type="button" class="tools-btn tools-btn--secondary qlik-set-history-button" data-powerbi-undo data-powerbi-i18n="powerbiDax.history.undo" disabled></button>
                                    <button type="button" class="tools-btn tools-btn--secondary qlik-set-history-button" data-powerbi-redo data-powerbi-i18n="powerbiDax.history.redo" disabled></button>
                                </div>
                                <div class="tableau-calc__current-formula qlik-set-current-formula">
                                    <div class="tableau-calc__preview-label qlik-set-current-formula__label" data-powerbi-i18n="powerbiDax.formula.current"></div>
                                    <pre class="tableau-calc__preview-code qlik-set-current-formula__pre" data-powerbi-current-formula></pre>
                                </div>
                                <div class="tableau-calc__mini-grid qlik-set-mini-grid">
                                    <x-tools.field label-key="powerbiDax.formula.name">
                                        <input class="pii-policy-input" type="text" value="Sales" data-powerbi-measure-name>
                                    </x-tools.field>
                                    <x-tools.field label-key="powerbiDax.formula.field">
                                        <input class="pii-policy-input" type="text" value="Sales[Sales]" data-powerbi-measure-field>
                                    </x-tools.field>
                                </div>
                                <div class="tableau-calc__actions qlik-set-actions">
                                    <select class="pii-policy-input tableau-calc__inline-control" data-powerbi-aggregation aria-label="Aggregation">
                                        <option value="SUM">SUM</option>
                                        <option value="COUNT">COUNT</option>
                                        <option value="DISTINCTCOUNT">DISTINCTCOUNT</option>
                                        <option value="AVERAGE">AVERAGE</option>
                                        <option value="MIN">MIN</option>
                                        <option value="MAX">MAX</option>
                                    </select>
                                    <button type="button" class="tools-btn tools-btn--primary" data-powerbi-apply-base data-powerbi-i18n="powerbiDax.formula.apply"></button>
                                </div>

                                <div class="qlik-set-palette qlik-set-base-list" aria-labelledby="powerbi-dax-base-list-title">
                                    <div class="qlik-set-palette__header">
                                        <div>
                                            <h4 id="powerbi-dax-base-list-title" data-powerbi-i18n="powerbiDax.baseList.title"></h4>
                                            <span class="qlik-set-palette__hint" data-powerbi-i18n="powerbiDax.baseList.hint"></span>
                                        </div>
                                        <button type="button" class="tools-btn tools-btn--secondary qlik-set-palette__toggle" data-powerbi-base-list-toggle aria-expanded="false" data-powerbi-i18n="powerbiDax.baseList.show"></button>
                                    </div>
                                    <div class="qlik-set-palette__scroll qlik-set-description-body" data-powerbi-base-list-body hidden>
                                        <div class="qlik-set-mini-grid qlik-set-mini-grid--one">
                                            <x-tools.field label-key="powerbiDax.baseList.active">
                                                <select class="pii-policy-input" data-powerbi-base-measure-select></select>
                                            </x-tools.field>
                                        </div>
                                        <div class="qlik-set-action-row">
                                            <button type="button" class="tools-btn tools-btn--secondary" data-powerbi-load-base data-powerbi-i18n="powerbiDax.baseList.load"></button>
                                            <button type="button" class="tools-btn tools-btn--primary" data-powerbi-save-current-base data-powerbi-i18n="powerbiDax.baseList.saveCurrent"></button>
                                            <button type="button" class="tools-btn tools-btn--secondary" data-powerbi-new-base data-powerbi-i18n="powerbiDax.baseList.newBase"></button>
                                        </div>
                                        <div class="tableau-calc__item-list" data-powerbi-base-list></div>
                                        <x-tools.field label-key="powerbiDax.baseList.csv">
                                            <textarea class="pii-policy-input qlik-set-template-text" rows="6" data-powerbi-base-measures>name,expression,description_de,description_en
Sales,SUM(Sales[Sales]),Umsatz,Sales
Costs,SUM(Sales[Costs]),Kosten,Costs
Margin,SUM(Sales[Margin]),Marge,Margin
Quantity,SUM(Sales[Quantity]),Menge,Quantity</textarea>
                                        </x-tools.field>
                                        <p class="qlik-set-small" data-powerbi-i18n="powerbiDax.baseList.rawHint"></p>
                                    </div>
                                </div>

                                <div class="tableau-calc__palette qlik-set-palette">
                                    <div class="tableau-calc__palette-header qlik-set-palette__header">
                                        <div>
                                            <h4 data-powerbi-i18n="powerbiDax.functions.title"></h4>
                                            <span class="qlik-set-palette__hint" data-powerbi-i18n="powerbiDax.functions.hint"></span>
                                        </div>
                                    </div>
                                    <div class="tableau-calc__block-grid qlik-set-block-grid qlik-set-block-grid--dense">
                                        <button type="button" class="tableau-calc__block qlik-set-block qlik-set-block--function" data-powerbi-function="SUM(Sales[Sales])">SUM</button>
                                        <button type="button" class="tableau-calc__block qlik-set-block qlik-set-block--function" data-powerbi-function="DISTINCTCOUNT(Sales[Customer])">DISTINCTCOUNT</button>
                                        <button type="button" class="tableau-calc__block qlik-set-block qlik-set-block--function" data-powerbi-function="AVERAGE(Sales[Margin])">AVERAGE</button>
                                        <button type="button" class="tableau-calc__block qlik-set-block qlik-set-block--function" data-powerbi-function="DIVIDE([Sales] - [Costs], [Sales])">DIVIDE</button>
                                        <button type="button" class="tableau-calc__block qlik-set-block qlik-set-block--function" data-powerbi-function="CALCULATE([Sales], Sales[Region] = &quot;DACH&quot;)">CALCULATE</button>
                                        <button type="button" class="tableau-calc__block qlik-set-block qlik-set-block--function" data-powerbi-function="TOTALYTD([Sales], 'Date'[Date])">YTD</button>
                                        <button type="button" class="tableau-calc__block qlik-set-block qlik-set-block--function" data-powerbi-function="RANKX(ALL(Sales[Customer]), [Sales])">RANKX</button>
                                        <button type="button" class="tableau-calc__block qlik-set-block qlik-set-block--function" data-powerbi-function="SELECTEDVALUE(Sales[Region])">SELECTEDVALUE</button>
                                    </div>
                                </div>
                            </main>

                            <aside class="tableau-calc__column tableau-calc__column--rules qlik-set-filter-builder" data-powerbi-column="builder" data-qlik-column="builder">
                                <div class="qlik-set-builder-tabs" role="tablist" aria-label="Power BI builder">
                                    <button type="button" class="qlik-set-builder-tab is-active" data-powerbi-builder-tab="definitions" aria-selected="true" data-powerbi-i18n="powerbiDax.tabs.definitions"></button>
                                    <button type="button" class="qlik-set-builder-tab" data-powerbi-builder-tab="hierarchy" aria-selected="false" data-powerbi-i18n="powerbiDax.tabs.hierarchy"></button>
                                    <button type="button" class="qlik-set-builder-tab" data-powerbi-builder-tab="extras" aria-selected="false" data-powerbi-i18n="powerbiDax.tabs.extras"></button>
                                </div>

                                <div class="tableau-calc__builder-panel qlik-set-builder-panel is-active" data-powerbi-builder-panel="definitions">
                                    <div class="qlik-set-section-title">
                                        <h3 data-powerbi-i18n="powerbiDax.definitions.title">Definitionen</h3>
                                        <p data-powerbi-i18n="powerbiDax.definitions.hint"></p>
                                    </div>
                                    <div class="tableau-calc__stack">
                                        <div class="tableau-calc__dropzone qlik-set-filter-dropzone" data-powerbi-definition-dropzone>
                                            <strong data-powerbi-i18n="powerbiDax.definitions.dropTitle"></strong>
                                            <span data-powerbi-i18n="powerbiDax.definitions.dropHint"></span>
                                        </div>
                                        <div class="qlik-set-mini-grid qlik-set-mini-grid--one">
                                            <x-tools.field label-key="powerbiDax.definitions.column">
                                                <select class="pii-policy-input" data-powerbi-definition-column></select>
                                            </x-tools.field>
                                            <x-tools.field label-key="powerbiDax.definitions.values">
                                                <select class="pii-policy-input qlik-set-multi-select" data-powerbi-definition-values multiple size="6"></select>
                                            </x-tools.field>
                                            <x-tools.field label-key="powerbiDax.definitions.name">
                                                <input class="pii-policy-input" type="text" value="Region DACH" data-powerbi-definition-name>
                                            </x-tools.field>
                                        </div>
                                        <div class="qlik-set-action-row">
                                            <button type="button" class="tools-btn tools-btn--primary" data-powerbi-add-definition data-powerbi-i18n="powerbiDax.definitions.add"></button>
                                        </div>
                                        <p class="tableau-calc__message qlik-set-builder-message" data-powerbi-message hidden></p>
                                        <div class="qlik-set-current-formula">
                                            <div class="qlik-set-current-formula__label" data-powerbi-i18n="powerbiDax.definitions.preview"></div>
                                            <pre class="qlik-set-current-formula__pre" data-powerbi-definition-preview></pre>
                                        </div>
                                        <div class="qlik-set-generated-list" data-powerbi-definition-list aria-live="polite"></div>
                                        <details class="qlik-set-details">
                                            <summary data-powerbi-i18n="powerbiDax.definitions.raw"></summary>
                                            <textarea class="pii-policy-input qlik-set-template-text" rows="8" data-powerbi-definitions>name,table,column,values,expression,description
Region DACH,Sales,Region,DACH,,DACH market
Current Year,Date,Date,,YEAR('Date'[Date]) = YEAR(TODAY()),Current year</textarea>
                                        </details>
                                    </div>
                                </div>

                                <div class="tableau-calc__builder-panel qlik-set-builder-panel" data-powerbi-builder-panel="hierarchy" hidden>
                                    <div class="qlik-set-hierarchy">
                                        <div class="qlik-set-section-title">
                                            <h3 data-powerbi-i18n="powerbiDax.hierarchy.title">Hierarchie</h3>
                                            <p data-powerbi-i18n="powerbiDax.hierarchy.hint"></p>
                                        </div>
                                        <div class="tableau-calc__dropzone qlik-set-hierarchy-dropzone" data-powerbi-hierarchy-dropzone>
                                            <strong data-powerbi-i18n="powerbiDax.hierarchy.dropTitle"></strong>
                                            <span data-powerbi-i18n="powerbiDax.hierarchy.dropHint"></span>
                                        </div>
                                        <div class="qlik-set-hierarchy-preview" data-powerbi-hierarchy-preview aria-live="polite"></div>
                                        <textarea class="pii-policy-input qlik-set-hierarchy-text" rows="6" data-powerbi-hierarchy>Sales[Region]
  Sales[Country]
    Sales[City]
Sales[Category]</textarea>
                                    </div>
                                    <div class="qlik-set-child-tree">
                                        <div class="qlik-set-section-title">
                                            <h3 data-powerbi-i18n="powerbiDax.tree.title"></h3>
                                            <p data-powerbi-i18n="powerbiDax.tree.hint"></p>
                                        </div>
                                        <div class="qlik-set-tree-preview" data-powerbi-hierarchy-list aria-live="polite"></div>
                                    </div>
                                </div>

                                <div class="tableau-calc__builder-panel qlik-set-builder-panel" data-powerbi-builder-panel="extras" hidden>
                                    <div class="qlik-set-section-title">
                                        <h3 data-powerbi-i18n="powerbiDax.extras.title"></h3>
                                        <p data-powerbi-i18n="powerbiDax.extras.hint"></p>
                                    </div>
                                    <x-tools.field label-key="powerbiDax.extras.mode">
                                        <select class="pii-policy-input" data-powerbi-mode>
                                            <option value="single" data-powerbi-i18n="powerbiDax.extras.modeSingle"></option>
                                            <option value="combined" data-powerbi-i18n="powerbiDax.extras.modeCombined"></option>
                                            <option value="dimension-group" data-powerbi-i18n="powerbiDax.extras.modeDimensionGroup"></option>
                                        </select>
                                    </x-tools.field>
                                    <x-tools.field label-key="powerbiDax.extras.dateColumn">
                                        <input class="pii-policy-input" type="text" value="'Date'[Date]" data-powerbi-date-column>
                                    </x-tools.field>
                                    <p class="qlik-set-small" data-powerbi-i18n="powerbiDax.extras.dateHint"></p>
                                    <div class="qlik-set-section-title">
                                        <h3 data-powerbi-i18n="powerbiDax.description.title"></h3>
                                        <p data-powerbi-i18n="powerbiDax.description.hint"></p>
                                    </div>
                                    <x-tools.field label-key="powerbiDax.description.de">
                                        <textarea class="pii-policy-input" rows="3" data-powerbi-description-de>{baseDescription} Erweiterung: {definition}. Filter: {condition}.</textarea>
                                    </x-tools.field>
                                    <x-tools.field label-key="powerbiDax.description.en">
                                        <textarea class="pii-policy-input" rows="3" data-powerbi-description-en>{baseDescription} Extension: {definition}. Filter: {condition}.</textarea>
                                    </x-tools.field>
                                </div>
                            </aside>
                        </div>
                    </div>
                </div>
            </x-tools.panel>

            <section class="pii-policy-panel pii-policy-panel--code qlik-set-output-panel-wrap" aria-label="Power BI output">
                <div class="qlik-set-output-tabbar">
                    <div class="qlik-set-output-tabs" role="tablist" aria-label="Power BI output">
                        <button type="button" class="qlik-set-output-tab is-active" data-powerbi-tab="measures" aria-controls="powerbi-output-measures" aria-selected="true" data-powerbi-i18n="powerbiDax.output.measures"></button>
                        <button type="button" class="qlik-set-output-tab" data-powerbi-tab="time" aria-controls="powerbi-output-time" aria-selected="false" data-powerbi-i18n="powerbiDax.output.time"></button>
                        <button type="button" class="qlik-set-output-tab" data-powerbi-tab="hierarchy" aria-controls="powerbi-output-hierarchy" aria-selected="false" data-powerbi-i18n="powerbiDax.output.hierarchy"></button>
                        <button type="button" class="qlik-set-output-tab" data-powerbi-tab="definitions" aria-controls="powerbi-output-definitions" aria-selected="false" data-powerbi-i18n="powerbiDax.output.defs"></button>
                        <button type="button" class="qlik-set-output-tab" data-powerbi-tab="csv" aria-controls="powerbi-output-csv" aria-selected="false" data-powerbi-i18n="powerbiDax.output.csv"></button>
                    </div>
                </div>

                <div id="powerbi-output-measures" class="qlik-set-output-panel is-active" data-powerbi-output-panel="measures">
                    <div class="qlik-set-output-actions">
                        <button type="button" class="tools-btn" data-powerbi-copy="measures" data-powerbi-i18n="powerbiDax.copy"></button>
                        <button type="button" class="tools-btn tools-btn--secondary" data-powerbi-download-xlsx="measures" data-powerbi-i18n="powerbiDax.downloadXlsx"></button>
                        <button type="button" class="tools-btn tools-btn--secondary" data-powerbi-download-csv data-powerbi-i18n="powerbiDax.downloadCsv"></button>
                    </div>
                    <pre class="pii-policy-code" data-powerbi-output="measures"></pre>
                </div>
                <div id="powerbi-output-time" class="qlik-set-output-panel" data-powerbi-output-panel="time" hidden>
                    <div class="qlik-set-output-actions">
                        <button type="button" class="tools-btn" data-powerbi-copy="time" data-powerbi-i18n="powerbiDax.copy"></button>
                    </div>
                    <pre class="pii-policy-code" data-powerbi-output="time"></pre>
                </div>
                <div id="powerbi-output-hierarchy" class="qlik-set-output-panel" data-powerbi-output-panel="hierarchy" hidden>
                    <div class="qlik-set-output-actions">
                        <button type="button" class="tools-btn" data-powerbi-copy="hierarchy" data-powerbi-i18n="powerbiDax.copy"></button>
                    </div>
                    <pre class="pii-policy-code" data-powerbi-output="hierarchy"></pre>
                </div>
                <div id="powerbi-output-definitions" class="qlik-set-output-panel" data-powerbi-output-panel="definitions" hidden>
                    <div class="qlik-set-output-actions">
                        <button type="button" class="tools-btn" data-powerbi-copy="definitions" data-powerbi-i18n="powerbiDax.copy"></button>
                    </div>
                    <pre class="pii-policy-code" data-powerbi-output="definitions"></pre>
                </div>
                <div id="powerbi-output-csv" class="qlik-set-output-panel" data-powerbi-output-panel="csv" hidden>
                    <div class="qlik-set-output-actions">
                        <button type="button" class="tools-btn" data-powerbi-copy="csv" data-powerbi-i18n="powerbiDax.copy"></button>
                        <button type="button" class="tools-btn tools-btn--secondary" data-powerbi-download-csv data-powerbi-i18n="powerbiDax.downloadCsv"></button>
                    </div>
                    <pre class="pii-policy-code" data-powerbi-output="csv"></pre>
                </div>
            </section>
        </div>
    </x-tools.generator-page>
@endsection
