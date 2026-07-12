<?php

namespace App\Playbooks;

use App\Support\LocaleUrl;
use App\Support\PlaybookImagePath;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Support\Str;

final class PlaybookMarkdownRenderer
{
    /**
     * @return array{html: string, toc: list<PlaybookTocEntry>}
     */
    public function render(string $markdown, string $idPrefix = ''): array
    {
        $html = Str::markdown($markdown, [], [
            new PlaybookFencedCodeExtension,
            new PlaybookVideoFenceExtension,
            new PlaybookFlowchartFenceExtension,
        ]);

        return $this->postProcess($html, $idPrefix);
    }

    /**
     * @return array{html: string, toc: list<PlaybookTocEntry>}
     */
    private function postProcess(string $html, string $idPrefix = ''): array
    {
        if (trim($html) === '') {
            return ['html' => '', 'toc' => []];
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $document->loadHTML(
            '<?xml encoding="UTF-8"><div id="playbook-root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();

        $root = $document->getElementById('playbook-root');

        if ($root === null) {
            return ['html' => $html, 'toc' => []];
        }

        $toc = [];
        $usedIds = [];

        foreach (['h2', 'h3'] as $tag) {
            $headings = $root->getElementsByTagName($tag);

            /** @var list<DOMElement> $nodes */
            $nodes = [];

            foreach ($headings as $heading) {
                if ($heading instanceof DOMElement) {
                    $nodes[] = $heading;
                }
            }

            foreach ($nodes as $heading) {
                $text = trim($heading->textContent ?? '');

                if ($text === '') {
                    continue;
                }

                $id = $this->uniqueId($this->prefixedId(Str::slug($text), $idPrefix), $usedIds);
                $heading->setAttribute('id', $id);

                $toc[] = new PlaybookTocEntry(
                    id: $id,
                    text: $text,
                    level: $tag === 'h2' ? 2 : 3,
                );
            }
        }

        $this->wrapTables($document, $root);
        $this->enhanceImages($root);
        $this->enhanceBlockquotes($document, $root);
        $this->enhanceLinks($root, $idPrefix);

        $innerHtml = '';

        foreach ($root->childNodes as $child) {
            $innerHtml .= $document->saveHTML($child);
        }

        return [
            'html' => $innerHtml,
            'toc' => $this->sortTocByDocumentOrder($toc, $root),
        ];
    }

    /**
     * @param  list<PlaybookTocEntry>  $toc
     * @return list<PlaybookTocEntry>
     */
    private function sortTocByDocumentOrder(array $toc, DOMElement $root): array
    {
        $order = [];
        $position = 0;

        foreach ($root->getElementsByTagName('*') as $element) {
            if ($element instanceof DOMElement && in_array($element->tagName, ['h2', 'h3'], true)) {
                $id = $element->getAttribute('id');

                if ($id !== '') {
                    $order[$id] = $position++;
                }
            }
        }

        usort($toc, fn (PlaybookTocEntry $a, PlaybookTocEntry $b): int => ($order[$a->id] ?? 0) <=> ($order[$b->id] ?? 0));

        return $toc;
    }

    private function prefixedId(string $base, string $prefix): string
    {
        if ($prefix === '') {
            return $base;
        }

        return $prefix.'-'.($base !== '' ? $base : 'section');
    }

    /**
     * @param  array<string, true>  $usedIds
     */
    private function uniqueId(string $base, array &$usedIds): string
    {
        $candidate = $base !== '' ? $base : 'section';
        $suffix = 2;

        while (isset($usedIds[$candidate])) {
            $candidate = $base.'-'.$suffix;
            $suffix++;
        }

        $usedIds[$candidate] = true;

        return $candidate;
    }

    private function wrapTables(DOMDocument $document, DOMElement $root): void
    {
        $xpath = new DOMXPath($document);
        $tables = $xpath->query('.//table', $root);

        if ($tables === false) {
            return;
        }

        /** @var list<DOMElement> $tableNodes */
        $tableNodes = [];

        foreach ($tables as $table) {
            if ($table instanceof DOMElement) {
                $tableNodes[] = $table;
            }
        }

        foreach ($tableNodes as $table) {
            if ($table->parentNode instanceof DOMElement && $table->parentNode->getAttribute('class') === 'playbook-prose__table-wrap') {
                continue;
            }

            $wrapper = $document->createElement('div');
            $wrapper->setAttribute('class', 'playbook-prose__table-wrap');
            $table->parentNode?->replaceChild($wrapper, $table);
            $wrapper->appendChild($table);
        }
    }

    private function enhanceBlockquotes(DOMDocument $document, DOMElement $root): void
    {
        /** @var list<DOMElement> $blockquotes */
        $blockquotes = [];

        foreach ($root->getElementsByTagName('blockquote') as $blockquote) {
            if ($blockquote instanceof DOMElement) {
                $blockquotes[] = $blockquote;
            }
        }

        foreach ($blockquotes as $blockquote) {
            $existing = trim($blockquote->getAttribute('class'));
            $blockquote->setAttribute('class', trim($existing.' playbook-prose__quote'));

            $lastParagraph = null;

            foreach ($blockquote->childNodes as $child) {
                if ($child instanceof DOMElement && $child->tagName === 'p') {
                    $lastParagraph = $child;
                }
            }

            if ($lastParagraph === null) {
                continue;
            }

            $text = trim($lastParagraph->textContent ?? '');

            if (! preg_match('/(?:^|\R)\s*(?:—|--|-\s)\s*(.+)\s*$/u', $text, $matches)) {
                continue;
            }

            $cite = trim($matches[1]);
            $quoteText = trim(preg_replace('/(?:\R)\s*(?:—|--|-\s)\s*.+\s*$/u', '', $text) ?? $text);

            if ($cite === '') {
                continue;
            }

            if ($quoteText !== '') {
                $lastParagraph->textContent = $quoteText;
            }

            $footer = $document->createElement('footer');
            $footer->setAttribute('class', 'playbook-prose__quote-cite');
            $footer->textContent = $cite;
            $blockquote->appendChild($footer);

            if ($quoteText === '') {
                $blockquote->removeChild($lastParagraph);
            }
        }
    }

    private function enhanceImages(DOMElement $root): void
    {
        foreach ($root->getElementsByTagName('img') as $image) {
            if ($image instanceof DOMElement) {
                $src = trim($image->getAttribute('src'));

                if ($src !== '') {
                    $image->setAttribute('src', $this->resolveImageUrl($src));
                }

                $existing = trim($image->getAttribute('class'));
                $image->setAttribute('class', trim($existing.' playbook-prose__image'));
                $image->setAttribute('loading', 'lazy');
            }
        }
    }

    private function resolveImageUrl(string $src): string
    {
        if (
            str_starts_with($src, 'http://')
            || str_starts_with($src, 'https://')
            || str_starts_with($src, '//')
        ) {
            return $src;
        }

        return PlaybookImagePath::assetUrl(PlaybookImagePath::normalize(ltrim($src, '/'))) ?? asset(ltrim($src, '/'));
    }

    private function enhanceLinks(DOMElement $root, string $locale): void
    {
        foreach ($root->getElementsByTagName('a') as $anchor) {
            if (! $anchor instanceof DOMElement) {
                continue;
            }

            $href = trim($anchor->getAttribute('href'));

            if ($href === '') {
                continue;
            }

            $anchor->setAttribute('href', $this->resolveLinkUrl($href, $locale));
        }
    }

    private function resolveLinkUrl(string $href, string $locale): string
    {
        if (
            str_starts_with($href, 'http://')
            || str_starts_with($href, 'https://')
            || str_starts_with($href, '//')
            || str_starts_with($href, '#')
            || str_starts_with($href, 'mailto:')
        ) {
            return $href;
        }

        if (str_starts_with($href, '/')) {
            return LocaleUrl::path($href, $locale !== '' ? $locale : null);
        }

        return $href;
    }

    public function readingTimeMinutes(string $markdown): int
    {
        $words = preg_split('/\s+/u', trim(strip_tags(Str::markdown($markdown)))) ?: [];

        return max(1, (int) ceil(count(array_filter($words)) / 200));
    }
}
