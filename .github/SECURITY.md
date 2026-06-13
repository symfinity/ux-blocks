# Security Policy

## Supported Versions

| Version | Supported |
|---------|-----------|
| 0.1.x   | Yes       |

## Reporting a Vulnerability

If you discover a security vulnerability, **do not** open a public issue. Email **dev@symfinity.net** with:

- Type of vulnerability
- Full paths of source file(s) related to the issue
- The location of the affected code (tag, branch, commit, or URL)
- Step-by-step reproduction instructions
- Proof-of-concept or exploit code (if possible)
- Impact and plausible attack scenario

We aim to acknowledge within 48 hours and provide a detailed response within 7 days.

## Security best practices

UX Blocks is an SDK bundle — registry helpers and PHPUnit assertions, not runtime UI rendering:

1. Keep Symfony and tier packages (`symfinity/ux-blocks-*`) updated
2. Treat component Twig templates like any other user-facing markup — escape output and validate form actions in your app
3. When pairing with `symfinity/ui-action`, enforce native HTTP semantics in your component tests rather than skipping validation

## Security contact

**dev@symfinity.net**
