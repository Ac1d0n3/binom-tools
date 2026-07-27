<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use App\Seo\SitemapBuilder;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SitemapController extends Controller
{
    public function __construct(
        private readonly SitemapBuilder $sitemap,
    ) {}

    public function html(): View
    {
        $sections = [
            [
                'id' => 'governance',
                'title' => ['de' => 'Governance', 'en' => 'Governance'],
                'links' => [
                    ['href' => locale_route('governance.index'), 'label' => ['de' => 'Governance Hub', 'en' => 'Governance Hub']],
                    ['href' => locale_route('governance.radar'), 'label' => ['de' => 'Governance Radar', 'en' => 'Governance Radar']],
                ],
            ],
            [
                'id' => 'hubs',
                'title' => ['de' => 'Hubs', 'en' => 'Hubs'],
                'links' => [
                    ['href' => locale_route('tools.overview'), 'label' => ['de' => 'Tools', 'en' => 'Tools']],
                    ['href' => locale_route('playbooks.index'), 'label' => ['de' => 'Playbooks', 'en' => 'Playbooks']],
                    ['href' => locale_route('resources.index'), 'label' => ['de' => 'Resources', 'en' => 'Resources']],
                    ['href' => locale_route('suppliers.index'), 'label' => ['de' => 'Supplier Library', 'en' => 'Supplier library']],
                    ['href' => locale_route('compliance.index'), 'label' => ['de' => 'Compliance', 'en' => 'Compliance']],
                    ['href' => locale_route('glossary.index'), 'label' => ['de' => 'Glossar', 'en' => 'Glossary']],
                    ['href' => locale_route('learning-paths.index'), 'label' => ['de' => 'Learning Paths', 'en' => 'Learning paths']],
                    ['href' => locale_route('roles.index'), 'label' => ['de' => 'Rollen', 'en' => 'Roles']],
                ],
            ],
            [
                'id' => 'legal',
                'title' => ['de' => 'Rechtliches & Meta', 'en' => 'Legal & meta'],
                'links' => [
                    ['href' => locale_route('about.show'), 'label' => ['de' => 'Über das Projekt', 'en' => 'About']],
                    ['href' => locale_route('legal.impressum'), 'label' => ['de' => 'Impressum', 'en' => 'Legal notice']],
                    ['href' => locale_route('legal.privacy'), 'label' => ['de' => 'Datenschutz', 'en' => 'Privacy']],
                    ['href' => locale_route('legal.disclaimer'), 'label' => ['de' => 'Disclaimer', 'en' => 'Disclaimer']],
                ],
            ],
        ];

        return view('seo.sitemap', [
            'sections' => $sections,
            'xmlSitemapUrl' => route('seo.sitemap'),
        ]);
    }

    public function index(): Response
    {
        $entries = $this->sitemap->indexEntries();
        $body = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $body .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($entries as $entry) {
            $body .= "  <sitemap>\n";
            $body .= '    <loc>'.$this->escape($entry['loc'])."</loc>\n";
            $body .= '    <lastmod>'.$this->escape($entry['lastmod'])."</lastmod>\n";
            $body .= "  </sitemap>\n";
        }
        $body .= '</sitemapindex>'."\n";

        return $this->xml($body);
    }

    public function group(string $group): Response
    {
        if (! in_array($group, $this->sitemap->groups(), true)) {
            throw new NotFoundHttpException('Unknown sitemap group.');
        }

        $urls = $this->sitemap->urlsForGroup($group);
        $body = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $body .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($urls as $url) {
            $body .= "  <url>\n";
            $body .= '    <loc>'.$this->escape($url['loc'])."</loc>\n";
            if (! empty($url['lastmod'])) {
                $body .= '    <lastmod>'.$this->escape((string) $url['lastmod'])."</lastmod>\n";
            }
            if (! empty($url['changefreq'])) {
                $body .= '    <changefreq>'.$this->escape((string) $url['changefreq'])."</changefreq>\n";
            }
            if (! empty($url['priority'])) {
                $body .= '    <priority>'.$this->escape((string) $url['priority'])."</priority>\n";
            }
            $body .= "  </url>\n";
        }
        $body .= '</urlset>'."\n";

        return $this->xml($body);
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    private function xml(string $body): Response
    {
        return response($body, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
