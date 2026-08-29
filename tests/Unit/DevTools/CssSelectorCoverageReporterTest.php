<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Unit\DevTools;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfinity\UxBlocks\DevTools\CssSelectorCoverageReporter;

final class CssSelectorCoverageReporterTest extends TestCase
{
    #[Test]
    public function extractAssertedRoleKeysFromForeachArrays(): void
    {
        $source = <<<'PHP'
            foreach (['hero', 'cta-band'] as $role) {
                self::assertStringContainsString('[data-ui-role="' . $role . '"]', $css, $role);
            }
            PHP;

        self::assertEqualsCanonicalizing(
            ['hero', 'cta-band'],
            (new CssSelectorCoverageReporter())->extractAssertedRoleKeysFromTestSource($source),
        );
    }

    #[Test]
    public function extractAssertedRoleKeysFromLiteralsAndForeach(): void
    {
        $source = <<<'PHP'
            self::assertStringContainsString('[data-ui-role="flash"]', $css);
            foreach (['breadcrumb', 'navbar'] as $role) {
                self::assertStringContainsString('[data-ui-role="' . $role . '"]', $css, $role);
            }
            foreach (['dialog'] as $role) {
                self::assertDoesNotMatchRegularExpression('/^\[data-ui-role="' . preg_quote($role, '/') . '"\]/m', $css);
            }
            PHP;

        self::assertEqualsCanonicalizing(
            ['flash', 'breadcrumb', 'navbar'],
            (new CssSelectorCoverageReporter())->extractAssertedRoleKeysFromTestSource($source),
        );
    }

    #[Test]
    public function inventoryHeredocCountsTowardAssertedKeys(): void
    {
        $packageDir = sys_get_temp_dir() . '/ux-blocks-coverage-' . uniqid('', true);
        mkdir($packageDir . '/assets/styles/roles', 0775, true);
        mkdir($packageDir . '/tests/Unit/Css', 0775, true);

        file_put_contents(
            $packageDir . '/assets/styles/roles/_bundle.css',
            '[data-ui-role="hero"] { display: block; }',
        );

        file_put_contents(
            $packageDir . '/tests/Unit/Css/RoleSelectorInventoryTest.php',
            <<<'PHP'
                private const SELECTOR_INVENTORY = <<<'SELECTORS'
                [data-ui-role="hero"]
                SELECTORS;
                PHP,
        );

        $report = (new CssSelectorCoverageReporter())->reportForPackage($packageDir);

        self::assertSame(1, $report['audit']);
        self::assertSame(1, $report['asserted']);
        self::assertEqualsWithDelta(1.0, $report['ratio'], PHP_FLOAT_EPSILON);
    }
}
