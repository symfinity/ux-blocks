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
> **Read-only mirror.** See [CONTRIBUTING.md](CONTRIBUTING.md).

## Features

- **Registry schema** — version `1.1`, default prefix `blocks`, fragment id helpers
- **Tier role catalogs** — canonical role lists for core, extended, interactive, live, marketing, ecommerce, and lab packages
- **PHPUnit assertions** — `ChameleonMarkupAssertions` for `data-ui-role` / `data-ui-fragment` DOM checks
- **Slim SDK boundary** — no Twig components here; tiers ship in `symfinity/ux-blocks-*` packages
- **Symfony Flex recipe** — bundle registered for all environments

## Prerequisites

Add the [symfinity/recipes](https://github.com/symfinity/recipes) Flex endpoint to your project's `composer.json` (see [recipes README](https://github.com/symfinity/recipes/blob/main/README.md)) — recipes are not in Symfony's official recipe repository yet.

## Installation

```bash
composer require symfinity/ux-blocks
```

Usually installed as a dependency of a tier package. See [Installation](docs/installation.md).

## Quick Start

```php
use Symfinity\UxBlocks\Registry\CoreRoleCatalog;
use Symfinity\UxBlocks\Registry\RegistrySchema;

$fragment = RegistrySchema::fragmentId('button'); // blocks.button
$roles = CoreRoleCatalog::roles();
```

```bash
composer require symfinity/ux-blocks-core
```

See [Quick start](docs/quickstart.md) for PHPUnit markup assertions and tier pairing.

## Stage A consumer profile

Stage A apps use Twig tags + optional ui-kernel theme. They do **not** need workshop, catalog-audit, or runtime/Turbo fragments.

| Install | Packages |
|---------|----------|
| Headless | `symfinity/ux-blocks-core` |
| Chameleon theme | `symfinity/ui-kernel` + `symfinity/ux-blocks-core` + `symfinity/ux-blocks` |

- `data-ui-role` is injected by the role attribute bridge (wave 1: core).
- `data-ui-fragment` is **off** by default (`symfinity_ux_blocks_core.fragment_ids: false`).
- Maintainer contract: Symfinity org spec **106** Stage A consumer profile (`stage-a-consumer-contract`).
- Dogfood smoke: `make dogfood-new SLUG=ux-blocks-core-lab` then `make dogfood-serve SLUG=ux-blocks-core-lab`.

## Maintainer — registry export

```bash
# From symfinity monorepo root
php bin/console ux-blocks:registry-export --package=ux-blocks-core
php bin/console ux-blocks:registry-export --check --package=ux-blocks-core
```

SSOT: each tier `config/ux_roles.yaml`. README inventory lives between `ux-blocks:registry` markers only.

## Documentation

- **[Quick start](docs/quickstart.md)** — registry helpers and test trait
- **[Installation](docs/installation.md)** — Flex and manual setup
- **[Configuration](docs/configuration.md)** — no app YAML required
- **[Registry](docs/registry.md)** — schema, catalogs, markup contract
- **[Components](docs/components.md)** — tier packages and styling
- **[Upgrade](docs/upgrade.md)** — first release notes

## Requirements

- PHP 8.2 or higher
- Symfony 7.4 or 8.x

## Support

- [GitHub Issues](https://github.com/symfinity/ux-blocks/issues)
- [Security](.github/SECURITY.md)
- [Contributing](CONTRIBUTING.md)

## License

[MIT](LICENSE)
