<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * Closed declaration of the UX Blocks composition language (symfinity 108).
 *
 * Every catalog component's public interface is `attributes + one content block`.
 * Attributes are members of the modifier lexicon or the scalar-content lexicon;
 * multi-region structure is expressed with the closed region vocabulary.
 *
 * This class is the single source of truth for the language; roles reference it
 * via their registry definitions and the conformance gate enforces it.
 */
final class CompositionLanguage
{
    /**
     * Closed modifier lexicon keyed by attribute name to its canonical token set.
     * `tone` carries no enumerated set here (existing tone tokens are role-defined);
     * its membership as a key is what conformance validates.
     *
     * @var array<string, list<string>>
     */
    public const MODIFIER_LEXICON = [
        'variant' => ['primary', 'secondary', 'success', 'warning', 'danger', 'info', 'neutral'],
        'size' => ['xs', 'sm', 'md', 'lg', 'xl'],
        'density' => ['comfortable', 'compact'],
        'appearance' => ['solid', 'soft', 'outline', 'ghost', 'link'],
        'tone' => [],
        'watermarkPosition' => ['top-start', 'top-end', 'bottom-start', 'bottom-end', 'center'],
        'surface' => ['solid', 'glass', 'flat', 'retro'],
    ];

    /** Boolean state modifiers. @var list<string> */
    public const STATE_FLAGS = ['disabled', 'loading', 'invalid', 'open', 'checked', 'dismissible'];

    /** Bounded scalar-content attributes a component may render internally. @var list<string> */
    public const SCALAR_CONTENT_LEXICON = ['title', 'description', 'label', 'error', 'hint', 'icon', 'href', 'iconWatermark', 'buttonLabel'];

    /** CLOSED region vocabulary — exactly five. @var list<string> */
    public const REGION_VOCABULARY = ['header', 'footer', 'media', 'actions', 'aside'];

    /** Canonical default for `size` (legacy `default` normalizes to this). */
    public const SIZE_CANONICAL_DEFAULT = 'md';

    /**
     * Modifier attribute names = lexicon keys plus state flags.
     *
     * @return list<string>
     */
    public static function modifierAttributes(): array
    {
        return array_merge(array_keys(self::MODIFIER_LEXICON), self::STATE_FLAGS);
    }

    public static function isModifierAttribute(string $name): bool
    {
        return \in_array($name, self::modifierAttributes(), true);
    }

    public static function isScalarContent(string $name): bool
    {
        return \in_array($name, self::SCALAR_CONTENT_LEXICON, true);
    }

    /**
     * Any attribute that may be public: a modifier or a scalar-content attribute.
     */
    public static function isPublicAttribute(string $name): bool
    {
        return self::isModifierAttribute($name) || self::isScalarContent($name);
    }

    public static function isRegionPart(string $part): bool
    {
        return \in_array($part, self::REGION_VOCABULARY, true);
    }

    /**
     * Canonical accepted token set for a sized/enumerated modifier key, or null
     * when the key carries no enumerated set (e.g. `tone`).
     *
     * @return list<string>|null
     */
    public static function tokenSet(string $modifierKey): ?array
    {
        if (!\array_key_exists($modifierKey, self::MODIFIER_LEXICON)) {
            return null;
        }

        $set = self::MODIFIER_LEXICON[$modifierKey];

        return [] === $set ? null : $set;
    }
}
