<?php

namespace App\Http\Controllers\Governance;

use App\Http\Controllers\Controller;
use App\Support\ToolsNav;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class GovernanceHubController extends Controller
{
    public function index(): View
    {
        /** @var list<array<string, mixed>> $tools */
        $tools = ToolsNav::withRegisteredRoutes(config('tools.nav', []));
        $toolsById = [];
        foreach ($tools as $tool) {
            $id = is_string($tool['id'] ?? null) ? $tool['id'] : '';
            if ($id !== '') {
                $toolsById[$id] = $tool;
            }
        }

        /** @var array<string, array<string, mixed>> $stacks */
        $stacks = config('vendor-resources.stacks', []);
        /** @var list<array<string, mixed>> $resources */
        $resources = config('vendor-resources.products', []);
        /** @var list<array<string, mixed>> $suppliers */
        $suppliers = config('suppliers.products', []);
        /** @var list<array<string, mixed>> $compliance */
        $compliance = config('compliance.items', []);

        $featuredToolIds = [
            'kpi-requirements-intake',
            'source-scope-builder',
            'mart-design-brief-generator',
            'governance-stack-advisor',
            'pii-dsdr-readiness-checker',
            'decision-brief-generator',
            'vendor-learning-path-builder',
            'stakeholder-matrix',
            'kpi-definition',
            'report-inventory',
            'architecture-fit',
            'impact-effort',
            'pii-policy-generator',
            'pii-recommend-generator',
            'dbt-dq-rules-generator',
            'meta-export-generator',
        ];

        $featuredTools = [];
        foreach ($featuredToolIds as $id) {
            if (isset($toolsById[$id])) {
                $featuredTools[] = $toolsById[$id];
            }
        }

        return view('governance.index', [
            'counts' => [
                'tools' => count($tools),
                'resources' => count($resources),
                'suppliers' => count($suppliers),
                'stacks' => count($stacks),
                'compliance' => count($compliance),
            ],
            'featuredTools' => $featuredTools,
            'journeys' => $this->journeys(),
        ]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function journeys(): array
    {
        $route = static fn (string $name, array $parameters = []): ?string => Route::has($name)
            ? locale_route($name, $parameters)
            : null;

        return [
            [
                'id' => 'kpi',
                'icon' => 'fa-gauge-high',
                'label' => ['de' => 'KPI-Anforderungen sammeln', 'en' => 'Collect KPI requirements'],
                'lead' => [
                    'de' => 'Von Geschäftsfrage und Stakeholdern zu KPI Card, Grain, Owner und ersten Mart-Kandidaten.',
                    'en' => 'Move from business question and stakeholders to KPI card, grain, owner, and first mart candidates.',
                ],
                'links' => array_values(array_filter([
                    ['href' => $route('tools.stakeholder-matrix'), 'label' => ['de' => 'Stakeholder & RACI', 'en' => 'Stakeholder & RACI']],
                    ['href' => $route('tools.kpi-requirements-intake'), 'label' => ['de' => 'KPI Requirements Intake', 'en' => 'KPI Requirements Intake']],
                    ['href' => $route('tools.kpi-definition'), 'label' => ['de' => 'KPI Definition Card', 'en' => 'KPI Definition Card']],
                    ['href' => $route('tools.mart-design-brief-generator'), 'label' => ['de' => 'Mart Design Brief', 'en' => 'Mart Design Brief']],
                ], static fn (array $link): bool => is_string($link['href'] ?? null))),
            ],
            [
                'id' => 'supplier',
                'icon' => 'fa-database',
                'label' => ['de' => 'Quelle anbinden', 'en' => 'Scope a source'],
                'lead' => [
                    'de' => 'Supplier auswählen, Kernobjekte verstehen, PII/DSDR prüfen und Skip-Tabellen vor dem Load markieren.',
                    'en' => 'Pick a supplier, understand core entities, review PII/DSDR, and mark skip tables before loading.',
                ],
                'links' => array_values(array_filter([
                    ['href' => $route('tools.source-scope-builder'), 'label' => ['de' => 'Source Scope Builder', 'en' => 'Source Scope Builder']],
                    ['href' => $route('suppliers.index'), 'label' => ['de' => 'Supplier Library', 'en' => 'Supplier library']],
                    ['href' => $route('tools.pii-recommend-generator'), 'label' => ['de' => 'PII Recommend', 'en' => 'PII Recommend']],
                ], static fn (array $link): bool => is_string($link['href'] ?? null))),
            ],
            [
                'id' => 'stack',
                'icon' => 'fa-layer-group',
                'label' => ['de' => 'Stack entscheiden', 'en' => 'Choose a stack'],
                'lead' => [
                    'de' => 'Fabric, Databricks, Snowflake, dbt, BI und Catalog nicht isoliert betrachten, sondern als Governance-Stack.',
                    'en' => 'Treat Fabric, Databricks, Snowflake, dbt, BI, and catalog tools as one governance stack.',
                ],
                'links' => array_values(array_filter([
                    ['href' => $route('tools.governance-stack-advisor'), 'label' => ['de' => 'Governance Stack Advisor', 'en' => 'Governance Stack Advisor']],
                    ['href' => $route('resources.index'), 'label' => ['de' => 'Stack Filter', 'en' => 'Stack filter']],
                    ['href' => $route('tools.architecture-fit'), 'label' => ['de' => 'Architecture Fit', 'en' => 'Architecture fit']],
                    ['href' => $route('tools.vendor-learning-path-builder'), 'label' => ['de' => 'Learning Path', 'en' => 'Learning path']],
                ], static fn (array $link): bool => is_string($link['href'] ?? null))),
            ],
            [
                'id' => 'pii',
                'icon' => 'fa-shield-halved',
                'label' => ['de' => 'PII und DSDR absichern', 'en' => 'Secure PII and DSDR'],
                'lead' => [
                    'de' => 'Personenbezug, Freitext, Kopien, Maskierung und Nachweisbarkeit als frühen Projektpfad behandeln.',
                    'en' => 'Handle personal data, free text, copies, masking, and evidence as an early project path.',
                ],
                'links' => array_values(array_filter([
                    ['href' => $route('tools.pii-dsdr-readiness-checker'), 'label' => ['de' => 'PII/DSDR Readiness', 'en' => 'PII/DSDR Readiness']],
                    ['href' => $route('tools.pii-policy-generator'), 'label' => ['de' => 'PII Policy', 'en' => 'PII Policy']],
                    ['href' => $route('tools.pii-unreviewed-gate-generator'), 'label' => ['de' => 'PII Table Gate', 'en' => 'PII Table Gate']],
                    ['href' => $route('tools.decision-brief-generator'), 'label' => ['de' => 'Decision Brief', 'en' => 'Decision Brief']],
                ], static fn (array $link): bool => is_string($link['href'] ?? null))),
            ],
        ];
    }
}
