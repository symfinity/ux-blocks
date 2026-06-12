<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Registry;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfinity\UxBlocks\Registry\RegistrySchema;
use Symfinity\UxBlocks\Registry\CoreRoleCatalog;

final class RegistrySchemaTest extends TestCase
{
    #[Test]
    public function defaultPrefixIsBlocks(): void
    {
        self::assertSame('blocks', RegistrySchema::DEFAULT_PREFIX);
    }

    #[Test]
    public function fragmentIdUsesPrefixAndRole(): void
    {
        self::assertSame('blocks.alert', RegistrySchema::fragmentId('alert'));
    }

    #[Test]
    public function coreCatalogHasTwentyThreeAtomRolesIncludingFigure(): void
    {
        self::assertCount(23, CoreRoleCatalog::roles());
        self::assertContains('figure', CoreRoleCatalog::roles());
    }
}
