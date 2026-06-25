<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Registry;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfinity\UxBlocks\Registry\CoreRoleCatalog;
use Symfinity\UxBlocks\Registry\TierCatalog;

final class CoreRoleCatalogTest extends TestCase
{
    #[Test]
    public function phpCatalogMatchesUxBlocksCoreYaml(): void
    {
        $monorepoRoot = \dirname(__DIR__, 4);
        $yamlPath = $monorepoRoot . '/packages/ux-blocks-core/config/ux_roles.yaml';

        if (!is_file($yamlPath)) {
            self::markTestSkipped('ux-blocks-core ux_roles.yaml not available in this checkout.');
        }

        $catalog = TierCatalog::fromYamlFile($yamlPath);
        $yamlRoles = array_map(static fn ($role) => $role->role, $catalog->roles);

        self::assertSame(CoreRoleCatalog::roles(), $yamlRoles);
        self::assertCount(25, $yamlRoles);
    }
}
