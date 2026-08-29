<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\DevTools;

/**
 * Programmatic SC-003 gate for blocks-css --check and qa:lite hooks.
 */
final class CssSelectorCoverageGate
{
    public function __construct(
        private readonly CssSelectorCoverageReporter $reporter = new CssSelectorCoverageReporter(),
    ) {
    }

    /**
     * @return list<string>
     */
    public function findFailures(string $packagesRoot): array
    {
        $failures = [];

        foreach (glob(rtrim($packagesRoot, '/\\') . '/ux-blocks-*/', GLOB_ONLYDIR) ?: [] as $packageDir) {
            if (!is_dir($packageDir . '/assets/scss')) {
                continue;
            }

            $report = $this->reporter->reportForPackage($packageDir);
            if ($report['audit'] === 0) {
                continue;
            }

            if ($report['ratio'] >= CssSelectorCoverageReporter::THRESHOLD) {
                continue;
            }

            $failures[] = sprintf(
                '%s selector coverage %.1f%% (%d/%d) below 80%% threshold; missing: %s',
                basename(rtrim($packageDir, '/')),
                $report['ratio'] * 100,
                $report['asserted'],
                $report['audit'],
                implode(', ', array_slice($report['missing'], 0, 12)),
            );
        }

        return $failures;
    }
}
