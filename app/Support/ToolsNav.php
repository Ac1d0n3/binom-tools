<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

final class ToolsNav
{
    /**
     * Env/config key pattern: TOOL_{UPPER_SNAKE_ID}_ENABLED (default true).
     */
    public static function enabledEnvKey(string $toolId): string
    {
        return 'TOOL_'.strtoupper(str_replace('-', '_', $toolId)).'_ENABLED';
    }

    /**
     * Env/config key pattern: TOOL_{UPPER_SNAKE_ID}_LOGIN_REQUIRED (default false).
     */
    public static function loginRequiredEnvKey(string $toolId): string
    {
        return 'TOOL_'.strtoupper(str_replace('-', '_', $toolId)).'_LOGIN_REQUIRED';
    }

    public static function isToolEnabled(string $toolId): bool
    {
        $map = config('tools.enabled', []);

        if (array_key_exists($toolId, $map)) {
            return (bool) $map[$toolId];
        }

        return true;
    }

    /**
     * Whether a tool requires login when accounts mode is enabled.
     */
    public static function isToolLoginRequired(string $toolId): bool
    {
        $map = config('tools.login_required', []);

        if (array_key_exists($toolId, $map)) {
            return (bool) $map[$toolId];
        }

        return false;
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @return list<array<string, mixed>>
     */
    public static function withRegisteredRoutes(array $items): array
    {
        return \App\Support\ToolWorkflow::enrichNavItems(array_values(array_filter(
            $items,
            static function (array $item): bool {
                $id = $item['id'] ?? null;

                if (! is_string($id) || $id === '') {
                    return false;
                }

                if (! self::isToolEnabled($id)) {
                    return false;
                }

                return isset($item['route']) && Route::has($item['route']);
            },
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
                if (! is_string($stepId) || ! self::isToolEnabled($stepId)) {
                    continue;
                }

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

    /**
     * Small "for what" labels shown on tool cards.
     *
     * @param  array<string, mixed>  $item
     * @return list<string>
     */
    public static function platformMarks(array $item): array
    {
        $allowed = ['fabric', 'databricks', 'qlik', 'ai'];
        $targets = $item['for'] ?? [];

        if (! is_array($targets)) {
            return [];
        }

        $targets = array_values(array_filter(
            array_map(
                static fn (mixed $target): string => is_string($target) ? trim($target) : '',
                $targets,
            ),
            static fn (string $target): bool => $target !== '' && in_array(strtolower($target), $allowed, true),
        ));

        if (self::showsDbtBadge($item)) {
            $targets = array_values(array_filter(
                $targets,
                static fn (string $target): bool => strtolower($target) !== 'dbt',
            ));
        }

        return $targets;
    }

    /**
     * Tools tagged for AI (Prompt Studio, AI Sanitizer, …).
     *
     * @param  array<string, mixed>  $item
     */
    public static function isAiTool(array $item): bool
    {
        if (($item['workflow'] ?? null) === 'ai-prompt-workflow') {
            return true;
        }

        $targets = $item['for'] ?? [];
        if (! is_array($targets)) {
            return false;
        }

        foreach ($targets as $target) {
            if (is_string($target) && strtolower(trim($target)) === 'ai') {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @return list<array<string, mixed>>
     */
    public static function aiTools(array $items): array
    {
        return array_values(array_filter($items, static fn (array $item): bool => self::isAiTool($item)));
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @return list<array<string, mixed>>
     */
    public static function withoutAiTools(array $items): array
    {
        return array_values(array_filter($items, static fn (array $item): bool => ! self::isAiTool($item)));
    }

    /**
     * Sidebar accordion groups by product (AI, dbt, Fabric, …).
     *
     * @return array<string, array{de: string, en: string}>
     */
    public static function productGroupLabels(): array
    {
        return [
            'ai' => ['de' => 'AI', 'en' => 'AI'],
            'dbt' => ['de' => 'dbt', 'en' => 'dbt'],
            'fabric' => ['de' => 'Fabric', 'en' => 'Fabric'],
            'databricks' => ['de' => 'Databricks', 'en' => 'Databricks'],
            'qlik' => ['de' => 'Qlik', 'en' => 'Qlik'],
            'discovery' => ['de' => 'Discovery', 'en' => 'Discovery'],
            'more' => ['de' => 'Metadata', 'en' => 'Metadata'],
        ];
    }

    /**
     * Resolve product bucket(s) for a tool. Multi-platform tools can appear in several groups.
     *
     * @param  array<string, mixed>  $item
     * @return list<string>
     */
    public static function productKeysForTool(array $item): array
    {
        if (self::isAiTool($item)) {
            return ['ai'];
        }

        $keys = [];
        foreach (self::platformMarks($item) as $mark) {
            $key = strtolower(trim($mark));
            if ($key !== '' && ! in_array($key, $keys, true)) {
                $keys[] = $key;
            }
        }

        if ($keys !== []) {
            return $keys;
        }

        if (self::showsDbtBadge($item)) {
            return ['dbt'];
        }

        if (($item['workflow'] ?? null) === 'discovery-assessment') {
            return ['discovery'];
        }

        return ['more'];
    }

    /**
     * Group nav tools into product accordions (stable product order).
     *
     * @param  list<array<string, mixed>>  $items
     * @return list<array{id: string, label: array{de: string, en: string}, items: list<array<string, mixed>>}>
     */
    public static function groupByProduct(array $items): array
    {
        $labels = self::productGroupLabels();
        /** @var array<string, list<array<string, mixed>>> $buckets */
        $buckets = [];
        /** @var array<string, array<string, true>> $seen */
        $seen = [];

        foreach ($items as $item) {
            $id = is_string($item['id'] ?? null) ? $item['id'] : '';
            foreach (self::productKeysForTool($item) as $key) {
                if (! isset($labels[$key])) {
                    $key = 'more';
                }
                if ($id !== '' && isset($seen[$key][$id])) {
                    continue;
                }
                $buckets[$key][] = $item;
                if ($id !== '') {
                    $seen[$key][$id] = true;
                }
            }
        }

        $groups = [];
        foreach (array_keys($labels) as $key) {
            if (empty($buckets[$key])) {
                continue;
            }
            $groups[] = [
                'id' => $key,
                'label' => $labels[$key],
                'items' => $buckets[$key],
            ];
        }

        return $groups;
    }
}
