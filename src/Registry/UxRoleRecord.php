<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

final readonly class UxRoleRecord
{
    public function __construct(
        public string $role,
        public string $twigComponent,
        public string $fragmentId,
        public string $interaction,
        public string $status,
    ) {
    }
}
