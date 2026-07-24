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
            <section class="tools-panel qlik-set-help is-collapsed" aria-labelledby="tableau-calc-help-title" data-tableau-help>
                <div class="qlik-set-help__header">
                    <div>
                        <h2 id="tableau-calc-help-title" class="tools-panel__title" data-tableau-i18n="tableauCalc.help.title">Tableau Calculation Hilfe</h2>
                        <p class="qlik-set-help__lead" data-tableau-i18n="tableauCalc.help.lead"></p>
                        <div class="qlik-set-help__links" aria-label="Tableau product links">
                            <a class="qlik-set-help__link" href="https://www.tableau.com/" target="_blank" rel="noopener noreferrer" data-tableau-i18n="tableauCalc.help.productLink">Tableau Produktseite</a>
                            <a class="qlik-set-help__link" href="https://help.tableau.com/" target="_blank" rel="noopener noreferrer" data-tableau-i18n="tableauCalc.help.productHelpLink">Tableau Hilfe-Portal</a>
                            <a class="qlik-set-help__link" href="https://help.tableau.com/current/pro/desktop/en-us/calculations_calculatedfields_create.htm" target="_blank" rel="noopener noreferrer" data-tableau-i18n="tableauCalc.help.calculatedFieldsLink">Calculated Fields Dokumentation</a>
                            <a class="qlik-set-help__link" href="https://help.tableau.com/current/pro/desktop/en-us/calculations_calculatedfields_lod.htm" target="_blank" rel="noopener noreferrer" data-tableau-i18n="tableauCalc.help.lodLink">LOD Expressions Dokumentation</a>
                        </div>
                    </div>
                    <button type="button" class="tools-btn tools-btn--secondary qlik-set-help__toggle" aria-expanded="false" data-tableau-help-toggle data-tableau-i18n="tableauCalc.help.show">Hilfe anzeigen</button>
                </div>
                <div class="qlik-set-help__body" data-tableau-help-body hidden>
                    <div class="tableau-calc__help-grid">
                        <article class="tableau-calc__help-card">
                            <strong data-tableau-i18n="tableauCalc.help.catalogTitle"></strong>
                            <p data-tableau-i18n="tableauCalc.help.step1"></p>
                        </article>
                        <article class="tableau-calc__help-card">
                            <strong data-tableau-i18n="tableauCalc.help.definitionsTitle"></strong>
                            <p data-tableau-i18n="tableauCalc.help.step2"></p>
                        </article>
                        <article class="tableau-calc__help-card">
                            <strong data-tableau-i18n="tableauCalc.help.baseTitle"></strong>
                            <p data-tableau-i18n="tableauCalc.help.step3"></p>
                        </article>
                        <article class="tableau-calc__help-card">
                            <strong data-tableau-i18n="tableauCalc.help.outputTitle"></strong>
                            <p data-tableau-i18n="tableauCalc.help.step4"></p>
                        </article>
                    </div>
                    <div class="tableau-calc__help-note">
                        <strong data-tableau-i18n="tableauCalc.help.whereTitle"></strong>
                        <p data-tableau-i18n="tableauCalc.help.whereBody"></p>
                    </div>
                </div>
            </section>

            <x-tools.panel heading-id="tableau-calc-workbench-title" title-key="tableauCalc.workbench.title" description-key="tableauCalc.workbench.description">
                <div class="qlik-set-workbench-shell">
                    <div class="qlik-set-workbench-control">
                        <div>
                            <strong data-tableau-i18n="tableauCalc.workbench.controlTitle"></strong>
                            <span data-tableau-i18n="tableauCalc.workbench.controlHint"></span>
                        </div>
                        <button type="button" class="tools-btn tools-btn--secondary" aria-expanded="true" data-tableau-workbench-toggle data-tableau-i18n="tableauCalc.workbench.hide"></button>
                    </div>
                    <div class="qlik-set-workbench-body" data-tableau-workbench-body>
                        <div class="qlik-set-appbar" aria-label="Saved Tableau apps">
                            <x-tools.field label-key="tableauCalc.apps.name">
                                <input class="pii-policy-input" type="text" value="Demo Tableau Workbook" data-tableau-app-name>
                            </x-tools.field>
                            <x-tools.field label-key="tableauCalc.apps.saved">
                                <select class="pii-policy-input" data-tableau-saved-apps>
                                    <option value="" data-tableau-i18n="tableauCalc.apps.none"></option>
                                </select>
                            </x-tools.field>
                            <div class="qlik-set-appbar__actions">
                                <button type="button" class="tools-btn tools-btn--primary" data-tableau-save-app data-tableau-i18n="tableauCalc.apps.save">App speichern</button>
                                <button type="button" class="tools-btn tools-btn--secondary" data-tableau-load-app data-tableau-i18n="tableauCalc.apps.load">App laden</button>
                                <button type="button" class="tools-btn tools-btn--secondary" data-tableau-delete-app data-tableau-i18n="tableauCalc.apps.delete">App löschen</button>
                            </div>
                            <p class="qlik-set-builder-message" data-tableau-app-message aria-live="polite" hidden></p>
                        </div>
                        <div class="qlik-set-workbench-toolbar" aria-label="Workbench layout">
                            <button type="button" class="tools-btn tools-btn--secondary qlik-set-layout-button is-active" data-tableau-layout-toggle="catalog" data-tableau-i18n="tableauCalc.layout.catalog"></button>
                            <button type="button" class="tools-btn tools-btn--secondary qlik-set-layout-button is-active" data-tableau-layout-toggle="composer" data-tableau-i18n="tableauCalc.layout.formula"></button>
                            <button type="button" class="tools-btn tools-btn--secondary qlik-set-layout-button is-active" data-tableau-layout-toggle="builder" data-tableau-i18n="tableauCalc.layout.builder"></button>
                            <button type="button" class="tools-btn tools-btn--secondary" data-tableau-layout-preset="focus-formula" data-tableau-i18n="tableauCalc.layout.focusFormula"></button>
                            <button type="button" class="tools-btn tools-btn--secondary" data-tableau-layout-preset="all" data-tableau-i18n="tableauCalc.layout.showAll"></button>
                        </div>

                        <div class="tableau-calc__grid qlik-set-workbench" data-layout-catalog="open" data-layout-composer="open" data-layout-builder="open">
                            <aside class="tableau-calc__column tableau-calc__column--catalog qlik-set-rail" data-tableau-column="catalog" data-qlik-column="catalog">
                                <h3 data-tableau-i18n="tableauCalc.catalog.title">App-Katalog</h3>
                                <p class="tableau-calc__small" data-tableau-i18n="tableauCalc.catalog.hint"></p>
                                <div class="qlik-set-catalog-tabs" role="tablist" aria-label="Tableau catalog">
                                    <button type="button" class="qlik-set-catalog-tab is-active" data-tableau-catalog-tab="dimensions" aria-selected="true"><span class="qlik-set-catalog-tab__icon" aria-hidden="true">D</span><span class="qlik-set-catalog-tab__label" data-tableau-i18n="tableauCalc.catalog.tabs.dimensions"></span></button>
                                    <button type="button" class="qlik-set-catalog-tab" data-tableau-catalog-tab="measures" aria-selected="false"><span class="qlik-set-catalog-tab__icon" aria-hidden="true">#</span><span class="qlik-set-catalog-tab__label" data-tableau-i18n="tableauCalc.catalog.tabs.measures"></span></button>
                                </div>
                                <div class="qlik-set-catalog-panel is-active" data-tableau-catalog-panel="dimensions">
                                    <div class="qlik-set-chip-row qlik-set-chip-row--stacked" data-tableau-dimension-chips></div>
                                </div>
                                <div class="qlik-set-catalog-panel" data-tableau-catalog-panel="measures" hidden>
                                    <div class="qlik-set-chip-row qlik-set-chip-row--stacked" data-tableau-measure-chips></div>
                                </div>
                                <button type="button" class="tools-btn tools-btn--secondary" data-tableau-import-modal-open data-tableau-i18n="tableauCalc.catalog.rawData"></button>
                                <dialog class="qlik-set-modal" data-tableau-import-modal>
                                    <div class="qlik-set-modal__form">
                                        <div class="qlik-set-modal__header">
                                            <div>
                                                <h3 class="qlik-set-modal__title" data-tableau-i18n="tableauCalc.catalog.rawData"></h3>
                                                <p class="qlik-set-small" data-tableau-i18n="tableauCalc.catalog.rawDataHint"></p>
                                            </div>
                                            <button type="button" class="tools-btn tools-btn--secondary" data-tableau-import-modal-close data-tableau-i18n="tableauCalc.modal.close"></button>
                                        </div>
                                        <div class="qlik-set-modal-tabs" role="tablist" aria-label="Edit Tableau catalog data">
                                            <button type="button" class="qlik-set-modal-tab is-active" data-tableau-import-tab="fields" aria-selected="true" data-tableau-i18n="tableauCalc.catalog.fields"></button>
                                            <button type="button" class="qlik-set-modal-tab" data-tableau-import-tab="values" aria-selected="false" data-tableau-i18n="tableauCalc.catalog.values"></button>
                                        </div>
                                        <div class="qlik-set-modal-panel is-active" data-tableau-import-panel="fields">
                                            <label class="tools-btn tools-btn--secondary qlik-set-file-button">
                                                <span data-tableau-i18n="tableauCalc.catalog.fieldsUpload"></span>
                                                <input type="file" accept=".csv,text/csv,text/plain" data-tableau-fields-file>
                                            </label>
                                            <x-tools.field label-key="tableauCalc.catalog.fields">
                                                <textarea class="pii-policy-input" rows="12" data-tableau-fields>field,type,tags
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
                                            </x-tools.field>
                                        </div>
                                        <div class="qlik-set-modal-panel" data-tableau-import-panel="values" hidden>
                                            <label class="tools-btn tools-btn--secondary qlik-set-file-button">
                                                <span data-tableau-i18n="tableauCalc.catalog.valuesUpload"></span>
                                                <input type="file" accept=".csv,text/csv,text/plain" data-tableau-values-file>
                                            </label>
                                            <x-tools.field label-key="tableauCalc.catalog.values">
                                                <textarea class="pii-policy-input" rows="12" data-tableau-values>dimension,value,label
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
                                            </x-tools.field>
                                        </div>
                                    </div>
                                </dialog>
                            </aside>

                            <main class="tableau-calc__column tableau-calc__column--formula qlik-set-composer" data-tableau-column="composer" data-qlik-column="composer">
                                <div class="qlik-set-section-title">
                                    <h3 data-tableau-i18n="tableauCalc.formula.title">Formel</h3>
                                    <p data-tableau-i18n="tableauCalc.formula.hint"></p>
                                </div>
                                <textarea class="pii-policy-input tableau-calc__formula-dropzone" rows="5" data-tableau-base-expression>SUM([Sales])</textarea>
                                <div class="qlik-set-history-bar" aria-label="Formula history">
                                    <button type="button" class="tools-btn tools-btn--secondary qlik-set-history-button" data-tableau-undo data-tableau-i18n="tableauCalc.history.undo" disabled></button>
                                    <button type="button" class="tools-btn tools-btn--secondary qlik-set-history-button" data-tableau-redo data-tableau-i18n="tableauCalc.history.redo" disabled></button>
                                </div>
                                <div class="tableau-calc__current-formula qlik-set-current-formula">
                                    <div class="tableau-calc__preview-label qlik-set-current-formula__label" data-tableau-i18n="tableauCalc.formula.current"></div>
                                    <pre class="tableau-calc__preview-code qlik-set-current-formula__pre" data-tableau-current-formula></pre>
                                </div>
                                <div class="tableau-calc__mini-grid qlik-set-mini-grid">
                                    <x-tools.field label-key="tableauCalc.formula.name">
                                        <input class="pii-policy-input" type="text" value="Sales" data-tableau-measure-name>
                                    </x-tools.field>
                                    <x-tools.field label-key="tableauCalc.formula.field">
                                        <input class="pii-policy-input" type="text" value="Sales" data-tableau-measure-field>
                                    </x-tools.field>
                                </div>
                                <div class="tableau-calc__actions qlik-set-actions">
                                    <select class="pii-policy-input tableau-calc__inline-control" data-tableau-aggregation aria-label="Aggregation">
                                        <option value="SUM">SUM</option>
                                        <option value="COUNT">COUNT</option>
                                        <option value="COUNTD">COUNTD</option>
                                        <option value="AVG">AVG</option>
                                        <option value="MIN">MIN</option>
                                        <option value="MAX">MAX</option>
                                    </select>
                                    <button type="button" class="tools-btn tools-btn--primary" data-tableau-apply-base data-tableau-i18n="tableauCalc.formula.apply"></button>
                                </div>

                                <div class="qlik-set-palette qlik-set-base-list" aria-labelledby="tableau-calc-base-list-title">
                                    <div class="qlik-set-palette__header">
                                        <div>
                                            <h4 id="tableau-calc-base-list-title" data-tableau-i18n="tableauCalc.baseList.title"></h4>
                                            <span class="qlik-set-palette__hint" data-tableau-i18n="tableauCalc.baseList.hint"></span>
                                        </div>
                                        <button type="button" class="tools-btn tools-btn--secondary qlik-set-palette__toggle" data-tableau-base-list-toggle aria-expanded="false" data-tableau-i18n="tableauCalc.baseList.show"></button>
                                    </div>
                                    <div class="qlik-set-palette__scroll qlik-set-description-body" data-tableau-base-list-body hidden>
                                        <div class="qlik-set-mini-grid qlik-set-mini-grid--one">
                                            <x-tools.field label-key="tableauCalc.baseList.active">
                                                <select class="pii-policy-input" data-tableau-base-measure-select></select>
                                            </x-tools.field>
                                        </div>
                                        <div class="qlik-set-action-row">
                                            <button type="button" class="tools-btn tools-btn--secondary" data-tableau-load-base data-tableau-i18n="tableauCalc.baseList.load"></button>
                                            <button type="button" class="tools-btn tools-btn--primary" data-tableau-save-current-base data-tableau-i18n="tableauCalc.baseList.saveCurrent"></button>
                                            <button type="button" class="tools-btn tools-btn--secondary" data-tableau-new-base data-tableau-i18n="tableauCalc.baseList.newBase"></button>
                                        </div>
                                        <div class="tableau-calc__item-list" data-tableau-base-list></div>
                                        <x-tools.field label-key="tableauCalc.baseList.csv">
                                            <textarea class="pii-policy-input qlik-set-template-text" rows="6" data-tableau-base-measures>name,expression,description_de,description_en
