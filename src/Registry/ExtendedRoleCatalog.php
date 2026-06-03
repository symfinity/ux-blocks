<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * Canonical role ids for symfinity/ux-blocks-extended (symfinity 025).
 *
 * @return list<string>
 */
final class ExtendedRoleCatalog
{
    /** @return list<string> */
    public static function roles(): array
    {
        return [
            'tabs',
            'alert-dialog-enhanced',
            'drawer',
            'sheet',
            'dropdown-menu',
            'combobox',
            'slider',
            'toggle',
            'toggle-group',
            'calendar',
            'date-picker',
            'input-otp',
            'sidebar',
            'stacked-layout-interactive',
            'command-palette',
            'toast',
            'context-menu',
            'hover-card',
            'resizable',
            'menubar',
            'navigation-menu',
            'data-table-chrome',
            'carousel-interactive',
            'rating',
            'filter-chips',
        ];
    }
}
