<?php

namespace Tests\Unit\Calendar;

use App\Calendar\CalendarUrlFetchGuard;
use InvalidArgumentException;
use Tests\TestCase;

final class CalendarUrlFetchGuardTest extends TestCase
{
    public function test_rejects_private_ip_literal(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('private or reserved IP');

        app(CalendarUrlFetchGuard::class)->validateUrl('http://127.0.0.1/holidays.ics');
    }

    public function test_dns_failure_message_is_explicit(): void
    {
        try {
            app(CalendarUrlFetchGuard::class)->validateUrl(
                'https://no-such-host-binom-calendar-test.invalid/holidays.ics',
            );
            $this->fail('Expected InvalidArgumentException for DNS failure');
        } catch (InvalidArgumentException $e) {
            $this->assertStringContainsString('DNS resolution failed', $e->getMessage());
            $this->assertStringNotContainsString('private or reserved IP', $e->getMessage());
        }
    }
}
