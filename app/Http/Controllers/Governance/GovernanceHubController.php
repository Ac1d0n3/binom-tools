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

        $supplierIds = ['salesforce', 'hubspot', 'sap-s4hana', 'workday', 'servicenow', 'sharepoint'];
        $featuredSuppliers = $this->pickById($suppliers, $supplierIds);

        return view('governance.index', [
            'counts' => [
                'tools' => count($tools),
                'resources' => count($resources),
                'suppliers' => count($suppliers),
                'stacks' => count($stacks),
                'compliance' => count($compliance),
            ],
            'featuredTools' => $featuredTools,
            'featuredSuppliers' => $featuredSuppliers,
            'journeys' => $this->journeys(),
        ]);
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @param  list<string>  $ids
     * @return list<array<string, mixed>>
     */
    private function pickById(array $items, array $ids): array
    {
        $byId = [];
        foreach ($items as $item) {
            $id = is_string($item['id'] ?? null) ? $item['id'] : '';
            if ($id !== '') {
                $byId[$id] = $item;
            }
        }

        $picked = [];
        foreach ($ids as $id) {
            if (isset($byId[$id])) {
                $picked[] = $byId[$id];
            }
        }

        return $picked;
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
                    'de' => 'Von Geschaeftsfrage und Stakeholdern zu KPI Card, Grain, Owner und ersten Mart-Kandidaten.',
                    'en' => 'Move from business question and stakeholders to KPI card, grain, owner, and first mart candidates.',
                ],
                'links' => array_values(array_filter([
                    ['href' => $route('tools.stakeholder-matrix'), 'label' => ['de' => 'Stakeholder & RACI', 'en' => 'Stakeholder & RACI']],
                    ['href' => $route('tools.kpi-definition'), 'label' => ['de' => 'KPI Definition Card', 'en' => 'KPI Definition Card']],
                    ['href' => $route('tools.report-inventory'), 'label' => ['de' => 'Report Inventory', 'en' => 'Report inventory']],
                    ['href' => $route('playbooks.index'), 'label' => ['de' => 'KPI Playbooks', 'en' => 'KPI playbooks']],
                ], static fn (array $link): bool => is_string($link['href'] ?? null))),
            ],
            [
                'id' => 'supplier',
                'icon' => 'fa-database',
                'label' => ['de' => 'Quelle anbinden', 'en' => 'Scope a source'],
                'lead' => [
                    'de' => 'Supplier auswaehlen, Kernobjekte verstehen, PII/DSDR pruefen und Skip-Tabellen vor dem Load markieren.',
                    'en' => 'Pick a supplier, understand core entities, review PII/DSDR, and mark skip tables before loading.',
                ],
                'links' => array_values(array_filter([
                    ['href' => $route('suppliers.index'), 'label' => ['de' => 'Supplier Library', 'en' => 'Supplier library']],
                    ['href' => $route('tools.meta-export-generator'), 'label' => ['de' => 'Meta Export Generator', 'en' => 'Meta Export Generator']],
                    ['href' => $route('tools.pii-recommend-generator'), 'label' => ['de' => 'PII Recommend', 'en' => 'PII Recommend']],
                    ['href' => $route('resources.index'), 'label' => ['de' => 'Vendor Resources', 'en' => 'Vendor resources']],
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
                    ['href' => $route('resources.index'), 'label' => ['de' => 'Stack Filter', 'en' => 'Stack filter']],
                    ['href' => $route('tools.architecture-fit'), 'label' => ['de' => 'Architecture Fit', 'en' => 'Architecture fit']],
                    ['href' => $route('tools.impact-effort'), 'label' => ['de' => 'Impact-Effort', 'en' => 'Impact-effort']],
                    ['href' => $route('compliance.roadmap'), 'label' => ['de' => 'Zertifikats-Roadmap', 'en' => 'Certification roadmap']],
                ], static fn (array $link): bool => is_string($link['href'] ?? null))),
            ],
            [
                'id' => 'pii',
                'icon' => 'fa-shield-halved',
                'label' => ['de' => 'PII und DSDR absichern', 'en' => 'Secure PII and DSDR'],
                'lead' => [
                    'de' => 'Personenbezug, Freitext, Kopien, Maskierung und Nachweisbarkeit als fruehen Projektpfad behandeln.',
                    'en' => 'Handle personal data, free text, copies, masking, and evidence as an early project path.',
                ],
                'links' => array_values(array_filter([
                    ['href' => $route('tools.pii-policy-generator'), 'label' => ['de' => 'PII Policy', 'en' => 'PII Policy']],
                    ['href' => $route('tools.pii-unreviewed-gate-generator'), 'label' => ['de' => 'PII Table Gate', 'en' => 'PII Table Gate']],
                    ['href' => $route('tools.governance-ai-sanitizer'), 'label' => ['de' => 'AI Sanitizer', 'en' => 'AI Sanitizer']],
                    ['href' => $route('compliance.index'), 'label' => ['de' => 'Compliance Hub', 'en' => 'Compliance hub']],
                ], static fn (array $link): bool => is_string($link['href'] ?? null))),
            ],
        ];
    }
}
