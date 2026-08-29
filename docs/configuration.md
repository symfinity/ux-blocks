# Configuration

**symfinity/ux-blocks** has no application-level configuration file.

## What the bundle loads

On boot, `SymfinityUxBlocksExtension` loads `config/services.yaml` from the package. Services use Symfony defaults (`autowire`, `autoconfigure`). There are no public parameters or env vars.

## App config

You do **not** add `config/packages/symfinity_ux_blocks.yaml` unless you maintain a private fork. The Flex recipe registers the bundle only.

## Related packages

| Package | Configuration |
|---------|----------------|
| [symfinity/ui-kernel](https://github.com/symfinity/ui-kernel) | `symfinity_ui_kernel` — themes and generated CSS |
| [symfinity/ux-blocks-core](https://github.com/symfinity/ux-blocks-core) (and other tiers) | Component defaults in each tier bundle |

## See also

- [Installation](installation.md)
- [Registry](registry.md)
