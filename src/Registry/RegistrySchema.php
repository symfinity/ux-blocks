<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

final class RegistrySchema
{
    public const VERSION = '1.4';

    public const DEFAULT_PREFIX = 'blocks';

    public static function fragmentId(string $role, string $prefix = self::DEFAULT_PREFIX): string
    {
        return $prefix . '.' . $role;
    }

    public static function fragmentPattern(string $role, string $prefix = self::DEFAULT_PREFIX): string
    {
        return $prefix . '.' . $role . '.{n}';
    }
}
