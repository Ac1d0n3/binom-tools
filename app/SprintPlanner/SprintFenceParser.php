<?php

namespace App\SprintPlanner;

/**
 * Parses ```sprint fenced blocks from sprint-plan markdown bodies.
 */
final class SprintFenceParser
{
    private const KNOWN_FIELD_TYPES = [
        'text',
        'textarea',
        'number',
        'date',
        'select',
        'multiselect',
        'url',
        'checkbox',
        'person',
        'team',
    ];

    /**
     * @return array{sprints: list<array<string, mixed>>, warnings: list<string>, errors: list<string>}
     */
    public function parse(string $body): array
    {
        $sprints = [];
        $warnings = [];
        $errors = [];

        if (! preg_match_all('/```sprint\s*\n(.*?)```/s', $body, $matches, PREG_SET_ORDER)) {
            return [
                'sprints' => [],
                'warnings' => [],
                'errors' => ['No sprint blocks found in template body.'],
            ];
        }

        foreach ($matches as $index => $match) {
            $block = $match[1];
            $parsed = $this->parseBlock($block, $index + 1);

            if ($parsed['error'] !== null) {
                $errors[] = $parsed['error'];

                continue;
            }

            $sprints[] = $parsed['sprint'];
            foreach ($parsed['warnings'] as $warning) {
                $warnings[] = $warning;
            }
        }

        return [
            'sprints' => $sprints,
            'warnings' => $warnings,
            'errors' => $errors,
        ];
    }

    /**
     * @return array{sprint: ?array<string, mixed>, warnings: list<string>, error: ?string}
     */
    private function parseBlock(string $block, int $ordinal): array
    {
        $warnings = [];
        $lines = preg_split('/\r\n|\n|\r/', $block) ?: [];
        $data = [
            'id' => null,
            'number' => null,
            'title' => null,
            'goal' => null,
            'description' => null,
            'estimated_effort' => null,
            'notes' => false,
            'linkedStories' => [],
            'tasks' => [],
            'deliverables' => [],
            'fields' => [],
        ];

        $section = null;
        $currentItem = null;
        $itemKey = null;

        foreach ($lines as $rawLine) {
            $line = rtrim($rawLine);

            if (trim($line) === '') {
                continue;
            }

            if (preg_match('/^(tasks|deliverables|fields|linkedstories):\s*$/i', trim($line), $m)) {
                if ($currentItem !== null && $itemKey !== null) {
                    $data[$itemKey][] = $currentItem;
                    $currentItem = null;
                    $itemKey = null;
                }
                $section = strtolower($m[1]) === 'linkedstories' ? 'linkedStories' : strtolower($m[1]);

                continue;
            }

            if ($section === 'linkedStories' && preg_match('/^\s*-\s+(.+)\s*$/', $line, $m)) {
                $slug = $this->stripQuotes(trim($m[1]));
                if ($slug !== '') {
                    $data['linkedStories'][] = $slug;
                }

                continue;
            }

            if ($section !== null && $section !== 'linkedStories' && preg_match('/^\s*-\s+id:\s*(.+)\s*$/', $line, $m)) {
                if ($currentItem !== null && $itemKey !== null) {
                    $data[$itemKey][] = $this->normalizeItem($currentItem, $itemKey, $warnings, $ordinal);
                }
                $itemKey = $section;
                $currentItem = ['id' => $this->stripQuotes(trim($m[1]))];

                continue;
            }

            if ($currentItem !== null && preg_match('/^\s{2,}([a-zA-Z_]+):\s*(.*)$/', $line, $m)) {
                $key = strtolower($m[1]);
                $value = $this->castItemValue($key, $this->stripQuotes(trim($m[2])));
                $currentItem[$key] = $value;

                continue;
            }

            // Top-level sprint keys (no leading indent) — flush open list items first.
            if (preg_match('/^([a-zA-Z_]+):\s*(.*)$/', $line, $m) && ! preg_match('/^\s/', $rawLine)) {
                if ($currentItem !== null && $itemKey !== null) {
                    $data[$itemKey][] = $this->normalizeItem($currentItem, $itemKey, $warnings, $ordinal);
                    $currentItem = null;
                    $itemKey = null;
                }
                $section = null;

                $key = strtolower($m[1]);
                $value = $this->stripQuotes(trim($m[2]));

                if ($key === 'notes') {
                    $data['notes'] = in_array(strtolower($value), ['true', '1', 'yes'], true);

                    continue;
                }

                if ($key === 'number') {
                    $data['number'] = (int) $value;

                    continue;
                }

                if (array_key_exists($key, $data) && ! in_array($key, ['tasks', 'deliverables', 'fields', 'linkedStories'], true)) {
                    $data[$key] = $value === '' ? null : $value;
                }

                continue;
            }
        }

        if ($currentItem !== null && $itemKey !== null) {
            $data[$itemKey][] = $this->normalizeItem($currentItem, $itemKey, $warnings, $ordinal);
        }

        $data['linkedStories'] = array_values(array_unique(array_filter(
            array_map('strval', $data['linkedStories'] ?? []),
            static fn (string $slug): bool => $slug !== '',
        )));

        if ($data['id'] === null || $data['id'] === '') {
            return [
                'sprint' => null,
                'warnings' => $warnings,
                'error' => "Sprint block #{$ordinal} is missing required field: id",
            ];
        }

        if ($data['number'] === null) {
            return [
                'sprint' => null,
                'warnings' => $warnings,
                'error' => "Sprint block #{$ordinal} ({$data['id']}) is missing required field: number",
            ];
        }

        if ($data['title'] === null || $data['title'] === '') {
            return [
                'sprint' => null,
                'warnings' => $warnings,
                'error' => "Sprint block #{$ordinal} ({$data['id']}) is missing required field: title",
            ];
        }

        if ($data['goal'] === null || $data['goal'] === '') {
            return [
                'sprint' => null,
                'warnings' => $warnings,
                'error' => "Sprint block #{$ordinal} ({$data['id']}) is missing required field: goal",
            ];
        }

        return [
            'sprint' => $data,
            'warnings' => $warnings,
            'error' => null,
        ];
    }

