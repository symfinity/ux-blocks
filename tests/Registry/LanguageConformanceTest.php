<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Registry;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfinity\UxBlocks\Registry\LanguageConformance;
use Symfinity\UxBlocks\Registry\RoleLanguageDefinition;

final class LanguageConformanceTest extends TestCase
{
    #[Test]
    public function conformantRoleDefinitionHasNoViolations(): void
    {
        $def = new RoleLanguageDefinition(
            role: 'card',
            tier: 'extended',
            attributes: ['variant', 'size', 'density', 'appearance'],
            scalarContent: ['title', 'description'],
            styledParts: ['header', 'footer', 'media', 'actions'],
        );

        self::assertSame([], LanguageConformance::checkRoleDefinition($def));
    }

    #[Test]
    public function offLexiconPublicAttributeFails(): void
    {
        $def = new RoleLanguageDefinition('badge', 'core', attributes: ['variant'], scalarContent: ['icon']);

        $failures = LanguageConformance::failuresOnly(
            LanguageConformance::checkPublicAttributes($def, ['variant', 'icon', 'rogueProp']),
        );

        self::assertCount(1, $failures);
        self::assertSame('K1', $failures[0]->check);
        self::assertStringContainsString('rogueProp', $failures[0]->message);
    }

    #[Test]
    public function undeclaredModifierOnRoleFails(): void
    {
        $def = new RoleLanguageDefinition('badge', 'core', attributes: ['variant'], scalarContent: ['icon']);

        $failures = LanguageConformance::failuresOnly(
            LanguageConformance::checkPublicAttributes($def, ['variant', 'size']),
        );

        self::assertCount(1, $failures);
        self::assertSame('K1', $failures[0]->check);
        self::assertStringContainsString('size', $failures[0]->message);
    }

    #[Test]
    public function unknownEmittedPartFails(): void
    {
        $failures = LanguageConformance::failuresOnly(
            LanguageConformance::checkEmittedParts('card', ['header', 'footer', 'sidebar']),
        );

        self::assertCount(1, $failures);
        self::assertSame('K2', $failures[0]->check);
        self::assertStringContainsString('sidebar', $failures[0]->message);
    }

    #[Test]
    public function perConceptCompoundRoleFails(): void
    {
        $failures = LanguageConformance::failuresOnly(
            LanguageConformance::checkNoCompoundRoles(['card', 'card-header', 'alert-title', 'description-list']),
        );

        self::assertCount(2, $failures);
        self::assertSame('K3', $failures[0]->check);
        self::assertStringContainsString('card-header', $failures[0]->message);
        self::assertStringContainsString('alert-title', $failures[1]->message);
    }

    #[Test]
    public function descriptionListIsNotFlaggedAsCompound(): void
    {
        self::assertSame([], LanguageConformance::checkNoCompoundRoles(['description-list', 'page-heading', 'section-heading']));
    }

    #[Test]
    public function styledPartOutsideRegionVocabularyFails(): void
    {
        $def = new RoleLanguageDefinition('card', 'extended', styledParts: ['header', 'sidebar']);

        $failures = LanguageConformance::failuresOnly(LanguageConformance::checkRoleDefinition($def));

        self::assertCount(1, $failures);
        self::assertSame('K4', $failures[0]->check);
    }

    #[Test]
    public function themeVariantDriftFails(): void
    {
        $failures = LanguageConformance::failuresOnly(
            LanguageConformance::checkDriftReconciled('card', ['variant', 'themeVariant']),
        );

        self::assertCount(1, $failures);
        self::assertSame('K6', $failures[0]->check);
    }

    #[Test]
    public function legacyDefaultSizeValueFails(): void
    {
        $failures = LanguageConformance::failuresOnly(
            LanguageConformance::checkDriftReconciled('card', ['size'], ['default']),
        );

        self::assertCount(1, $failures);
        self::assertSame('K6', $failures[0]->check);
        self::assertStringContainsString('default', $failures[0]->message);
    }

    #[Test]
    public function regionVocabularyClosedCheckPasses(): void
    {
        self::assertSame([], LanguageConformance::checkRegionVocabularyClosed());
    }

    #[Test]
    public function styledPartWithoutCssWarns(): void
    {
        $def = new RoleLanguageDefinition('card', 'extended', styledParts: ['header', 'footer']);
        $css = '[data-ui-role="card"]:has([data-ui-part="header"]) { display: grid; }';

        $violations = LanguageConformance::checkStyledPartsHaveCss($def, $css);

        self::assertCount(1, $violations);
        self::assertSame('K5', $violations[0]->check);
        self::assertSame('warn', $violations[0]->level);
        self::assertStringContainsString('footer', $violations[0]->message);
    }
}
