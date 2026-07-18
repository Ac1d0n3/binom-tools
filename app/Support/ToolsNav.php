<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

final class ToolsNav
{
    /**
     * @param  list<array<string, mixed>>  $items
     * @return list<array<string, mixed>>
     */
    public static function withRegisteredRoutes(array $items): array
    {
        return \App\Support\ToolWorkflow::enrichNavItems(array_values(array_filter(
            $items,
            static fn (array $item): bool => isset($item['route']) && Route::has($item['route']),
        )));
    }

    /**
     * @param  array<string, array<string, mixed>>  $workflows
     * @return array<string, array<string, mixed>>
     */
    public static function workflowsWithRegisteredRoutes(array $workflows): array
    {
        $filtered = [];

        foreach ($workflows as $workflowId => $workflow) {
            $steps = $workflow['steps'] ?? [];
            $registeredSteps = [];

            foreach ($steps as $stepId) {
                $navItem = collect(config('tools.nav', []))->firstWhere('id', $stepId);
                if ($navItem !== null && isset($navItem['route']) && Route::has($navItem['route'])) {
                    $registeredSteps[] = $stepId;
                }
            }

            if ($registeredSteps === []) {
                continue;
            }

            $filtered[$workflowId] = [
                ...$workflow,
                'steps' => $registeredSteps,
            ];
        }

        return $filtered;
    }

    /**
     * Whether the overview/landing card should show the dbt badge.
     * Only dbt-related tools — not every tool that happens to sit in a workflow.
     *
     * @param  array<string, mixed>  $item
     */
    public static function showsDbtBadge(array $item): bool
    {
        if (array_key_exists('dbt', $item)) {
            return (bool) $item['dbt'];
        }

        $workflow = $item['workflow'] ?? null;

        return is_string($workflow) && str_starts_with($workflow, 'dbt-');
    }
}
