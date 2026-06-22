<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Registry;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfinity\UxBlocks\Registry\CompositionLanguage;

final class CompositionLanguageTest extends TestCase
{
    #[Test]
    public function regionVocabularyIsExactlyTheClosedFive(): void
    {
        self::assertSame(
            ['header', 'footer', 'media', 'actions', 'aside'],
            CompositionLanguage::REGION_VOCABULARY,
        );
        self::assertNotContains('body', CompositionLanguage::REGION_VOCABULARY);
    }

    #[Test]
    public function modifierAttributesIncludeKeysAndStateFlags(): void
    {
        $attributes = CompositionLanguage::modifierAttributes();

        self::assertContains('variant', $attributes);
        self::assertContains('size', $attributes);
        self::assertContains('density', $attributes);
        self::assertContains('appearance', $attributes);
        self::assertContains('tone', $attributes);
        self::assertContains('disabled', $attributes);
        self::assertContains('loading', $attributes);
        self::assertContains('checked', $attributes);
    }

    #[Test]
    public function themeVariantIsNotAModifierAttribute(): void
    {
        self::assertFalse(CompositionLanguage::isModifierAttribute('themeVariant'));
    }

    #[Test]
    public function scalarContentLexiconIsBounded(): void
    {
        self::assertTrue(CompositionLanguage::isScalarContent('title'));
        self::assertTrue(CompositionLanguage::isScalarContent('error'));
        self::assertFalse(CompositionLanguage::isScalarContent('body'));
    }

    #[Test]
    public function sizeTokenSetHasMdCanonicalDefault(): void
    {
        self::assertContains('md', CompositionLanguage::tokenSet('size') ?? []);
        self::assertSame('md', CompositionLanguage::SIZE_CANONICAL_DEFAULT);
        self::assertNotContains('default', CompositionLanguage::tokenSet('size') ?? []);
    }

    #[Test]
    public function toneCarriesNoEnumeratedTokenSet(): void
    {
        self::assertNull(CompositionLanguage::tokenSet('tone'));
        self::assertNull(CompositionLanguage::tokenSet('not-a-key'));
    }
}
