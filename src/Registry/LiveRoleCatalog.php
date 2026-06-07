<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * Canonical role ids for symfinity/ux-blocks-live (symfinity 054).
 *
 * @return list<string>
 */
final class LiveRoleCatalog
{
    /** @return list<string> */
    public static function roles(): array
    {
        return [
            'collapsible',
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
            'date-range-picker',
            'input-otp',
            'tags-input',
            'sidebar',
            'stacked-layout-interactive',
            'command-palette',
            'toast',
            'context-menu',
            'hover-card',
            'resizable',
            'menubar',
            'navigation-menu',
            'data-table-chrome-interactive',
            'carousel-interactive',
            'rating',
            'filter-chips',
            'table-of-contents',
            'tree-view',
        ];
    }
}
