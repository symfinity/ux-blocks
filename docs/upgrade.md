# Upgrade guide

## First public release (`v0.1.0`)

This is the first tagged release on Packagist and the read-only split mirror. There is no migration from a prior semver line.

### Install

```bash
composer require symfinity/ux-blocks:^0.1
```

Ensure the [symfinity/recipes](https://github.com/symfinity/recipes) Flex endpoint is configured so the install recipe runs.

### What you get

- Symfony bundle registered for all environments
- Registry schema `1.1` with default prefix `blocks`
- Tier role catalog classes (`CoreRoleCatalog`, `ExtendedRoleCatalog`, …)
- `BlocksMarkupAssertions` PHPUnit trait for component DOM tests
- Consumer handbook under `docs/`

### Pair with tier packages

This SDK does not render UI. Require tier packages separately, for example:

```bash
composer require symfinity/ux-blocks-core
composer require symfinity/ui-kernel
```

### After upgrading

1. Use `RegistrySchema::fragmentId()` in tests and tooling — [Quick start](quickstart.md)
2. Confirm `composer show symfinity/ux-blocks` reports `^0.1`
3. Clear Symfony cache in each environment after adding tier bundles

## 0.1.4

Patch release after [v0.1.3](https://github.com/symfinity/ux-blocks/releases/tag/v0.1.3). Handbook and README install-profile docs aligned with `symfinity/ux-blocks-full` — no PHP API changes.

```bash
composer update symfinity/ux-blocks
```

After upgrade:

1. Prefer `composer require symfinity/ux-blocks-full` when you need core, form, extended, interactive, and live tiers together — see [Installation — install profiles](installation.md#install-profiles).
2. Add `symfinity/ui-kernel` alongside full when you want Chameleon styling on the complete catalog.

## 0.1.5

Maintenance release — public roadmap, sponsorship metadata, and split-mirror CI reliability. No registry schema, catalog, or PHPUnit API changes.

```bash
composer update symfinity/ux-blocks
```

No config or template changes required after upgrade.

## 0.1.3

Patch release after [v0.1.2](https://github.com/symfinity/ux-blocks/releases/tag/v0.1.2). Adds `search-form` to `ExtendedRoleCatalog` (21 extended-tier role ids) — aligns SDK catalog with `symfinity/ux-blocks-extended` `config/ux_roles.yaml`.

```bash
composer update symfinity/ux-blocks
```

After upgrade:

1. Bump tier constraints when you consume `SearchForm`: `symfinity/ux-blocks-extended` `^0.1.1` and `symfinity/ux-blocks` `^0.1.3`.
2. Clear Symfony cache if you cache role catalog lists in dev.

## 0.1.2

Patch release after [v0.1.1](https://github.com/symfinity/ux-blocks/releases/tag/v0.1.1). PHPUnit trait rename and handbook updates — no registry schema changes.

```bash
composer update symfinity/ux-blocks
```

After upgrade:

1. In tests, replace `ChameleonMarkupAssertions` with `BlocksMarkupAssertions` (`Symfinity\UxBlocks\Test\BlocksMarkupAssertions`).
2. Rename test imports from `ChameleonMarkupAssertionsTest` to `BlocksMarkupAssertionsTest` if you extended the example test.

## 0.1.1

Documentation patch after [v0.1.0](https://github.com/symfinity/ux-blocks/releases/tag/v0.1.0). README install profiles — no PHP API changes.

```bash
composer update symfinity/ux-blocks
```

## Future releases

See [CHANGELOG](https://github.com/symfinity/ux-blocks/blob/main/CHANGELOG.md) for version-to-version notes.
