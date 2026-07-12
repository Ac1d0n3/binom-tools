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

    public function test_renders_enhanced_blockquote_with_attribution(): void
    {
        $renderer = new PlaybookMarkdownRenderer;

        $result = $renderer->render(<<<'MD'
> Governance is not a tool problem.
> — Thomas Lindackers
MD);

        $this->assertStringContainsString('class="playbook-prose__quote"', $result['html']);
        $this->assertStringContainsString('class="playbook-prose__quote-cite"', $result['html']);
        $this->assertStringContainsString('Thomas Lindackers', $result['html']);
        $this->assertStringNotContainsString('— Thomas Lindackers', $result['html']);
    }

    public function test_renders_video_fence_without_iframe(): void
    {
        $renderer = new PlaybookMarkdownRenderer;

        $result = $renderer->render(<<<'MD'
```video Demo
https://www.youtube.com/watch?v=dQw4w9WgXcQ
```
MD);

        $this->assertStringContainsString('data-video-embed', $result['html']);
        $this->assertStringContainsString('data-platform="youtube"', $result['html']);
        $this->assertStringContainsString('playbook-video__consent-trigger', $result['html']);
        $this->assertStringNotContainsString('<iframe', $result['html']);
    }

    public function test_renders_invalid_video_fence_as_error_block(): void
    {
        $renderer = new PlaybookMarkdownRenderer;

        $result = $renderer->render(<<<'MD'
```video
https://example.com/not-a-video
```
MD);

        $this->assertStringContainsString('playbook-video--error', $result['html']);
        $this->assertStringContainsString('Unsupported or invalid video URL.', $result['html']);
    }

    public function test_renders_flowchart_fence_as_chevron_flow(): void
    {
        $renderer = new PlaybookMarkdownRenderer;

        $result = $renderer->render(<<<'MD'
```flowchart
Governance policy
dbt project
Warehouse
```
MD);

        $this->assertStringContainsString('playbook-flowchart--chevron', $result['html']);
        $this->assertStringContainsString('playbook-flowchart__chevron', $result['html']);
        $this->assertStringContainsString('Governance policy', $result['html']);
        $this->assertStringContainsString('dbt project', $result['html']);
    }

    public function test_renders_linear_flowchart_variant(): void
    {
        $renderer = new PlaybookMarkdownRenderer;

        $result = $renderer->render(<<<'MD'
```flow linear
Collect
Transform
Publish
```
MD);

        $this->assertStringContainsString('playbook-flowchart--linear', $result['html']);
        $this->assertStringContainsString('playbook-flowchart__step', $result['html']);
    }

    public function test_rewrites_internal_markdown_links_with_app_base_and_locale(): void
    {
        config(['app.url' => 'http://localhost/binom-tools']);
        $this->app['url']->forceRootUrl('http://localhost/binom-tools');
        $this->app->instance('request', \Illuminate\Http\Request::create('/binom-tools/playbooks/eight-pillars', 'GET'));

        $renderer = new PlaybookMarkdownRenderer;

        $english = $renderer->render(
            'Related: [Eight Pillars](/playbooks/eight-pillars).',
            'en',
        );

        $this->assertStringContainsString(
            'href="/binom-tools/playbooks/eight-pillars"',
            $english['html'],
        );

        $german = $renderer->render(
            'Verwandt: [Acht Säulen](/playbooks/eight-pillars).',
            'de',
        );

        $this->assertStringContainsString(
            'href="/binom-tools/de/playbooks/eight-pillars"',
            $german['html'],
        );
    }
}
