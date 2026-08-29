<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfinity\UxBlocks\Command\RegistryExportCommand;
use Symfinity\UxBlocks\Registry\RegistryExporter;
use Symfinity\UxBlocks\Registry\RegistryMarkdownGenerator;
use Symfinity\UxBlocks\Registry\RegistryPackageLocator;
use Symfony\Component\Console\Tester\CommandTester;

final class RegistryExportCommandTest extends TestCase
{
    private string $fixtureRoot;

    protected function setUp(): void
    {
        $this->fixtureRoot = sys_get_temp_dir() . '/ux-blocks-registry-export-' . uniqid('', true);
        mkdir($this->fixtureRoot . '/packages/demo-pkg/config', 0777, true);
        file_put_contents($this->fixtureRoot . '/mono.json', '{}');
        file_put_contents($this->fixtureRoot . '/packages/demo-pkg/config/ux_roles.yaml', <<<'YAML'
ux_role_registry: "1.1"
registry_prefix: blocks
roles:
  - role: button
    twig_component: Button
    fragment_id: blocks.button
    interaction: nat
    status: shipped
YAML);
        file_put_contents($this->fixtureRoot . '/packages/demo-pkg/README.md', <<<'MD'
# Demo

<!-- ux-blocks:registry:start -->
stale
<!-- ux-blocks:registry:end -->
MD);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->fixtureRoot);
    }

    #[Test]
    public function checkDetectsDrift(): void
    {
        $command = $this->createCommand(['demo-pkg']);
        $tester = new CommandTester($command);
        $exit = $tester->execute([
            '--check' => true,
            '--monorepo-root' => $this->fixtureRoot,
            '--package' => 'demo-pkg',
        ]);

        self::assertSame(1, $exit);
        self::assertStringContainsString('drift', strtolower($tester->getDisplay()));
    }

    #[Test]
    public function exportRegeneratesMarkdownMarkers(): void
    {
        $command = $this->createCommand(['demo-pkg']);
        $tester = new CommandTester($command);
        $exit = $tester->execute([
            '--monorepo-root' => $this->fixtureRoot,
            '--package' => 'demo-pkg',
        ]);

        self::assertSame(0, $exit);
        $readme = (string) file_get_contents($this->fixtureRoot . '/packages/demo-pkg/README.md');
        self::assertStringContainsString('| button | Button |', $readme);
        self::assertStringNotContainsString("stale\n", $readme);

        $check = new CommandTester($command);
        self::assertSame(0, $check->execute([
            '--check' => true,
            '--monorepo-root' => $this->fixtureRoot,
            '--package' => 'demo-pkg',
        ]));
    }

    /**
     * @param list<string> $slugs
     */
    private function createCommand(array $slugs): RegistryExportCommand
    {
        $locator = new class($slugs) extends RegistryPackageLocator {
            /** @param list<string> $slugs */
            public function __construct(private readonly array $slugs)
            {
            }

            public function resolveTargetSlugs(?string $packageOption): array
            {
                return $packageOption ? [$packageOption] : $this->slugs;
            }

            public function packageDir(string $slug, ?string $monorepoRoot = null): string
            {
                return $monorepoRoot . '/packages/' . $slug;
            }
        };

        return new RegistryExportCommand(new RegistryExporter(new RegistryMarkdownGenerator()), $locator);
    }

    private function removeDir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        foreach (scandir($dir) ?: [] as $entry) {
            if (\in_array($entry, ['.', '..'], true)) {
                continue;
            }

            $path = $dir . '/' . $entry;
            is_dir($path) ? $this->removeDir($path) : unlink($path);
        }

        rmdir($dir);
    }
}
