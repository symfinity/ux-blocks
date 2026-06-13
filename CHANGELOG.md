# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.1.0] - 2026-06-14

### Added

- Initial release of UX Blocks SDK bundle for Symfony
- Registry schema version `1.1` with default fragment prefix `blocks`
- Fragment helpers: `RegistrySchema::fragmentId()`, `RegistrySchema::fragmentPattern()`
- Tier role catalog classes: `CoreRoleCatalog`, `ExtendedRoleCatalog`, `InteractiveRoleCatalog`, `LiveRoleCatalog`, `MarketingRoleCatalog`, `EcommerceRoleCatalog`, `LabRoleCatalog`
- PHPUnit trait `ChameleonMarkupAssertions` for `data-ui-role` and `data-ui-fragment` DOM assertions
- Symfony Flex recipe `0.1` — bundle registered for all environments
- Consumer handbook under `docs/`
- Symfony 7.4 and 8.x compatibility (PHP 8.2+)

### Notes

- This package is the SDK only — Twig components ship in separate `symfinity/ux-blocks-*` tier packages.
- Split mirror CI: PHP 8.2–8.5 × Symfony 7.4 / 8.0 / 8.1 (see `.github/workflows/ci.yml`).
