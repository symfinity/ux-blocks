<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Test;

use PHPUnit\Framework\Assert;
use Symfinity\UxBlocks\Registry\ConformanceViolation;
use Symfinity\UxBlocks\Registry\LanguageConformance;
use Symfinity\UxBlocks\Registry\RoleLanguageDefinition;

/**
 * Shared composition-language conformance assertions for tier test suites (symfinity 108).
 *
 * Wraps {@see LanguageConformance} so each tier package can assert K1-K7 against
 * its own role definitions, reflected public attributes, and emitted markup.
 */
trait ConformanceAssertions
{
    protected function assertRoleDefinitionConformant(RoleLanguageDefinition $def): void
    {
        self::assertNoFailures(LanguageConformance::checkRoleDefinition($def), $def->role);
    }

    /**
     * @param list<string> $publicAttributes
     */
    protected function assertPublicAttributesConformant(RoleLanguageDefinition $def, array $publicAttributes): void
    {
        self::assertNoFailures(LanguageConformance::checkPublicAttributes($def, $publicAttributes), $def->role);
        self::assertNoFailures(
            LanguageConformance::checkDriftReconciled($def->role, $publicAttributes),
            $def->role,
        );
    }

    /**
     * @param list<string> $emittedParts
     */
    protected function assertEmittedPartsConformant(string $role, array $emittedParts): void
    {
        self::assertNoFailures(LanguageConformance::checkEmittedParts($role, $emittedParts), $role);
    }

    /**
     * @param list<string> $roleIds
     */
    protected function assertNoCompoundRoles(array $roleIds): void
    {
        self::assertNoFailures(LanguageConformance::checkNoCompoundRoles($roleIds), '(catalog)');
    }

    protected function assertRegionVocabularyClosed(): void
    {
        self::assertNoFailures(LanguageConformance::checkRegionVocabularyClosed(), '(region_vocabulary)');
    }

    /**
     * Extract the set of `data-ui-part` values emitted in a rendered fragment.
     *
     * @return list<string>
     */
    protected function emittedParts(string $html): array
    {
        if (!preg_match_all('/data-ui-part="([^"]+)"/', $html, $matches)) {
            return [];
        }

        return array_values(array_unique($matches[1]));
    }

    /**
     * @param list<ConformanceViolation> $violations
     */
    private static function assertNoFailures(array $violations, string $context): void
    {
        $failures = LanguageConformance::failuresOnly($violations);

        Assert::assertSame(
            [],
            array_map(static fn (ConformanceViolation $v): string => $v->describe(), $failures),
            sprintf('Composition-language conformance failed for %s', $context),
        );
    }
}
