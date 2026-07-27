<?php

namespace App\Seo;

use App\Catalog\SearchIndex;
use App\Support\Locale;
use Carbon\Carbon;

final class SitemapBuilder
{
    public function __construct(
        private readonly SearchIndex $searchIndex,
    ) {}

    /**
     * @return list<string>
     */
    public function groups(): array
    {
        return ['pages', 'playbooks', 'resources', 'suppliers', 'tools', 'glossary', 'compliance', 'learning'];
    }

    /**
     * @return list<array{loc: string, lastmod?: string, changefreq?: string, priority?: string}>
     */
    public function urlsForGroup(string $group): array
    {
        return match ($group) {
            'pages' => $this->pageUrls(),
            'playbooks' => $this->entryUrls(['story', 'series', 'radar'], 'weekly', '0.7'),
            'resources' => $this->resourceHubUrls(),
            'suppliers' => $this->entryUrls(['supplier'], 'weekly', '0.6'),
            'tools' => $this->entryUrls(['tool'], 'weekly', '0.7'),
            'glossary' => $this->entryUrls(['glossary'], 'monthly', '0.5'),
            'compliance' => $this->entryUrls(['compliance'], 'monthly', '0.6'),
            'learning' => $this->entryUrls(['path', 'role'], 'monthly', '0.5'),
            default => [],
        };
    }

    /**
     * @return list<array{loc: string, lastmod: string}>
     */
    public function indexEntries(): array
    {
        $now = Carbon::now()->toAtomString();

        return array_map(
            fn (string $group): array => [
                'loc' => url('/sitemap-'.$group.'.xml'),
                'lastmod' => $now,
            ],
            $this->groups(),
        );
    }

    /**
     * @param  list<string>  $types
     * @return list<array{loc: string, lastmod?: string, changefreq?: string, priority?: string}>
     */
    private function entryUrls(array $types, string $changefreq, string $priority): array
    {
        $urls = [];
        $typeSet = array_fill_keys($types, true);

        foreach ($this->searchIndex->all() as $entry) {
            $type = (string) ($entry['type'] ?? '');
            if (! isset($typeSet[$type])) {
                continue;
            }

            $route = (string) ($entry['route'] ?? '');
            if ($route === '') {
                continue;
            }

            /** @var array<string, string> $params */
            $params = is_array($entry['params'] ?? null) ? $entry['params'] : [];

            foreach (Locale::SUPPORTED as $locale) {
                $urls[] = $this->url(
                    locale_route($route, $params, $locale),
                    $changefreq,
                    $priority,
                );
            }
        }

        return $this->uniqueByLoc($urls);
    }

    /**
     * @return list<array{loc: string, lastmod?: string, changefreq?: string, priority?: string}>
     */
    private function pageUrls(): array
    {
        $routes = [
            ['tools.landing', [], 'daily', '1.0'],
            ['tools.overview', [], 'weekly', '0.8'],
            ['governance.index', [], 'daily', '0.9'],
            ['governance.advisor', [], 'weekly', '0.9'],
            ['governance.stacks', [], 'weekly', '0.8'],
            ['governance.kpi-requirements', [], 'weekly', '0.8'],
            ['governance.supplier-discovery', [], 'weekly', '0.8'],
            ['governance.discovery-canvas', [], 'weekly', '0.8'],
            ['governance.radar', [], 'daily', '0.7'],
            ['playbooks.index', [], 'weekly', '0.8'],
            ['resources.index', [], 'weekly', '0.8'],
            ['suppliers.index', [], 'weekly', '0.8'],
            ['compliance.index', [], 'weekly', '0.7'],
            ['compliance.roadmap', [], 'monthly', '0.5'],
            ['glossary.index', [], 'weekly', '0.6'],
            ['learning-paths.index', [], 'weekly', '0.6'],
            ['roles.index', [], 'weekly', '0.6'],
            ['about.show', [], 'monthly', '0.5'],
            ['legal.impressum', [], 'yearly', '0.2'],
            ['legal.disclaimer', [], 'yearly', '0.2'],
            ['legal.privacy', [], 'yearly', '0.2'],
            ['calendar.index', [], 'weekly', '0.4'],
        ];

        $urls = [];
        foreach ($routes as [$name, $params, $changefreq, $priority]) {
            if (! \Illuminate\Support\Facades\Route::has($name)) {
                continue;
            }

            foreach (Locale::SUPPORTED as $locale) {
                $urls[] = $this->url(
                    locale_route($name, $params, $locale),
                    $changefreq,
                    $priority,
                );
            }
        }

        return $this->uniqueByLoc($urls);
    }

    /**
     * @return list<array{loc: string, lastmod?: string, changefreq?: string, priority?: string}>
     */
    private function resourceHubUrls(): array
    {
        $urls = [];
        foreach (Locale::SUPPORTED as $locale) {
            $urls[] = $this->url(locale_route('resources.index', [], $locale), 'weekly', '0.8');
        }

        // Resource cards are filter deep-links on the hub; keep index only for clean SEO.
        return $this->uniqueByLoc($urls);
    }

    /**
     * @return array{loc: string, lastmod: string, changefreq: string, priority: string}
     */
    private function url(string $loc, string $changefreq, string $priority): array
    {
        return [
            'loc' => $loc,
            'lastmod' => Carbon::now()->startOfDay()->toAtomString(),
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }

    /**
     * @param  list<array{loc: string, lastmod?: string, changefreq?: string, priority?: string}>  $urls
     * @return list<array{loc: string, lastmod?: string, changefreq?: string, priority?: string}>
     */
    private function uniqueByLoc(array $urls): array
    {
        $seen = [];
        $unique = [];
        foreach ($urls as $url) {
            $loc = $url['loc'];
            if (isset($seen[$loc])) {
                continue;
            }
            $seen[$loc] = true;
            $unique[] = $url;
        }

        return $unique;
    }
}
