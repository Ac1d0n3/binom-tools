/** Shared reference model used across all dbt PII governance tool outputs. */

export const REFERENCE_MODEL_NAME = 'example_table';
export const REFERENCE_SOURCE_TABLE = 'raw.example_table';
export const REFERENCE_PII_VERSION = 'cf38c9353be46d305f35c22a8d926c62';

/** @type {string[]} */
export const REFERENCE_ACCESS_ROLES = ['analyst', 'support', 'admin', 'dpo', 'security', 'fulfillment'];

/** @type {string[]} */
export const REFERENCE_MODEL_ACCESS_GROUPS = ['analyst', 'dpo'];
