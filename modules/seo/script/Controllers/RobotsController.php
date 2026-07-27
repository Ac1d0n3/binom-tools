<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function __invoke(): Response
    {
        $sitemap = url('/sitemap.xml');
        $lines = [
            'User-agent: *',
            'Allow: /',
            '',
            '# Keep private workspaces, auth, and APIs out of the index.',
            'Disallow: /account',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /api/',
            'Disallow: /governance/sessions',
            'Disallow: /de/account',
            'Disallow: /de/login',
            'Disallow: /de/register',
            'Disallow: /de/governance/sessions',
            'Disallow: /en/account',
            'Disallow: /en/login',
            'Disallow: /en/register',
            'Disallow: /en/governance/sessions',
            '',
            'Sitemap: '.$sitemap,
            '',
        ];

        return response(implode("\n", $lines), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
