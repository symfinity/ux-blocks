<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * Composition-language conformance gate (symfinity 108).
 *
 * Implements checks K1-K7 from the registry-conformance contract. Each method
 * returns a list of {@see ConformanceViolation}; an empty list means conformant.
 * The engine is pure (no rendering) so it is unit-testable; tier suites feed it
 * reflected public attributes, emitted `data-ui-part` values, and role ids.
 */
final class LanguageConformance
{
    /** Suffixes that mark a role id as a per-concept compound sub-component (K3). */
    private const COMPOUND_ROLE_SUFFIXES = ['header', 'footer', 'title', 'description', 'content', 'action', 'media'];

    /** Container role ids whose `-{suffix}` rows were removed in symfinity 108. */
    private const COMPOUND_CONTAINER_PREFIXES = [
        'card',
        'alert',
        'empty',
        'field',
        'table',
    ];

    /**
     * K1 (definition) + K4: a role's declared facets must be subsets of the lexicons.
     *
     * @return list<ConformanceViolation>
     */
    public static function checkRoleDefinition(RoleLanguageDefinition $def): array
    {
        $violations = [];

        foreach ($def->attributes as $attr) {
            if (!CompositionLanguage::isModifierAttribute($attr)) {
                $violations[] = new ConformanceViolation(
                    'K1',
                    $def->role,
                    ConformanceViolation::LEVEL_FAIL,
                    sprintf('declared attribute "%s" is not in the modifier lexicon', $attr),
                );
            }
        }

        foreach ($def->scalarContent as $scalar) {
            if (!CompositionLanguage::isScalarContent($scalar)) {
                $violations[] = new ConformanceViolation(
                    'K1',
                    $def->role,
                    ConformanceViolation::LEVEL_FAIL,
                    sprintf('declared scalar_content "%s" is not in the scalar-content lexicon', $scalar),
                );
            }
        }

        foreach ($def->styledParts as $part) {
            if (!CompositionLanguage::isRegionPart($part)) {
                $violations[] = new ConformanceViolation(
                    'K4',
                    $def->role,
                    ConformanceViolation::LEVEL_FAIL,
                    sprintf('styled_part "%s" is not in the closed region vocabulary', $part),
                );
            }
        }

        return $violations;
    }

    /**
     * K1: every public attribute ⊆ that role's declared `attributes` ∪ `scalar_content`.
     *
     * @param list<string> $publicAttributes
     *
     * @return list<ConformanceViolation>
     */
    public static function checkPublicAttributes(RoleLanguageDefinition $def, array $publicAttributes): array
    {
        $violations = [];

        foreach ($publicAttributes as $attr) {
            $allowed = \in_array($attr, $def->attributes, true)
                || \in_array($attr, $def->scalarContent, true);

            if (!$allowed) {
                $violations[] = new ConformanceViolation(
                    'K1',
                    $def->role,
                    ConformanceViolation::LEVEL_FAIL,
                    sprintf('public attribute "%s" is not declared on role (attributes ∪ scalar_content)', $attr),
                );
            }
        }

        return $violations;
    }

    /**
     * K2: every emitted `data-ui-part` value belongs to the closed region vocabulary.
     *
     * @param list<string> $emittedParts
     *
     * @return list<ConformanceViolation>
     */
    public static function checkEmittedParts(string $role, array $emittedParts): array
    {
        $violations = [];

        foreach ($emittedParts as $part) {
            if (!CompositionLanguage::isRegionPart($part)) {
                $violations[] = new ConformanceViolation(
                    'K2',
                    $role,
                    ConformanceViolation::LEVEL_FAIL,
                    sprintf('emitted data-ui-part "%s" is not in the region vocabulary', $part),
                );
            }
        }

        return $violations;
    }

