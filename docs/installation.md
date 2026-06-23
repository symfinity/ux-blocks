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

## Install profiles {#install-profiles}

Pick the smallest profile that fits your app. **Marketing, ecommerce, and lab tiers are explicit opt-in** — they are never pulled in by `symfinity/ux-blocks-full`.

| Profile | `composer require` | Use case |
|---------|--------------------|----------|
| **Headless atoms** | `symfinity/ux-blocks-core` | Custom CSS; no UI Kernel theme |
| **Headless + forms** | `symfinity/ux-blocks-core symfinity/ux-blocks-form` | Atoms plus form controls |
| **Kernel-styled app** | `symfinity/ui-kernel symfinity/ux-blocks-core` | Symfinity default styled atoms |
| **Kernel-styled app + forms** | `symfinity/ui-kernel symfinity/ux-blocks-core symfinity/ux-blocks-form` | Typical CRUD apps |
| **Full app UI** | `symfinity/ux-blocks-full` | Admin/product shell (core + form + extended + interactive) |
| **Full + live** | `symfinity/ux-blocks-full symfinity/ux-blocks-live` | Adds Turbo/LiveComponent tier |
| **Vertical landing** | `… symfinity/ux-blocks-marketing` | Marketing sections — **opt-in** |
| **Vertical shop** | `… symfinity/ux-blocks-ecommerce` | Shop sections — **opt-in** |
| **Incubator lab** | `symfinity/ux-blocks-lab` | Experimental roles — **not** for production defaults |

```bash
# Headless atoms
composer require symfinity/ux-blocks-core

# Kernel-styled app
composer require symfinity/ui-kernel symfinity/ux-blocks-core

# Full app UI (core + extended + interactive)
composer require symfinity/ux-blocks-full
```

- `data-ui-role` is injected by the role attribute bridge (core tier and above).
- `data-ui-fragment` is **off** by default; enable in tier config when you need Turbo/runtime fragment ids.

## Next steps

- [Quick start](quickstart.md) — registry helpers and test assertions
- [Components](components.md) — tier packages and role catalogs
