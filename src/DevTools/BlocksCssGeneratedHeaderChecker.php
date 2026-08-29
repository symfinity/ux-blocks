<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\DevTools;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Ensures Sass-generated CSS carries the compile marker (114 T021).
 */
final class BlocksCssGeneratedHeaderChecker
{
    /**
     * @return list<string> relative paths under assets/styles/
     */
    public function findMissingHeaders(SassPackage $package): array
    {
        $missing = [];
        $header = BlocksCssAuditBundleCompiler::GENERATED_HEADER;
        $rolesDir = $package->stylesDirectory . '/roles';

        if (!is_dir($rolesDir)) {
            return [];
        }

        /** @var SplFileInfo $file */
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rolesDir)) as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'css') {
                continue;
            }

            $basename = $file->getBasename('.css');
            if (!$this->requiresGeneratedHeader($package, $basename)) {
                continue;
            }

            $css = (string) file_get_contents($file->getPathname());
            if (!str_contains($css, $header)) {
                $relative = 'assets/styles/roles/' . $file->getBasename();
                $missing[] = $relative;
            }
        }

        return $missing;
    }

    private function requiresGeneratedHeader(SassPackage $package, string $roleBasename): bool
    {
        if ($roleBasename === '_bundle') {
            return is_file($package->stylesSrcDirectory . '/roles/_bundle.scss');
        }

        $scssRole = $package->stylesSrcDirectory . '/roles/' . $roleBasename . '.scss';

        return is_file($scssRole);
    }
}
