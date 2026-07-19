<?php

namespace App\SprintPlanner;

use Illuminate\Support\Str;

final class SprintPlanFrontmatterParser
{
    /**
     * @return array{meta: array<string, mixed>, body: string}
     */
    public function parse(string $raw, string $slug): array
    {
        $raw = ltrim($raw, "\xEF\xBB\xBF");

        if (! str_starts_with($raw, "---\n") && ! str_starts_with($raw, "---\r\n")) {
            return [
                'meta' => $this->defaults($slug),
                'body' => $raw,
            ];
        }

        $closing = preg_match('/\r\n---\r\n|\n---\n/', $raw, $matches, PREG_OFFSET_CAPTURE, 4);

        if ($closing !== 1) {
            return [
                'meta' => $this->defaults($slug),
                'body' => $raw,
            ];
        }

        $offset = $matches[0][1];
        $frontmatter = substr($raw, 4, $offset - 4);
        $body = substr($raw, $offset + strlen($matches[0][0]));

        return [
            'meta' => array_merge($this->defaults($slug), $this->parseFrontmatterBlock($frontmatter, $slug)),
            'body' => $body,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function defaults(string $slug): array
    {
        return [
            'type' => null,
            'title' => Str::headline(str_replace('-', ' ', $slug)),
            'slug' => $slug,
            'description' => '',
            'duration' => 0,
            'unit' => 'week',
            'category' => null,
            'author' => null,
            'version' => 1,
            'locale' => null,
            'tags' => [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function parseFrontmatterBlock(string $block, string $slug): array
    {
        $meta = [];
        $lines = preg_split('/\r\n|\n|\r/', $block) ?: [];
        $index = 0;
        $count = count($lines);

        while ($index < $count) {
            $line = trim($lines[$index]);

            if ($line === '') {
                $index++;

                continue;
            }

            if (! str_contains($line, ':')) {
                $index++;

                continue;
            }

            [$key, $value] = array_map('trim', explode(':', $line, 2));
            $key = strtolower($key);

            if ($value === '' && $index + 1 < $count && str_starts_with(trim($lines[$index + 1]), '-')) {
                $list = [];
                $index++;

                while ($index < $count) {
                    $listLine = trim($lines[$index]);

                    if (! str_starts_with($listLine, '-')) {
                        break;
                    }

                    $list[] = trim(substr($listLine, 1));
                    $index++;
                }

                $meta[$key] = $list;

                continue;
            }

            $meta[$key] = $this->castValue($key, $this->stripQuotes($value));
            $index++;
        }

        if (isset($meta['tags']) && is_string($meta['tags'])) {
            $meta['tags'] = array_values(array_filter(array_map(
                'trim',
                preg_split('/\s*,\s*/', $meta['tags']) ?: []
            )));
        }

        if (! isset($meta['title']) || $meta['title'] === '') {
            $meta['title'] = Str::headline(str_replace('-', ' ', $slug));
        }

        if (! isset($meta['slug']) || $meta['slug'] === '' || $meta['slug'] === null) {
            $meta['slug'] = $slug;
        }

        return $meta;
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

    private function castValue(string $key, string $value): mixed
    {
        if (in_array($key, ['duration', 'version'], true)) {
            return (int) $value;
        }

        if ($key === 'tags') {
            return array_values(array_filter(array_map(
                'trim',
                preg_split('/\s*,\s*/', $value) ?: []
            )));
        }

        if ($value === 'null' || $value === '') {
            return null;
        }

        return $value;
    }
}
