<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Test;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfinity\UxBlocks\Registry\RoleLanguageDefinition;
use Symfinity\UxBlocks\Test\ConformanceAssertions;

final class ConformanceAssertionsTest extends TestCase
{
    use ConformanceAssertions;

    #[Test]
    public function conformantRolePassesAssertions(): void
    {
        $def = new RoleLanguageDefinition(
            role: 'card',
            tier: 'extended',
            attributes: ['variant', 'size'],
            scalarContent: ['title'],
            styledParts: ['header', 'footer'],
        );

        $this->assertRoleDefinitionConformant($def);
        $this->assertPublicAttributesConformant($def, ['variant', 'size', 'title']);
        $this->assertNoCompoundRoles(['card', 'description-list']);
        $this->assertRegionVocabularyClosed();
    }

    #[Test]
    public function emittedPartsAreExtractedFromMarkup(): void
    {
        $html = '<div data-ui-role="card"><header data-ui-part="header">x</header><footer data-ui-part="footer">y</footer></div>';

        $parts = $this->emittedParts($html);

        self::assertSame(['header', 'footer'], $parts);
        $this->assertEmittedPartsConformant('card', $parts);
    }
}
