<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Registry;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfinity\UxBlocks\Registry\FormRoleCatalog;
use Symfinity\UxBlocks\Registry\TierCatalog;

final class FormRoleCatalogTest extends TestCase
{
    #[Test]
    public function phpCatalogMatchesUxBlocksFormYaml(): void
    {
        $monorepoRoot = \dirname(__DIR__, 4);
        $yamlPath = $monorepoRoot . '/packages/ux-blocks-form/config/ux_roles.yaml';

        if (!is_file($yamlPath)) {
            self::markTestSkipped('ux-blocks-form ux_roles.yaml not available in this checkout.');
        }

        $catalog = TierCatalog::fromYamlFile($yamlPath);
        $yamlRoles = array_map(static fn ($role) => $role->role, $catalog->roles);

        self::assertSame(FormRoleCatalog::roles(), $yamlRoles);
        self::assertCount(17, $yamlRoles);
    }
}
