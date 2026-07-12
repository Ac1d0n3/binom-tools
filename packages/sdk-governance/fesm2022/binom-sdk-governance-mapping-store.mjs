function isMappingExpired(mapping, referenceDate = new Date()) {
    const expiresAt = mapping.meta?.expiresAt;
    if (!expiresAt) {
        return false;
    }
    return referenceDate.getTime() >= Date.parse(expiresAt);
}
class BnInMemoryGovernanceMappingStore {
    entries = new Map();
    defaultTtlMs;
    maxEntries;
    constructor(config) {
        this.defaultTtlMs = config?.defaultTtlMs;
        this.maxEntries = config?.maxEntries;
    }
    save(mapping) {
        const createdAt = mapping.meta.createdAt ?? new Date().toISOString();
        const expiresAt = mapping.meta.expiresAt ??
            (this.defaultTtlMs
                ? new Date(Date.parse(createdAt) + this.defaultTtlMs).toISOString()
                : undefined);
        const normalized = {
            ...mapping,
            meta: {
                ...mapping.meta,
                createdAt,
                expiresAt,
            },
        };
        this.entries.set(normalized.id, normalized);
        this.enforceMaxEntries();
    }
    get(id) {
        const mapping = this.entries.get(id);
        if (!mapping) {
            return undefined;
        }
        if (isMappingExpired(mapping)) {
            return undefined;
        }
        return mapping;
    }
    delete(id) {
        this.entries.delete(id);
    }
    clearExpiredMappings(referenceDate = new Date()) {
        let removed = 0;
        for (const [id, mapping] of this.entries.entries()) {
            if (isMappingExpired(mapping, referenceDate)) {
                this.entries.delete(id);
                removed += 1;
            }
        }
        return removed;
    }
    listIds() {
        return [...this.entries.keys()];
    }
    enforceMaxEntries() {
        if (!this.maxEntries || this.entries.size <= this.maxEntries) {
            return;
        }
        const sorted = [...this.entries.values()].sort((a, b) => Date.parse(a.meta.createdAt) - Date.parse(b.meta.createdAt));
        while (this.entries.size > this.maxEntries) {
            const oldest = sorted.shift();
            if (!oldest) {
                break;
            }
            this.entries.delete(oldest.id);
        }
    }
}
function createMappingStore(config) {
    return new BnInMemoryGovernanceMappingStore(config);
}
function clearExpiredMappings(store, referenceDate) {
    return store.clearExpiredMappings(referenceDate);
}

/**
 * Generated bundle index. Do not edit.
 */

export { BnInMemoryGovernanceMappingStore, clearExpiredMappings, createMappingStore };
//# sourceMappingURL=binom-sdk-governance-mapping-store.mjs.map
