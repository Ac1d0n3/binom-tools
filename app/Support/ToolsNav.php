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
}
