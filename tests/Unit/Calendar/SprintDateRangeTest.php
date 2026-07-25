<?php

namespace Tests\Unit\Calendar;

use App\Calendar\SprintDateRange;
use Tests\TestCase;

final class SprintDateRangeTest extends TestCase
{
    public function test_resolves_week_range_from_started_at(): void
    {
        $range = SprintDateRange::compute('2026-06-29', 1);
        $this->assertNotNull($range);
        $this->assertSame('2026-06-29', $range['start']->toDateString());
        $this->assertSame('2026-07-05', $range['end']->toDateString());

        $week2 = SprintDateRange::compute('2026-06-29', 2);
        $this->assertNotNull($week2);
        $this->assertSame('2026-07-06', $week2['start']->toDateString());
        $this->assertSame('2026-07-12', $week2['end']->toDateString());
    }

    public function test_returns_null_for_invalid_input(): void
    {
        $this->assertNull(SprintDateRange::compute(null, 1));
        $this->assertNull(SprintDateRange::compute('2026-06-29', 0));
        $this->assertNull(SprintDateRange::compute('not-a-date', 1));
    }
}
