# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.1.7] - 2026-11-20

### Changed

- `navbar` is a core role and is no longer listed in the extended catalog
- `page-header` is no longer listed in the extended catalog, or in any catalog in this package

### Fixed

- The CSS output normalizer rewrites single-quoted `data-ui-*` attribute values to double quotes, the same way it already rewrites unquoted values
- PHPUnit bootstrap registers this package's PSR-4 prefixes from `composer.json` after loading the monorepo or package autoload, and narrows the decoded autoload sections before reading `psr-4`

## [0.1.6] - 2026-07-05

### Added

- Handbook [Verification](docs/verification.md) — P0 integration profile, local `composer test` / `composer phpstan`, and clean-app smoke (`ux-blocks:registry-export`, `<twig:Button />` markup contract)
- `integration_profile: P0` frontmatter on handbook index (OOTB consumer experience)

### Changed

- PHPUnit bootstrap: `tests/bootstrap.php` resolves monorepo or split-mirror Composer autoload (split CI parity)

### Notes

- No registry schema, catalog class, or PHPUnit API changes — documentation and test bootstrap hygiene
- Upgrading from **0.1.5** needs no template or config edits

## [0.1.5] - 2026-06-29

### Changed

- **Split mirror CI** — Composer package cache and `GITHUB_TOKEN` authentication so GitHub Actions reliably resolves `symfinity/*` dependencies across the PHP × Symfony matrix

### Added

- **ROADMAP.md** — public milestone table for the 0.1.x → 1.0.x SDK release line
- **SUPPORTERS.md** — sponsor acknowledgment page for the split mirror ecosystem
- Composer **`funding` metadata and `.github/FUNDING.yml`** for [GitHub Sponsors](https://github.com/sponsors/serotoninja)

### Notes

- No registry schema, catalog class, or PHPUnit API changes — documentation and CI maintenance patch
- Upgrading from **0.1.4** needs no template or config edits

## [0.1.4] - 2026-06-29

### Changed

- **README** — install profiles section documents `composer require symfinity/ux-blocks-full` for the complete official catalog; links to [docs/installation.md](docs/installation.md#install-profiles) instead of monorepo-only paths
- **Handbook install profiles** — table and examples aligned with `symfinity/ux-blocks-full` (core, form, extended, interactive, live); **Full app UI + Chameleon** profile added (`symfinity/ui-kernel` + full metapackage)
- **Handbook tier links** — [docs/components.md](docs/components.md) and [docs/registry.md](docs/registry.md) use Packagist URLs for published tier packages; form tier listed alongside core and extended
- **Registry handbook** — publishing status notes for interactive, live, marketing, ecommerce, and lab tiers not yet on Packagist

### Notes

- No registry schema, catalog class, or PHPUnit API changes — documentation patch only
- Pair with `symfinity/ux-blocks-full` ^0.1 when you want the five-tier catalog in one Composer line

## [0.1.3] - 2026-06-25

### Added

- `search-form` role id in `ExtendedRoleCatalog` (21 extended-tier roles) — aligns split CI with `symfinity/ux-blocks-extended` `config/ux_roles.yaml`

### Notes

- Requires `symfinity/ux-blocks-extended` split publish that ships the `SearchForm` role — bump consumer constraint to `^0.1.3`

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
