<?php

namespace Tests\Unit\Tools;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Guards the Set Analysis / Tableau / Power BI shared workbench layout.
 * Desktop must stay 3 columns side-by-side; column stacking only in @media.
 */
class BiFormulaWorkbenchCssContractTest extends TestCase
{
    private function cssPath(): string
    {
        return resource_path('css/tools/bi-formula-workbench.css');
    }

    private function stripAtMediaBlocks(string $css): string
    {
        // Contract applies to desktop rules; this stylesheet keeps @media blocks at the end.
        $parts = preg_split('/@media\b/', $css, 2);

        return is_array($parts) ? (string) $parts[0] : $css;
    }

    private function ruleBody(string $css, string $selector): ?string
    {
        $pattern = '/'.preg_quote($selector, '/').'\s*\{([^{}]*)\}/s';
        if (! preg_match($pattern, $css, $matches)) {
            return null;
        }

        return $matches[1];
    }

    #[Test]
    public function desktop_workbench_stays_horizontal_three_columns(): void
    {
        $path = $this->cssPath();
        $this->assertFileExists($path);

        $css = (string) file_get_contents($path);
        $desktop = $this->stripAtMediaBlocks($css);

        $workbench = $this->ruleBody($desktop, '.qlik-set-workbench');
        $this->assertNotNull($workbench, 'Missing .qlik-set-workbench rule outside @media');
        $this->assertMatchesRegularExpression('/display\s*:\s*flex/i', $workbench);
        $this->assertMatchesRegularExpression('/flex-wrap\s*:\s*nowrap/i', $workbench);
        $this->assertDoesNotMatchRegularExpression('/flex-direction\s*:\s*column/i', $workbench);

        // Solo column rules (not the shared ".rail, .composer, .filter-builder" block).
        $this->assertMatchesRegularExpression(
            '/(?:^|[\n;}])\s*\.qlik-set-rail\s*\{\s*flex\s*:/s',
            $desktop,
            '.qlik-set-rail must declare flex basis for the 3-column layout',
        );
        $this->assertMatchesRegularExpression(
            '/(?:^|[\n;}])\s*\.qlik-set-composer\s*\{\s*flex\s*:/s',
            $desktop,
            '.qlik-set-composer must declare flex basis for the 3-column layout',
        );
        $this->assertMatchesRegularExpression(
            '/(?:^|[\n;}])\s*\.qlik-set-filter-builder\s*\{\s*flex\s*:/s',
            $desktop,
            '.qlik-set-filter-builder must declare flex basis for the 3-column layout',
        );
    }

    #[Test]
    public function set_analysis_blade_keeps_three_workbench_columns(): void
    {
        $path = resource_path('views/tools/qlik-set-analysis-generator/show.blade.php');
        $this->assertFileExists($path);

        $blade = (string) file_get_contents($path);
        $this->assertStringContainsString('data-qlik-column="catalog"', $blade);
        $this->assertStringContainsString('data-qlik-column="composer"', $blade);
        $this->assertStringContainsString('data-qlik-column="builder"', $blade);
        $this->assertStringContainsString('class="qlik-set-workbench"', $blade);
    }
}
