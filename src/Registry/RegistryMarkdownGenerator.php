<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

final class RegistryMarkdownGenerator
{
    public function generate(TierCatalog $catalog): string
    {
        $lines = [
            '| Role | Twig | Interaction | Fragment | Status |',
            '|------|------|-------------|----------|--------|',
        ];

        foreach ($catalog->roles as $role) {
            $lines[] = sprintf(
                '| %s | %s | %s | `%s` | %s |',
                $role->role,
                $role->twigComponent,
                $role->interaction,
                $role->fragmentId,
                $this->displayStatus($role->status),
            );
        }

        return implode("\n", $lines);
    }

    private function displayStatus(string $status): string
    {
        return match ($status) {
            'shipped', 'planned', 'deprecated' => $status,
            'v0', 'v1' => 'shipped',
            default => $status,
        };
    }
}
