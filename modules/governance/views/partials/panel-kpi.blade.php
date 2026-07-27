<div class="governance-hub__section-heading governance-hub__section-heading--stack">
    <p
        class="tools-section__lead"
        data-hub-lead
        data-text-de="Stakeholder, KPI-Karten, Grain und Quelle — danach Mart Design."
        data-text-en="Stakeholders, KPI cards, grain, and source — then mart design."
    >Stakeholders, KPI cards, grain, and source — then mart design.</p>
    <div class="governance-hub__hero-actions">
        <a class="governance-hub__button governance-hub__button--primary" href="{{ locale_route('tools.kpi-requirements-intake') }}">
            <i class="fa-solid fa-gauge-high" aria-hidden="true"></i>
            <span data-text-de="KPI Intake öffnen" data-text-en="Open KPI intake">Open KPI intake</span>
        </a>
    </div>
</div>

<ol class="governance-landing__steps">
    <li>
        <strong data-text-de="Stakeholder & RACI" data-text-en="Stakeholders & RACI">Stakeholders & RACI</strong>
        <span data-text-de="Wer entscheidet, wer liefert Definitionen, wer konsumiert Reports?" data-text-en="Who decides, who supplies definitions, who consumes reports?">Who decides, who supplies definitions, who consumes reports?</span>
    </li>
    <li>
        <strong data-text-de="Business-Fragen & Report Inventory" data-text-en="Business questions & report inventory">Business questions & report inventory</strong>
        <span data-text-de="Welche Entscheidungen sollen besser werden?" data-text-en="Which decisions should improve?">Which decisions should improve?</span>
    </li>
    <li>
        <strong data-text-de="KPI Cards" data-text-en="KPI cards">KPI cards</strong>
        <span data-text-de="Definition, Formel, Grain, Owner, Beispiel." data-text-en="Definition, formula, grain, owner, example.">Definition, formula, grain, owner, example.</span>
    </li>
    <li>
        <strong data-text-de="Source Scope & Mart Design" data-text-en="Source scope & mart design">Source scope & mart design</strong>
        <span data-text-de="Tabellen, Skip-Hinweise, Facts/Dimensions ableiten." data-text-en="Derive tables, skip hints, facts/dimensions.">Derive tables, skip hints, facts/dimensions.</span>
    </li>
</ol>

<p class="governance-hub__soft-label" data-text-de="Passende Tools" data-text-en="Related tools">Related tools</p>
<x-governance.tool-cards :tools="$kpiRelatedTools" />
