<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * Orthogonal surface substrate axis (symfinity 109).
 *
 * Separate from the composition-language modifier lexicon ({@see CompositionLanguage}).
 */
final class SurfaceSubstrate
{
    /** @var list<string> */
    public const VALUES = ['solid', 'glass'];

    /**
     * Roles that may emit {@code data-ui-surface} in v1 (audit allowlist).
     *
     * @var list<string>
     */
    public const ALLOWLISTED_ROLES = [
        'modal',
        'popover',
        'menu',
        'navbar',
        'card',
        'hero',
        'content-section',
        'flash',
        'alert',
        'drawer-content',
        'data-table-chrome-toolbar',
    ];

    public static function normalize(string $surface, string $default = 'solid'): string
    {
        return \in_array($surface, self::VALUES, true) ? $surface : $default;
    }

    public static function isAllowlistedRole(string $role): bool
    {
        return \in_array($role, self::ALLOWLISTED_ROLES, true);
    }
}
