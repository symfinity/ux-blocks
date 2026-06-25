<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * Canonical compound role ids for symfinity/ux-blocks-extended (symfinity 054).
 *
 * @return list<string>
 */
final class ExtendedRoleCatalog
{
    /** @return list<string> */
    public static function roles(): array
    {
        return [
            'card',
            'table',
            'alert',
            'description-list',
            'stat',
            'timeline',
            'accordion',
            'carousel',
            'dialog',
            'popover',
            'tooltip',
            'navbar',
            'steps',
            'auth-layout',
            'dashboard-shell',
            'data-table-chrome',
            'empty',
            'bento-box-panel',
            'app-shell',
            'page-header',
            'search-form',
        ];
    }
}
