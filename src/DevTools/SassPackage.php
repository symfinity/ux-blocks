<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\DevTools;

final readonly class SassPackage
{
    public function __construct(
        public string $composerName,
        public string $packageDirectory,
        public string $stylesSrcDirectory,
        public string $stylesDirectory,
    ) {
    }
}