    /**
     * @param  array<string, mixed>  $item
     * @param  list<string>  $warnings
     * @return array<string, mixed>
     */
    private function normalizeItem(array $item, string $section, array &$warnings, int $ordinal): array
    {
        if ($section === 'tasks') {
            $linked = $item['linkedstories'] ?? $item['linkedStories'] ?? [];
            if (is_string($linked)) {
                $linked = array_map('trim', explode(',', $linked));
            }
            if (! is_array($linked)) {
                $linked = [];
            }

            return [
                'id' => (string) ($item['id'] ?? ''),
                'label' => (string) ($item['label'] ?? ''),
                'assigneeType' => $item['assigneetype'] ?? $item['assigneeType'] ?? 'person',
                'assigneeId' => $item['assigneeid'] ?? $item['assigneeId'] ?? null,
                'linkedStorySlugs' => array_values(array_filter(array_map('strval', $linked))),
            ];
        }

        if ($section === 'deliverables') {
            return [
                'id' => (string) ($item['id'] ?? ''),
                'label' => (string) ($item['label'] ?? ''),
            ];
        }

        $type = (string) ($item['type'] ?? 'text');
        if (! in_array($type, self::KNOWN_FIELD_TYPES, true)) {
            $warnings[] = "Sprint #{$ordinal}: unknown field type \"{$type}\" for field \"{$item['id']}\" — falling back to text.";
            $type = 'text';
        }

        return [
            'id' => (string) ($item['id'] ?? ''),
            'label' => (string) ($item['label'] ?? ''),
            'type' => $type,
            'placeholder' => isset($item['placeholder']) ? (string) $item['placeholder'] : null,
            'options' => $item['options'] ?? null,
        ];
    }

    private function castItemValue(string $key, string $value): mixed
    {
        if ($value === 'null' || $value === '') {
            return null;
        }

        if (in_array(strtolower($value), ['true', 'false'], true)) {
            return strtolower($value) === 'true';
        }

        return $value;
    }

    private function stripQuotes(string $value): string
    {
        if (
            (str_starts_with($value, '"') && str_ends_with($value, '"'))
            || (str_starts_with($value, "'") && str_ends_with($value, "'"))
        ) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}
