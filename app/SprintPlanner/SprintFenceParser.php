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
     * Keys that, when found at the item's nested indent level, terminate an
     * in-progress multiline scalar block (helpText / demoCode).
     */
    private const ITEM_KEY_STOP_LIST = [
        'label',
        'assigneetype',
        'assigneeid',
        'linkedstories',
        'helplinks',
        'helptext',
        'stories',
        'democode',
        'tablecolumns',
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
            'stories' => [],
            'flowVariant' => 'linear',
            'flowLayout' => 'vertical',
            'flowSteps' => [],
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
        $currentStory = null;
        $multilineKey = null;
        $multilineLines = [];

        $flushMultiline = static function () use (&$currentItem, &$multilineKey, &$multilineLines): void {
            if ($multilineKey === null || $currentItem === null) {
                return;
            }
            $currentItem[$multilineKey] = implode("\n", $multilineLines);
            $multilineKey = null;
            $multilineLines = [];
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

        $flushStory = static function () use (&$data, &$currentItem, &$itemSubSection, &$currentStory, &$section): void {
            if ($currentStory === null) {
                return;
            }
            $normalized = [
                'slug' => (string) ($currentStory['slug'] ?? ''),
                'required' => (bool) ($currentStory['required'] ?? false),
            ];
            if ($normalized['slug'] !== '') {
                if ($itemSubSection === 'stories' && $currentItem !== null) {
                    if (! isset($currentItem['stories']) || ! is_array($currentItem['stories'])) {
                        $currentItem['stories'] = [];
                    }
                    $currentItem['stories'][] = $normalized;
                } elseif ($section === 'stories') {
                    $data['stories'][] = $normalized;
                }
            }
            $currentStory = null;
        };

        $flushItem = function () use (&$data, &$currentItem, &$itemKey, &$itemSubSection, &$warnings, $ordinal, $flushMultiline, $flushLink, $flushStory): void {
            $flushMultiline();
            $flushLink();
            $flushStory();
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
                if ($multilineKey !== null) {
                    $multilineLines[] = '';
                }

                continue;
            }

            // Multiline scalar body (helpText / demoCode), indented continuation.
            if ($multilineKey !== null && $currentItem !== null && preg_match('/^\s{4,}(.*)$/', $rawLine, $m)) {
                // Stop multiline if a nested list key starts at 4 spaces with a known key.
                if (preg_match('/^\s{4}([a-zA-Z_]+):\s*(.*)$/', $rawLine, $km)
                    && in_array(strtolower($km[1]), self::ITEM_KEY_STOP_LIST, true)
                ) {
                    $flushMultiline();
                } else {
                    $multilineLines[] = rtrim($m[1]);

                    continue;
                }
            } elseif ($multilineKey !== null && ! preg_match('/^\s{4,}/', $rawLine)) {
                $flushMultiline();
            }

            // Section headers: tasks / deliverables / fields / linkedStories / links / stories / flowSteps
            // Must be at column 0 so nested "stories:" / "helpLinks:" under an item are not mistaken for these.
            if (preg_match('/^(tasks|deliverables|fields|linkedstories|links|stories|flowsteps):\s*$/i', $trimmed, $m)
                && ! preg_match('/^\s/', $rawLine)
            ) {
                $flushItem();
                $key = strtolower($m[1]);
                $section = match ($key) {
                    'linkedstories' => 'linkedStories',
                    'flowsteps' => 'flowSteps',
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

            // Sprint-level flowSteps list items
            if ($section === 'flowSteps' && preg_match('/^\s*-\s+(.+)\s*$/', $line, $m)) {
                $step = $this->stripQuotes(trim($m[1]));
                if ($step !== '') {
                    $data['flowSteps'][] = $step;
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

            // Stories list item start ("- slug: ..."), sprint-level or nested under an item.
            if (($section === 'stories' || ($currentItem !== null && $itemSubSection === 'stories'))
                && preg_match('/^\s*-\s+slug:\s*(.*)$/i', $line, $m)
            ) {
                $flushStory();
                $currentStory = ['slug' => $this->stripQuotes(trim($m[1])), 'required' => false];

                continue;
            }

            // Stories "required" continuation, sprint-level or nested under an item.
            if ($currentStory !== null && preg_match('/^\s+required:\s*(.*)$/i', $line, $m)) {
                $value = strtolower($this->stripQuotes(trim($m[1])));
                $currentStory['required'] = in_array($value, ['true', '1', 'yes'], true);

                continue;
            }

            // New list item under tasks/deliverables/fields
            if ($section !== null
                && ! in_array($section, ['linkedStories', 'links', 'stories', 'flowSteps'], true)
                && preg_match('/^\s*-\s+id:\s*(.+)\s*$/', $line, $m)
            ) {
                $flushItem();
                $itemKey = $section;
                $currentItem = ['id' => $this->stripQuotes(trim($m[1]))];
                $itemSubSection = null;

                continue;
            }

            // Nested helpLinks under a task/deliverable
            if ($currentItem !== null && preg_match('/^\s{2,}helplinks:\s*$/i', $line)) {
                $flushMultiline();
                $flushLink();
                $flushStory();
                $itemSubSection = 'helplinks';
                if (! isset($currentItem['helplinks']) || ! is_array($currentItem['helplinks'])) {
                    $currentItem['helplinks'] = [];
                }

                continue;
            }

            // Nested stories under a task/deliverable
            if ($currentItem !== null && preg_match('/^\s{2,}stories:\s*$/i', $line)) {
                $flushMultiline();
                $flushLink();
                $flushStory();
                $itemSubSection = 'stories';
                if (! isset($currentItem['stories']) || ! is_array($currentItem['stories'])) {
                    $currentItem['stories'] = [];
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
                $flushStory();
                $itemSubSection = null;
                $key = strtolower($m[1]);
                $value = $this->stripQuotes(trim($m[2]));

                if (($key === 'helptext' || $key === 'democode') && ($value === '|' || $value === '>')) {
                    $multilineKey = $key;
                    $multilineLines = [];

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

                if ($key === 'flowvariant') {
                    $data['flowVariant'] = $value === '' ? 'linear' : $value;

                    continue;
                }

                if ($key === 'flowlayout') {
                    $data['flowLayout'] = $value === '' ? 'vertical' : $value;

                    continue;
                }

                if (array_key_exists($key, $data)
                    && ! in_array($key, ['tasks', 'deliverables', 'fields', 'linkedStories', 'links', 'stories', 'flowSteps'], true)
                ) {
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

        $data['flowSteps'] = array_values(array_filter(
            array_map('strval', $data['flowSteps'] ?? []),
            static fn (string $step): bool => $step !== '',
        ));

        $data['links'] = array_values(array_filter(
            $data['links'] ?? [],
            static fn (array $link): bool => ($link['href'] ?? '') !== '',
        ));

        $data['stories'] = $this->mergeStoriesWithLegacyLinkedStories($data['stories'] ?? [], $data['linkedStories']);

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
     * Merges a legacy flat slug list into a list of story objects, adding any
     * slug not already present as `required: true`.
     *
     * @param  list<array<string, mixed>>  $stories
     * @param  list<string>  $legacySlugs
     * @return list<array{slug: string, required: bool}>
     */
    private function mergeStoriesWithLegacyLinkedStories(array $stories, array $legacySlugs): array
    {
        $merged = [];
        $seen = [];

        foreach ($stories as $story) {
            if (! is_array($story)) {
                continue;
            }
            $slug = (string) ($story['slug'] ?? '');
            if ($slug === '' || isset($seen[$slug])) {
                continue;
            }
            $merged[] = [
                'slug' => $slug,
                'required' => (bool) ($story['required'] ?? false),
            ];
            $seen[$slug] = true;
        }

        foreach ($legacySlugs as $slug) {
            $slug = (string) $slug;
            if ($slug === '' || isset($seen[$slug])) {
                continue;
            }
            $merged[] = ['slug' => $slug, 'required' => true];
            $seen[$slug] = true;
        }

        return $merged;
    }

    /**
     * Builds the `stories: [{slug, required}]` list for a task/deliverable item,
     * merging its nested `stories:` block with the legacy `linkedStories` CSV/array
     * field (treated as `required: true`).
     *
     * @param  array<string, mixed>  $item
     * @return list<array{slug: string, required: bool}>
     */
    private function normalizeItemStories(array $item): array
    {
        $nested = $item['stories'] ?? [];
        if (! is_array($nested)) {
            $nested = [];
        }

        $linked = $item['linkedstories'] ?? $item['linkedStories'] ?? [];
        if (is_string($linked)) {
            $linked = array_map('trim', explode(',', $linked));
        }
        if (! is_array($linked)) {
            $linked = [];
        }

        return $this->mergeStoriesWithLegacyLinkedStories(
            $nested,
            array_values(array_filter(array_map('strval', $linked), static fn (string $slug): bool => $slug !== '')),
        );
    }

    /**
     * @param  array<string, mixed>  $item
     * @return list<array{label: string, href: string}>
     */
    private function normalizeItemHelpLinks(array $item): array
    {
        $helpLinks = $item['helplinks'] ?? $item['helpLinks'] ?? [];
        if (! is_array($helpLinks)) {
            return [];
        }

        $normalized = [];
        foreach ($helpLinks as $link) {
            if (! is_array($link)) {
                continue;
            }
            $href = (string) ($link['href'] ?? '');
            if ($href === '') {
                continue;
            }
            $normalized[] = [
                'label' => (string) ($link['label'] ?? ''),
                'href' => $href,
            ];
        }

        return $normalized;
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function normalizeItemMultilineText(array $item, string $lowerKey): ?string
    {
        $value = $item[$lowerKey] ?? null;
        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }

    /**
     * @param  array<string, mixed>  $item
     * @param  list<string>  $warnings
     * @return array<string, mixed>
     */
    private function normalizeItem(array $item, string $section, array &$warnings, int $ordinal): array
    {
        if ($section === 'tasks') {
            $stories = $this->normalizeItemStories($item);

            return [
                'id' => (string) ($item['id'] ?? ''),
                'label' => (string) ($item['label'] ?? ''),
                'assigneeType' => $item['assigneetype'] ?? $item['assigneeType'] ?? 'person',
                'assigneeId' => $item['assigneeid'] ?? $item['assigneeId'] ?? null,
                'stories' => $stories,
                'linkedStorySlugs' => array_values(array_map(static fn (array $s): string => $s['slug'], $stories)),
                'helpText' => $this->normalizeItemMultilineText($item, 'helptext'),
                'helpLinks' => $this->normalizeItemHelpLinks($item),
                'demoCode' => $this->normalizeItemMultilineText($item, 'democode'),
                'table' => $this->normalizeItemTable($item),
            ];
        }

        if ($section === 'deliverables') {
            $stories = $this->normalizeItemStories($item);

            return [
                'id' => (string) ($item['id'] ?? ''),
                'label' => (string) ($item['label'] ?? ''),
                'stories' => $stories,
                'helpText' => $this->normalizeItemMultilineText($item, 'helptext'),
                'helpLinks' => $this->normalizeItemHelpLinks($item),
                'demoCode' => $this->normalizeItemMultilineText($item, 'democode'),
                'table' => $this->normalizeItemTable($item),
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

    /**
     * Parse tableColumns: "Name, Role, …" into { columns, rows: [] }.
     *
     * @param  array<string, mixed>  $item
     * @return array{columns: list<array{id: string, label: string}>, rows: list<array{id: string, cells: array<string, string>}>}|null
     */
    private function normalizeItemTable(array $item): ?array
    {
        $raw = $item['tablecolumns'] ?? $item['tableColumns'] ?? null;
        if (! is_string($raw) || trim($raw) === '') {
            // Allow already-normalized nested table from other sources.
            if (isset($item['table']) && is_array($item['table'])) {
                $columns = $item['table']['columns'] ?? null;
                if (is_array($columns) && $columns !== []) {
                    return [
                        'columns' => $this->normalizeTableColumns($columns),
                        'rows' => [],
                    ];
                }
            }

            return null;
        }

        $labels = preg_split('/\s*,\s*/', $raw) ?: [];
        $columns = [];
        foreach ($labels as $index => $label) {
            $label = trim((string) $label);
            if ($label === '') {
                continue;
            }
            $id = $this->slugifyColumnId($label, $index);
            $columns[] = ['id' => $id, 'label' => $label];
        }

        if ($columns === []) {
            return null;
        }

        return [
            'columns' => $columns,
            'rows' => [],
        ];
    }

    /**
     * @param  list<mixed>  $columns
     * @return list<array{id: string, label: string}>
     */
    private function normalizeTableColumns(array $columns): array
    {
        $out = [];
        foreach ($columns as $index => $col) {
            if (is_string($col)) {
                $label = trim($col);
                if ($label === '') {
                    continue;
                }
                $out[] = ['id' => $this->slugifyColumnId($label, $index), 'label' => $label];

                continue;
            }
            if (! is_array($col)) {
                continue;
            }
            $label = trim((string) ($col['label'] ?? $col['id'] ?? ''));
            $id = trim((string) ($col['id'] ?? ''));
            if ($label === '' && $id === '') {
                continue;
            }
            if ($id === '') {
                $id = $this->slugifyColumnId($label, $index);
            }
            if ($label === '') {
                $label = $id;
            }
            $out[] = ['id' => $id, 'label' => $label];
        }

        return $out;
    }

    private function slugifyColumnId(string $label, int $index): string
    {
        $slug = strtolower(trim($label));
        $slug = preg_replace('/[^a-z0-9]+/', '_', $slug) ?? '';
        $slug = trim($slug, '_');

        return $slug !== '' ? $slug : ('col_'.($index + 1));
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
