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
            'field',
            'fieldset',
            'radio-group',
            'input-group',
            'button-group',
            'card',
            'table',
            'alert',
            'grid',
            'stack',
            'list',
            'description-list',
            'stat',
            'timeline',
            'accordion',
            'carousel',
            'dialog',
            'popover',
            'tooltip',
            'navbar',
            'breadcrumb',
            'steps',
            'pagination',
            'page-heading',
            'section-heading',
            'auth-layout',
            'dashboard-shell',
            'data-table-chrome',
        ];
    }
}
