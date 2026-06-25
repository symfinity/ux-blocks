<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Registry;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfinity\UxBlocks\Registry\CoreRoleCatalog;
use Symfinity\UxBlocks\Registry\EcommerceRoleCatalog;
use Symfinity\UxBlocks\Registry\ExtendedRoleCatalog;
use Symfinity\UxBlocks\Registry\FormRoleCatalog;
use Symfinity\UxBlocks\Registry\InteractiveRoleCatalog;
use Symfinity\UxBlocks\Registry\LabRoleCatalog;
use Symfinity\UxBlocks\Registry\LiveRoleCatalog;
use Symfinity\UxBlocks\Registry\MarketingRoleCatalog;

/**
 * Each role id belongs to exactly one tier catalog across the UX Blocks family.
 */
final class RoleMigrationCoverageTest extends TestCase
{
    /** @return array<string, list<string>> */
    private static function tierCatalogs(): array
    {
        return [
            'core' => CoreRoleCatalog::roles(),
            'form' => FormRoleCatalog::roles(),
            'extended' => ExtendedRoleCatalog::roles(),
            'interactive' => InteractiveRoleCatalog::roles(),
            'live' => LiveRoleCatalog::roles(),
            'marketing' => MarketingRoleCatalog::roles(),
            'ecommerce' => EcommerceRoleCatalog::roles(),
            'lab' => LabRoleCatalog::roles(),
        ];
    }

    #[Test]
    public function tierCatalogsDoNotOverlap(): void
    {
        $catalogs = self::tierCatalogs();
        $names = array_keys($catalogs);

        foreach ($names as $i => $left) {
            for ($j = $i + 1, $c = count($names); $j < $c; ++$j) {
                $right = $names[$j];
                self::assertSame(
                    [],
                    array_intersect($catalogs[$left], $catalogs[$right]),
                    sprintf('%s ∩ %s must be empty', $left, $right),
                );
            }
        }
    }

    #[Test]
    public function tierCatalogsHaveExpectedCounts(): void
    {
        $expected = [
            'core' => 25,
            'form' => 17,
            'extended' => 21,
            'interactive' => 27,
            'live' => 5,
            'marketing' => 22,
            'ecommerce' => 10,
            'lab' => 45,
        ];

        $catalogs = self::tierCatalogs();

        foreach ($expected as $tier => $count) {
            self::assertCount($count, $catalogs[$tier], $tier);
        }
    }

    #[Test]
    public function unionCoversFullCatalogWithoutDuplicates(): void
    {
        $union = array_merge(...array_values(self::tierCatalogs()));

        self::assertCount(172, $union);
        self::assertSame($union, array_values(array_unique($union)));
    }
}