Sales,SUM([Sales]),Umsatz,Sales
Costs,SUM([Costs]),Kosten,Costs
Margin,SUM([Margin]),Marge,Margin
Quantity,SUM([Quantity]),Menge,Quantity</textarea>
                                        </x-tools.field>
                                        <p class="qlik-set-small" data-tableau-i18n="tableauCalc.baseList.rawHint"></p>
                                    </div>
                                </div>

                                <div class="tableau-calc__palette qlik-set-palette">
                                    <div class="tableau-calc__palette-header qlik-set-palette__header">
                                        <div>
                                            <h4 data-tableau-i18n="tableauCalc.functions.title"></h4>
                                            <span class="qlik-set-palette__hint" data-tableau-i18n="tableauCalc.functions.hint"></span>
                                        </div>
                                    </div>
                                    <div class="tableau-calc__block-grid qlik-set-block-grid qlik-set-block-grid--dense">
                                        <button type="button" class="tableau-calc__block qlik-set-block qlik-set-block--function" data-tableau-function="SUM([Sales])">SUM</button>
                                        <button type="button" class="tableau-calc__block qlik-set-block qlik-set-block--function" data-tableau-function="COUNTD([Customer])">COUNTD</button>
                                        <button type="button" class="tableau-calc__block qlik-set-block qlik-set-block--function" data-tableau-function="AVG([Margin])">AVG</button>
                                        <button type="button" class="tableau-calc__block qlik-set-block qlik-set-block--function" data-tableau-function="IF [Sales] > 0 THEN [Sales] END">IF</button>
                                        <button type="button" class="tableau-calc__block qlik-set-block qlik-set-block--function" data-tableau-function="{ FIXED [Region] : SUM([Sales]) }">LOD</button>
                                        <button type="button" class="tableau-calc__block qlik-set-block qlik-set-block--function" data-tableau-function="ZN(SUM([Sales]))">ZN</button>
                                        <button type="button" class="tableau-calc__block qlik-set-block qlik-set-block--function" data-tableau-function="RANK(SUM([Sales]))">RANK</button>
                                        <button type="button" class="tableau-calc__block qlik-set-block qlik-set-block--function" data-tableau-function="DATEPART('year', [Order Date])">DATEPART</button>
                                    </div>
                                </div>
                            </main>

                            <aside class="tableau-calc__column tableau-calc__column--rules qlik-set-filter-builder" data-tableau-column="builder" data-qlik-column="builder">
                                <div class="qlik-set-builder-tabs" role="tablist" aria-label="Tableau builder">
                                    <button type="button" class="qlik-set-builder-tab is-active" data-tableau-builder-tab="definitions" aria-selected="true" data-tableau-i18n="tableauCalc.tabs.definitions"></button>
                                    <button type="button" class="qlik-set-builder-tab" data-tableau-builder-tab="hierarchy" aria-selected="false" data-tableau-i18n="tableauCalc.tabs.hierarchy"></button>
                                    <button type="button" class="qlik-set-builder-tab" data-tableau-builder-tab="extras" aria-selected="false" data-tableau-i18n="tableauCalc.tabs.extras"></button>
                                </div>

                                <div class="tableau-calc__builder-panel qlik-set-builder-panel is-active" data-tableau-builder-panel="definitions">
                                    <div class="qlik-set-section-title">
                                        <h3 data-tableau-i18n="tableauCalc.definitions.title">Definitionen</h3>
                                        <p data-tableau-i18n="tableauCalc.definitions.hint"></p>
                                    </div>
                                    <div class="tableau-calc__stack">
                                        <div class="tableau-calc__dropzone qlik-set-filter-dropzone" data-tableau-definition-dropzone>
                                            <strong data-tableau-i18n="tableauCalc.definitions.dropTitle"></strong>
                                            <span data-tableau-i18n="tableauCalc.definitions.dropHint"></span>
                                        </div>
                                        <div class="qlik-set-mini-grid qlik-set-mini-grid--one">
                                            <x-tools.field label-key="tableauCalc.definitions.dimension">
                                                <select class="pii-policy-input" data-tableau-definition-dimension></select>
                                            </x-tools.field>
                                            <x-tools.field label-key="tableauCalc.definitions.values">
                                                <select class="pii-policy-input qlik-set-multi-select" data-tableau-definition-values multiple size="6"></select>
                                            </x-tools.field>
                                            <x-tools.field label-key="tableauCalc.definitions.name">
                                                <input class="pii-policy-input" type="text" value="Region DACH" data-tableau-definition-name>
                                            </x-tools.field>
                                        </div>
                                        <div class="qlik-set-action-row">
                                            <button type="button" class="tools-btn tools-btn--primary" data-tableau-add-definition data-tableau-i18n="tableauCalc.definitions.add"></button>
                                        </div>
                                        <p class="tableau-calc__message qlik-set-builder-message" data-tableau-message hidden></p>
                                        <div class="qlik-set-current-formula">
                                            <div class="qlik-set-current-formula__label" data-tableau-i18n="tableauCalc.definitions.preview"></div>
                                            <pre class="qlik-set-current-formula__pre" data-tableau-definition-preview></pre>
                                        </div>
                                        <div class="qlik-set-generated-list" data-tableau-definition-list aria-live="polite"></div>
                                        <details class="qlik-set-details">
                                            <summary data-tableau-i18n="tableauCalc.definitions.raw"></summary>
                                            <textarea class="pii-policy-input qlik-set-template-text" rows="8" data-tableau-definitions>name,dimensions,values,expression,description
