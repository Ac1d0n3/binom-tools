import { getAllBuiltinDetectors, scanRegex } from '@binom/sdk-governance/detectors';

const registry = new Map();
function ensureBuiltins() {
    if (registry.size > 0) {
        return;
    }
    for (const detector of getAllBuiltinDetectors()) {
        registry.set(detector.id, detector);
    }
}
function registerDetector(detector) {
    ensureBuiltins();
    registry.set(detector.id, detector);
}
function getDetector(id) {
    ensureBuiltins();
    return registry.get(id);
}
function getRegisteredDetectors() {
    ensureBuiltins();
    return Array.from(registry.values());
}
function createDetector(def) {
    const priority = def.priority ?? 50;
    const confidence = def.confidence ?? 0.75;
    if (def.detect) {
        return {
            id: def.id,
            priority,
            detect: def.detect,
            validate: def.validate,
        };
    }
    if (!def.pattern) {
        throw new Error(`[createDetector] Detector "${def.id}" requires pattern or detect function`);
    }
    const pattern = def.pattern;
    return {
        id: def.id,
        priority,
        validate: def.validate,
        detect: (input, options) => scanRegex(input, def.id, pattern, confidence, def.validate, options),
    };
}
function resetDetectorRegistry(forTesting = false) {
    registry.clear();
    if (!forTesting) {
        ensureBuiltins();
    }
}

/**
 * Generated bundle index. Do not edit.
 */

export { createDetector, getDetector, getRegisteredDetectors, registerDetector, resetDetectorRegistry };
//# sourceMappingURL=binom-sdk-governance-registry.mjs.map
