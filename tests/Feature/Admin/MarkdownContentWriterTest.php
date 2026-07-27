<?php

namespace Tests\Feature\Admin;

use App\Admin\Content\MarkdownContentWriter;
use Tests\TestCase;

class MarkdownContentWriterTest extends TestCase
{
    public function test_writes_markdown_pair_without_git(): void
    {
        $dir = sys_get_temp_dir().'/bn-md-'.bin2hex(random_bytes(4));
        mkdir($dir, 0775, true);
        $writer = new MarkdownContentWriter($dir);
        $writer->write('demo-story', 'en', "---\ntitle: Demo\n---\n\nHello\n");
        $writer->write('demo-story', 'de', "---\ntitle: Demo\n---\n\nHallo\n");

        $this->assertSame("---\ntitle: Demo\n---\n\nHello\n", $writer->read('demo-story', 'en'));
        $rows = $writer->listSlugs();
        $this->assertTrue(collect($rows)->contains(fn ($r) => $r['slug'] === 'demo-story' && $r['en'] && $r['de']));

        $writer->delete('demo-story');
        $this->assertNull($writer->read('demo-story', 'en'));
        @rmdir($dir);
    }
}
