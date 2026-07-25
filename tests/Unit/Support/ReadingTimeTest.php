<?php

namespace Tests\Unit\Support;

use App\Support\ReadingTime;
use PHPUnit\Framework\TestCase;

class ReadingTimeTest extends TestCase
{
    public function test_formats_minutes_below_one_hour(): void
    {
        $this->assertSame('0 min', ReadingTime::format(0, 'en'));
        $this->assertSame('1 min', ReadingTime::format(1, 'de'));
        $this->assertSame('59 min', ReadingTime::format(59, 'en'));
    }

    public function test_formats_whole_hours(): void
    {
        $this->assertSame('1 h', ReadingTime::format(60, 'en'));
        $this->assertSame('1 Std', ReadingTime::format(60, 'de'));
        $this->assertSame('2 h', ReadingTime::format(120, 'en'));
        $this->assertSame('2 Std', ReadingTime::format(120, 'de'));
    }

    public function test_formats_hours_and_minutes(): void
    {
        $this->assertSame('1 h 5 min', ReadingTime::format(65, 'en'));
        $this->assertSame('1 Std 5 min', ReadingTime::format(65, 'de'));
        $this->assertSame('2 h 22 min', ReadingTime::format(142, 'en'));
        $this->assertSame('2 Std 22 min', ReadingTime::format(142, 'de'));
    }

    public function test_helper_matches_class(): void
    {
        $this->assertSame(ReadingTime::format(90, 'de'), format_reading_time(90, 'de'));
        $this->assertSame(ReadingTime::format(90, 'en'), format_reading_time(90, 'en'));
    }
}
