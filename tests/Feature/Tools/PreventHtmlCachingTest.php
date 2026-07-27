<?php

namespace Tests\Feature\Tools;

use Tests\TestCase;

class PreventHtmlCachingTest extends TestCase
{
    public function test_html_responses_are_not_cacheable(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $cacheControl = (string) $response->headers->get('Cache-Control');

        $this->assertStringContainsString('no-store', $cacheControl);
        $this->assertStringContainsString('no-cache', $cacheControl);
        $this->assertSame('no-cache', $response->headers->get('Pragma'));
        $this->assertSame('0', $response->headers->get('Expires'));
        $this->assertNull($response->headers->get('ETag'));
        $this->assertNull($response->headers->get('Last-Modified'));

        $css = $response->getContent() ?: '';
        $this->assertMatchesRegularExpression(
            '#/build/assets/app-[^"\']+\.css\?b=\d+#',
            $css,
            'Vite CSS URLs must carry a deploy bust query so Safari cannot keep a truncated FTP copy',
        );
    }
}
