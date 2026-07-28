<?php

namespace App\Admin\Content;

use App\Playbooks\PlaybookFrontmatterParser;

/**
 * Prefill markdown drafts for new stories (single vs series).
 */
final class StoryDraftTemplates
{
    public function __construct(
        private readonly MarkdownContentWriter $writer,
        private readonly PlaybookFrontmatterParser $parser = new PlaybookFrontmatterParser,
    ) {}

    /**
     * @return list<array{id: string, title: string, parts: int, nextPart: int}>
     */
    public function listSeries(): array
    {
        /** @var array<string, array{id: string, title: string, parts: int, nextPart: int, maxPart: int}> $map */
        $map = [];

        foreach ($this->writer->listSlugs() as $row) {
            $slug = $row['slug'];
            foreach (['en', 'de'] as $locale) {
                $raw = $this->writer->read($slug, $locale);
                if ($raw === null || trim($raw) === '') {
                    continue;
                }
                $meta = $this->parser->parse($raw, $slug)['meta'];
                $seriesId = is_string($meta['series'] ?? null) ? trim((string) $meta['series']) : '';
                if ($seriesId === '') {
                    continue;
                }

                $partRaw = $meta['seriespart'] ?? $meta['seriesPart'] ?? null;
                $part = is_numeric($partRaw) ? (int) $partRaw : 0;
                $titleRaw = $meta['seriestitle'] ?? $meta['seriesTitle'] ?? null;
                $title = is_string($titleRaw) && trim($titleRaw) !== ''
                    ? trim($titleRaw)
                    : $seriesId;

                if (! isset($map[$seriesId])) {
                    $map[$seriesId] = [
                        'id' => $seriesId,
                        'title' => $title,
                        'parts' => 0,
                        'nextPart' => 1,
                        'maxPart' => 0,
                        'seenSlugs' => [],
                    ];
                }

                if ($locale === 'en' && $title !== $seriesId) {
                    $map[$seriesId]['title'] = $title;
                } elseif ($map[$seriesId]['title'] === $seriesId && $title !== $seriesId) {
                    $map[$seriesId]['title'] = $title;
                }

                if (! isset($map[$seriesId]['seenSlugs'][$slug])) {
                    $map[$seriesId]['seenSlugs'][$slug] = true;
                    $map[$seriesId]['parts']++;
                }
                $map[$seriesId]['maxPart'] = max($map[$seriesId]['maxPart'], $part);
            }
        }

        $rows = [];
        foreach ($map as $entry) {
            $rows[] = [
                'id' => $entry['id'],
                'title' => $entry['title'],
                'parts' => $entry['parts'],
                'nextPart' => max(1, $entry['maxPart'] + 1),
            ];
        }

        usort($rows, static fn (array $a, array $b): int => strcasecmp($a['title'], $b['title']));

        return $rows;
    }

