<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Unit\Registry;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfinity\UxBlocks\Registry\SurfaceSubstrate;

final class SurfaceSubstrateTest extends TestCase
{
    #[Test]
    public function normalizeCoercesUnknownValuesToSolid(): void
    {
        self::assertSame('solid', SurfaceSubstrate::normalize('neumorphic'));
        self::assertSame('glass', SurfaceSubstrate::normalize('glass'));
    }

    #[Test]
    public function allowlistCoversV1FloatingRoles(): void
    {
        foreach (['modal', 'popover', 'menu', 'navbar', 'card', 'hero', 'content-section', 'flash', 'alert', 'drawer-content', 'data-table-chrome-toolbar'] as $role) {
            self::assertTrue(SurfaceSubstrate::isAllowlistedRole($role), $role);
        }

        self::assertFalse(SurfaceSubstrate::isAllowlistedRole('button'));
    }
}
