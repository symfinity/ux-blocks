<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Test;

use PHPUnit\Framework\Assert;

/**
 * Shared DOM assertions for UX Blocks Twig component tests (used by ux-blocks-core).
 */
trait ChameleonMarkupAssertions
{
    protected function assertChameleonRoot(string $html, string $role, string $fragment): void
    {
        Assert::assertStringContainsString(sprintf('data-ui-role="%s"', $role), $html);
        Assert::assertStringContainsString(sprintf('data-ui-fragment="%s"', $fragment), $html);
        Assert::assertDoesNotMatchRegularExpression('/html_cva|tailwind_merge|twig-tailwind-extra/', $html);
    }
}
