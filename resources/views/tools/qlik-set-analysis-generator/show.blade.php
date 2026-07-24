@extends('layouts.tools', [
    'viteEntries' => ['resources/js/tools/qlik-set-analysis-generator/index.js'],
])

@section('title', 'Qlik Set Analysis Generator — ' . config('app.name'))

@section('content')
    <x-tools.generator-page
        title-key="qlikSet.pageTitle"
        lead-key="qlikSet.pageLead"
        tool-id="qlik-set-analysis-generator"
        app-id="qlik-set-analysis-generator-app"
        title-badge="V!"
    >
        <section class="tools-panel qlik-set-help is-collapsed" aria-labelledby="qlik-set-help-title">
            <div class="qlik-set-help__header">
                <div>
                    <h2 id="qlik-set-help-title" class="tools-panel__title" data-i18n="qlikSet.help.title"></h2>
                    <p class="qlik-set-help__lead" data-i18n="qlikSet.help.lead"></p>
                </div>
                <button type="button" id="qlik-set-help-toggle" class="tools-btn tools-btn--secondary qlik-set-help__toggle" aria-expanded="false" data-i18n="qlikSet.help.show"></button>
            </div>
            <div id="qlik-set-help-body" class="qlik-set-help__body" hidden>
                <div class="qlik-set-help__tabs" role="tablist" aria-label="Qlik Set Analysis help">
                    <button type="button" class="qlik-set-help__tab is-active" data-qlik-help-tab="quick" data-i18n="qlikSet.howto.summary"></button>
                    <button type="button" class="qlik-set-help__tab" data-qlik-help-tab="fields" data-i18n="qlikSet.fields.summary"></button>
                    <button type="button" class="qlik-set-help__tab" data-qlik-help-tab="master" data-i18n="qlikSet.masterItems.summary"></button>
                    <button type="button" class="qlik-set-help__tab" data-qlik-help-tab="cases" data-i18n="qlikSet.useCases.summary"></button>
                </div>
                <div class="qlik-set-help__panel is-active" data-qlik-help-panel="quick">
                    <p data-i18n="qlikSet.howto.intro"></p>
                    <ol class="qlik-set-help__steps">
                        <li data-i18n="qlikSet.howto.step1"></li>
                        <li data-i18n="qlikSet.howto.step2"></li>
                        <li data-i18n="qlikSet.howto.step3"></li>
                    </ol>
                    <p class="qlik-set-help__note" data-i18n="qlikSet.howto.tip"></p>
                </div>
                <div class="qlik-set-help__panel" data-qlik-help-panel="fields" hidden>
                    <p data-i18n="qlikSet.export.intro"></p>
                    <ol class="qlik-set-help__steps">
                        <li data-i18n="qlikSet.export.step1"></li>
                        <li data-i18n="qlikSet.export.step2"></li>
                        <li data-i18n="qlikSet.export.step3"></li>
                        <li data-i18n="qlikSet.export.step4"></li>
                    </ol>
                    <pre class="pii-policy-code qlik-set-example-csv">dimension,value,label
