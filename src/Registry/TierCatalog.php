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

        $schemaVersion = (string) ($data['ux_role_registry'] ?? '');
        $registryPrefix = (string) ($data['registry_prefix'] ?? RegistrySchema::DEFAULT_PREFIX);

        $roles = [];
        foreach ($data['roles'] ?? [] as $row) {
            if (!\is_array($row)) {
                continue;
            }

            $role = (string) ($row['role'] ?? '');
            if ('' === $role) {
                continue;
            }

            $roles[] = new UxRoleRecord(
                role: $role,
                twigComponent: (string) ($row['twig_component'] ?? ''),
                fragmentId: (string) ($row['fragment_id'] ?? RegistrySchema::fragmentId($role, $registryPrefix)),
                interaction: (string) ($row['interaction'] ?? 'nat'),
                status: (string) ($row['status'] ?? 'shipped'),
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
