<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\DevTools;

/**
 * Static modular-vs-paste audit for ux-blocks Sass author sources (120 SC-006).
 */
final class ScssModularAuditor
{
    public const VERDICT_MODULAR = 'modular';

    public const VERDICT_STRUCTURAL = 'structural';

    public const VERDICT_MIXED = 'mixed';

    public const VERDICT_PASTE = 'paste';

    /**
     * @param list<string> $semanticColours
     */
    public function __construct(
        private readonly ScssTopLevelRuleParser $parser = new ScssTopLevelRuleParser(),
        private readonly array $semanticColours = [
            'primary',
            'secondary',
            'accent',
            'success',
            'danger',
            'info',
            'warning',
            'neutral',
        ],
    ) {
    }

    /**
     * @return list<array{
     *     package: string,
     *     file: string,
     *     relative: string,
     *     verdict: string,
     *     findings: list<array{code: string, message: string, line: int}>
     * }>
     */
    public function auditPackagesRoot(string $packagesRoot): array
    {
        $reports = [];

        foreach (glob(rtrim($packagesRoot, '/\\') . '/ux-blocks-*/', GLOB_ONLYDIR) ?: [] as $packageDir) {
            if (!is_dir($packageDir . '/assets/scss')) {
                continue;
            }

            $reports = [...$reports, ...$this->auditPackage($packageDir)];
        }

        return $reports;
    }

    /**
     * @return list<array{
     *     package: string,
     *     file: string,
     *     relative: string,
     *     verdict: string,
     *     findings: list<array{code: string, message: string, line: int}>
     * }>
     */
    public function auditPackage(string $packageDirectory): array
    {
        $packageDirectory = rtrim($packageDirectory, '/\\');
        $packageName = basename($packageDirectory);
        $rolesDir = $packageDirectory . '/assets/scss/roles';

        if (!is_dir($rolesDir)) {
            return [];
        }

        $reports = [];

        foreach (glob($rolesDir . '/*.scss') ?: [] as $file) {
            $basename = basename($file);
            if ($basename === '_bundle.scss') {
                $report = $this->auditBundleFile($packageName, $packageDirectory, $file);
            } else {
                $report = $this->auditRoleFile($packageName, $packageDirectory, $file);
            }

            if ($report !== null) {
                $reports[] = $report;
            }
        }

        return $reports;
    }

    /**
     * @return list<array{code: string, message: string, line: int}>
     */
    public function detectVariantAppearancePaste(string $scss): array
    {
        $rules = $this->parser->parse($scss);
        $groups = [];

        foreach ($rules as $rule) {
            if (!$this->selectorHasListVariantOrAppearance($rule['selector'])) {
                continue;
            }

            $bodyKey = $this->normalizeDeclarationBody($rule['body']);
            $selectorKey = $this->normalizeSelectorStem($rule['selector']);
            $groups[$bodyKey . "\0" . $selectorKey][] = $rule;
        }

        $findings = [];

        foreach ($groups as $group) {
            if (count($group) < 3) {
                continue;
            }

            usort($group, static fn (array $a, array $b): int => $a['line'] <=> $b['line']);

            $findings[] = [
                'code' => 'variant-appearance-paste',
                'message' => sprintf(
                    '%d consecutive variant/appearance rules share the same declaration block without @include (lines %d–%d)',
                    count($group),
                    $group[0]['line'],
                    $group[array_key_last($group)]['line'],
                ),
                'line' => $group[0]['line'],
            ];
        }

        return $findings;
    }

    /**
     * @return array{
     *     package: string,
     *     file: string,
     *     relative: string,
     *     verdict: string,
     *     findings: list<array{code: string, message: string, line: int}>
     * }|null
     */
    private function auditRoleFile(string $packageName, string $packageDirectory, string $file): ?array
    {
        $scss = (string) file_get_contents($file);
        $findings = $this->detectVariantAppearancePaste($scss);

        if ($findings === []) {
            return null;
        }

        $hasSharedUse = (bool) preg_match("/@use\\s+'\\.\\.\\/_shared\\//", $scss);
        $hasInclude = str_contains($scss, '@include');
        $verdict = ($hasSharedUse || $hasInclude) ? self::VERDICT_MIXED : self::VERDICT_PASTE;

        return $this->buildReport($packageName, $packageDirectory, $file, $verdict, $findings);
    }

    /**
     * @return array{
     *     package: string,
     *     file: string,
     *     relative: string,
     *     verdict: string,
     *     findings: list<array{code: string, message: string, line: int}>
     * }|null
     */
    private function auditBundleFile(string $packageName, string $packageDirectory, string $file): ?array
    {
        $scss = (string) file_get_contents($file);
        $findings = [];

        if ((bool) preg_match('/^\s*\[data-ui-/m', preg_replace('/^\s*(?:@use|@forward|@import)\s+.+$/m', '', $scss) ?? $scss)) {
            $findings[] = [
                'code' => 'bundle-not-modular',
                'message' => '_bundle.scss must aggregate partials via @use only (no raw selectors)',
                'line' => 1,
            ];
        }

        if ($findings === []) {
            return null;
        }

        return $this->buildReport($packageName, $packageDirectory, $file, self::VERDICT_PASTE, $findings);
    }

    /**
     * @param list<array{code: string, message: string, line: int}> $findings
     *
     * @return array{
     *     package: string,
     *     file: string,
     *     relative: string,
     *     verdict: string,
     *     findings: list<array{code: string, message: string, line: int}>
     * }
     */
    private function buildReport(
        string $packageName,
        string $packageDirectory,
        string $file,
        string $verdict,
        array $findings,
    ): array {
        return [
            'package' => $packageName,
            'file' => $file,
            'relative' => ltrim(str_replace($packageDirectory, '', $file), '/'),
            'verdict' => $verdict,
            'findings' => $findings,
        ];
    }

    private function selectorHasListVariantOrAppearance(string $selector): bool
    {
        if (preg_match('/\[data-ui-appearance=["\']([^"\']+)["\']\]/', $selector)) {
            return true;
        }

        if (!preg_match('/\[data-ui-variant=["\']([^"\']+)["\']\]/', $selector, $matches)) {
            return false;
        }

        return in_array($matches[1], $this->semanticColours, true);
    }

    private function normalizeSelectorStem(string $selector): string
    {
        $normalized = preg_replace('/\[data-ui-variant=["\'][^"\']+["\']\]/', '[data-ui-variant=*]', $selector) ?? $selector;

        return preg_replace('/\[data-ui-appearance=["\'][^"\']+["\']\]/', '[data-ui-appearance=*]', $normalized) ?? $normalized;
    }

    private function normalizeDeclarationBody(string $body): string
    {
        $body = preg_replace('/--ui-color-[a-z-]+/', '--ui-color-*', $body) ?? $body;
        $body = preg_replace('/\s+/', ' ', trim($body)) ?? trim($body);

        return $body;
    }
}
