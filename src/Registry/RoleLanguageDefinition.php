<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * Declarative composition-language facets of a single catalog role (symfinity 108).
 *
 * Mirrors the per-role registry fields: which modifier/scalar attributes the role
 * exposes, which region parts its CSS positions, and optional a11y wiring.
 */
final readonly class RoleLanguageDefinition
{
    /**
     * @param list<string>                $attributes    subset of the modifier lexicon (keys + state flags)
     * @param list<string>                $scalarContent subset of the scalar-content lexicon
     * @param list<string>                $styledParts   subset of the region vocabulary the container positions
     * @param array<string, list<string>> $a11y          optional describedby/labelledby wiring map
     */
    public function __construct(
        public string $role,
        public string $tier,
        public array $attributes = [],
        public array $scalarContent = [],
        public array $styledParts = [],
        public array $a11y = [],
        public bool $compositionLanguageEnforced = false,
    ) {
    }

    /**
     * @param array<string, mixed> $row a single `roles:` entry from ux_roles.yaml
     */
    public static function fromRegistryRow(string $tier, array $row): self
    {
        $roleValue = $row['role'] ?? '';
        $role = \is_string($roleValue) ? $roleValue : '';

        $compositionLanguage = $row['composition_language'] ?? null;

        return new self(
            role: $role,
            tier: $tier,
            attributes: self::stringList($row['attributes'] ?? []),
            scalarContent: self::stringList($row['scalar_content'] ?? []),
            styledParts: self::stringList($row['styled_parts'] ?? []),
            a11y: self::a11yMap($row['a11y'] ?? []),
            compositionLanguageEnforced: 'enforced' === $compositionLanguage,
        );
    }

    /**
     * @param mixed $value
     *
     * @return list<string>
     */
    private static function stringList($value): array
    {
        if (!\is_array($value)) {
            return [];
        }

        $out = [];
        foreach ($value as $item) {
            if (\is_string($item)) {
                $out[] = $item;
            }
        }

        return $out;
    }

    /**
     * @param mixed $value
     *
     * @return array<string, list<string>>
     */
    private static function a11yMap($value): array
    {
        if (!\is_array($value)) {
            return [];
        }

        $out = [];
        foreach ($value as $key => $list) {
            if (\is_string($key)) {
                $out[$key] = self::stringList($list);
            }
        }

        return $out;
    }
}
