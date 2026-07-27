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
     *     layout: 'horizontal'|'vertical',
     *     steps: list<array{label: string, state: 'active'|'completed'|null}>
     * }|null
     */
    public static function parse(string $info, string $body): ?array
    {
        if (! self::isFlowchartFence($info)) {
            return null;
        }

        $info = trim($info);
        $tokens = preg_split('/\s+/', strtolower($info)) ?: [];
        // Skip the fence language token (flowchart|flow).
        $optionTokens = array_slice($tokens, 1);

        $variant = 'chevron';
        $layout = 'horizontal';

        foreach ($optionTokens as $token) {
            if (in_array($token, ['linear', 'box', 'boxes'], true)) {
                $variant = 'linear';
            } elseif (in_array($token, ['chevron', 'chevrons'], true)) {
                $variant = 'chevron';
            } elseif ($token === 'vertical') {
                $layout = 'vertical';
            } elseif ($token === 'horizontal') {
                $layout = 'horizontal';
            }
        }

        $steps = self::parseSteps($body);

        if (count($steps) < 2) {
            return null;
        }

        return [
            'variant' => $variant,
            'layout' => $layout,
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

            // Skip arrow-only rows from legacy ```text migrations (↓ → ↺ etc.).
            if (preg_match('/^[↓↑←→⇓⇑⇐⇒➜➝➞➔➡️⬇️⬆️↺↻⟲⟳]+$/u', $line) === 1) {
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
