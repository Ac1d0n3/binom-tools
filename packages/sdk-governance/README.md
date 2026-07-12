# @binom/sdk-governance

Framework-independent governance SDK for Binom — PII detection, sanitization, masking, and restore.

## Documentation

Browse interactive docs, API reference, installation guides, and live demos on [ngx-docs.binom.net](https://ngx-docs.binom.net/sdk/sdk-governance).

## Installation

```bash
npm install @binom/sdk-governance
```

## Peer Dependencies

Make sure the following peer dependencies are installed in your Angular 21 project:

- None

## Entry Points

This library provides the following secondary entry points:

| Entry Point | Import Path |
|-------------|-------------|
| Core | `@binom/sdk-governance/core` |
| Detectors | `@binom/sdk-governance/detectors` |
| Registry | `@binom/sdk-governance/registry` |
| Sanitizer | `@binom/sdk-governance/sanitizer` |
| Types | `@binom/sdk-governance/types` |
| Validators | `@binom/sdk-governance/validators` |

## Usage

Import from the main entry point or a secondary entry point:

```typescript
import { /* ... */ } from '@binom/sdk-governance';
// or
import { /* ... */ } from '@binom/sdk-governance/core';
```

## Support

- **Website:** [binom.net](https://binom.net)
- **Documentation:** [ngx-docs.binom.net](https://ngx-docs.binom.net/sdk/sdk-governance)
- **Bug reports & feature requests:** [GitHub Issues](https://github.com/Ac1d0n3/binom-ngx-docs/issues)

## Development

Run unit tests:

```bash
nx test sdk-governance
```
