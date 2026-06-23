<div align="center">

# UX Blocks

### Registry schema and shared test helpers for the Symfinity UX Blocks component family

[![PHP Version](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php&logoColor=white)](composer.json)
[![Symfony](https://img.shields.io/badge/Symfony-7.4+-343434?style=flat&logo=symfony&logoColor=white)](composer.json)
<br/>
[![CI](https://github.com/symfinity/ux-blocks/actions/workflows/ci.yml/badge.svg)](https://github.com/symfinity/ux-blocks/actions/workflows/ci.yml)
<br/>
[![Release](https://img.shields.io/packagist/v/symfinity/ux-blocks.svg?style=flat&logo=packagist&logoColor=white)](https://packagist.org/packages/symfinity/ux-blocks)
[![Downloads](https://img.shields.io/packagist/dt/symfinity/ux-blocks.svg?style=flat&logo=packagist&logoColor=white)](https://packagist.org/packages/symfinity/ux-blocks)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat)](LICENSE)

</div>

> [!NOTE]
> **Read-only mirror.**
> See [CONTRIBUTING.md](CONTRIBUTING.md) for how to propose changes.

## Features

- **Registry schema** — shared `data-ui-role` and fragment vocabulary across tier packages
- **Tier role catalogs** — canonical role lists for core, extended, interactive, and vertical packages
- **PHPUnit helpers** — markup assertions for component packages in dev and test
- **Slim SDK boundary** — Twig components ship in `symfinity/ux-blocks-*` packages
- **Symfony Flex recipe** — bundle registered for all environments

## Prerequisites

Add the [symfinity/recipes](https://github.com/symfinity/recipes) Flex endpoint to your project's `composer.json` (see [recipes README](https://github.com/symfinity/recipes/blob/main/README.md)) — recipes are not in Symfony's official recipe repository yet.

## Installation

```bash
composer require symfinity/ux-blocks
```

Usually installed as a dependency of a tier package. See [Installation](docs/installation.md).

## Quick Start

```bash
composer require symfinity/ux-blocks-core
```

```twig
<twig:PageHeading title="Dashboard" description="Welcome back." />
<twig:Button variant="default">Save</twig:Button>
```

See [Quick start](docs/quickstart.md) for PHPUnit markup assertions and tier pairing.

## Install profiles

Pick the smallest profile that fits your app. See [Install profiles](docs/installation.md#install-profiles) for headless, kernel-styled, full-app, and vertical tier choices.

## Documentation

- **[Quick start](docs/quickstart.md)** — registry helpers and test trait
- **[Installation](docs/installation.md)** — Flex, manual setup, and install profiles
- **[Configuration](docs/configuration.md)** — no app YAML required
- **[Usage](docs/usage.md)** — registry helpers and PHPUnit assertions
- **[Registry](docs/registry.md)** — schema, catalogs, and markup contract
- **[Components](docs/components.md)** — tier packages and styling
- **[Upgrade](docs/upgrade.md)** — version migration

## Requirements

- PHP 8.2 or higher
- Symfony 7.4 or 8.x

## Support

- [GitHub Issues](https://github.com/symfinity/ux-blocks/issues)
- [Security](.github/SECURITY.md)
- [Contributing](CONTRIBUTING.md)

## License

[MIT](LICENSE)
