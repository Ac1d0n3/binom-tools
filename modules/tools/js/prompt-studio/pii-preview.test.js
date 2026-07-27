import { describe, it, expect } from 'vitest';
import { PiiPreview } from './pii-preview.js';

describe('PiiPreview', () => {
    it('does not flag English list items as license plates', () => {
        const preview = new PiiPreview();
        const prompt = `You are an experienced Business Analyst facilitating creative brainstorming.

Deliver:
1. Brainstorming prompts and rules
2. At least 15 diverse ideas (grouped by theme)
3. Top 5 ideas with brief rationale
4. Next steps for evaluation`;

        const result = preview.scan(prompt);
        const licensePlates = result.findings.filter((f) => f.type === 'licensePlate');

        expect(licensePlates).toEqual([]);
    });

    it('still detects real license plates', () => {
        const preview = new PiiPreview();
        const result = preview.scan('Vehicle B-AB 1234 was seen near the office.');

        expect(result.findings.some((f) => f.type === 'licensePlate')).toBe(true);
    });
});
