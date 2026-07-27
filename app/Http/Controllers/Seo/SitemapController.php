<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use App\Seo\SitemapBuilder;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SitemapController extends Controller
{
    public function __construct(
        private readonly SitemapBuilder $sitemap,
    ) {}

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
