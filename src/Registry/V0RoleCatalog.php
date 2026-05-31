<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * Canonical v0 role ids for symfinity/ux-blocks-core (symfinity 003).
 *
 * @return list<string>
 */
final class V0RoleCatalog
{
    /** @return list<string> */
    public static function roles(): array
    {
        return [
            'button',
            'separator',
            'typography',
            'card',
            'empty',
            'table',
            'alert',
            'label',
            'input',
            'textarea',
            'select',
            'field',
            'checkbox',
            'radio-group',
        ];
    }
}