Region,DACH,DACH
Region,North America,North America
Channel,Online,Online
Channel,Retail,Retail</pre>
                </div>
                <div class="qlik-set-help__panel" data-qlik-help-panel="master" hidden>
                    <p data-i18n="qlikSet.masterItems.intro"></p>
                    <ol class="qlik-set-help__steps">
                        <li data-i18n="qlikSet.masterItems.step1"></li>
                        <li data-i18n="qlikSet.masterItems.step2"></li>
                        <li data-i18n="qlikSet.masterItems.step3"></li>
                        <li data-i18n="qlikSet.masterItems.step4"></li>
                        <li data-i18n="qlikSet.masterItems.step5"></li>
                    </ol>
                    <p class="qlik-set-help__note" data-i18n="qlikSet.masterItems.tip"></p>
                </div>
                <div class="qlik-set-help__panel" data-qlik-help-panel="cases" hidden>
                    <p data-i18n="qlikSet.useCases.intro"></p>
                    <ul class="qlik-set-help__cases">
                        <li data-i18n="qlikSet.useCases.case1"></li>
                        <li data-i18n="qlikSet.useCases.case2"></li>
                        <li data-i18n="qlikSet.useCases.case3"></li>
                        <li data-i18n="qlikSet.useCases.case4"></li>
                        <li data-i18n="qlikSet.useCases.case5"></li>
                        <li data-i18n="qlikSet.useCases.case6"></li>
                    </ul>
                </div>
            </div>
        </section>

        <datalist id="qlik-set-field-options"></datalist>
        <datalist id="qlik-set-variable-options"></datalist>

        <x-tools.panel heading-id="qlik-set-workbench-title" title-key="qlikSet.workbench.title" description-key="qlikSet.workbench.description">
            <div class="qlik-set-workbench-toolbar" aria-label="Workbench layout">
                <button type="button" class="tools-btn tools-btn--secondary qlik-set-layout-button is-active" data-qlik-layout-toggle="catalog" data-i18n="qlikSet.layout.catalog"></button>
                <button type="button" class="tools-btn tools-btn--secondary qlik-set-layout-button is-active" data-qlik-layout-toggle="composer" data-i18n="qlikSet.layout.composer"></button>
                <button type="button" class="tools-btn tools-btn--secondary qlik-set-layout-button is-active" data-qlik-layout-toggle="builder" data-i18n="qlikSet.layout.builder"></button>
                <button type="button" class="tools-btn tools-btn--secondary" data-qlik-layout-preset="focus-formula" data-i18n="qlikSet.layout.focusFormula"></button>
                <button type="button" class="tools-btn tools-btn--secondary" data-qlik-layout-preset="all" data-i18n="qlikSet.layout.showAll"></button>
            </div>
            <div class="qlik-set-workbench" data-layout-catalog="open" data-layout-composer="open" data-layout-builder="open">
                <aside class="qlik-set-rail" data-qlik-column="catalog" aria-labelledby="qlik-set-assets-title">
                    <div class="qlik-set-rail__header">
                        <h3 id="qlik-set-assets-title" data-i18n="qlikSet.assets.title"></h3>
                        <div class="qlik-set-upload-row">
                            <label class="tools-btn tools-btn--secondary qlik-set-file-button">
                                <span data-i18n="qlikSet.catalog.fieldsUpload"></span>
                                <input id="qlik-set-fields-file" type="file" accept=".csv,text/csv,text/plain">
                            </label>
                            <label class="tools-btn tools-btn--secondary qlik-set-file-button">
                                <span data-i18n="qlikSet.catalog.variableUpload"></span>
                                <input id="qlik-set-vars-file" type="file" accept=".csv,text/csv,text/plain">
                            </label>
                        </div>
                    </div>
                    <p class="qlik-set-small" data-i18n="qlikSet.assets.hint"></p>
                    <div class="qlik-set-chip-row qlik-set-chip-row--stacked" id="qlik-set-field-chips" aria-live="polite"></div>
                    <div class="qlik-set-chip-row qlik-set-chip-row--stacked" id="qlik-set-variable-chips" aria-live="polite"></div>
                    <button type="button" id="qlik-set-import-modal-open" class="tools-btn tools-btn--secondary" data-i18n="qlikSet.catalog.rawData"></button>
                    <dialog id="qlik-set-import-modal" class="qlik-set-modal">
                        <div class="qlik-set-modal__form">
                            <div class="qlik-set-modal__header">
                                <div>
                                    <h3 class="qlik-set-modal__title" data-i18n="qlikSet.catalog.rawData"></h3>
                                    <p class="qlik-set-small" data-i18n="qlikSet.catalog.rawDataHint"></p>
                                </div>
                                <button type="button" id="qlik-set-import-modal-close" class="tools-btn tools-btn--secondary" data-i18n="qlikSet.modal.close"></button>
                            </div>
                            <div class="qlik-set-mini-grid">
                                <x-tools.field label-key="qlikSet.catalog.fields">
                                    <textarea id="qlik-set-fields" class="pii-policy-input" rows="12">field,type,tags
