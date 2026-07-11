<?php

namespace Tests\Unit\Playbooks;

use App\Playbooks\PlaybookMarkdownRenderer;
use Tests\TestCase;

class PlaybookMarkdownRendererTest extends TestCase
{
    public function test_adds_heading_ids_and_toc_entries(): void
    {
        $renderer = new PlaybookMarkdownRenderer;

        $result = $renderer->render(<<<'MD'
## Overview

Intro

### Details

More
MD, 'en');

        $this->assertStringContainsString('id="en-overview"', $result['html']);
        $this->assertStringContainsString('id="en-details"', $result['html']);
        $this->assertCount(2, $result['toc']);
        $this->assertSame('en-overview', $result['toc'][0]->id);
        $this->assertSame('en-details', $result['toc'][1]->id);
    }

    public function test_renders_enhanced_code_fence_wrapper(): void
    {
        $renderer = new PlaybookMarkdownRenderer;

        $result = $renderer->render(<<<'MD'
```php title="Example.php" {2}
<?php
echo 'hello';
```
MD);

        $this->assertStringContainsString('class="playbook-code"', $result['html']);
        $this->assertStringContainsString('data-title="Example.php"', $result['html']);
        $this->assertStringContainsString('data-highlight="2"', $result['html']);
        $this->assertStringContainsString('language-php', $result['html']);
    }

    public function test_rewrites_local_image_src_through_asset_helper(): void
    {
        $this->app['url']->forceRootUrl('http://localhost/binom-tools');

        $renderer = new PlaybookMarkdownRenderer;

        $result = $renderer->render(<<<'MD'
<figure>
    <img src="/images/playbooks/example.png" alt="Example" />
    <img src="images/playbooks/other.png" alt="Other" />
    <img src="https://cdn.example.com/remote.png" alt="Remote" />
</figure>
MD);

        $this->assertStringContainsString(
            'src="http://localhost/binom-tools/images/playbooks/example.png"',
            $result['html'],
        );
        $this->assertStringContainsString(
            'src="http://localhost/binom-tools/images/playbooks/other.png"',
            $result['html'],
        );
        $this->assertStringContainsString(
            'src="https://cdn.example.com/remote.png"',
            $result['html'],
        );
    }
}