    /**
     * K3: no per-concept compound sub-component is registered as a role.
     *
     * @param list<string> $roleIds
     *
     * @return list<ConformanceViolation>
     */
    public static function checkNoCompoundRoles(array $roleIds): array
    {
        $violations = [];

        foreach ($roleIds as $roleId) {
            foreach (self::COMPOUND_ROLE_SUFFIXES as $suffix) {
                if (!str_ends_with($roleId, '-' . $suffix)) {
                    continue;
                }

                $prefix = substr($roleId, 0, -\strlen($suffix) - 1);
                if (!\in_array($prefix, self::COMPOUND_CONTAINER_PREFIXES, true)) {
                    continue;
                }

                $violations[] = new ConformanceViolation(
                    'K3',
                    $roleId,
                    ConformanceViolation::LEVEL_FAIL,
                    sprintf('per-concept compound role "%s" is forbidden; use a universal region component', $roleId),
                );
                break;
            }
        }

        return $violations;
    }

    /**
     * K6: no `themeVariant` public attribute; `size` values are within the lexicon set.
     *
     * @param list<string> $publicAttributes
     * @param list<string> $sizeValues       declared/accepted size tokens for the role
     *
     * @return list<ConformanceViolation>
     */
    public static function checkDriftReconciled(string $role, array $publicAttributes, array $sizeValues = []): array
    {
        $violations = [];

        if (\in_array('themeVariant', $publicAttributes, true)) {
            $violations[] = new ConformanceViolation(
                'K6',
                $role,
                ConformanceViolation::LEVEL_FAIL,
                'declares "themeVariant"; use "variant" from the modifier lexicon',
            );
        }

        $sizeSet = CompositionLanguage::tokenSet('size') ?? [];
        foreach ($sizeValues as $value) {
            if (!\in_array($value, $sizeSet, true)) {
                $violations[] = new ConformanceViolation(
                    'K6',
                    $role,
                    ConformanceViolation::LEVEL_FAIL,
                    sprintf('size value "%s" is outside the lexicon set (%s)', $value, implode(', ', $sizeSet)),
                );
            }
        }

        return $violations;
    }

    /**
     * K5: each declared styled_part has corresponding parent role CSS (warn).
     *
     * @return list<ConformanceViolation>
     */
    public static function checkStyledPartsHaveCss(RoleLanguageDefinition $def, string $roleCss): array
    {
        $violations = [];

        foreach ($def->styledParts as $part) {
            if (!str_contains($roleCss, sprintf('data-ui-part="%s"', $part))
                && !str_contains($roleCss, sprintf("data-ui-part='%s'", $part))
                && !str_contains($roleCss, sprintf('[data-ui-part~="%s"]', $part))) {
                $violations[] = new ConformanceViolation(
                    'K5',
                    $def->role,
                    ConformanceViolation::LEVEL_WARN,
                    sprintf('styled_part "%s" has no matching parent role CSS', $part),
                );
            }
        }

        return $violations;
    }

    /**
     * K7: the region vocabulary remains exactly the closed five.
     *
     * @return list<ConformanceViolation>
     */
    public static function checkRegionVocabularyClosed(): array
    {
        $expected = ['header', 'footer', 'media', 'actions', 'aside'];

        $actual = CompositionLanguage::REGION_VOCABULARY;
        sort($actual);
        sort($expected);

        if ($actual !== $expected) {
            return [new ConformanceViolation(
                'K7',
                '(region_vocabulary)',
                ConformanceViolation::LEVEL_FAIL,
                sprintf(
                    'region vocabulary drifted from the closed five: [%s]',
                    implode(', ', CompositionLanguage::REGION_VOCABULARY),
                ),
            )];
        }

        return [];
    }

    /**
     * Convenience: keep only failure-level violations.
     *
     * @param list<ConformanceViolation> $violations
     *
     * @return list<ConformanceViolation>
     */
    public static function failuresOnly(array $violations): array
    {
        return array_values(array_filter($violations, static fn (ConformanceViolation $v): bool => $v->isFailure()));
    }
}
