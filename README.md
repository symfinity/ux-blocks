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

- **Registry schema** — version `1.4` with default fragment prefix `blocks`, modifier/scalar lexicons, and closed region vocabulary
- **Composition language** — `CompositionLanguage`, `RoleLanguageDefinition`, and `LanguageConformance` helpers for tier registry rows
- **Tier role catalogs** — canonical role lists for core, extended, interactive, live, marketing, ecommerce, and lab packages
- **PHPUnit assertions** — `ChameleonMarkupAssertions` for `data-ui-role` and `data-ui-fragment` DOM checks
- **Slim SDK boundary** — no Twig components here; tiers ship in `symfinity/ux-blocks-*` packages
- **Symfony Flex recipe** — bundle registered for all environments

## Prerequisites

Add the [symfinity/recipes](https://github.com/symfinity/recipes) Flex endpoint to your project's `composer.json` (see [recipes README](https://github.com/symfinity/recipes/blob/main/README.md)) — recipes are not in Symfony's official recipe repository yet.

## Installation

```bash
composer require symfinity/ux-blocks
```

Usually installed as a dependency of a tier package. See [Installation](docs/installation.md).

## Quick start

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

## Install profiles

Pick the smallest profile that fits your app. **Marketing, ecommerce, and lab tiers are explicit opt-in** — they are never pulled in by `symfinity/ux-blocks-full`.

| Profile | `composer require` | Use case |
|---------|--------------------|----------|
| **Headless atoms** | `symfinity/ux-blocks-core` | Custom CSS; no Chameleon theme |
| **Headless + forms** | `symfinity/ux-blocks-core symfinity/ux-blocks-form` | Atoms plus form controls |
| **Chameleon app** | `symfinity/ui-kernel symfinity/ux-blocks-core` | Symfinity default styled atoms |
| **Chameleon app + forms** | `symfinity/ui-kernel symfinity/ux-blocks-core symfinity/ux-blocks-form` | Typical CRUD apps |
| **Full app UI** | `symfinity/ux-blocks-full` | Admin/product shell (core + form + extended + interactive) |
| **Full + live** | `symfinity/ux-blocks-full symfinity/ux-blocks-live` | Adds Turbo/LiveComponent tier |
| **Vertical landing** | `… symfinity/ux-blocks-marketing` | Marketing sections — **opt-in** |
| **Vertical shop** | `… symfinity/ux-blocks-ecommerce` | Shop sections — **opt-in** |
| **Incubator lab** | `symfinity/ux-blocks-lab` | Experimental roles — **not** for production defaults |

```bash
# Headless atoms
composer require symfinity/ux-blocks-core

# Chameleon-styled app
composer require symfinity/ui-kernel symfinity/ux-blocks-core

# Full app UI (core + extended + interactive)
composer require symfinity/ux-blocks-full
```

- `data-ui-role` is injected by the role attribute bridge (core tier and above).
- `data-ui-fragment` is **off** by default; enable in tier config when you need Turbo/runtime fragment ids.

## Documentation

- **[Quick start](docs/quickstart.md)** — registry helpers and test trait
- **[Installation](docs/installation.md)** — Flex and manual setup
- **[Configuration](docs/configuration.md)** — no app YAML required
- **[Usage](docs/usage.md)** — registry helpers and PHPUnit assertions
- **[Registry](docs/registry.md)** — schema, catalogs, markup contract
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
