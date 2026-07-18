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

    public function test_normalize_resolves_bare_filename_under_playbooks_dir(): void
    {
        $pngPath = public_path('images/playbooks/playbook-image-path-bare-test.png');
        @unlink($pngPath);

        try {
            file_put_contents($pngPath, 'png');
            $this->assertSame(
                'images/playbooks/playbook-image-path-bare-test.png',
                PlaybookImagePath::normalize('playbook-image-path-bare-test.png'),
            );
        } finally {
            @unlink($pngPath);
        }
    }

    public function test_normalize_collapses_double_dot_before_extension(): void
    {
        $this->assertSame(
            'images/playbooks/slowlychange-dim-img2-en.png',
            PlaybookImagePath::normalize('images/playbooks/slowlychange-dim-img2-en..png'),
        );
    }

    public function test_asset_url_uses_canonical_playbook_path(): void
    {
        $this->assertSame(
            asset('images/playbooks/dbt-role-hero.png'),
            PlaybookImagePath::assetUrl('images/stories/dbt-role-hero.png'),
        );
    }

    public function test_webp_path_rewrites_png_extension(): void
    {
        $this->assertSame(
            'images/playbooks/dbt-role-hero.webp',
            PlaybookImagePath::webpPath('images/playbooks/dbt-role-hero.png'),
        );
    }

    public function test_webp_url_returns_null_when_webp_file_is_missing(): void
    {
        $this->assertNull(
            PlaybookImagePath::webpUrl('images/playbooks/nonexistent-playbook-image.png'),
        );
    }

    public function test_webp_url_returns_asset_url_when_webp_file_exists(): void
    {
        $webpPath = public_path('images/playbooks/playbook-image-path-test.webp');

        try {
            if (! is_dir(dirname($webpPath))) {
                mkdir(dirname($webpPath), 0755, true);
            }

            file_put_contents($webpPath, 'test');

            $this->assertSame(
                asset('images/playbooks/playbook-image-path-test.webp'),
                PlaybookImagePath::webpUrl('images/playbooks/playbook-image-path-test.png'),
            );
        } finally {
            @unlink($webpPath);
        }
    }

    public function test_picture_sources_returns_null_without_webp_file(): void
    {
        $this->assertNull(
            PlaybookImagePath::pictureSources('images/playbooks/nonexistent-playbook-image.png'),
        );
    }

    public function test_picture_sources_accepts_asset_urls(): void
    {
        $webpPath = public_path('images/playbooks/playbook-image-path-test.webp');

        try {
            if (! is_dir(dirname($webpPath))) {
                mkdir(dirname($webpPath), 0755, true);
            }

            file_put_contents($webpPath, 'test');

            $sources = PlaybookImagePath::pictureSources(
                asset('images/playbooks/playbook-image-path-test.png'),
            );

            $this->assertIsArray($sources);
            $this->assertSame(
                asset('images/playbooks/playbook-image-path-test.webp'),
                $sources['webp'],
            );
            $this->assertSame(
                asset('images/playbooks/playbook-image-path-test.png'),
                $sources['fallback'],
            );
        } finally {
            @unlink($webpPath);
        }
    }

    public function test_picture_sources_strips_subdirectory_from_asset_urls(): void
    {
        config(['app.url' => 'http://localhost/binom-tools']);
        $this->app['url']->forceRootUrl('http://localhost/binom-tools');

        $webpPath = public_path('images/playbooks/playbook-image-path-subdir-test.webp');

        try {
            if (! is_dir(dirname($webpPath))) {
                mkdir(dirname($webpPath), 0755, true);
            }

            file_put_contents($webpPath, 'test');

            $sources = PlaybookImagePath::pictureSources(
                asset('images/playbooks/playbook-image-path-subdir-test.png'),
            );

            $this->assertIsArray($sources);
            $this->assertSame(
                asset('images/playbooks/playbook-image-path-subdir-test.webp'),
                $sources['webp'],
            );
        } finally {
            @unlink($webpPath);
        }
    }
}
