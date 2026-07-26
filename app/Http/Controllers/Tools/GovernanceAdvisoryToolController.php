<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GovernanceAdvisoryToolController extends Controller
{
    public function show(Request $request): View
    {
        $toolId = $request->route('toolId');
        if (! is_string($toolId) || ! isset($this->tools()[$toolId])) {
            throw new NotFoundHttpException;
        }

        return view('tools.governance-advisory.show', [
            'toolId' => $toolId,
            'tool' => $this->tools()[$toolId],
        ]);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function tools(): array
    {
        return [
            'kpi-requirements-intake' => [
                'title' => ['de' => 'KPI Requirements Intake Form', 'en' => 'KPI Requirements Intake Form'],
                'lead' => [
                    'de' => 'Sammelt KPI-Anforderungen so, dass daraus Definition, Grain, Owner, Source-Felder und erste Mart-Fragen entstehen.',
                    'en' => 'Collect KPI requirements so definition, grain, owner, source fields, and first mart questions become clear.',
                ],
                'icon' => 'fa-gauge-high',
                'question' => ['de' => 'Welche KPI wollen wir wirklich entscheiden?', 'en' => 'Which KPI are we really deciding with?'],
                'helps' => [
                    'Geschaeftsfrage, Entscheidung und KPI-Zweck klaeren.',
                    'Formel, Grain, Zeitlogik, Filter und Dimensionen erfassen.',
                    'Owner, Approver und Akzeptanzbeispiel sichtbar machen.',
                    'Offene Definitionsfragen markieren, bevor BI gebaut wird.',
                ],
                'inputs' => ['Business question', 'KPI name', 'Formula in words', 'Grain', 'Time logic', 'Dimensions', 'Owner', 'Acceptance example'],
                'outputs' => ['KPI Card', 'Open questions', 'Fact/dimension hints', 'DQ rule candidates'],
                'template' => [
                    'KPI',
                    'Business question',
                    'Decision supported',
                    'Formula',
                    'Grain',
                    'Time logic',
                    'Dimensions',
                    'Owner / approver',
                    'Acceptance example',
                ],
                'links' => [
                    ['route' => 'tools.kpi-definition', 'label' => 'Open KPI Definition Card'],
                    ['route' => 'tools.report-inventory', 'label' => 'Check Report Inventory'],
                ],
            ],
            'source-scope-builder' => [
                'title' => ['de' => 'Source Scope Builder', 'en' => 'Source Scope Builder'],
                'lead' => [
                    'de' => 'Bereitet eine Quellanbindung vor: must-have Objekte, optionale Tabellen, Skip-Ballast, PII/DSDR und KPI-Nutzen.',
                    'en' => 'Prepare a source load: must-have objects, optional tables, skip ballast, PII/DSDR, and KPI usefulness.',
                ],
                'icon' => 'fa-database',
                'question' => ['de' => 'Was laden wir aus dieser Quelle und was bewusst nicht?', 'en' => 'What do we load from this source, and what do we deliberately skip?'],
                'helps' => [
                    'Supplier auswaehlen und Kernobjekte gegen KPI-Zweck pruefen.',
                    'PII, Freitext, Anhaenge und DSDR-Suchkeys frueh markieren.',
                    'RAW/Bronze Scope von Curated/Mart Scope trennen.',
                    'Skip-Entscheidungen mit Grund dokumentieren.',
                ],
                'inputs' => ['Supplier', 'Target KPIs', 'Required dimensions', 'Refresh cadence', 'History need', 'PII risk', 'System owner'],
                'outputs' => ['Must-have load scope', 'Optional scope', 'Skip list', 'PII/DSDR watchlist', 'Review questions'],
                'template' => ['Supplier', 'Use case', 'Must-have entities', 'Optional entities', 'Skip entities', 'PII fields', 'DSDR keys', 'Refresh', 'Owner'],
                'links' => [
                    ['route' => 'suppliers.index', 'label' => 'Open Supplier Library'],
                    ['route' => 'tools.meta-export-generator', 'label' => 'Generate metadata export'],
                ],
            ],
            'mart-design-brief-generator' => [
                'title' => ['de' => 'Mart Design Brief Generator', 'en' => 'Mart Design Brief Generator'],
                'lead' => [
                    'de' => 'Uebersetzt KPI Cards und Source Scope in ein kompaktes Briefing fuer Facts, Dimensions, Grain, History und Tests.',
                    'en' => 'Translate KPI cards and source scope into a compact brief for facts, dimensions, grain, history, and tests.',
                ],
                'icon' => 'fa-table-columns',
                'question' => ['de' => 'Welche Tabellenstruktur traegt diese KPI sauber?', 'en' => 'Which table structure carries this KPI cleanly?'],
                'helps' => [
                    'Fact-Kandidat, Dimensionen und Grain aus KPI-Anforderungen ableiten.',
                    'History/SCD-Bedarf und Snapshot-Fragen markieren.',
                    'Governance-Meta und DQ Tests als Teil des Designs behandeln.',
                    'Analytics Engineering Brief statt Bauchgefuehl erzeugen.',
                ],
                'inputs' => ['KPI cards', 'Source scope', 'Business grain', 'Dimensions', 'SCD/history need', 'Target platform', 'BI tool'],
                'outputs' => ['Fact candidate', 'Dimension candidates', 'Grain statement', 'Measure location', 'DQ tests', 'schema.yml hints'],
                'template' => ['Mart name', 'Primary fact grain', 'Measures', 'Dimensions', 'Source entities', 'History strategy', 'DQ gates', 'Owner'],
                'links' => [
                    ['route' => 'tools.kpi-requirements-intake', 'label' => 'Start KPI Intake'],
                    ['route' => 'tools.dbt-dq-rules-generator', 'label' => 'Generate DQ rules'],
                ],
            ],
            'governance-stack-advisor' => [
                'title' => ['de' => 'Governance Stack Advisor', 'en' => 'Governance Stack Advisor'],
                'lead' => [
                    'de' => 'Fuehrt von Cloud-, BI-, Catalog-, Residency- und Skill-Fragen zu einer belastbaren Stack-Shortlist.',
                    'en' => 'Move from cloud, BI, catalog, residency, and skill questions to a defensible stack shortlist.',
                ],
                'icon' => 'fa-layer-group',
                'question' => ['de' => 'Welcher Governance Stack passt zu Ziel, Team und Risiko?', 'en' => 'Which governance stack fits the goal, team, and risk?'],
                'helps' => [
                    'Fabric, Databricks, Snowflake/dbt, GCP, SAP und Open Source vergleichbar machen.',
                    'Residency, Zertifikate, BI-Praeferenz und Catalog-Reife einordnen.',
                    'Team-Skills und Betriebsmodell als Entscheidungsfaktor nutzen.',
                    'Shortlist und offene Architekturfragen dokumentieren.',
                ],
                'inputs' => ['Cloud preference', 'Data residency', 'BI tool', 'Catalog maturity', 'Security pressure', 'Team skills', 'Budget model'],
                'outputs' => ['Stack shortlist', 'Decision risks', 'Learning path', 'Certification links', 'Next tools'],
                'template' => ['Candidate stack', 'Why it fits', 'Risks', 'Required skills', 'Certifications', 'Open decisions', 'Pilot scope'],
                'links' => [
                    ['route' => 'resources.index', 'label' => 'Open Vendor Resources'],
                    ['route' => 'tools.architecture-fit', 'label' => 'Run Architecture Fit'],
                ],
            ],
            'pii-dsdr-readiness-checker' => [
                'title' => ['de' => 'PII/DSDR Readiness Checker', 'en' => 'PII/DSDR Readiness Checker'],
                'lead' => [
                    'de' => 'Prueft vor dem Load Personenbezug, Freitext, Kopien, DSDR-Suchkeys, Retention und Governance Gates.',
                    'en' => 'Check personal data, free text, copies, DSDR search keys, retention, and governance gates before loading.',
                ],
                'icon' => 'fa-shield-halved',
                'question' => ['de' => 'Koennen wir diese Daten verantwortbar laden und wiederfinden?', 'en' => 'Can we load and find this data responsibly?'],
                'helps' => [
                    'Direkte Identifier, Quasi-Identifier und Workforce Data markieren.',
                    'DSDR-Suchpfade ueber RAW, Curated, Mart, BI und Activation sichtbar machen.',
                    'Freitext/Anhaenge und Retention als Review-Gates behandeln.',
                    'Policy- und Masking-Entscheidungen vorbereiten.',
                ],
                'inputs' => ['Supplier', 'Person type', 'Identifiers', 'Free text', 'Attachments', 'Warehouse copies', 'BI extracts', 'Retention'],
                'outputs' => ['PII watchlist', 'DSDR search path', 'Risk heatmap', 'Policy questions', 'Gate checklist'],
                'template' => ['Dataset', 'Person type', 'Identifiers', 'Sensitive fields', 'Copies', 'DSDR keys', 'Retention', 'Controls'],
                'links' => [
                    ['route' => 'tools.pii-policy-generator', 'label' => 'Generate PII policy'],
                    ['route' => 'tools.pii-recommend-generator', 'label' => 'Run PII Recommend'],
                ],
            ],
            'decision-brief-generator' => [
                'title' => ['de' => 'Decision Brief Generator', 'en' => 'Decision Brief Generator'],
                'lead' => [
                    'de' => 'Verdichtet Discovery-Ergebnisse zu einer kompakten Entscheidungsvorlage fuer Sponsor, Architekturboard oder ersten Sprint.',
                    'en' => 'Condense discovery results into a compact decision brief for sponsor, architecture board, or first sprint.',
                ],
                'icon' => 'fa-file-signature',
                'question' => ['de' => 'Welche Entscheidung treffen wir jetzt und was bleibt offen?', 'en' => 'Which decision do we make now, and what remains open?'],
                'helps' => [
                    'Stakeholder, KPI, Source Scope, Risiken und Impact/Effort zusammenfuehren.',
                    'Pilot-Scope und Nicht-Ziele sauber abgrenzen.',
                    'Offene Entscheidungen und Annahmen sichtbar machen.',
                    'Sprint Planner oder Projektstart mit einem Briefing fuettern.',
                ],
                'inputs' => ['Stakeholders', 'KPI cards', 'Source scope', 'Risk backlog', 'Architecture fit', 'Impact/effort', 'Assumptions'],
                'outputs' => ['One-page decision brief', 'Pilot scope', 'Open decisions', 'First sprint candidates', 'Risk notes'],
                'template' => ['Decision', 'Context', 'Recommended option', 'Pilot scope', 'Risks', 'Open questions', 'Next sprint'],
                'links' => [
                    ['route' => 'tools.impact-effort', 'label' => 'Prioritize impact/effort'],
                    ['route' => 'sprint-planner.index', 'label' => 'Open Sprint Planner'],
                ],
            ],
            'vendor-learning-path-builder' => [
                'title' => ['de' => 'Vendor Learning Path Builder', 'en' => 'Vendor Learning Path Builder'],
                'lead' => [
                    'de' => 'Ordnet offizielle Lernpfade, Zertifikate, Playbooks und Uebungen nach Rolle und Stack.',
                    'en' => 'Organize official learning paths, certifications, playbooks, and exercises by role and stack.',
                ],
                'icon' => 'fa-graduation-cap',
                'question' => ['de' => 'Was sollte ich fuer diese Rolle und diesen Stack lernen?', 'en' => 'What should I learn for this role and stack?'],
                'helps' => [
                    'Consultant, Engineer, Steward, BI und Privacy/Security Rollen unterscheiden.',
                    'Offizielle Zertifikatslinks mit praktischen Binom-Uebungen verbinden.',
                    '30-Tage Lernpfad als realistische Reihenfolge formulieren.',
                    'Zertifikate als Glaubwuerdigkeits- und Projektargument einordnen.',
                ],
                'inputs' => ['Role', 'Stack', 'Experience level', 'Target certification', 'Project goal', 'Available time'],
                'outputs' => ['30-day learning path', 'Official links', 'Practice tools', 'Playbook reading list', 'Certification notes'],
                'template' => ['Role', 'Stack', 'Week 1', 'Week 2', 'Week 3', 'Week 4', 'Official certifications', 'Practice tasks'],
                'links' => [
                    ['route' => 'resources.index', 'label' => 'Open certification resources'],
                    ['route' => 'compliance.roadmap', 'label' => 'Open certification roadmap'],
                ],
            ],
        ];
    }
}
