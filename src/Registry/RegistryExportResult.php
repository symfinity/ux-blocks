<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

final readonly class RegistryExportResult
{
    public function __construct(
        public string $package,
        public string $markdown,
        public int $roleCount,
        public bool $drift,
    ) {
    }
}
