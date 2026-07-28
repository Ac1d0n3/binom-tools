<?php

namespace App\Governance;

/**
 * Resolve curated Advisor content cards (stories, suppliers, vendors) from catalog JSON.
 */
final class AdvisorContentCardResolver
{
    private const KINDS = ['story', 'supplier', 'vendor'];

    private const GUIDANCE_STORY_KEYS = [
        'eight-pillars' => 'eightPillars',
        'bridge-solution' => 'bridgeSolutionStory',
        'metadata-catalog-lineage' => 'metadataCatalogStory',
    ];

    /**
     * @param  array<string, mixed>  $catalog  config('advisor-recommendations')
     * @return list<array<string, mixed>>
     */
    public function resolveContentCards(array $catalog): array
    {
        $storySlugs = $this->storySlugSet();
        $supplierIds = $this->supplierIdSet();
        $vendorIds = $this->vendorIdSet();

        $cards = [];
        foreach ($catalog['items'] ?? [] as $raw) {
            if (! is_array($raw)) {
                continue;
            }
            $card = $this->normalizeItem($raw, $storySlugs, $supplierIds, $vendorIds);
            if ($card !== null) {
                $cards[] = $card;
            }
        }

        return $cards;
    }

    /**
     * Guidance keys → resolved playbook URLs for story refs still used by advisor-guidance.js.
     *
     * @param  list<array<string, mixed>>  $contentCards
     * @return array<string, string>
     */
    public function guidanceStoryUrls(array $contentCards): array
    {
        $byRef = [];
        foreach ($contentCards as $card) {
            if (($card['kind'] ?? '') !== 'story') {
                continue;
            }
            $ref = (string) ($card['ref'] ?? '');
            $url = (string) ($card['url'] ?? '');
            if ($ref !== '' && $url !== '' && $url !== '#') {
                $byRef[$ref] = $url;
            }
        }

        $out = [];
        foreach (self::GUIDANCE_STORY_KEYS as $slug => $key) {
            if (isset($byRef[$slug])) {
                $out[$key] = $byRef[$slug];

                continue;
            }
            // Fall back to route even if not in catalog (guidance cert/gap cards).
            if ($this->storyExists($slug)) {
                $out[$key] = locale_route('playbooks.show', ['slug' => $slug]);
            }
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>  $raw
     * @param  array<string, true>  $storySlugs
     * @param  array<string, true>  $supplierIds
     * @param  array<string, true>  $vendorIds
     * @return array<string, mixed>|null
     */
    private function normalizeItem(array $raw, array $storySlugs, array $supplierIds, array $vendorIds): ?array
    {
        if (! ($raw['enabled'] ?? true)) {
            return null;
        }

        $id = trim((string) ($raw['id'] ?? ''));
        $kind = trim((string) ($raw['kind'] ?? ''));
        $ref = trim((string) ($raw['ref'] ?? ''));
        if ($id === '' || $ref === '' || ! in_array($kind, self::KINDS, true)) {
            return null;
        }
        if (! preg_match('/^[a-z0-9-]+$/', $id) || ! preg_match('/^[a-z0-9-]+$/', $ref)) {
            return null;
        }

        $url = match ($kind) {
            'story' => isset($storySlugs[$ref])
                ? locale_route('playbooks.show', ['slug' => $ref])
                : null,
            'supplier' => isset($supplierIds[$ref])
                ? locale_route('suppliers.show', ['slug' => $ref])
                : null,
            'vendor' => isset($vendorIds[$ref])
                ? locale_route('resources.index').'?vendor='.rawurlencode($ref)
                : null,
            default => null,
        };

        if ($url === null) {
            return null;
        }

        $group = trim((string) ($raw['group'] ?? ''));
        if ($group === '') {
            $group = $kind === 'supplier' ? 'suppliers' : 'resources';
        }

        $tags = [];
        foreach ($raw['tags'] ?? [] as $tag) {
            if (is_string($tag) && $tag !== '') {
                $tags[] = $tag;
            }
        }

        return [
            'id' => $id,
            'kind' => $kind,
            'ref' => $ref,
            'group' => $group,
            'icon' => trim((string) ($raw['icon'] ?? '')) ?: 'fa-arrow-right',
            'score' => max(0, (int) ($raw['score'] ?? 70)),
            'tags' => array_values(array_unique($tags)),
            'when' => $this->normalizeWhen(is_array($raw['when'] ?? null) ? $raw['when'] : []),
            'title' => $this->localePair($raw['title'] ?? null, $ref),
            'reason' => $this->localePair($raw['reason'] ?? null, ''),
            'url' => $url,
        ];
    }

    /**
     * @param  array<string, mixed>  $when
     * @return array{goals: list<string>, scenarios: list<string>, domains: list<string>, platforms: list<string>, roles: list<string>}
     */
    private function normalizeWhen(array $when): array
    {
        $out = [
            'goals' => [],
            'scenarios' => [],
            'domains' => [],
            'platforms' => [],
            'roles' => [],
        ];
        foreach (array_keys($out) as $key) {
            $list = $when[$key] ?? [];
            if (! is_array($list)) {
                continue;
            }
            foreach ($list as $value) {
                if (is_string($value) && $value !== '') {
                    $out[$key][] = $value;
                }
            }
            $out[$key] = array_values(array_unique($out[$key]));
        }

        return $out;
    }

    /**
     * @return array{de: string, en: string}
     */
    private function localePair(mixed $value, string $fallback): array
    {
        if (is_array($value)) {
            $de = trim((string) ($value['de'] ?? ''));
            $en = trim((string) ($value['en'] ?? ''));
            if ($de === '') {
                $de = $en !== '' ? $en : $fallback;
            }
            if ($en === '') {
                $en = $de !== '' ? $de : $fallback;
            }

            return ['de' => $de, 'en' => $en];
        }

        $text = is_string($value) ? trim($value) : $fallback;

        return ['de' => $text, 'en' => $text];
    }

    /**
     * @return array<string, true>
     */
    private function storySlugSet(): array
    {
        $set = [];
        $dir = base_path('content/stories');
        if (! is_dir($dir)) {
            return $set;
        }
        foreach (scandir($dir) ?: [] as $file) {
            if (! is_string($file) || ! preg_match('/^([a-z0-9-]+)\.(de|en)\.md$/', $file, $m)) {
                continue;
            }
            $set[$m[1]] = true;
        }

        return $set;
    }

    private function storyExists(string $slug): bool
    {
        return isset($this->storySlugSet()[$slug]);
    }

    /**
     * @return array<string, true>
     */
    private function supplierIdSet(): array
    {
        $set = [];
        foreach (config('suppliers.products', []) as $product) {
            if (! is_array($product)) {
                continue;
            }
            $id = trim((string) ($product['id'] ?? ''));
            if ($id !== '') {
                $set[$id] = true;
            }
        }

        return $set;
    }

    /**
     * @return array<string, true>
     */
    private function vendorIdSet(): array
    {
        $set = [];
        $vendors = config('vendor-resources.vendors', []);
        if (! is_array($vendors)) {
            return $set;
        }
        foreach (array_keys($vendors) as $id) {
            if (is_string($id) && $id !== '') {
                $set[$id] = true;
            }
        }

        return $set;
    }
}
