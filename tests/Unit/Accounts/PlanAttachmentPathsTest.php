<?php

namespace Tests\Unit\Accounts;

use App\Accounts\AccountsConfig;
use Tests\TestCase;

class PlanAttachmentPathsTest extends TestCase
{
    public function test_plan_attachments_directory_is_nested_under_plans(): void
    {
        $config = new AccountsConfig;
        $dir = $config->planAttachmentsDirectory('plan_20260719_abc123');

        $this->assertStringContainsString('plans', $dir);
        $this->assertStringEndsWith('plan_20260719_abc123'.DIRECTORY_SEPARATOR.'attachments', $dir);
    }

    public function test_plan_attachments_directory_sanitizes_id(): void
    {
        $config = new AccountsConfig;
        $dir = $config->planAttachmentsDirectory('plan_../evil');

        $this->assertStringNotContainsString('..', basename(dirname($dir)));
        $this->assertStringEndsWith('attachments', $dir);
    }
}