Region,dimension,geo
Country,dimension,geo
City,dimension,geo
Channel,dimension,sales-channel
Salesperson,dimension,sales-org
Customer,dimension,customer
Customer Group,dimension,customer
Product,dimension,product
Product Category,dimension,product
Segment,dimension,customer
Order Date,date,calendar
Year,date,calendar
Month,date,calendar
Sales,measure,currency
Quantity,measure,number
Discount,measure,percent</textarea>
                                </x-tools.field>
                                <x-tools.field label-key="qlikSet.catalog.variables">
                                    <textarea id="qlik-set-vars" class="pii-policy-input" rows="12">name,definition,description
vSales,Sum(Sales),Base sales measure
vQuantity,Sum(Quantity),Base quantity measure
vAvgDiscount,Avg(Discount),Average discount measure</textarea>
                                </x-tools.field>
                            </div>
                        </div>
                    </dialog>
                </aside>

                <section class="qlik-set-composer" data-qlik-column="composer" aria-labelledby="qlik-set-formula-title">
                    <div class="qlik-set-section-title">
                        <h3 id="qlik-set-formula-title" data-i18n="qlikSet.formula.title"></h3>
                        <p data-i18n="qlikSet.formula.hint"></p>
                    </div>
                    <textarea id="qlik-set-base-measure" class="pii-policy-input qlik-set-formula-dropzone" rows="5" data-qlik-dropzone="formula">Sum(Sales)</textarea>
                    <div class="qlik-set-history-bar" aria-label="Formula history">
                        <button type="button" id="qlik-set-undo" class="tools-btn tools-btn--secondary qlik-set-history-button" data-i18n="qlikSet.history.undo" disabled></button>
                        <button type="button" id="qlik-set-redo" class="tools-btn tools-btn--secondary qlik-set-history-button" data-i18n="qlikSet.history.redo" disabled></button>
                        <span id="qlik-set-kpi-state" class="qlik-set-state-pill" aria-live="polite" data-i18n="qlikSet.kpis.none"></span>
                    </div>
                    <div class="qlik-set-current-formula">
                        <div class="qlik-set-current-formula__label" data-i18n="qlikSet.formula.current"></div>
                        <pre id="qlik-set-current-formula-pre" class="qlik-set-current-formula__pre"></pre>
                    </div>
                    <div class="qlik-set-actions">
                        <select id="qlik-set-aggregation" class="pii-policy-input qlik-set-inline-control" aria-label="Aggregation">
                            <option value="Sum">Sum</option>
                            <option value="Count">Count</option>
                            <option value="Avg">Avg</option>
                            <option value="Min">Min</option>
                            <option value="Max">Max</option>
                        </select>
                        <input id="qlik-set-measure-field" class="pii-policy-input qlik-set-inline-control" type="text" value="Sales" list="qlik-set-field-options" aria-label="Measure field">
                        <button type="button" id="qlik-set-apply-base" class="tools-btn tools-btn--primary" data-i18n="qlikSet.composer.apply"></button>
                        <input id="qlik-set-variable-use" class="pii-policy-input qlik-set-inline-control" type="text" value="vSales" list="qlik-set-variable-options" aria-label="Variable">
                        <button type="button" id="qlik-set-use-variable-base" class="tools-btn tools-btn--secondary" data-i18n="qlikSet.composer.useVariable"></button>
                    </div>
                    <div class="qlik-set-palette" aria-labelledby="qlik-set-palette-title">
                        <div class="qlik-set-palette__header">
                            <div>
                                <h4 id="qlik-set-palette-title" data-i18n="qlikSet.palette.title"></h4>
                                <span class="qlik-set-palette__hint" data-i18n="qlikSet.palette.hint"></span>
                            </div>
                            <button type="button" id="qlik-set-palette-toggle" class="tools-btn tools-btn--secondary qlik-set-palette__toggle" aria-expanded="true" data-i18n="qlikSet.palette.hide"></button>
                        </div>
                        <div id="qlik-set-palette-body" class="qlik-set-palette__scroll">
                            <div class="qlik-set-palette__group" aria-labelledby="qlik-set-functions-title">
                                <h5 id="qlik-set-functions-title" data-i18n="qlikSet.functions.title"></h5>
                                <div class="qlik-set-block-grid qlik-set-block-grid--dense">
                                    <button type="button" class="qlik-set-block qlik-set-block--function" data-qlik-function="Sum([Sales])">Sum</button>
                                    <button type="button" class="qlik-set-block qlik-set-block--function" data-qlik-function="Count(DISTINCT [Customer])">Count Distinct</button>
                                    <button type="button" class="qlik-set-block qlik-set-block--function" data-qlik-function="Avg([Sales])">Avg</button>
                                    <button type="button" class="qlik-set-block qlik-set-block--function" data-qlik-function="Num(Sum([Sales]), '#,##0')">Num</button>
                                    <button type="button" class="qlik-set-block qlik-set-block--function" data-qlik-function="If(Sum([Sales]) > 0, Sum([Sales]))">If</button>
                                    <button type="button" class="qlik-set-block qlik-set-block--function" data-qlik-function="Alt(Sum([Sales]), 0)">Alt</button>
                                    <button type="button" class="qlik-set-block qlik-set-block--function" data-qlik-function="RangeSum(Above(Sum([Sales]), 0, 12))">Rolling 12</button>
                                    <button type="button" class="qlik-set-block qlik-set-block--function" data-qlik-function="Aggr(Sum([Sales]), [Region])">Aggr</button>
                                    <button type="button" class="qlik-set-block qlik-set-block--function" data-qlik-function="Rank(Sum([Sales]))">Rank</button>
                                    <button type="button" class="qlik-set-block qlik-set-block--function" data-qlik-function="Pick(Match([Region], 'DACH', 'NA'), Sum([Sales]), Sum([Sales]))">Pick/Match</button>
                                    <button type="button" class="qlik-set-block qlik-set-block--function" data-qlik-function="Fractile([Sales], 0.9)">Fractile</button>
                                    <button type="button" class="qlik-set-block qlik-set-block--function" data-qlik-function="Only({$<[Region]={'DACH'}>} [Salesperson])">Only + Set</button>
                                </div>
                            </div>
                            <div class="qlik-set-palette__group" aria-labelledby="qlik-set-kpis-title">
                                <h5 id="qlik-set-kpis-title" data-i18n="qlikSet.kpis.title"></h5>
                                <p class="qlik-set-small" data-i18n="qlikSet.kpis.hint"></p>
                                <div class="qlik-set-block-grid">
                                    <button type="button" class="qlik-set-block" data-qlik-kpi="current" data-i18n="qlikSet.kpis.current"></button>
                                    <button type="button" class="qlik-set-block" data-qlik-kpi="cy" data-i18n="qlikSet.kpis.cy"></button>
                                    <button type="button" class="qlik-set-block" data-qlik-kpi="py" data-i18n="qlikSet.kpis.py"></button>
                                    <button type="button" class="qlik-set-block" data-qlik-kpi="ytd" data-i18n="qlikSet.kpis.ytd"></button>
                                    <button type="button" class="qlik-set-block" data-qlik-kpi="mtd" data-i18n="qlikSet.kpis.mtd"></button>
                                    <button type="button" class="qlik-set-block" data-qlik-kpi="last12m" data-i18n="qlikSet.kpis.last12m"></button>
                                    <button type="button" class="qlik-set-block" data-qlik-kpi="yoy" data-i18n="qlikSet.kpis.yoy"></button>
                                    <button type="button" class="qlik-set-block" data-qlik-kpi="yoyPct" data-i18n="qlikSet.kpis.yoyPct"></button>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>

                <aside class="qlik-set-filter-builder" data-qlik-column="builder" aria-labelledby="qlik-set-filter-title">
                    <div class="qlik-set-builder-tabs" role="tablist" aria-label="Set builder sections">
                        <button type="button" class="qlik-set-builder-tab is-active" data-qlik-builder-tab="filter" aria-selected="true" data-i18n="qlikSet.tabs.filter"></button>
                        <button type="button" class="qlik-set-builder-tab" data-qlik-builder-tab="hierarchy" aria-selected="false" data-i18n="qlikSet.tabs.hierarchy"></button>
                        <button type="button" class="qlik-set-builder-tab" data-qlik-builder-tab="extras" aria-selected="false" data-i18n="qlikSet.tabs.extras"></button>
                    </div>

                    <div class="qlik-set-builder-panel is-active" data-qlik-builder-panel="filter">
                        <div class="qlik-set-section-title">
                            <h3 id="qlik-set-filter-title" data-i18n="qlikSet.filter.title"></h3>
                            <p data-i18n="qlikSet.filter.hint"></p>
                        </div>
                        <div id="qlik-set-filter-dropzone" class="qlik-set-filter-dropzone" data-qlik-dropzone="filter">
                            <strong data-i18n="qlikSet.filter.dropTitle"></strong>
                            <span data-i18n="qlikSet.filter.dropText"></span>
                        </div>
                        <div class="qlik-set-mini-grid qlik-set-mini-grid--one">
                            <x-tools.field label-key="qlikSet.builder.dimension">
                                <input id="qlik-set-builder-dimension" class="pii-policy-input" type="text" value="Region" list="qlik-set-field-options">
                            </x-tools.field>
                            <x-tools.field label-key="qlikSet.builder.value">
                                <input id="qlik-set-builder-value" class="pii-policy-input" type="text" value="DACH">
                            </x-tools.field>
                            <x-tools.field label-key="qlikSet.builder.label">
                                <input id="qlik-set-builder-label" class="pii-policy-input" type="text" value="DACH">
                            </x-tools.field>
                        </div>
                        <button type="button" id="qlik-set-add-dimension-row" class="tools-btn tools-btn--primary" data-i18n="qlikSet.builder.addRow"></button>
                        <div id="qlik-set-filter-preview" class="qlik-set-filter-preview" aria-live="polite"></div>
                    </div>

                    <div class="qlik-set-builder-panel" data-qlik-builder-panel="hierarchy" hidden>
                        <div class="qlik-set-hierarchy" aria-labelledby="qlik-set-hierarchy-title">
                            <div class="qlik-set-section-title">
                                <h3 id="qlik-set-hierarchy-title" data-i18n="qlikSet.hierarchy.title"></h3>
                                <p data-i18n="qlikSet.hierarchy.hint"></p>
                            </div>
                            <div id="qlik-set-hierarchy-dropzone" class="qlik-set-hierarchy-dropzone" data-qlik-dropzone="hierarchy">
                                <strong data-i18n="qlikSet.hierarchy.dropTitle"></strong>
                                <span data-i18n="qlikSet.hierarchy.dropText"></span>
                            </div>
                            <div id="qlik-set-hierarchy-preview" class="qlik-set-hierarchy-preview" aria-live="polite"></div>
                            <textarea id="qlik-set-hierarchy-levels" class="pii-policy-input qlik-set-hierarchy-text" rows="4"></textarea>
                        </div>
                        <div class="qlik-set-child-tree" aria-labelledby="qlik-set-tree-title">
                            <div class="qlik-set-section-title">
                                <h3 id="qlik-set-tree-title" data-i18n="qlikSet.tree.title"></h3>
                                <p data-i18n="qlikSet.tree.hint"></p>
                            </div>
                            <div id="qlik-set-tree-preview" class="qlik-set-tree-preview" aria-live="polite"></div>
                        </div>
                    </div>

                    <div class="qlik-set-builder-panel" data-qlik-builder-panel="extras" hidden>
                        <div class="qlik-set-setvar-builder">
                            <div class="qlik-set-section-title">
                                <h3 data-i18n="qlikSet.setSearch.title"></h3>
                                <p data-i18n="qlikSet.setSearch.hint"></p>
                            </div>
                            <x-tools.field label-key="qlikSet.setSearch.expression">
                                <textarea id="qlik-set-search-expression" class="pii-policy-input qlik-set-hierarchy-text" rows="4">=Sum(Sales)>1000</textarea>
                            </x-tools.field>
                            <div class="qlik-set-block-grid qlik-set-block-grid--dense">
                                <button type="button" class="qlik-set-block qlik-set-block--function" data-qlik-set-search="=Sum(Sales)>1000">Sales &gt; 1000</button>
                                <button type="button" class="qlik-set-block qlik-set-block--function" data-qlik-set-search="=Count(DISTINCT OrderID)>5">Orders &gt; 5</button>
                                <button type="button" class="qlik-set-block qlik-set-block--function" data-qlik-set-search="=Rank(Sum(Sales))<=10">Top 10</button>
                                <button type="button" class="qlik-set-block qlik-set-block--function" data-qlik-set-search="=Sum({$<[Year]={$(vCurrentYear)}>} Sales)>Sum({$<[Year]={$(vPreviousYear)}>} Sales)">CY &gt; PY</button>
                            </div>
                            <button type="button" id="qlik-set-add-search-filter" class="tools-btn tools-btn--secondary" data-i18n="qlikSet.setSearch.add"></button>
                        </div>
                        <div class="qlik-set-setvar-builder">
                            <div class="qlik-set-section-title">
                                <h3 data-i18n="qlikSet.setVars.title"></h3>
                                <p data-i18n="qlikSet.setVars.hint"></p>
                            </div>
                            <div class="qlik-set-mini-grid qlik-set-mini-grid--one">
                                <x-tools.field label-key="qlikSet.setVars.name">
                                    <input id="qlik-set-set-var-name" class="pii-policy-input" type="text" value="vSetRegionDACH">
                                </x-tools.field>
                                <x-tools.field label-key="qlikSet.setVars.values">
                                    <input id="qlik-set-set-var-values" class="pii-policy-input" type="text" value="DACH,AT,CH">
                                </x-tools.field>
                            </div>
                            <button type="button" id="qlik-set-add-set-variable" class="tools-btn tools-btn--secondary" data-i18n="qlikSet.setVars.add"></button>
                        </div>
                        <details class="qlik-set-details">
                            <summary data-i18n="qlikSet.input.advanced"></summary>
                            <div class="qlik-set-mini-grid">
                                <x-tools.field label-key="qlikSet.input.measureName">
                                    <input id="qlik-set-measure-name" class="pii-policy-input" type="text" value="Sales">
                                </x-tools.field>
                                <x-tools.field label-key="qlikSet.input.baseDescription">
                                    <textarea id="qlik-set-base-description" class="pii-policy-input" rows="4">Umsatz basierend auf Sum(Sales).</textarea>
                                </x-tools.field>
                                <x-tools.field label-key="qlikSet.input.variablePrefix">
                                    <input id="qlik-set-variable-prefix" class="pii-policy-input" type="text" value="vSales">
                                </x-tools.field>
                                <x-tools.field label-key="qlikSet.input.mode">
                                    <select id="qlik-set-mode" class="pii-policy-input">
                                        <option value="single">One child per dimension value</option>
                                        <option value="combined">Combine dimensions into set variants</option>
                                        <option value="dimension-group">One child per dimension with all listed values</option>
                                    </select>
                                </x-tools.field>
                                <x-tools.field label-key="qlikSet.input.setIdentifier">
                                    <select id="qlik-set-identifier" class="pii-policy-input">
                                        <option value="$">$ current selections</option>
                                        <option value="1">1 full data set</option>
                                        <option value="">No identifier</option>
                                    </select>
                                </x-tools.field>
                                <x-tools.field label-key="qlikSet.input.assignment">
                                    <select id="qlik-set-assignment" class="pii-policy-input">
                                        <option value="=">= replace selection</option>
                                        <option value="+=">+= add values</option>
                                        <option value="-=">-= exclude values</option>
                                        <option value="*=">*= intersect values</option>
                                    </select>
                                </x-tools.field>
                                <x-tools.field label-key="qlikSet.input.expressionStyle">
                                    <select id="qlik-set-expression-style" class="pii-policy-input">
                                        <option value="inner">Inner set analysis: Sum({$&lt;...&gt;} Sales)</option>
                                        <option value="outer">Outer set analysis: {$&lt;...&gt;} Sum(Sales)</option>
                                    </select>
                                </x-tools.field>
                                <x-tools.field label-key="qlikSet.input.valueMode">
                                    <select id="qlik-set-value-mode" class="pii-policy-input">
                                        <option value="literal">Literal values: {'DACH'}</option>
                                        <option value="search">Search strings: {"=Sum(Sales)&gt;0"}</option>
                                        <option value="numeric">Numeric values: {2024}</option>
                                        <option value="wildcard">Wildcard/search text: {'*Retail*'}</option>
                                    </select>
                                </x-tools.field>
                                <x-tools.field label-key="qlikSet.input.useVariables">
                                    <select id="qlik-set-use-variables" class="pii-policy-input">
                                        <option value="yes">Create variables and use them in child measures</option>
                                        <option value="no">Generate direct expressions only</option>
                                    </select>
                                </x-tools.field>
                                <x-tools.field label-key="qlikSet.input.csvUpload">
                                    <input id="qlik-set-csv-file" class="pii-policy-input" type="file" accept=".csv,text/csv,text/plain">
                                </x-tools.field>
                                <x-tools.field label-key="qlikSet.input.dateField">
                                    <input id="qlik-set-date-field" class="pii-policy-input" type="text" value="Date">
                                </x-tools.field>
                                <x-tools.field label-key="qlikSet.input.yearField">
                                    <input id="qlik-set-year-field" class="pii-policy-input" type="text" value="Year">
                                </x-tools.field>
                            </div>
                            <x-tools.field label-key="qlikSet.input.rows">
                                <textarea id="qlik-set-rows" class="pii-policy-input" rows="8">dimension,value,label,operator,assignment
