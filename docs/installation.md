# Installation

## Prerequisites

Add the [symfinity/recipes](https://github.com/symfinity/recipes) Flex endpoint to your project's `composer.json` (see [recipes README](https://github.com/symfinity/recipes/blob/main/README.md)).

## Composer

```bash
composer require symfinity/ux-blocks
```

This package is typically pulled in automatically when you require a tier package such as `symfinity/ux-blocks-core`.

## Symfony Flex

The recipe applies:

- Bundle registration for **all** environments (`all`)

There is no app config file to copy — the bundle only loads internal services. See [Configuration](configuration.md).

## Manual installation

When Flex is unavailable:

1. `composer require symfinity/ux-blocks`
2. Register `Symfinity\UxBlocks\SymfinityUxBlocksBundle` in `config/bundles.php` for all environments

## Verify installation

```bash
php bin/console debug:container --tag=container.service_locator | grep -i uxblocks || true
php bin/console about
```

The bundle has no public console commands. Confirm the package is present in `composer show symfinity/ux-blocks`.

## Next steps

- [Quick start](quickstart.md) — registry helpers and test assertions
- [Components](components.md) — tier packages and role catalogs
