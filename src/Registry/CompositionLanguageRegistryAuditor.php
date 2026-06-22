<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * Tier registry composition-language audit (symfinity 108, G2).
 *
 * Runs K1–K7 against a lane's ux_roles.yaml, enforced component classes, and templates.
 */
final class CompositionLanguageRegistryAuditor
{
    public function __construct(
        private readonly TierRegistryLocator $locator = new TierRegistryLocator(),
    ) {
    }

    /**
     * @return list<ConformanceViolation>
     */
    public function auditLane(string $lane): array
    {
        if (!$this->locator->supportsLane($lane)) {
            return [];
        }

        $tier = $this->locator->tierLabel($lane);
        $rows = $this->locator->roleRows($lane);
        $roleIds = [];

        $violations = LanguageConformance::checkRegionVocabularyClosed();

        foreach ($rows as $row) {
            $def = RoleLanguageDefinition::fromRegistryRow($tier, $row);
            $roleIds[] = $def->role;
            $violations = [...$violations, ...LanguageConformance::checkRoleDefinition($def)];

            $phpClass = $row['php_class'] ?? null;
            if (\is_string($phpClass) && $def->compositionLanguageEnforced) {
                $publicAttributes = ComponentPublicAttributeReflector::reflect($phpClass);
                $violations = [
                    ...$violations,
                    ...LanguageConformance::checkPublicAttributes($def, $publicAttributes),
                    ...LanguageConformance::checkDriftReconciled(
                        $def->role,
                        $publicAttributes,
                        ComponentPublicAttributeReflector::defaultStringValues($phpClass, ['size']),
                    ),
                ];
            }

            if ([] !== $def->styledParts) {
                $cssPath = $this->locator->roleCssPath($lane, $def->role);
                if (null !== $cssPath) {
                    $violations = [
                        ...$violations,
                        ...LanguageConformance::checkStyledPartsHaveCss($def, (string) file_get_contents($cssPath)),
                    ];
                }
            }

            if ($def->compositionLanguageEnforced) {
                $twigComponent = $row['twig_component'] ?? null;
                if (\is_string($twigComponent)) {
                    $templatePath = $this->locator->componentTemplatePath($lane, $twigComponent);
                    if (null !== $templatePath) {
                        $parts = TwigTemplatePartScanner::scanFile($templatePath);
                        $violations = [...$violations, ...LanguageConformance::checkEmittedParts($def->role, $parts)];
                    }
                }
            }
        }

        return [...$violations, ...LanguageConformance::checkNoCompoundRoles($roleIds)];
    }
}
