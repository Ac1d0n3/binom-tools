<?php

namespace App\Playbooks;

final class PlaybookFlowchartParser
{
    public static function isFlowchartFence(string $info): bool
    {
        $language = strtolower(explode(' ', trim($info))[0] ?? '');

        return in_array($language, ['flowchart', 'flow'], true);
    }

    /**
     * @return array{
     *     variant: 'chevron'|'linear',
     *     steps: list<array{label: string, state: 'active'|'completed'|null}>
     * }|null
     */
    public static function parse(string $info, string $body): ?array
    {
        if (! self::isFlowchartFence($info)) {
            return null;
        }

        $info = trim($info);
        $tokens = preg_split('/\s+/', $info) ?: [];
        $variantToken = strtolower($tokens[1] ?? 'chevron');
        $variant = match ($variantToken) {
            'linear', 'box', 'boxes' => 'linear',
            default => 'chevron',
        };

        $steps = self::parseSteps($body);

        if (count($steps) < 2) {
            return null;
        }

        return [
            'variant' => $variant,
            'steps' => $steps,
        ];
    }

    /**
     * @return list<array{label: string, state: 'active'|'completed'|null}>
     */
    private static function parseSteps(string $body): array
    {
        $steps = [];

        foreach (preg_split('/\R/', $body) ?: [] as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $line = preg_replace('/^[-*+]\s+/', '', $line) ?? $line;
            $line = preg_replace('/^\d+[.)]\s+/', '', $line) ?? $line;

            $state = null;

            if (preg_match('/\[(active|done|completed)\]\s*$/i', $line, $matches) === 1) {
                $state = strtolower($matches[1]) === 'active' ? 'active' : 'completed';
                $line = preg_replace('/\[(active|done|completed)\]\s*$/i', '', $line) ?? $line;
            }

            $label = trim($line);

            if ($label === '') {
                continue;
            }

            $steps[] = [
                'label' => $label,
                'state' => $state,
            ];
        }

        return $steps;
    }
}
