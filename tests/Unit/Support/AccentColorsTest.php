<?php

namespace Tests\Unit\Support;

use App\Support\AccentColors;
use App\Support\AvatarIcons;
use PHPUnit\Framework\TestCase;

class AccentColorsTest extends TestCase
{
    public function test_next_unused_prefers_first_free_token(): void
    {
        $this->assertSame('accent-1', AccentColors::nextUnused([]));
        $this->assertSame('accent-3', AccentColors::nextUnused(['accent-1', 'accent-2']));
        $this->assertSame(
            'accent-1',
            AccentColors::nextUnused(AccentColors::TEAM_TOKENS, 6),
        );
    }

    public function test_hex_outline_and_chip_style(): void
    {
        $this->assertSame('#0d9488', AccentColors::hex('accent-2'));
        $this->assertTrue(AccentColors::isOutline('outline-1'));
        $this->assertFalse(AccentColors::isOutline('accent-1'));
        $this->assertStringContainsString('background-color:#fff', AccentColors::chipStyle('outline-2'));
        $this->assertSame('accent-1', AccentColors::normalize('nope'));
        $this->assertSame('accent-12', AccentColors::normalize('accent-12'));
    }

    public function test_avatar_icons_normalize(): void
    {
        $this->assertSame('user-tie', AvatarIcons::normalize('fa-solid fa-user-tie'));
        $this->assertSame('', AvatarIcons::normalize('nope'));
        $this->assertSame('fa-solid fa-rocket', AvatarIcons::cssClass('rocket'));
        $this->assertSame('icons/avatar/rocket.svg', AvatarIcons::svgPublicPath('rocket'));
        $markup = AvatarIcons::svgMarkup('rocket');
        $this->assertStringContainsString('<svg', $markup);
        $this->assertStringContainsString('sp-avatar-icon-svg', $markup);
        $this->assertStringContainsString('currentColor', $markup);
    }
}
