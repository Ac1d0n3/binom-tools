<?php

namespace App\Governance;

use DateTimeImmutable;
use InvalidArgumentException;
use SimpleXMLElement;

final class GovernanceRadarFeedParser
{
    /**
     * @return list<array{guid: string, title: string, summary: string, url: string, published_at: ?string, topics: list<string>}>
     */
    public function parse(string $xml): array
    {
        $previous = libxml_use_internal_errors(true);
        try {
            $root = simplexml_load_string($xml, SimpleXMLElement::class, LIBXML_NONET | LIBXML_NOCDATA);
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }

        if ($root === false) {
            throw new InvalidArgumentException('Feed XML could not be parsed.');
        }

        $rootName = strtolower($root->getName());
        if ($rootName === 'rss' || isset($root->channel)) {
            return $this->parseRss($root);
        }

        if ($rootName === 'feed' || $this->hasAtomNamespace($root)) {
            return $this->parseAtom($root);
        }

        throw new InvalidArgumentException('Unsupported feed format (expected RSS or Atom).');
    }

    /**
     * @return list<array{guid: string, title: string, summary: string, url: string, published_at: ?string, topics: list<string>}>
     */
    private function parseRss(SimpleXMLElement $root): array
    {
        $channel = $root->channel ?? $root;
        $items = [];
        foreach ($channel->item ?? [] as $item) {
            $title = $this->text($item->title ?? null);
            $link = $this->text($item->link ?? null);
            $guid = $this->text($item->guid ?? null) ?: $link ?: $title;
            if ($title === '' || $guid === '') {
                continue;
            }

            $summary = $this->text($item->description ?? null);
            if ($summary === '') {
                $summary = $this->text($item->children('content', true)->encoded ?? null);
            }

            $topics = [];
            foreach ($item->category ?? [] as $category) {
                $value = $this->text($category);
                if ($value !== '') {
                    $topics[] = $value;
                }
            }

            $items[] = [
                'guid' => mb_substr($guid, 0, 500),
                'title' => mb_substr($title, 0, 500),
                'summary' => $this->plainSummary($summary),
                'url' => mb_substr($link, 0, 1000),
                'published_at' => $this->normalizeDate($this->text($item->pubDate ?? null) ?: $this->text($item->children('dc', true)->date ?? null)),
                'topics' => array_values(array_unique($topics)),
            ];
        }

        return $items;
    }

    /**
     * @return list<array{guid: string, title: string, summary: string, url: string, published_at: ?string, topics: list<string>}>
     */
    private function parseAtom(SimpleXMLElement $root): array
    {
        $items = [];
        foreach ($root->entry ?? [] as $entry) {
            $title = $this->text($entry->title ?? null);
            $link = $this->atomLink($entry);
            $guid = $this->text($entry->id ?? null) ?: $link ?: $title;
            if ($title === '' || $guid === '') {
                continue;
            }

            $summary = $this->text($entry->summary ?? null);
            if ($summary === '') {
                $summary = $this->text($entry->content ?? null);
            }

            $topics = [];
            foreach ($entry->category ?? [] as $category) {
                $attrs = $category->attributes();
                $value = trim((string) ($attrs['term'] ?? $attrs['label'] ?? $this->text($category)));
                if ($value !== '') {
                    $topics[] = $value;
                }
            }

            $items[] = [
                'guid' => mb_substr($guid, 0, 500),
                'title' => mb_substr($title, 0, 500),
                'summary' => $this->plainSummary($summary),
                'url' => mb_substr($link, 0, 1000),
                'published_at' => $this->normalizeDate(
                    $this->text($entry->published ?? null) ?: $this->text($entry->updated ?? null)
                ),
                'topics' => array_values(array_unique($topics)),
            ];
        }

        return $items;
    }

    private function hasAtomNamespace(SimpleXMLElement $root): bool
    {
        $namespaces = $root->getNamespaces(true);

        return in_array('http://www.w3.org/2005/Atom', $namespaces, true);
    }

    private function atomLink(SimpleXMLElement $entry): string
    {
        foreach ($entry->link ?? [] as $link) {
            $attrs = $link->attributes();
            $rel = strtolower((string) ($attrs['rel'] ?? 'alternate'));
            $href = trim((string) ($attrs['href'] ?? ''));
            if ($href !== '' && ($rel === 'alternate' || $rel === '')) {
                return $href;
            }
        }

        foreach ($entry->link ?? [] as $link) {
            $href = trim((string) ($link->attributes()['href'] ?? ''));
            if ($href !== '') {
                return $href;
            }
        }

        return '';
    }

    private function text(mixed $node): string
    {
        if ($node === null) {
            return '';
        }

        return trim(html_entity_decode(strip_tags((string) $node), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    private function plainSummary(string $value): string
    {
        $plain = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? '');

        return mb_substr($plain, 0, 2000);
    }

    private function normalizeDate(string $value): ?string
    {
        if ($value === '') {
            return null;
        }

        try {
            return (new DateTimeImmutable($value))->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            return null;
        }
    }
}
