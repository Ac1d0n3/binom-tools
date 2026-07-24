<?php

namespace Tests\Unit\Playbooks;

use App\Playbooks\PlaybookVideoEmbedResolver;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PlaybookVideoEmbedResolverTest extends TestCase
{
    #[DataProvider('supportedVideoUrls')]
    public function test_resolves_supported_video_urls(string $url, string $platform, string $id): void
    {
        $resolved = PlaybookVideoEmbedResolver::resolve($url);

        $this->assertNotNull($resolved);
        $this->assertSame($platform, $resolved['platform']);
        $this->assertSame($id, $resolved['id']);
        $this->assertNotEmpty($resolved['embedUrl']);
    }

    /** @return array<string, array{0: string, 1: string, 2: string}> */
    public static function supportedVideoUrls(): array
    {
        return [
            'youtube watch' => ['https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'youtube', 'dQw4w9WgXcQ'],
            'youtu.be' => ['https://youtu.be/dQw4w9WgXcQ', 'youtube', 'dQw4w9WgXcQ'],
            'youtube embed' => ['https://www.youtube.com/embed/dQw4w9WgXcQ', 'youtube', 'dQw4w9WgXcQ'],
            'vimeo' => ['https://vimeo.com/123456789', 'vimeo', '123456789'],
            'loom' => ['https://www.loom.com/share/abc123def', 'loom', 'abc123def'],
            'wistia medias' => ['https://fast.wistia.com/medias/abc123xyz', 'wistia', 'abc123xyz'],
            'wistia embed' => ['https://fast.wistia.net/embed/iframe/abc123xyz', 'wistia', 'abc123xyz'],
        ];
    }

    public function test_youtube_uses_privacy_embed_url(): void
    {
        $resolved = PlaybookVideoEmbedResolver::resolve('https://www.youtube.com/watch?v=dQw4w9WgXcQ');

        $this->assertSame(
            'https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ',
            $resolved['privacyEmbedUrl'] ?? null,
        );
    }

    public function test_rejects_unknown_urls(): void
    {
        $this->assertNull(PlaybookVideoEmbedResolver::resolve('https://example.com/video/1'));
        $this->assertNull(PlaybookVideoEmbedResolver::resolve('not-a-url'));
        $this->assertNull(PlaybookVideoEmbedResolver::resolve(''));
    }

    public function test_resolves_local_playbook_videos(): void
    {
        $resolved = PlaybookVideoEmbedResolver::resolve('/videos/playbooks/intro.mp4');

        $this->assertNotNull($resolved);
        $this->assertSame('local', $resolved['platform']);
        $this->assertSame('intro.mp4', $resolved['id']);
        $this->assertSame('videos/playbooks/intro.mp4', $resolved['publicPath'] ?? null);
        $this->assertSame('video/mp4', $resolved['mimeType'] ?? null);
        $this->assertNotEmpty($resolved['srcUrl'] ?? null);

        $relative = PlaybookVideoEmbedResolver::resolve('videos/playbooks/demo.webm');
        $this->assertNotNull($relative);
        $this->assertSame('local', $relative['platform']);
        $this->assertSame('video/webm', $relative['mimeType'] ?? null);
    }

    public function test_rejects_unsafe_local_video_paths(): void
    {
        $this->assertNull(PlaybookVideoEmbedResolver::resolve('/videos/playbooks/../secret.mp4'));
        $this->assertNull(PlaybookVideoEmbedResolver::resolve('/videos/other/intro.mp4'));
        $this->assertNull(PlaybookVideoEmbedResolver::resolve('/videos/playbooks/nested/dir/intro.mp4'));
        $this->assertNull(PlaybookVideoEmbedResolver::resolve('/videos/playbooks/intro.avi'));
    }
}
