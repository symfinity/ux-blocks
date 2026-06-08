<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * Canonical LiveComponent role ids for symfinity/ux-blocks-live (symfinity 057).
 *
 * @return list<string>
 */
final class LiveRoleCatalog
{
    /** @return list<string> */
    public static function roles(): array
    {
        return [
            'combobox',
            'date-picker',
            'date-range-picker',
            'tags-input',
            'data-table-chrome-interactive',
        ];
    }
}
