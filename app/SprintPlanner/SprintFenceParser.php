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
            'links' => [],
            'tasks' => [],
            'deliverables' => [],
            'fields' => [],
        ];

        $section = null;
        $currentItem = null;
        $itemKey = null;
        $itemSubSection = null;
        $currentLink = null;
        $helpTextMultiline = false;
        $helpTextLines = [];

        $flushHelpText = static function () use (&$currentItem, &$helpTextMultiline, &$helpTextLines): void {
            if (! $helpTextMultiline || $currentItem === null) {
                return;
            }
            $currentItem['helptext'] = implode("\n", $helpTextLines);
            $helpTextMultiline = false;
            $helpTextLines = [];
        };

        $flushLink = static function () use (&$data, &$currentItem, &$itemSubSection, &$currentLink, &$section): void {
            if ($currentLink === null) {
                return;
            }
            $normalized = [
                'label' => (string) ($currentLink['label'] ?? ''),
                'href' => (string) ($currentLink['href'] ?? ''),
            ];
            if ($normalized['href'] !== '') {
                if ($itemSubSection === 'helplinks' && $currentItem !== null) {
                    if (! isset($currentItem['helplinks']) || ! is_array($currentItem['helplinks'])) {
                        $currentItem['helplinks'] = [];
                    }
                    $currentItem['helplinks'][] = $normalized;
                } elseif ($section === 'links') {
                    $data['links'][] = $normalized;
                }
            }
            $currentLink = null;
        };

        $flushItem = function () use (&$data, &$currentItem, &$itemKey, &$itemSubSection, &$warnings, $ordinal, $flushHelpText, $flushLink): void {
            $flushHelpText();
            $flushLink();
            $itemSubSection = null;
            if ($currentItem !== null && $itemKey !== null) {
                $data[$itemKey][] = $this->normalizeItem($currentItem, $itemKey, $warnings, $ordinal);
            }
            $currentItem = null;
            $itemKey = null;
        };

        foreach ($lines as $rawLine) {
            $line = rtrim($rawLine);
            $trimmed = trim($line);

            if ($trimmed === '') {
                if ($helpTextMultiline) {
                    $helpTextLines[] = '';
                }

                continue;
            }

            // Multiline helpText body (indented continuation).
            if ($helpTextMultiline && $currentItem !== null && preg_match('/^\s{4,}(.*)$/', $rawLine, $m)) {
                // Stop multiline if a nested list key starts at 4 spaces with a known key.
                if (preg_match('/^\s{4}([a-zA-Z_]+):\s*(.*)$/', $rawLine, $km)
                    && in_array(strtolower($km[1]), ['label', 'assigneetype', 'assigneeid', 'linkedstories', 'helplinks', 'helptext'], true)
                ) {
                    $flushHelpText();
                } else {
                    $helpTextLines[] = rtrim($m[1]);

                    continue;
                }
            } elseif ($helpTextMultiline && ! preg_match('/^\s{4,}/', $rawLine)) {
                $flushHelpText();
            }

            // Section headers: tasks / deliverables / fields / linkedStories / links
            if (preg_match('/^(tasks|deliverables|fields|linkedstories|links):\s*$/i', $trimmed, $m)) {
                $flushItem();
                $key = strtolower($m[1]);
                $section = match ($key) {
                    'linkedstories' => 'linkedStories',
                    default => $key,
                };

                continue;
            }

            // Sprint-level linkedStories list items
            if ($section === 'linkedStories' && preg_match('/^\s*-\s+(.+)\s*$/', $line, $m)) {
                $slug = $this->stripQuotes(trim($m[1]));
                if ($slug !== '') {
                    $data['linkedStories'][] = $slug;
                }

                continue;
            }

            // Sprint-level links: start of link object
            if ($section === 'links' && preg_match('/^\s*-\s+label:\s*(.*)$/i', $line, $m)) {
                $flushLink();
                $currentLink = ['label' => $this->stripQuotes(trim($m[1])), 'href' => ''];

                continue;
            }

            if ($section === 'links' && $currentLink !== null && preg_match('/^\s+href:\s*(.*)$/i', $line, $m)) {
                $currentLink['href'] = $this->stripQuotes(trim($m[1]));

                continue;
            }

            // New list item under tasks/deliverables/fields
            if ($section !== null
                && ! in_array($section, ['linkedStories', 'links'], true)
                && preg_match('/^\s*-\s+id:\s*(.+)\s*$/', $line, $m)
            ) {
                $flushItem();
                $itemKey = $section;
                $currentItem = ['id' => $this->stripQuotes(trim($m[1]))];
                $itemSubSection = null;

                continue;
            }

            // Nested helpLinks under a task
            if ($currentItem !== null && preg_match('/^\s{2,}helplinks:\s*$/i', $line)) {
                $flushHelpText();
                $flushLink();
                $itemSubSection = 'helplinks';
                if (! isset($currentItem['helplinks']) || ! is_array($currentItem['helplinks'])) {
                    $currentItem['helplinks'] = [];
                }

                continue;
            }

            if ($currentItem !== null && $itemSubSection === 'helplinks' && preg_match('/^\s*-\s+label:\s*(.*)$/i', $line, $m)) {
                $flushLink();
                $currentLink = ['label' => $this->stripQuotes(trim($m[1])), 'href' => ''];

                continue;
            }

            if ($currentItem !== null && $itemSubSection === 'helplinks' && $currentLink !== null && preg_match('/^\s+href:\s*(.*)$/i', $line, $m)) {
                $currentLink['href'] = $this->stripQuotes(trim($m[1]));

                continue;
            }

            // Task/item scalar properties (2+ spaces)
            if ($currentItem !== null && preg_match('/^\s{2,}([a-zA-Z_]+):\s*(.*)$/', $line, $m)) {
                $flushLink();
                $itemSubSection = null;
                $key = strtolower($m[1]);
                $value = $this->stripQuotes(trim($m[2]));

                if ($key === 'helptext' && ($value === '|' || $value === '>')) {
                    $helpTextMultiline = true;
                    $helpTextLines = [];

                    continue;
                }

                $currentItem[$key] = $this->castItemValue($key, $value);

                continue;
            }

            // Top-level sprint keys (no leading indent)
            if (preg_match('/^([a-zA-Z_]+):\s*(.*)$/', $line, $m) && ! preg_match('/^\s/', $rawLine)) {
                $flushItem();
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

                if (array_key_exists($key, $data) && ! in_array($key, ['tasks', 'deliverables', 'fields', 'linkedStories', 'links'], true)) {
                    $data[$key] = $value === '' ? null : $value;
                }

                continue;
            }
        }

        $flushItem();

        $data['linkedStories'] = array_values(array_unique(array_filter(
            array_map('strval', $data['linkedStories'] ?? []),
            static fn (string $slug): bool => $slug !== '',
        )));

        $data['links'] = array_values(array_filter(
            $data['links'] ?? [],
            static fn (array $link): bool => ($link['href'] ?? '') !== '',
        ));

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

            $helpLinks = $item['helplinks'] ?? $item['helpLinks'] ?? [];
            if (! is_array($helpLinks)) {
                $helpLinks = [];
            }
            $normalizedLinks = [];
            foreach ($helpLinks as $link) {
                if (! is_array($link)) {
                    continue;
                }
                $href = (string) ($link['href'] ?? '');
                if ($href === '') {
                    continue;
                }
                $normalizedLinks[] = [
                    'label' => (string) ($link['label'] ?? ''),
                    'href' => $href,
                ];
            }

            $helpText = $item['helptext'] ?? $item['helpText'] ?? null;
            if (is_string($helpText)) {
                $helpText = trim($helpText);
                if ($helpText === '') {
                    $helpText = null;
                }
            } else {
                $helpText = null;
            }

            return [
                'id' => (string) ($item['id'] ?? ''),
                'label' => (string) ($item['label'] ?? ''),
                'assigneeType' => $item['assigneetype'] ?? $item['assigneeType'] ?? 'person',
                'assigneeId' => $item['assigneeid'] ?? $item['assigneeId'] ?? null,
                'linkedStorySlugs' => array_values(array_filter(array_map('strval', $linked))),
                'helpText' => $helpText,
                'helpLinks' => $normalizedLinks,
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
