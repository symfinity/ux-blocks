<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * Canonical role ids for symfinity/ux-blocks-core (symfinity 003).
 *
 * Future sibling catalogs: FullRoleCatalog, LabRoleCatalog, …
 *
 * @return list<string>
 */
final class CoreRoleCatalog
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