Region DACH,Region,DACH,[Region] = 'DACH',DACH market
Current Year,Order Date,,YEAR([Order Date]) = YEAR(TODAY()),Current year</textarea>
                                        </details>
                                    </div>
                                </div>

                                <div class="tableau-calc__builder-panel qlik-set-builder-panel" data-tableau-builder-panel="hierarchy" hidden>
                                    <div class="qlik-set-hierarchy">
                                        <div class="qlik-set-section-title">
                                            <h3 data-tableau-i18n="tableauCalc.hierarchy.title">Hierarchie</h3>
                                            <p data-tableau-i18n="tableauCalc.hierarchy.hint"></p>
                                        </div>
                                        <div class="tableau-calc__dropzone qlik-set-hierarchy-dropzone" data-tableau-hierarchy-dropzone>
                                            <strong data-tableau-i18n="tableauCalc.hierarchy.dropTitle"></strong>
                                            <span data-tableau-i18n="tableauCalc.hierarchy.dropHint"></span>
                                        </div>
                                        <div class="qlik-set-hierarchy-preview" data-tableau-hierarchy-preview aria-live="polite"></div>
                                        <textarea class="pii-policy-input qlik-set-hierarchy-text" rows="6" data-tableau-hierarchy>Region
  Country
    City
Category</textarea>
                                    </div>
                                    <div class="qlik-set-child-tree">
                                        <div class="qlik-set-section-title">
                                            <h3 data-tableau-i18n="tableauCalc.tree.title"></h3>
                                            <p data-tableau-i18n="tableauCalc.tree.hint"></p>
                                        </div>
                                        <div class="qlik-set-tree-preview" data-tableau-hierarchy-list aria-live="polite"></div>
                                    </div>
                                </div>

                                <div class="tableau-calc__builder-panel qlik-set-builder-panel" data-tableau-builder-panel="extras" hidden>
                                    <div class="qlik-set-section-title">
                                        <h3 data-tableau-i18n="tableauCalc.extras.title"></h3>
                                        <p data-tableau-i18n="tableauCalc.extras.hint"></p>
                                    </div>
                                    <x-tools.field label-key="tableauCalc.extras.mode">
                                        <select class="pii-policy-input" data-tableau-mode>
                                            <option value="single" data-tableau-i18n="tableauCalc.extras.modeSingle"></option>
                                            <option value="combined" data-tableau-i18n="tableauCalc.extras.modeCombined"></option>
                                            <option value="dimension-group" data-tableau-i18n="tableauCalc.extras.modeDimensionGroup"></option>
                                        </select>
                                    </x-tools.field>
                                    <x-tools.field label-key="tableauCalc.extras.lodDimensions">
                                        <input class="pii-policy-input" type="text" value="Region" data-tableau-lod-dimensions placeholder="Region, Country">
                                    </x-tools.field>
                                    <p class="qlik-set-small" data-tableau-i18n="tableauCalc.extras.lodHint"></p>
                                    <div class="qlik-set-section-title">
                                        <h3 data-tableau-i18n="tableauCalc.description.title"></h3>
                                        <p data-tableau-i18n="tableauCalc.description.hint"></p>
                                    </div>
                                    <x-tools.field label-key="tableauCalc.description.de">
                                        <textarea class="pii-policy-input" rows="3" data-tableau-description-de>{baseDescription} Erweiterung: {definition}. Bedingung: {condition}.</textarea>
                                    </x-tools.field>
                                    <x-tools.field label-key="tableauCalc.description.en">
                                        <textarea class="pii-policy-input" rows="3" data-tableau-description-en>{baseDescription} Extension: {definition}. Condition: {condition}.</textarea>
                                    </x-tools.field>
                                </div>
                            </aside>
                        </div>
                    </div>
                </div>
            </x-tools.panel>

            <section class="pii-policy-panel pii-policy-panel--code qlik-set-output-panel-wrap" aria-label="Tableau output">
                <div class="qlik-set-output-tabbar">
                    <div class="qlik-set-output-tabs" role="tablist" aria-label="Tableau output">
                        <button type="button" class="qlik-set-output-tab is-active" data-tableau-tab="calculations" aria-controls="tableau-output-calculations" aria-selected="true" data-tableau-i18n="tableauCalc.output.calcs"></button>
                        <button type="button" class="qlik-set-output-tab" data-tableau-tab="lod" aria-controls="tableau-output-lod" aria-selected="false" data-tableau-i18n="tableauCalc.output.lod"></button>
                        <button type="button" class="qlik-set-output-tab" data-tableau-tab="hierarchy" aria-controls="tableau-output-hierarchy" aria-selected="false" data-tableau-i18n="tableauCalc.output.hierarchy"></button>
                        <button type="button" class="qlik-set-output-tab" data-tableau-tab="definitions" aria-controls="tableau-output-definitions" aria-selected="false" data-tableau-i18n="tableauCalc.output.defs"></button>
                        <button type="button" class="qlik-set-output-tab" data-tableau-tab="csv" aria-controls="tableau-output-csv" aria-selected="false" data-tableau-i18n="tableauCalc.output.csv"></button>
                    </div>
                </div>

                <div id="tableau-output-calculations" class="qlik-set-output-panel is-active" data-tableau-output-panel="calculations">
                    <div class="qlik-set-output-actions">
                        <button type="button" class="tools-btn" data-tableau-copy="calculations" data-tableau-i18n="tableauCalc.copy"></button>
                        <button type="button" class="tools-btn tools-btn--secondary" data-tableau-download-xlsx="calculations" data-tableau-i18n="tableauCalc.downloadXlsx"></button>
                        <button type="button" class="tools-btn tools-btn--secondary" data-tableau-download-csv data-tableau-i18n="tableauCalc.downloadCsv"></button>
                    </div>
                    <pre class="pii-policy-code" data-tableau-output="calculations"></pre>
                </div>
                <div id="tableau-output-lod" class="qlik-set-output-panel" data-tableau-output-panel="lod" hidden>
                    <div class="qlik-set-output-actions">
                        <button type="button" class="tools-btn" data-tableau-copy="lod" data-tableau-i18n="tableauCalc.copy"></button>
                    </div>
                    <pre class="pii-policy-code" data-tableau-output="lod"></pre>
                </div>
                <div id="tableau-output-hierarchy" class="qlik-set-output-panel" data-tableau-output-panel="hierarchy" hidden>
                    <div class="qlik-set-output-actions">
                        <button type="button" class="tools-btn" data-tableau-copy="hierarchy" data-tableau-i18n="tableauCalc.copy"></button>
                    </div>
                    <pre class="pii-policy-code" data-tableau-output="hierarchy"></pre>
                </div>
                <div id="tableau-output-definitions" class="qlik-set-output-panel" data-tableau-output-panel="definitions" hidden>
                    <div class="qlik-set-output-actions">
                        <button type="button" class="tools-btn" data-tableau-copy="definitions" data-tableau-i18n="tableauCalc.copy"></button>
                    </div>
                    <pre class="pii-policy-code" data-tableau-output="definitions"></pre>
                </div>
                <div id="tableau-output-csv" class="qlik-set-output-panel" data-tableau-output-panel="csv" hidden>
                    <div class="qlik-set-output-actions">
                        <button type="button" class="tools-btn" data-tableau-copy="csv" data-tableau-i18n="tableauCalc.copy"></button>
                        <button type="button" class="tools-btn tools-btn--secondary" data-tableau-download-csv data-tableau-i18n="tableauCalc.downloadCsv"></button>
                    </div>
                    <pre class="pii-policy-code" data-tableau-output="csv"></pre>
                </div>
            </section>
        </div>
    </x-tools.generator-page>
@endsection
