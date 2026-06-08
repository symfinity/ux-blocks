<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * Canonical role ids for symfinity/ux-blocks-interactive (symfinity 057).
 *
 * @return list<string>
 */
final class InteractiveRoleCatalog
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
            'slider',
            'toggle',
            'toggle-group',
            'calendar',
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
            'carousel-interactive',
            'rating',
            'filter-chips',
            'table-of-contents',
            'tree-view',
        ];
    }
}
