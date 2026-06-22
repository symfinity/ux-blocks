<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\DevTools;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final class BlocksCssBundleGenerator
{
    public function generate(SassPackage $package): ?string
    {
        $rolesDirectory = $package->stylesSrcDirectory . '/roles';

        if (!is_dir($rolesDirectory)) {
            return null;
        }

        $imports = [];

        /** @var SplFileInfo $file */
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rolesDirectory)) as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'scss') {
                continue;
            }

            $basename = $file->getBasename('.scss');

            if (str_starts_with($basename, '_')) {
                continue;
            }

            $relative = 'roles/' . $basename;
            $imports[] = $relative;
        }

        if ($imports === []) {
            return null;
        }

        sort($imports);

        $lines = [
            '// generated: blocks-css:compile — do not edit',
            '',
        ];

        foreach ($imports as $import) {
            $lines[] = '@use \'' . $import . '\';';
        }

        $bundlePath = $package->stylesSrcDirectory . '/bundle.scss';
        $content = implode("\n", $lines) . "\n";

        if (is_file($bundlePath) && hash('sha256', (string) file_get_contents($bundlePath)) === hash('sha256', $content)) {
            return null;
        }

        if (!is_dir(dirname($bundlePath))) {
            mkdir(dirname($bundlePath), 0775, true);
        }

        file_put_contents($bundlePath, $content);

        return $bundlePath;
    }
}
