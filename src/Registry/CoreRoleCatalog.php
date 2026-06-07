<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * Canonical atom role ids for symfinity/ux-blocks-core (symfinity 054).
 *
 * @return list<string>
 */
final class CoreRoleCatalog
{
    /** @return list<string> */
    public static function roles(): array
    {
        return [
            'typography',
            'button',
            'label',
            'input',
            'textarea',
            'checkbox',
            'select',
            'switch',
            'file-input',
            'separator',
            'divider',
            'aspect-ratio',
            'scroll-area',
            'badge',
            'avatar',
            'kbd',
            'link',
            'progress',
            'spinner',
            'skeleton',
            'empty',
            'image',
        ];
    }
}
