<?php

namespace App\Console\Commands;

use App\Seo\SitemapBuilder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SeoSitemapCheckCommand extends Command
{
    protected $signature = 'seo:sitemap-check {--http : Also HTTP-fetch sitemap endpoints against APP_URL}';

    protected $description = 'Validate sitemap groups and print Search Console / Bing submission checklist';

    public function handle(SitemapBuilder $sitemap): int
    {
        $this->info('Sitemap groups:');
        $total = 0;
        foreach ($sitemap->groups() as $group) {
            $count = count($sitemap->urlsForGroup($group));
            $total += $count;
            $this->line(sprintf('  %-12s %5d URLs  → %s', $group, $count, url('/sitemap-'.$group.'.xml')));
        }
        $this->line(sprintf('  %-12s %5d URLs  → %s', 'index', count($sitemap->indexEntries()), url('/sitemap.xml')));
        $this->newLine();
        $this->info("Total indexable URLs across groups: {$total}");

        if ($this->option('http')) {
            $this->newLine();
            $this->info('HTTP checks:');
            foreach (['/robots.txt', '/sitemap.xml', ...array_map(
                static fn (string $g): string => '/sitemap-'.$g.'.xml',
                $sitemap->groups(),
            )] as $path) {
                $url = url($path);
                try {
                    $response = Http::timeout(15)->get($url);
                    $ok = $response->successful();
                    $this->line(($ok ? '[ok] ' : '[!!] ').$url.' ('.$response->status().')');
                } catch (\Throwable $e) {
                    $this->line('[!!] '.$url.' ('.$e->getMessage().')');
                }
            }
        }

        $this->newLine();
        $this->info('Search provider submission checklist:');
        foreach ([
            'Verify Google Search Console property for the production host',
            'Submit '.url('/sitemap.xml').' in Google Search Console',
            'Verify Bing Webmaster Tools site for the same host',
            'Submit the same sitemap index in Bing Webmaster Tools',
            'Confirm robots.txt Allow + Sitemap directive on production',
            'Spot-check the first 20 priority URLs (Start, Governance hubs, Resources, Suppliers, top Playbooks/Tools)',
            'After 7–14 days: review Coverage/Indexing and move errors into an SEO backlog',
            'Optional later: IndexNow pings for new/changed Governance pages',
        ] as $step) {
            $this->line('  [ ] '.$step);
        }

        $this->newLine();
        $this->comment('Ops details: docs/governance-search-submission.de.md');

        return self::SUCCESS;
    }
}
