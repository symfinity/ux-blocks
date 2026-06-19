<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Command;

use Symfinity\UxBlocks\Registry\RegistryExporter;
use Symfinity\UxBlocks\Registry\RegistryPackageLocator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'ux-blocks:registry-export',
    description: 'Export ux_roles.yaml inventory to package README markers (106)',
)]
final class RegistryExportCommand extends Command
{
    public function __construct(
        private readonly RegistryExporter $exporter,
        private readonly RegistryPackageLocator $locator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('check', null, InputOption::VALUE_NONE, 'Exit non-zero when README markers drift from YAML')
            ->addOption('package', null, InputOption::VALUE_REQUIRED, 'Limit to one tier package slug (e.g. ux-blocks-core)')
            ->addOption('format', null, InputOption::VALUE_REQUIRED, 'markdown (default) or json', 'markdown')
            ->addOption('monorepo-root', null, InputOption::VALUE_REQUIRED, 'Symfinity monorepo root (auto-detected when omitted)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $check = (bool) $input->getOption('check');
        $format = (string) $input->getOption('format');
        $package = $input->getOption('package');
        $package = \is_string($package) && '' !== $package ? $package : null;
        $monorepoRoot = $input->getOption('monorepo-root');
        $monorepoRoot = \is_string($monorepoRoot) && '' !== $monorepoRoot ? $monorepoRoot : null;

        if (!\in_array($format, ['markdown', 'json'], true)) {
            $io->error('Invalid --format; use markdown or json.');

            return Command::INVALID;
        }

        if (null === $monorepoRoot) {
            $monorepoRoot = $this->locator->resolveMonorepoRoot();
        }

        if (null === $monorepoRoot) {
            $io->error('Cannot resolve monorepo root — pass --monorepo-root or run from symfinity checkout.');

            return Command::FAILURE;
        }

        $slugs = $this->locator->resolveTargetSlugs($package);
        $results = [];
        $hasDrift = false;

        foreach ($slugs as $slug) {
            $packageDir = $this->locator->packageDir($slug, $monorepoRoot);
            $result = $check
                ? $this->exporter->checkPackage($packageDir, $slug)
                : $this->exporter->exportPackage($packageDir, $slug);

            $results[] = $result;
            if ($result->drift) {
                $hasDrift = true;
            }
        }

        if ('json' === $format) {
            $exportDir = $monorepoRoot . '/var/export';
            if (!is_dir($exportDir)) {
                mkdir($exportDir, 0777, true);
            }

            $payload = array_map(static fn ($result) => [
                'package' => $result->package,
                'role_count' => $result->roleCount,
                'drift' => $result->drift,
                'markdown' => $result->markdown,
            ], $results);

            $path = $exportDir . '/ux-blocks-registry-' . date('Ymd-His') . '.json';
            file_put_contents($path, json_encode($payload, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES));
            $io->writeln($path);
        } else {
            foreach ($results as $result) {
                $io->section($result->package);
                $io->writeln($result->markdown);
                $io->writeln(sprintf('roles: %d · drift: %s', $result->roleCount, $result->drift ? 'yes' : 'no'));
            }
        }

        if ($hasDrift) {
            $io->warning('README registry markers drift from config/ux_roles.yaml.');

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
