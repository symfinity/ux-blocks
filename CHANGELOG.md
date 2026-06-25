# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.1.2] - 2026-06-25

### Changed

- **PHPUnit trait** — `ChameleonMarkupAssertions` renamed to `BlocksMarkupAssertions` (same `data-ui-role` and `data-ui-fragment` DOM assertions)
- **PHPUnit test** — `ChameleonMarkupAssertionsTest` renamed to `BlocksMarkupAssertionsTest`
- **Handbook** — new [docs/installation.md](docs/installation.md); README and docs cross-links refreshed (`components`, `quickstart`, `registry`, `upgrade`, `usage`)

### Notes

- No registry schema or public PHP API changes — rename and documentation patch
- Update test imports: `use Symfinity\UxBlocks\Test\BlocksMarkupAssertions;`

## [0.1.1] - 2026-06-23

### Changed

- README updated to consumer install profiles and handbook links

### Notes

- No functional or registry API changes in this release

## [0.1.0] - 2026-06-14

### Added

- Initial release of UX Blocks SDK bundle for Symfony
- Registry schema version `1.1` with default fragment prefix `blocks`
- Fragment helpers: `RegistrySchema::fragmentId()`, `RegistrySchema::fragmentPattern()`
- Tier role catalog classes: `CoreRoleCatalog`, `ExtendedRoleCatalog`, `InteractiveRoleCatalog`, `LiveRoleCatalog`, `MarketingRoleCatalog`, `EcommerceRoleCatalog`, `LabRoleCatalog`
- PHPUnit trait `BlocksMarkupAssertions` for `data-ui-role` and `data-ui-fragment` DOM assertions
- Symfony Flex recipe `0.1` — bundle registered for all environments
- Consumer handbook under `docs/`
- Symfony 7.4 and 8.x compatibility (PHP 8.2+)

### Notes

- This package is the SDK only — Twig components ship in separate `symfinity/ux-blocks-*` tier packages
