<?php

namespace App\Playbooks;

use App\Support\PlaybookImagePath;
use DOMDocument;
use DOMElement;

/**
 * Builds a locale-aware gallery of non-hero story body images for the index slides modal.
 */
final class PlaybookSlidesCatalog
{
    /**
     * @param  list<Playbook>  $playbooks
     * @return list<array{
     *     src: string,
     *     alt: string,
     *     caption: string,
     *     storySlug: string,
     *     storyTitle: string,
     *     storyUrl: string,
     *     seriesId: ?string,
     *     seriesTitle: ?string,
     *     seriesUrl: ?string,
     *     seriesPart: ?int
     * }>
     */
    public function build(array $playbooks, string $locale): array
    {
        $locale = $locale === 'de' ? 'de' : 'en';
        $slides = [];

        foreach ($playbooks as $playbook) {
            if (! $playbook instanceof Playbook) {
                continue;
            }

            $variant = $playbook->variant($locale)
                ?? $playbook->variant('en')
                ?? $playbook->variant('de');

            if ($variant === null) {
                continue;
            }

            $images = $this->extractBodyImages($variant->bodyHtml);
            if ($images === []) {
                continue;
            }

            $seriesId = is_string($playbook->seriesId) && $playbook->seriesId !== ''
                ? $playbook->seriesId
                : null;
            $seriesTitle = null;
            $seriesUrl = null;

            if ($seriesId !== null) {
                $seriesTitle = trim((string) ($variant->seriesTitle ?? ''));
                if ($seriesTitle === '') {
                    $seriesTitle = $seriesId;
                }
                $seriesUrl = locale_route('playbooks.series', ['seriesId' => $seriesId], $locale);
            }

            $storyTitle = $playbook->title($locale);
            $storyUrl = locale_route('playbooks.show', ['slug' => $playbook->slug], $locale);
            $seriesPart = $playbook->seriesPart;

            foreach ($images as $image) {
                $slides[] = [
                    'src' => $image['src'],
                    'alt' => $image['alt'],
                    'caption' => $image['caption'],
                    'storySlug' => $playbook->slug,
                    'storyTitle' => $storyTitle,
                    'storyUrl' => $storyUrl,
                    'seriesId' => $seriesId,
                    'seriesTitle' => $seriesTitle,
                    'seriesUrl' => $seriesUrl,
                    'seriesPart' => $seriesPart,
                ];
            }
        }

        return $this->sortSlides($slides);
    }

    /**
     * @return list<array{src: string, alt: string, caption: string}>
     */
    private function extractBodyImages(string $bodyHtml): array
    {
        if (trim($bodyHtml) === '') {
            return [];
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $document->loadHTML(
            '<?xml encoding="UTF-8"><div id="playbook-slides-root">'.$bodyHtml.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();

        $root = $document->getElementById('playbook-slides-root');
        if ($root === null) {
            return [];
        }

        $out = [];
        $seen = [];

        foreach ($root->getElementsByTagName('img') as $image) {
            if (! $image instanceof DOMElement) {
                continue;
            }

            $src = trim($image->getAttribute('src'));
            if ($src === '') {
                continue;
            }

            $relative = PlaybookImagePath::publicRelativePath($src);
            if ($relative === null || ! str_starts_with($relative, 'images/playbooks/')) {
                continue;
            }

            if ($this->isHeroPath($relative)) {
                continue;
            }

            $resolvedSrc = PlaybookImagePath::assetUrl($relative) ?? $src;
            $key = strtolower($relative);
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;

            $alt = trim($image->getAttribute('alt'));
            $caption = $this->captionForImage($image) ?: $alt;

            $out[] = [
                'src' => $resolvedSrc,
                'alt' => $alt,
                'caption' => $caption,
            ];
        }

        return $out;
    }

    private function isHeroPath(string $relativePath): bool
    {
        $filename = strtolower(basename($relativePath));

        return str_contains($filename, '-hero.')
            || str_contains($filename, '-hero-')
            || str_ends_with($filename, '-hero');
    }

    private function captionForImage(DOMElement $image): string
    {
        $figure = $image->parentNode;
        while ($figure !== null && ! ($figure instanceof DOMElement && strtolower($figure->tagName) === 'figure')) {
            $figure = $figure->parentNode;
        }

        if (! $figure instanceof DOMElement) {
            return '';
        }

        foreach ($figure->getElementsByTagName('figcaption') as $caption) {
            if ($caption instanceof DOMElement) {
                return trim($caption->textContent ?? '');
            }
        }

        return '';
    }

    /**
     * Series first (by title), then part number, then document order; standalones after by story title.
     *
     * @param  list<array{
     *     src: string,
     *     alt: string,
     *     caption: string,
     *     storySlug: string,
     *     storyTitle: string,
     *     storyUrl: string,
     *     seriesId: ?string,
     *     seriesTitle: ?string,
     *     seriesUrl: ?string,
     *     seriesPart: ?int
     * }>  $slides
     * @return list<array{
     *     src: string,
     *     alt: string,
     *     caption: string,
     *     storySlug: string,
     *     storyTitle: string,
     *     storyUrl: string,
     *     seriesId: ?string,
     *     seriesTitle: ?string,
     *     seriesUrl: ?string,
     *     seriesPart: ?int
     * }>
     */
    private function sortSlides(array $slides): array
    {
        $indexed = [];
        foreach ($slides as $index => $slide) {
            $indexed[] = ['index' => $index, 'slide' => $slide];
        }

        usort($indexed, static function (array $a, array $b): int {
            $left = $a['slide'];
            $right = $b['slide'];

            $leftSeries = is_string($left['seriesId'] ?? null) && $left['seriesId'] !== '';
            $rightSeries = is_string($right['seriesId'] ?? null) && $right['seriesId'] !== '';

            if ($leftSeries !== $rightSeries) {
                return $leftSeries ? -1 : 1;
            }

            if ($leftSeries && $rightSeries) {
                $titleCmp = strcasecmp((string) ($left['seriesTitle'] ?? ''), (string) ($right['seriesTitle'] ?? ''));
                if ($titleCmp !== 0) {
                    return $titleCmp;
                }

                $idCmp = strcmp((string) $left['seriesId'], (string) $right['seriesId']);
                if ($idCmp !== 0) {
                    return $idCmp;
                }

                $leftPart = (int) ($left['seriesPart'] ?? PHP_INT_MAX);
                $rightPart = (int) ($right['seriesPart'] ?? PHP_INT_MAX);
                if ($leftPart !== $rightPart) {
                    return $leftPart <=> $rightPart;
                }
            }

            $storyCmp = strcasecmp((string) ($left['storyTitle'] ?? ''), (string) ($right['storyTitle'] ?? ''));
            if ($storyCmp !== 0) {
                return $storyCmp;
            }

            return $a['index'] <=> $b['index'];
        });

        return array_values(array_map(
            static fn (array $row): array => $row['slide'],
            $indexed,
        ));
    }
}
