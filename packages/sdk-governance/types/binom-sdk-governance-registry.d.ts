import { BnGovernanceDetector, BnGovernanceDetectorDef } from '@binom/sdk-governance/types';

declare function registerDetector(detector: BnGovernanceDetector): void;
declare function getDetector(id: string): BnGovernanceDetector | undefined;
declare function getRegisteredDetectors(): BnGovernanceDetector[];
declare function createDetector(def: BnGovernanceDetectorDef): BnGovernanceDetector;
declare function resetDetectorRegistry(forTesting?: boolean): void;

export { createDetector, getDetector, getRegisteredDetectors, registerDetector, resetDetectorRegistry };
