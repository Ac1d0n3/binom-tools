<?php

namespace App\Support;

final class ToolWorkflow
{
    /**
     * @return array<string, array{label: array{de: string, en: string}, description?: array{de: string, en: string}, icon?: string, accent?: string, steps: list<string>}>
     */
    public static function workflows(): array
    {
        return config('tools.workflows', []);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function navItems(): array
    {
        return config('tools.nav', []);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function navById(): array
    {
        $navById = [];
        foreach (self::navItems() as $item) {
            $navById[$item['id']] = $item;
        }

        return $navById;
    }

    /**
     * @return array{workflow: array<string, mixed>, step: int, total: int, current: array<string, mixed>, prev: array<string, mixed>|null, next: array<string, mixed>|null}|null
     */
    public static function contextForToolId(string $toolId): ?array
    {
        foreach (self::workflows() as $workflowId => $workflow) {
            $steps = $workflow['steps'] ?? [];
            $index = array_search($toolId, $steps, true);
            if ($index === false) {
                continue;
            }

            $navById = self::navById();
            $current = $navById[$toolId] ?? null;
            if ($current === null) {
                continue;
            }

            $prevId = $steps[$index - 1] ?? null;
            $nextId = $steps[$index + 1] ?? null;

            return [
                'workflowId' => $workflowId,
                'workflow' => $workflow,
                'step' => $index + 1,
                'total' => count($steps),
                'current' => $current,
                'prev' => $prevId ? ($navById[$prevId] ?? null) : null,
                'next' => $nextId ? ($navById[$nextId] ?? null) : null,
            ];
        }

        return null;
    }

    /**
     * @return list<array{id: string, route: string, num: int, isActive: bool, isCompleted: bool, label: array{de: string, en: string}}>|null
     */
    public static function flowchartStepsForToolId(string $toolId): ?array
    {
        foreach (self::workflows() as $workflow) {
            $steps = $workflow['steps'] ?? [];
            $currentIndex = array_search($toolId, $steps, true);
            if ($currentIndex === false) {
                continue;
            }

            $navById = self::navById();
            $flowchartSteps = [];

            foreach ($steps as $index => $stepId) {
                $navItem = $navById[$stepId] ?? null;
                if ($navItem === null || ! isset($navItem['route']) || ! \Illuminate\Support\Facades\Route::has($navItem['route'])) {
                    continue;
                }

                $flowchartSteps[] = [
                    'id' => $stepId,
                    'route' => $navItem['route'],
                    'num' => $index + 1,
                    'isActive' => $index === $currentIndex,
                    'isCompleted' => $index < $currentIndex,
                    'label' => $navItem['label'],
                ];
            }

            return $flowchartSteps;
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    public static function enrichNavItem(array $item): array
    {
        $workflowId = $item['workflow'] ?? null;
        if (! is_string($workflowId) || $workflowId === '') {
            return $item;
        }

        $workflow = self::workflows()[$workflowId] ?? null;
        if ($workflow === null) {
            return $item;
        }

        if (isset($workflow['icon'])) {
            $item['icon'] = $workflow['icon'];
        }

        if (isset($workflow['accent'])) {
            $item['accent'] = $workflow['accent'];
        }

        return $item;
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @return list<array<string, mixed>>
     */
    public static function enrichNavItems(array $items): array
    {
        return array_map(fn (array $item): array => self::enrichNavItem($item), $items);
    }
}
