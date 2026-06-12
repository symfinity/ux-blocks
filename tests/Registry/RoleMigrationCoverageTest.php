<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Registry;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfinity\UxBlocks\Registry\CoreRoleCatalog;
use Symfinity\UxBlocks\Registry\ExtendedRoleCatalog;
use Symfinity\UxBlocks\Registry\LiveRoleCatalog;

/**
 * symfinity 054 — each post-cutover role belongs to exactly one tier catalog.
 */
final class RoleMigrationCoverageTest extends TestCase
{
    #[Test]
    public function tierCatalogsDoNotOverlap(): void
    {
        $core = CoreRoleCatalog::roles();
        $extended = ExtendedRoleCatalog::roles();
        $live = LiveRoleCatalog::roles();

        self::assertSame([], array_intersect($core, $extended), 'core ∩ extended must be empty');
        self::assertSame([], array_intersect($core, $live), 'core ∩ live must be empty');
        self::assertSame([], array_intersect($extended, $live), 'extended ∩ live must be empty');
    }

    #[Test]
    public function tierCatalogsHaveExpectedCounts(): void
    {
        self::assertCount(24, CoreRoleCatalog::roles());
        self::assertCount(27, ExtendedRoleCatalog::roles());
        self::assertCount(30, LiveRoleCatalog::roles());
    }

    #[Test]
    public function unionCoversFullCatalogWithoutDuplicates(): void
    {
        $union = array_merge(
            CoreRoleCatalog::roles(),
            ExtendedRoleCatalog::roles(),
            LiveRoleCatalog::roles(),
        );

        self::assertCount(81, $union);
        self::assertSame($union, array_values(array_unique($union)));
    }
}
