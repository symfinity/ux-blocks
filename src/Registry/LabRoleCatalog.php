<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * Canonical role ids for symfinity/ux-blocks-lab (symfinity 028 + 052 R2).
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
            'timeline-8star',
            'heatmap',
            'flip-clock',
            'marquee',
            'partition-bar',
            'status-indicator',
            'system-banner',
            'transport-badge',
            'json-viewer',
            'scroll-fade',
            'game-snake',
            'game-2048',
            'game-minesweeper',
            'flight-route-map',
            'streak-counter',
            'achievement-badge',
            'leaderboard',
            'onboarding-tour',
            'wave-player',
            'video-embed',
            'slide-deck',
            'lms-quiz',
            'flashcard-deck',
            'matching-pairs',
            'echo-text',
            'magnetic-card',
            'scroll-stack',
            'card-swap',
            'fade-content',
            'error-fallback-banner',
            'crash-recovery-modal',
        ];
    }
}