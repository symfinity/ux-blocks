<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Unit\Guard;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Component handbooks must ship in the Composer archive and show real child markup.
 */
final class ComponentDocsShipTest extends TestCase
{
    #[Test]
    public function tierArchivesKeepTheDocsDirectory(): void
    {
        $packages = dirname(__DIR__, 4);
        $offenders = [];

        foreach (['ux-blocks-core', 'ux-blocks-extended', 'ux-blocks-form'] as $slug) {
            $attributes = $packages . '/' . $slug . '/.gitattributes';
            self::assertFileExists($attributes);
            foreach (file($attributes, FILE_IGNORE_NEW_LINES) ?: [] as $line) {
                if (str_contains($line, '/docs export-ignore')) {
                    $offenders[] = $slug . ': ' . trim($line);
                }
            }
        }

        self::assertSame([], $offenders, "docs/ must ship in the package archive:\n" . implode("\n", $offenders));
    }

    #[Test]
    public function childMarkupDocsHaveARealPropTableAndANestedExample(): void
    {
        $packages = dirname(__DIR__, 4);
        $docs = [
            'ux-blocks-core/docs/components/pagination.md',
            'ux-blocks-core/docs/components/grid.md',
            'ux-blocks-extended/docs/components/card.md',
            'ux-blocks-extended/docs/components/empty.md',
        ];
        $offenders = [];

        foreach ($docs as $relative) {
            $path = $packages . '/' . $relative;
            self::assertFileExists($path);
            $markdown = (string) file_get_contents($path);
            if (str_contains($markdown, '| — | — | — |')) {
                $offenders[] = $relative . ' still has a placeholder prop row';
            }
            if (!preg_match('/<twig:[^>\n]+>/', $markdown) || (!str_contains($markdown, '<twig:') || (substr_count($markdown, '<twig:') < 2 && !str_contains($markdown, '{% block')))) {
                $hasNestedChild = (bool) preg_match('/<twig:[^>]+>[^<]*<twig:/s', $markdown) || str_contains($markdown, '{% block');
                if (!$hasNestedChild) {
                    $offenders[] = $relative . ' needs a nested Twig child or a block slot';
                }
            }
        }

        self::assertSame([], $offenders, implode("\n", $offenders));
    }
}
