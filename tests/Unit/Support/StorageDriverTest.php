<?php

namespace Tests\Unit\Support;

use App\Support\StorageDriver;
use InvalidArgumentException;
use Tests\TestCase;

final class StorageDriverTest extends TestCase
{
    public function test_default_is_file(): void
    {
        config(['storage.driver' => 'file']);
        $this->assertTrue(StorageDriver::isFile());
        $this->assertFalse(StorageDriver::isMysql());
    }

    public function test_invalid_driver_throws(): void
    {
        config(['storage.driver' => 'redis']);
        $this->expectException(InvalidArgumentException::class);
        StorageDriver::current();
    }
}
