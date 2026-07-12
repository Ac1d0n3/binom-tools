<?php

namespace Tests\Unit\Support;

use App\Support\PlaybookImagePath;
use Tests\TestCase;

class PlaybookImagePathTest extends TestCase
{
    public function test_normalize_rewrites_legacy_stories_prefix(): void
    {
        $this->assertSame(
            'images/playbooks/dbt-role-hero.png',
            PlaybookImagePath::normalize('images/stories/dbt-role-hero.png'),
        );
    }

    public function test_asset_url_uses_canonical_playbook_path(): void
    {
        $this->assertSame(
            asset('images/playbooks/dbt-role-hero.png'),
            PlaybookImagePath::assetUrl('images/stories/dbt-role-hero.png'),
        );
    }
}
