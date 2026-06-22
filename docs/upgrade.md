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
- `ChameleonMarkupAssertions` PHPUnit trait for component DOM tests
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

## Future releases

See [CHANGELOG](https://github.com/symfinity/ux-blocks/blob/main/CHANGELOG.md) for version-to-version notes.
