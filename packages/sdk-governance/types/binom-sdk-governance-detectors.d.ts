import { BnGovernanceDetectorId, BnGovernanceDetector, BnDetectorOptions, BnGovernanceFinding, BnGovernanceConfig } from '@binom/sdk-governance/types';

declare function createBuiltinDetector(id: BnGovernanceDetectorId): BnGovernanceDetector;
declare function getAllBuiltinDetectors(): BnGovernanceDetector[];

declare function resetFindingCounter(): void;
declare function createFindingId(): string;
declare function scanRegex(input: string, type: BnGovernanceDetectorId | string, pattern: RegExp, confidence: number, validate?: (value: string) => boolean, options?: BnDetectorOptions): BnGovernanceFinding[];
declare function isDetectorEnabled(detectors: Partial<Record<BnGovernanceDetectorId, boolean | BnDetectorOptions>> | undefined, id: BnGovernanceDetectorId): boolean;
declare function getDetectorOptions(detectors: Partial<Record<BnGovernanceDetectorId, boolean | BnDetectorOptions>> | undefined, id: BnGovernanceDetectorId): BnDetectorOptions | undefined;
declare function mergeConfig(base: BnGovernanceConfig, override?: Partial<BnGovernanceConfig>): BnGovernanceConfig;

export { createBuiltinDetector, createFindingId, getAllBuiltinDetectors, getDetectorOptions, isDetectorEnabled, mergeConfig, resetFindingCounter, scanRegex };