Region,DACH,DACH
Region,North America,North America
Channel,Online,Online
Channel,Retail,Retail</textarea>
                            </x-tools.field>
                            <button type="button" id="qlik-set-add-variable" class="tools-btn tools-btn--secondary" data-i18n="qlikSet.builder.addVariable"></button>
                        </details>
                    </div>
                </aside>
            </div>
        </x-tools.panel>

        <x-tools.panel-code heading-id="qlik-set-measures-title" title-key="qlikSet.output.measures" pre-id="qlik-set-measures-pre" copy-btn-id="qlik-set-copy-measures" copy-key="qlikSet.copy" />
        <x-tools.panel-code heading-id="qlik-set-variables-title" title-key="qlikSet.output.variables" pre-id="qlik-set-variables-pre" copy-btn-id="qlik-set-copy-variables" copy-key="qlikSet.copy" />
        <x-tools.panel-code heading-id="qlik-set-hierarchy-output-title" title-key="qlikSet.output.hierarchy" pre-id="qlik-set-hierarchy-pre" copy-btn-id="qlik-set-copy-hierarchy" copy-key="qlikSet.copy" />
        <x-tools.panel-code heading-id="qlik-set-time-vars-title" title-key="qlikSet.output.timeVariables" pre-id="qlik-set-time-vars-pre" copy-btn-id="qlik-set-copy-time-vars" copy-key="qlikSet.copy" />
        <x-tools.panel-code heading-id="qlik-set-modifiers-title" title-key="qlikSet.output.modifiers" pre-id="qlik-set-modifiers-pre" copy-btn-id="qlik-set-copy-modifiers" copy-key="qlikSet.copy" />
        <x-tools.panel-code heading-id="qlik-set-nested-if-title" title-key="qlikSet.output.nestedIf" pre-id="qlik-set-nested-if-pre" copy-btn-id="qlik-set-copy-nested-if" copy-key="qlikSet.copy" />
        <x-tools.panel-code heading-id="qlik-set-pick-match-title" title-key="qlikSet.output.pickMatch" pre-id="qlik-set-pick-match-pre" copy-btn-id="qlik-set-copy-pick-match" copy-key="qlikSet.copy" />
        <x-tools.panel-code heading-id="qlik-set-csv-title" title-key="qlikSet.output.csv" pre-id="qlik-set-csv-pre" copy-btn-id="qlik-set-copy-csv" copy-key="qlikSet.copy" />
    </x-tools.generator-page>
@endsection
