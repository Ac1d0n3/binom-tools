import { BnGovernanceMappingStore, BnGovernanceMapping } from '@binom/sdk-governance/types';

interface BnGovernanceMappingStoreConfig {
    defaultTtlMs?: number;
    maxEntries?: number;
}
declare class BnInMemoryGovernanceMappingStore implements BnGovernanceMappingStore {
    private readonly entries;
    private readonly defaultTtlMs?;
    private readonly maxEntries?;
    constructor(config?: BnGovernanceMappingStoreConfig);
    save(mapping: BnGovernanceMapping): void;
    get(id: string): BnGovernanceMapping | undefined;
    delete(id: string): void;
    clearExpiredMappings(referenceDate?: Date): number;
    listIds(): string[];
    private enforceMaxEntries;
}
declare function createMappingStore(config?: BnGovernanceMappingStoreConfig): BnGovernanceMappingStore;
declare function clearExpiredMappings(store: BnGovernanceMappingStore, referenceDate?: Date): number;

export { BnInMemoryGovernanceMappingStore, clearExpiredMappings, createMappingStore };
export type { BnGovernanceMappingStoreConfig };
