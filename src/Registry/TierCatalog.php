<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

use Symfony\Component\Yaml\Yaml;

final class TierCatalog
{
    public const MARKER_START = '<!-- ux-blocks:registry:start -->';

    public const MARKER_END = '<!-- ux-blocks:registry:end -->';

    /**
     * @param list<UxRoleRecord> $roles
     */
    public function __construct(
        public string $schemaVersion,
        public string $registryPrefix,
        public array $roles,
    ) {
    }

    public static function fromYamlFile(string $path): self
    {
        if (!is_file($path)) {
            throw new \InvalidArgumentException(sprintf('Registry YAML not found: %s', $path));
        }

        /** @var array<string, mixed> $data */
        $data = Yaml::parseFile($path);

        $schemaVersion = isset($data['ux_role_registry']) && (\is_string($data['ux_role_registry']) || is_int($data['ux_role_registry']))
            ? (string) $data['ux_role_registry']
            : '';
        $prefixRaw = $data['registry_prefix'] ?? RegistrySchema::DEFAULT_PREFIX;
        $registryPrefix = \is_string($prefixRaw) ? $prefixRaw : RegistrySchema::DEFAULT_PREFIX;

        $roles = [];
        $rolesRaw = $data['roles'] ?? [];
        if (!\is_array($rolesRaw)) {
            $rolesRaw = [];
        }

        foreach ($rolesRaw as $row) {
            if (!\is_array($row)) {
                continue;
            }

            $roleRaw = $row['role'] ?? '';
            $role = \is_string($roleRaw) ? $roleRaw : '';
            if ('' === $role) {
                continue;
            }

            $twigRaw = $row['twig_component'] ?? '';
            $interactionRaw = $row['interaction'] ?? 'nat';
            $statusRaw = $row['status'] ?? 'shipped';

            $roles[] = new UxRoleRecord(
                role: $role,
                twigComponent: \is_string($twigRaw) ? $twigRaw : '',
                fragmentId: \is_string($row['fragment_id'] ?? null)
                    ? $row['fragment_id']
                    : RegistrySchema::fragmentId($role, $registryPrefix),
                interaction: \is_string($interactionRaw) ? $interactionRaw : 'nat',
                status: \is_string($statusRaw) ? $statusRaw : 'shipped',
            );
        }

        return new self($schemaVersion, $registryPrefix, $roles);
    }

    public function findByTwigComponent(string $twigComponent): ?UxRoleRecord
    {
        foreach ($this->roles as $role) {
            if ($role->twigComponent === $twigComponent) {
                return $role;
            }
        }

        return null;
    }

    public function roleCount(): int
    {
        return \count($this->roles);
    }
}