    /**
     * @return array{bodyDe: string, bodyEn: string, template: string, seriesId: ?string, seriesLabel: ?string}
     */
    public function draft(string $template, ?string $seriesId = null): array
    {
        $template = $template === 'series' ? 'series' : 'single';
        $today = date('Y-m-d');

        if ($template === 'single') {
            $meta = $this->baseMeta($today);

            return [
                'bodyDe' => $this->renderDocument($meta, $this->starterBody('de')),
                'bodyEn' => $this->renderDocument($meta, $this->starterBody('en')),
                'template' => 'single',
                'seriesId' => null,
                'seriesLabel' => null,
            ];
        }

        $seriesId = is_string($seriesId) ? trim($seriesId) : '';
        if ($seriesId === '') {
            $meta = $this->baseMeta($today);
            $meta['series'] = '';
            $meta['seriesPart'] = 1;
            $meta['seriesTitle'] = '';

            return [
                'bodyDe' => $this->renderDocument($meta, $this->starterBody('de')),
                'bodyEn' => $this->renderDocument($meta, $this->starterBody('en')),
                'template' => 'series',
                'seriesId' => null,
                'seriesLabel' => 'New series',
            ];
        }

        $source = $this->findSeriesSource($seriesId);
        $nextPart = 1;
        $seriesTitleDe = '';
        $seriesTitleEn = '';
        $shared = $this->baseMeta($today);

        if ($source !== null) {
            $nextPart = $source['nextPart'];
            $shared['author'] = $source['author'] ?? $shared['author'];
            $shared['category'] = $source['category'] ?? '';
            $shared['tags'] = $source['tags'] ?? [];
            $seriesTitleDe = $source['seriesTitleDe'] ?? '';
            $seriesTitleEn = $source['seriesTitleEn'] ?? '';
        }

        $metaDe = $shared;
        $metaDe['series'] = $seriesId;
        $metaDe['seriesPart'] = $nextPart;
        $metaDe['seriesTitle'] = $seriesTitleDe !== '' ? $seriesTitleDe : $seriesTitleEn;

        $metaEn = $shared;
        $metaEn['series'] = $seriesId;
        $metaEn['seriesPart'] = $nextPart;
        $metaEn['seriesTitle'] = $seriesTitleEn !== '' ? $seriesTitleEn : $seriesTitleDe;

        $label = $seriesTitleEn !== '' ? $seriesTitleEn : ($seriesTitleDe !== '' ? $seriesTitleDe : $seriesId);

        return [
            'bodyDe' => $this->renderDocument($metaDe, $this->starterBody('de')),
            'bodyEn' => $this->renderDocument($metaEn, $this->starterBody('en')),
            'template' => 'series',
            'seriesId' => $seriesId,
            'seriesLabel' => $label.' · part '.$nextPart,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function baseMeta(string $today): array
    {
        return [
            'title' => '',
            'description' => '',
            'author' => 'Thomas Lindackers',
            'category' => '',
            'tags' => [],
            'order' => -1,
            'publishedAt' => $today,
            'hero' => 'images/playbooks/',
        ];
    }

    private function starterBody(string $locale): string
    {
        if ($locale === 'de') {
            return "## Überschrift\n\nSchreibe hier die Story…\n";
        }

        return "## Headline\n\nWrite the story here…\n";
    }

    /**
     * @param  array<string, mixed>  $meta
     */
    private function renderDocument(array $meta, string $body): string
    {
        return "---\n".$this->encodeFrontmatter($meta)."---\n\n".ltrim($body);
    }

    /**
     * @param  array<string, mixed>  $meta
     */
    private function encodeFrontmatter(array $meta): string
    {
        $lines = [];
        foreach ($meta as $key => $value) {
            if ($key === 'tags') {
                $tags = is_array($value) ? array_values(array_filter(array_map(
                    static fn ($tag): string => trim((string) $tag),
                    $value
                ), static fn (string $tag): bool => $tag !== '')) : [];
                $lines[] = 'tags:';
                if ($tags === []) {
                    $lines[] = '  - ';
                } else {
                    foreach ($tags as $tag) {
                        $lines[] = '  - '.$tag;
                    }
                }

                continue;
            }

            if ($value === null) {
                continue;
            }

            if (is_bool($value)) {
                $lines[] = $key.': '.($value ? 'true' : 'false');

                continue;
            }

            if (is_int($value) || is_float($value)) {
                $lines[] = $key.': '.$value;

                continue;
            }

            $string = trim((string) $value);
            if ($string === '') {
                $lines[] = $key.': ""';

                continue;
            }

            if (preg_match('/[:#\[\]{}&*!|>\'"@`]/', $string) === 1 || str_contains($string, "\n")) {
                $lines[] = $key.': "'.str_replace(['\\', '"'], ['\\\\', '\\"'], $string).'"';

                continue;
            }

            $lines[] = $key.': '.$string;
        }

        return implode("\n", $lines)."\n";
    }

    /**
     * @return array{
     *   nextPart: int,
     *   author: ?string,
     *   category: ?string,
     *   tags: list<string>,
     *   seriesTitleDe: string,
     *   seriesTitleEn: string
     * }|null
     */
    private function findSeriesSource(string $seriesId): ?array
    {
        $author = null;
        $category = null;
        $tags = [];
        $maxPart = 0;
        $seriesTitleDe = '';
        $seriesTitleEn = '';
        $found = false;

        foreach ($this->writer->listSlugs() as $row) {
            $slug = $row['slug'];
            foreach (['en', 'de'] as $locale) {
                $raw = $this->writer->read($slug, $locale);
                if ($raw === null || trim($raw) === '') {
                    continue;
                }
                $meta = $this->parser->parse($raw, $slug)['meta'];
                $id = is_string($meta['series'] ?? null) ? trim((string) $meta['series']) : '';
                if ($id !== $seriesId) {
                    continue;
                }

                $found = true;
                $partRaw = $meta['seriespart'] ?? $meta['seriesPart'] ?? null;
                $part = is_numeric($partRaw) ? (int) $partRaw : 0;
                $maxPart = max($maxPart, $part);

                $titleRaw = $meta['seriestitle'] ?? $meta['seriesTitle'] ?? null;
                $title = is_string($titleRaw) ? trim($titleRaw) : '';
                if ($title !== '') {
                    if ($locale === 'de') {
                        $seriesTitleDe = $title;
                    } else {
                        $seriesTitleEn = $title;
                    }
                }

                if ($author === null && is_string($meta['author'] ?? null) && trim((string) $meta['author']) !== '') {
                    $author = trim((string) $meta['author']);
                }
                if (($category === null || $category === '') && is_string($meta['category'] ?? null) && trim((string) $meta['category']) !== '') {
                    $category = trim((string) $meta['category']);
                }
                if ($tags === [] && is_array($meta['tags'] ?? null) && $meta['tags'] !== []) {
                    $tags = array_values(array_filter(array_map(
                        static fn ($tag): string => trim((string) $tag),
                        $meta['tags']
                    ), static fn (string $tag): bool => $tag !== ''));
                }
            }
        }

        if (! $found) {
            return null;
        }

        return [
            'nextPart' => max(1, $maxPart + 1),
            'author' => $author,
            'category' => $category,
            'tags' => $tags,
            'seriesTitleDe' => $seriesTitleDe,
            'seriesTitleEn' => $seriesTitleEn,
        ];
    }
}
