<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Registry;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfinity\UxBlocks\Registry\LanguageConformance;
use Symfinity\UxBlocks\Registry\RoleLanguageDefinition;
use Symfinity\UxBlocks\Test\ConformanceAssertions;
use Symfony\Component\Yaml\Yaml;

/**
 * Composition-language registry rows across all UX Blocks tiers (symfinity 108, T025).
 */
final class TierCompositionLanguageRegistryTest extends TestCase
{
    use ConformanceAssertions;

    /** @return iterable<string, array{0: string, 1: string}> */
    public static function tierRegistryPaths(): iterable
    {
        $packagesRoot = \dirname(__DIR__, 3);

        yield 'form' => [$packagesRoot . '/ux-blocks-form/config/ux_roles.yaml', 'form'];
        yield 'interactive' => [$packagesRoot . '/ux-blocks-interactive/config/ux_roles.yaml', 'interactive'];
        yield 'live' => [$packagesRoot . '/ux-blocks-live/config/ux_roles.yaml', 'live'];
        yield 'marketing' => [$packagesRoot . '/ux-blocks-marketing/config/ux_roles.yaml', 'marketing'];
        yield 'ecommerce' => [$packagesRoot . '/ux-blocks-ecommerce/config/ux_roles.yaml', 'ecommerce'];
        yield 'lab' => [$packagesRoot . '/ux-blocks-lab/config/ux_roles.yaml', 'lab'];
    }

    #[Test]
    #[DataProvider('tierRegistryPaths')]
    public function tierRegistryUsesCompositionLanguageSchema(string $path, string $tier): void
    {
        self::assertFileExists($path);

        /** @var array<string, mixed> $registry */
        $registry = Yaml::parseFile($path);

        self::assertSame('1.4', (string) ($registry['ux_role_registry'] ?? ''));

        /** @var list<array<string, mixed>> $rows */
        $rows = $registry['roles'] ?? [];

        $roleIds = [];
        foreach ($rows as $row) {
            $def = RoleLanguageDefinition::fromRegistryRow($tier, $row);
            $roleIds[] = $def->role;
            $this->assertRoleDefinitionConformant($def);
        }

        $this->assertNoCompoundRoles($roleIds);
        self::assertSame([], LanguageConformance::failuresOnly(LanguageConformance::checkNoCompoundRoles($roleIds)));
    }
}
