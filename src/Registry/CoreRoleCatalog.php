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
            'image',
            'figure',
            'flash',
            'flash-stack',
            'page-heading',
            'section-heading',
            'grid',
            'stack',
            'list',
            'breadcrumb',
            'pagination',
            'search-form',
            'button-group',
        ];
    }
}
