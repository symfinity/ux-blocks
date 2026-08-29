# Usage

Registry helpers and test assertions after [Installation](installation.md).

## Registry in PHP

```php
use Symfinity\UxBlocks\Registry\CoreRoleCatalog;
use Symfinity\UxBlocks\Registry\RegistrySchema;

$fragment = RegistrySchema::fragmentId('button'); // blocks.button
$roles = CoreRoleCatalog::roles();
```

Tier packages ship their own role catalogs (`ExtendedRoleCatalog`, …). Export inventory markers with `vendor/bin/console ux-blocks:registry-export` in the owning tier package.

## PHPUnit markup assertions

```php
use Symfinity\UxBlocks\Test\BlocksMarkupAssertions;

BlocksMarkupAssertions::assertRole($crawler, 'button');
BlocksMarkupAssertions::assertFragment($crawler, 'blocks.button');
```

Use in tier package tests — see [Quick start](quickstart.md).

## Install profiles

Pick the smallest tier set for your app. See README **Install profiles** and [Registry](registry.md) for Stage A headless vs full catalog depth.

## See also

- [Components](components.md) — tier package map
- [Configuration](configuration.md) — bundle options (minimal in v0.1)
