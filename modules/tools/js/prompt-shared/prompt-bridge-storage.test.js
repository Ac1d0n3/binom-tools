import { describe, it, expect, beforeEach } from 'vitest';
import {
    saveToSanitizer,
    saveToStudio,
    loadPromptBridge,
    clearPromptBridge,
    normalizeBridgePayload,
} from '../prompt-shared/prompt-bridge-storage.js';

describe('prompt-bridge-storage', () => {
    beforeEach(() => {
        localStorage.clear();
    });

    it('round-trips studio to sanitizer and back', () => {
        saveToSanitizer({
            compiled: 'Hello John Doe',
            sections: { user: 'Hello John Doe' },
            studioContext: {
                roleId: 'business-analyst',
                taskId: 'analysis',
                modelId: 'claude',
                parameterValues: { goal: 'Test' },
            },
        });

        const toSanitizer = loadPromptBridge();
        expect(toSanitizer && 'payload' in toSanitizer).toBe(true);
        if (!toSanitizer || !('payload' in toSanitizer)) return;

        expect(toSanitizer.payload.direction).toBe('to-sanitizer');
        expect(toSanitizer.payload.prompt.compiled).toBe('Hello John Doe');

        saveToStudio({
            restored: 'Hello [[BNM_PERSON_1]]',
            outbound: 'Hello [[BNM_PERSON_1]]',
            findingCount: 1,
            studioContext: toSanitizer.payload.studioContext,
        });

        const toStudio = loadPromptBridge();
        expect(toStudio && 'payload' in toStudio).toBe(true);
        if (!toStudio || !('payload' in toStudio)) return;

        expect(toStudio.payload.direction).toBe('to-studio');
        expect(toStudio.payload.sanitizerContext?.findingCount).toBe(1);
    });

    it('normalizes corrupt payloads safely', () => {
        const normalized = normalizeBridgePayload({ version: 2, direction: 'invalid' });
        expect(normalized.version).toBe(2);
        expect(normalized.direction).toBe('to-sanitizer');
    });

    it('clears bridge storage', () => {
        saveToSanitizer({ compiled: 'x' });
        clearPromptBridge();
        expect(loadPromptBridge()).toBeNull();
    });
});
