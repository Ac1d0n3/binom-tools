<?php

namespace Tests\Unit\Playbooks;

use App\Playbooks\PlaybookStatsStore;
use PHPUnit\Framework\TestCase;

final class PlaybookStatsStoreTest extends TestCase
{
    private string $dir;

    private PlaybookStatsStore $store;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dir = sys_get_temp_dir().'/playbook-stats-'.uniqid('', true);
        mkdir($this->dir, 0775, true);
        $this->store = new PlaybookStatsStore($this->dir);
    }

    protected function tearDown(): void
    {
        foreach (glob($this->dir.'/*.json') ?: [] as $file) {
            unlink($file);
        }
        rmdir($this->dir);
        parent::tearDown();
    }

    public function test_starts_at_zero_and_increments_views(): void
    {
        $this->assertSame(['views' => 0, 'likes' => 0], $this->store->get('eight-pillars'));

        $after = $this->store->incrementView('eight-pillars');
        $this->assertSame(['views' => 1, 'likes' => 0], $after);
        $this->assertSame(['views' => 1, 'likes' => 0], $this->store->get('eight-pillars'));
    }

    public function test_like_and_unlike(): void
    {
        $liked = $this->store->like('eight-pillars');
        $this->assertSame(1, $liked['likes']);
        $this->assertTrue($liked['liked']);

        $unliked = $this->store->unlike('eight-pillars');
        $this->assertSame(0, $unliked['likes']);
        $this->assertFalse($unliked['liked']);
    }

    public function test_rejects_invalid_slug(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->store->get('../etc/passwd');
    }

    public function test_get_many_and_attach_to_items(): void
    {
        $this->store->incrementView('alpha');
        $this->store->like('alpha');
        $this->store->incrementView('beta');

        $this->assertSame(
            [
                'alpha' => ['views' => 1, 'likes' => 1],
                'beta' => ['views' => 1, 'likes' => 0],
                'gamma' => ['views' => 0, 'likes' => 0],
            ],
            $this->store->getMany(['alpha', 'beta', 'gamma', 'alpha']),
        );

        $items = $this->store->attachToItems([
            ['slug' => 'alpha', 'title' => 'A'],
            ['slug' => 'gamma', 'title' => 'C'],
        ]);

        $this->assertSame(['views' => 1, 'likes' => 1], $items[0]['stats']);
        $this->assertSame(['views' => 0, 'likes' => 0], $items[1]['stats']);
    }

    public function test_set_overwrites_counts(): void
    {
        $this->store->set('seeded', 120, 14);
        $this->assertSame(['views' => 120, 'likes' => 14], $this->store->get('seeded'));

        $this->store->set('seeded', 200, 9);
        $this->assertSame(['views' => 200, 'likes' => 9], $this->store->get('seeded'));
    }
}
