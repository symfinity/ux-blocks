<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Unit\DevTools;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfinity\UxBlocks\DevTools\CssSelectorCoverageReporter;

final class CssSelectorCoverageGateTest extends TestCase
{
    /**
     * @return iterable<string, array{0: string}>
     */
    public static function tierPackageDirectories(): iterable
    {
        $packagesRoot = dirname(__DIR__, 4);

        foreach (glob($packagesRoot . '/ux-blocks-*/', GLOB_ONLYDIR) ?: [] as $packageDir) {
            if (!is_dir($packageDir . '/assets/scss')) {
                continue;
            }

            yield basename(rtrim($packageDir, '/')) => [$packageDir];
        }
    }

    #[Test]
    #[DataProvider('tierPackageDirectories')]
    public function tierMeetsSelectorCoverageThreshold(string $packageDirectory): void
    {
        $report = (new CssSelectorCoverageReporter())->reportForPackage($packageDirectory);

        if (0 === $report['audit']) {
            self::markTestSkipped(
                basename(rtrim($packageDirectory, '/')) . ' has no role CSS selectors to audit yet',
            );
        }

        self::assertGreaterThanOrEqual(
            CssSelectorCoverageReporter::THRESHOLD,
            $report['ratio'],
            sprintf(
                '%s selector coverage %.1f%% (%d/%d) below 80%% threshold; missing: %s',
                basename(rtrim($packageDirectory, '/')),
                $report['ratio'] * 100,
                $report['asserted'],
                $report['audit'],
                implode(', ', array_slice($report['missing'], 0, 12)),
            ),
        );
    }
}
