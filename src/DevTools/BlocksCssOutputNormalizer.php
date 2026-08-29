<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\DevTools;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * dart-sass emits unquoted attribute values; ux-blocks Css PHPUnit expects quoted selectors.
 */
final class BlocksCssOutputNormalizer
{
    /** @var list<string> */
    private const QUOTED_ATTRIBUTES = [
        'data-ui-role',
        'data-ui-variant',
        'data-ui-appearance',
        'data-ui-part',
        'data-ui-size',
        'data-ui-layout',
        'data-ui-state',
        'data-ui-placement',
        'data-ui-density',
        'data-ui-align',
        'data-ui-ratio',
        'data-ui-defer',
        'data-ui-direction',
        'data-ui-gap',
        'data-ui-divider',
        'data-ui-surface',
        'aria-current',
    ];

    public function normalizePackage(SassPackage $package): void
    {
        $stylesRoot = rtrim($package->stylesDirectory, '/\\');

        if (!is_dir($stylesRoot)) {
            return;
        }

        /** @var SplFileInfo $file */
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($stylesRoot)) as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'css') {
                continue;
            }

            $original = (string) file_get_contents($file->getPathname());
            $normalized = $this->normalizeCss($original);

            if ($normalized !== $original) {
                file_put_contents($file->getPathname(), $normalized);
            }
        }
    }

    public function normalizeCss(string $css): string
    {
        foreach (self::QUOTED_ATTRIBUTES as $attribute) {
            $css = (string) preg_replace(
                '/\[' . preg_quote($attribute, '/') . '=([a-zA-Z0-9_-]+)\]/',
                '[' . $attribute . '="$1"]',
                $css,
            );
        }

        return $css;
    }
}
