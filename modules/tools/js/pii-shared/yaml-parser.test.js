import { describe, expect, it } from 'vitest';
import { parseDbtSchemaYaml, parseDbtSchemaYamlResult } from './yaml-parser.js';

const validYaml = `version: 2

models:
  - name: example_table
    description: |
      This model contains data from the 'raw.example_table' table.
      PII details version: cf38c9353be46d305f35c22a8d926c62
      Access mode: role-based
    columns:
      - name: email
        description: Email address
        meta:
          pii_details:
            category: email.internal
            access_roles: [analyst]
`;

describe('parseDbtSchemaYamlResult', () => {
    it('parses valid dbt schema yaml', () => {
        const result = parseDbtSchemaYamlResult(validYaml);
        expect(result.ok).toBe(true);
        if (result.ok) {
            expect(result.state.modelName).toBe('example_table');
            expect(result.state.columns).toHaveLength(1);
        }
    });

    it('returns empty error for blank input', () => {
        const result = parseDbtSchemaYamlResult('   ');
        expect(result.ok).toBe(false);
        if (!result.ok) {
            expect(result.error.code).toBe('empty');
        }
    });

    it('returns missing_model when no model name exists', () => {
        const result = parseDbtSchemaYamlResult(`version: 2\nmodels:\n  description: test\n`);
        expect(result.ok).toBe(false);
        if (!result.ok) {
            expect(result.error.code).toBe('missing_model');
        }
    });

    it('returns missing_columns when model has no columns', () => {
        const result = parseDbtSchemaYamlResult(`version: 2\nmodels:\n  - name: example_table\n`);
        expect(result.ok).toBe(false);
        if (!result.ok) {
            expect(result.error.code).toBe('missing_columns');
        }
    });

    it('returns unsupported for sources-only yaml', () => {
        const result = parseDbtSchemaYamlResult(`version: 2\nsources:\n  - name: raw\n`);
        expect(result.ok).toBe(false);
        if (!result.ok) {
            expect(result.error.code).toBe('unsupported');
        }
    });
});

describe('parseDbtSchemaYaml', () => {
    it('returns null on failure for backward compatibility', () => {
        expect(parseDbtSchemaYaml('')).toBeNull();
    });
});
