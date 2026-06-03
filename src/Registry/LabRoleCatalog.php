<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * Canonical role ids for symfinity/ux-blocks-lab (symfinity 028).
 *
 * @return list<string>
 */
final class LabRoleCatalog
{
    /** @return list<string> */
    public static function roles(): array
    {
        return [
            'food-vote',
            'inline-edit-food',
            'product-grid-load-more',
            'invoice-creator',
            'todo-list-form',
            'registration-form-demo',
            'upload-files-demo',
            'dino-chart',
            'kanban-board',
            'turbo-mercure-spa',
            'terminal-swapper',
            'daisy-mockups',
            'fab-dock-chat',
            'author-github-badges',
        ];
    }
}
