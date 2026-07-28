<?php

namespace Tests\Unit\Admin;

use App\Admin\Content\ContentOwnership;
use PHPUnit\Framework\TestCase;

class ContentOwnershipTest extends TestCase
{
    public function test_ensure_markdown_owner_inserts_into_frontmatter(): void
    {
        $raw = "---\ntitle: \"Hi\"\n---\n\nBody\n";
        $out = ContentOwnership::ensureMarkdownOwner($raw, 'user_1');
        $this->assertSame('user_1', ContentOwnership::ownerFromMarkdown($out));
        $this->assertStringContainsString('title: "Hi"', $out);
    }

    public function test_ensure_markdown_owner_keeps_existing_when_only_if_missing(): void
    {
        $raw = "---\ncreatedByUserId: user_old\ntitle: \"Hi\"\n---\n\nBody\n";
        $out = ContentOwnership::ensureMarkdownOwner($raw, 'user_new');
        $this->assertSame('user_old', ContentOwnership::ownerFromMarkdown($out));
    }

    public function test_stamp_row(): void
    {
        $row = ContentOwnership::stampRow(['id' => 'a'], 'user_1');
        $this->assertSame('user_1', ContentOwnership::ownerFromRow($row));
        $again = ContentOwnership::stampRow($row, 'user_2');
        $this->assertSame('user_1', ContentOwnership::ownerFromRow($again));
    }
}
