<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Registry;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfinity\UxBlocks\Registry\CompositionLanguageRegistryAuditor;
use Symfinity\UxBlocks\Registry\LanguageConformance;
use Symfinity\UxBlocks\Registry\RoleLanguageDefinition;

final class CompositionLanguageRegistryAuditorTest extends TestCase
{
    #[Test]
    public function blocksCoreLanePassesEnforcedCompositionLanguage(): void
    {
        $auditor = new CompositionLanguageRegistryAuditor();
        $failures = LanguageConformance::failuresOnly($auditor->auditLane('blocks.core'));

        self::assertSame([], array_map(static fn ($v) => $v->describe(), $failures));
    }

    #[Test]
    public function blocksExtendedLanePassesEnforcedCompositionLanguage(): void
    {
        $auditor = new CompositionLanguageRegistryAuditor();
        $failures = LanguageConformance::failuresOnly($auditor->auditLane('blocks.extended'));

        self::assertSame([], array_map(static fn ($v) => $v->describe(), $failures));
    }

    #[Test]
    public function blocksFormLanePassesEnforcedCompositionLanguage(): void
    {
        $auditor = new CompositionLanguageRegistryAuditor();
        $failures = LanguageConformance::failuresOnly($auditor->auditLane('blocks.form'));

        self::assertSame([], array_map(static fn ($v) => $v->describe(), $failures));
    }

    #[Test]
    public function undeclaredPublicAttributeFailsForEnforcedRole(): void
    {
        $def = new RoleLanguageDefinition(
            role: 'card',
            tier: 'extended',
            attributes: ['variant'],
            scalarContent: ['title'],
            compositionLanguageEnforced: true,
        );

        $failures = LanguageConformance::failuresOnly(
            LanguageConformance::checkPublicAttributes($def, ['variant', 'title', 'rogueProp']),
        );

        self::assertCount(1, $failures);
        self::assertSame('K1', $failures[0]->check);
        self::assertStringContainsString('rogueProp', $failures[0]->message);
    }
}
