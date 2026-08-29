<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

final class RegistryExporter
{
    public function __construct(
        private readonly RegistryMarkdownGenerator $markdownGenerator,
    ) {
    }

    public function exportPackage(string $packageDir, string $slug): RegistryExportResult
    {
        $catalog = TierCatalog::fromYamlFile($packageDir . '/config/ux_roles.yaml');
        $markdown = $this->markdownGenerator->generate($catalog);
        $readmePath = $packageDir . '/README.md';
        $current = is_file($readmePath) ? (string) file_get_contents($readmePath) : '';
        $expected = $this->wrapMarkers($markdown);

        $updated = is_file($readmePath)
            ? $this->applyMarkers($current, $expected)
            : $expected . "\n";
        file_put_contents($readmePath, $updated);

        return new RegistryExportResult(
            package: $slug,
            markdown: $markdown,
            roleCount: $catalog->roleCount(),
            drift: false,
        );
    }

    public function checkPackage(string $packageDir, string $slug): RegistryExportResult
    {
        $catalog = TierCatalog::fromYamlFile($packageDir . '/config/ux_roles.yaml');
        $markdown = $this->markdownGenerator->generate($catalog);
        $readmePath = $packageDir . '/README.md';

        if (!is_file($readmePath)) {
            return new RegistryExportResult($slug, $markdown, $catalog->roleCount(), true);
        }

        $current = (string) file_get_contents($readmePath);
        $expected = $this->wrapMarkers($markdown);

        return new RegistryExportResult(
            package: $slug,
            markdown: $markdown,
            roleCount: $catalog->roleCount(),
            drift: !$this->markersMatch($current, $expected),
        );
    }

    public function wrapMarkers(string $markdown): string
    {
        return TierCatalog::MARKER_START . "\n" . $markdown . "\n" . TierCatalog::MARKER_END;
    }

    public function applyMarkers(string $readme, string $markerBlock): string
    {
        if ($this->containsMarkers($readme)) {
            $pattern = '/' . preg_quote(TierCatalog::MARKER_START, '/') . '.*?' . preg_quote(TierCatalog::MARKER_END, '/') . '/s';

            return (string) preg_replace($pattern, $markerBlock, $readme, 1);
        }

        return $readme . "\n\n" . $markerBlock . "\n";
    }

    private function markersMatch(string $readme, string $expectedBlock): bool
    {
        if (!$this->containsMarkers($readme)) {
            return false;
        }

        $pattern = '/' . preg_quote(TierCatalog::MARKER_START, '/') . '(.*?)' . preg_quote(TierCatalog::MARKER_END, '/') . '/s';
        if (!preg_match($pattern, $readme, $matches)) {
            return false;
        }

        return trim($matches[1]) === trim(str_replace(
            [TierCatalog::MARKER_START, TierCatalog::MARKER_END],
            ['', ''],
            $expectedBlock,
        ));
    }

    private function containsMarkers(string $readme): bool
    {
        return str_contains($readme, TierCatalog::MARKER_START)
            && str_contains($readme, TierCatalog::MARKER_END);
    }
}
