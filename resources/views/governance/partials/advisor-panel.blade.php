@php
    $standalone = $standalone ?? false;
    $sectionAttrs = $standalone
        ? 'class="governance-hub__section governance-advisor" id="governance-advisor" aria-labelledby="governance-advisor-title"'
        : 'class="governance-hub__section governance-advisor" id="governance-tab-advisor" aria-labelledby="governance-tab-button-advisor" data-governance-tab-panel="advisor" role="tabpanel"';
@endphp

        <section {!! $sectionAttrs !!}>

            <div class="governance-hub__section-heading governance-advisor__heading">
                <div>
                    <p class="governance-hub__eyebrow" data-text-de="Interaktive Entscheidungshilfe" data-text-en="Interactive decision aid">Interactive decision aid</p>
                    <h2
                        id="governance-advisor-title"
                        class="tools-section__title"
                        data-text-de="Beantworte kurz die Lage, dann bekommst du passende Tools, Supplier und Links"
                        data-text-en="Answer the situation, then get matching tools, suppliers, and links"
                    >Answer the situation, then get matching tools, suppliers, and links</h2>
                </div>
                <p
                    class="tools-section__lead"
                    data-hub-lead
                    data-text-de="Gedacht für drei echte Startpunkte: neu bauen, bestehende Umgebung ergänzen oder Orientierung in einer vorhandenen Landschaft finden."
                    data-text-en="Built for three real starting points: build new, extend an existing environment, or find orientation in a landscape that already exists."
                >Built for three real starting points: build new, extend an existing environment, or find orientation in a landscape that already exists.</p>
            </div>

            <div class="governance-hub__personas" data-governance-personas role="group" aria-label="Role shortcuts">
                <span class="governance-hub__personas-label" data-text-de="Rolle" data-text-en="Role">Role</span>
                <button type="button" class="governance-hub__persona" data-governance-persona="architect" data-persona-scenario="new" data-persona-goal="stack">
                    <span data-text-de="Architect" data-text-en="Architect">Architect</span>
                </button>
                <button type="button" class="governance-hub__persona" data-governance-persona="analyst" data-persona-scenario="extend" data-persona-goal="kpi">
                    <span data-text-de="Analyst" data-text-en="Analyst">Analyst</span>
                </button>
                <button type="button" class="governance-hub__persona" data-governance-persona="dpo" data-persona-scenario="help" data-persona-goal="pii">
                    <span data-text-de="DPO" data-text-en="DPO">DPO</span>
                </button>
            </div>

            <div class="governance-advisor__layout">
                <form class="governance-advisor__form" aria-label="Governance advisor questions" data-governance-advisor-form>
                    <fieldset class="governance-advisor__fieldset">
                        <legend data-text-de="Ausgangslage" data-text-en="Starting point">Starting point</legend>
                        <div class="governance-advisor__options governance-advisor__options--three">
                            <label class="governance-advisor__option">
                                <input type="radio" name="scenario" value="new" checked>
                                <span>
                                    <strong data-text-de="Ich baue neu auf" data-text-en="I build new">I build new</strong>
                                    <small data-text-de="Stack, Quellen, erste KPIs und Governance Gates klären." data-text-en="Clarify stack, sources, first KPIs, and governance gates.">Clarify stack, sources, first KPIs, and governance gates.</small>
                                </span>
                            </label>
                            <label class="governance-advisor__option">
                                <input type="radio" name="scenario" value="extend">
                                <span>
                                    <strong data-text-de="Ich ergänze Bestehendes" data-text-en="I extend existing">I extend existing</strong>
                                    <small data-text-de="Fit, Impact, neue Quelle und Abhängigkeiten prüfen." data-text-en="Check fit, impact, new source, and dependencies.">Check fit, impact, new source, and dependencies.</small>
                                </span>
                            </label>
                            <label class="governance-advisor__option">
                                <input type="radio" name="scenario" value="help">
                                <span>
                                    <strong data-text-de="Alles ist da, ich brauche Hilfe" data-text-en="Everything exists, I need help">Everything exists, I need help</strong>
                                    <small data-text-de="Stories, Ressourcen, Zertifikate und nächste Schritte finden." data-text-en="Find stories, resources, certifications, and next steps.">Find stories, resources, certifications, and next steps.</small>
                                </span>
                            </label>
                        </div>
                    </fieldset>

                    <fieldset class="governance-advisor__fieldset">
                        <legend data-text-de="Was soll entschieden werden?" data-text-en="What needs a decision?">What needs a decision?</legend>
                        <div class="governance-advisor__options">
                            @foreach ([
                                ['value' => 'stack', 'icon' => 'fa-layer-group', 'de' => 'Stack', 'en' => 'Stack'],
                                ['value' => 'supplier', 'icon' => 'fa-database', 'de' => 'Quelle/Supplier', 'en' => 'Source/supplier'],
                                ['value' => 'kpi', 'icon' => 'fa-gauge-high', 'de' => 'KPI & Mart', 'en' => 'KPI & mart'],
                                ['value' => 'pii', 'icon' => 'fa-shield-halved', 'de' => 'PII/DSDR', 'en' => 'PII/DSDR'],
                                ['value' => 'dq', 'icon' => 'fa-circle-check', 'de' => 'Datenqualität', 'en' => 'Data quality'],
                                ['value' => 'learning', 'icon' => 'fa-graduation-cap', 'de' => 'Lernen/Zertifikate', 'en' => 'Learning/certs'],
                            ] as $index => $goal)
                                <label class="governance-advisor__pill">
                                    <input type="radio" name="goal" value="{{ $goal['value'] }}" @checked($index === 0)>
                                    <span>
                                        <i class="fa-solid {{ $goal['icon'] }}" aria-hidden="true"></i>
                                        <strong data-text-de="{{ $goal['de'] }}" data-text-en="{{ $goal['en'] }}">{{ $goal['en'] }}</strong>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>

                    <div class="governance-advisor__followup" data-governance-followup>
                        <i class="fa-solid fa-route" aria-hidden="true"></i>
                        <div>
                            <span data-governance-followup-label data-text-de="Nächste Frage" data-text-en="Next question">Next question</span>
                            <strong data-governance-followup-copy>Which stack and source context defines the decision?</strong>
                        </div>
                    </div>

                    <div class="governance-advisor__select-grid">
                        <label>
                            <span
                                data-governance-domain-label
                                data-text-de="Quelltyp"
                                data-text-en="Source type"
                            >Source type</span>
                            <select name="domain">
                                <option value="unknown" data-text-de="Noch offen / gemischt" data-text-en="Open / mixed">Open / mixed</option>
                                <option value="crm" data-text-de="CRM & Revenue" data-text-en="CRM & revenue">CRM & revenue</option>
                                <option value="erp" data-text-de="ERP, Finance & Procurement" data-text-en="ERP, finance & procurement">ERP, finance & procurement</option>
                                <option value="hcm" data-text-de="HCM & Workforce" data-text-en="HCM & workforce">HCM & workforce</option>
                                <option value="collab" data-text-de="Collaboration & Service" data-text-en="Collaboration & service">Collaboration & service</option>
                                <option value="finance" data-text-de="Reguliertes Finance Reporting" data-text-en="Regulated finance reporting">Regulated finance reporting</option>
                            </select>
                        </label>
                        <label>
                            <span
                                data-governance-platform-label
                                data-text-de="Ziel-Stack"
                                data-text-en="Target stack"
                            >Target stack</span>
                            <select name="platform">
                                <option value="unknown" data-text-de="Noch offen / mehrere" data-text-en="Open / multiple">Open / multiple</option>
                                <option value="fabric" data-text-de="Microsoft Fabric / Power BI" data-text-en="Microsoft Fabric / Power BI">Microsoft Fabric / Power BI</option>
                                <option value="databricks" data-text-de="Databricks Lakehouse" data-text-en="Databricks Lakehouse">Databricks Lakehouse</option>
                                <option value="snowflake-dbt" data-text-de="Snowflake / dbt" data-text-en="Snowflake / dbt">Snowflake / dbt</option>
                                <option value="sap" data-text-de="SAP-nahe Landschaft" data-text-en="SAP-near landscape">SAP-near landscape</option>
                                <option value="opensource" data-text-de="Open Source / leichtgewichtig" data-text-en="Open source / lightweight">Open source / lightweight</option>
                            </select>
                        </label>
                    </div>

                    <div class="governance-advisor__dq" data-governance-dq-panel>
                        <div class="governance-advisor__dq-heading">
                            <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                            <span data-text-de="DQ-Entscheidung vertiefen" data-text-en="Refine DQ decision">Refine DQ decision</span>
                        </div>
                        <p
                            class="governance-advisor__dq-copy"
                            data-text-de="Data Quality ist hier ein Governance-Pfad: erst Problem und Schicht eingrenzen, dann passende Regeln, Monitoring und Gates ableiten."
                            data-text-en="Data quality is a governance path here: narrow down problem and layer first, then derive rules, monitoring, and gates."
                        >Data quality is a governance path here: narrow down problem and layer first, then derive rules, monitoring, and gates.</p>
                        <div class="governance-advisor__select-grid">
                            <label>
                                <span data-text-de="DQ Ziel" data-text-en="DQ goal">DQ goal</span>
                                <select name="dqMode">
                                    <option value="health_check" data-text-de="Health Check" data-text-en="Health check">Health check</option>
                                    <option value="known_issue" data-text-de="Bekanntes Problem" data-text-en="Known issue">Known issue</option>
                                    <option value="new_source_gate" data-text-de="Neue Quelle absichern" data-text-en="Gate a new source">Gate a new source</option>
                                    <option value="report_stabilization" data-text-de="Report stabilisieren" data-text-en="Stabilize a report">Stabilize a report</option>
                                    <option value="mart_quality_gate" data-text-de="Mart Quality Gate" data-text-en="Mart quality gate">Mart quality gate</option>
                                </select>
                            </label>
                            <label>
                                <span data-text-de="DQ Schicht" data-text-en="DQ layer">DQ layer</span>
                                <select name="dqLayer">
                                    <option value="source" data-text-de="Source/API" data-text-en="Source/API">Source/API</option>
                                    <option value="raw" data-text-de="Raw/Ingestion" data-text-en="Raw/ingestion">Raw/ingestion</option>
                                    <option value="transform" data-text-de="Transformation" data-text-en="Transformation">Transformation</option>
                                    <option value="mart" data-text-de="Mart" data-text-en="Mart">Mart</option>
                                    <option value="semantic" data-text-de="Semantic Layer" data-text-en="Semantic layer">Semantic layer</option>
                                    <option value="bi" data-text-de="BI Report" data-text-en="BI report">BI report</option>
                                    <option value="master_data" data-text-de="Stammdaten" data-text-en="Master data">Master data</option>
                                </select>
                            </label>
                        </div>
                        <fieldset class="governance-advisor__fieldset">
                            <legend data-text-de="Fehlerklasse" data-text-en="Issue class">Issue class</legend>
                            <div class="governance-advisor__options">
                                @foreach ([
                                    ['value' => 'completeness', 'de' => 'Vollständigkeit', 'en' => 'Completeness'],
                                    ['value' => 'duplicates', 'de' => 'Duplikate', 'en' => 'Duplicates'],
                                    ['value' => 'freshness', 'de' => 'Aktualität', 'en' => 'Freshness'],
                                    ['value' => 'value_range', 'de' => 'Wertebereich', 'en' => 'Value range'],
                                    ['value' => 'referential_integrity', 'de' => 'Referenzen', 'en' => 'References'],
                                    ['value' => 'business_rule', 'de' => 'Business-Regel', 'en' => 'Business rule'],
                                    ['value' => 'pii_access', 'de' => 'PII/Access', 'en' => 'PII/access'],
                                    ['value' => 'unknown', 'de' => 'Unbekannt', 'en' => 'Unknown'],
                                ] as $issue)
                                    <label class="governance-advisor__pill governance-advisor__pill--compact">
                                        <input type="checkbox" name="dqIssues[]" value="{{ $issue['value'] }}" @checked($issue['value'] === 'completeness')>
                                        <span>
                                            <strong data-text-de="{{ $issue['de'] }}" data-text-en="{{ $issue['en'] }}">{{ $issue['en'] }}</strong>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>
                    </div>
                </form>

                <aside class="governance-advisor__results" aria-live="polite">
                    <div class="governance-advisor__result-header">
                        <p class="governance-hub__eyebrow" data-text-de="Empfehlung" data-text-en="Recommendation">Recommendation</p>
                        <h3 data-governance-advisor-summary>Start with the stack decision, then validate sources and governance gates.</h3>
                    </div>
                    <div class="governance-advisor__result-grid" data-governance-advisor-results></div>
                </aside>
            </div>
        </section>
