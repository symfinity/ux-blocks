<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\DevTools;

/**
 * Product-only stale checks beyond generic {@see \Symfinity\MonoSass\Sass\SassCompiler} freshness.
 */
final class BlocksCssProductFreshnessChecker
{
    public function __construct(
        private readonly BlocksCssAuditBundleCompiler $auditBundleCompiler = new BlocksCssAuditBundleCompiler(),
        private readonly BlocksCssGeneratedHeaderChecker $generatedHeaderChecker = new BlocksCssGeneratedHeaderChecker(),
        private readonly CssSelectorCoverageReporter $coverageReporter = new CssSelectorCoverageReporter(),
    ) {
    }

    /**
     * @return list<string>
     */
    public function findStale(SassPackage $package): array
    {
        $stale = [];

        if ($this->auditBundleCompiler->isStale($package)) {
            $stale[] = $package->composerName . ': ' . $this->auditBundleCompiler->stalePath($package);
        }

        foreach ($this->generatedHeaderChecker->findMissingHeaders($package) as $path) {
            $stale[] = $package->composerName . ': missing generated header on ' . $path;
        }

        $coverage = $this->coverageReporter->reportForPackage($package->packageDirectory);
        if ($coverage['audit'] > 0 && $coverage['ratio'] < CssSelectorCoverageReporter::THRESHOLD) {
            $stale[] = sprintf(
                '%s: selector coverage %.1f%% (%d/%d) below 80%% threshold',
                $package->composerName,
                $coverage['ratio'] * 100,
                $coverage['asserted'],
                $coverage['audit'],
            );
        }

        return $stale;
    }
}
