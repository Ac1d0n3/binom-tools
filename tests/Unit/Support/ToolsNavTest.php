<?php

namespace Tests\Unit\Support;

use App\Support\ToolsNav;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ToolsNavTest extends TestCase
{
    #[DataProvider('dbtBadgeCases')]
    public function test_shows_dbt_badge_only_for_dbt_tools(array $item, bool $expected): void
    {
        $this->assertSame($expected, ToolsNav::showsDbtBadge($item));
    }

    public function test_enabled_env_key_and_default_enabled(): void
    {
        $this->assertSame('TOOL_PROMPT_STUDIO_ENABLED', ToolsNav::enabledEnvKey('prompt-studio'));
    }

    /**
     * @return array<string, array{0: array<string, mixed>, 1: bool}>
     */
    public static function dbtBadgeCases(): array
    {
        return [
            'explicit dbt true' => [['dbt' => true], true],
            'explicit dbt false' => [['dbt' => false, 'workflow' => 'dbt-pii-governance'], false],
            'dbt workflow' => [['workflow' => 'dbt-pii-governance'], true],
            'dbt dq workflow' => [['workflow' => 'dbt-dq-governance'], true],
            'ai workflow without dbt' => [['workflow' => 'ai-prompt-workflow'], false],
            'no workflow no flag' => [['id' => 'prompt-studio'], false],
        ];
    }
}
