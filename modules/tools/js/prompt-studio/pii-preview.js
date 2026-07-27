import { BnGovernanceSanitizerSvc } from '@binom/sdk-governance/sanitizer';

/**
 * Inline PII scan preview using BnGovernanceSanitizerSvc.
 */
export class PiiPreview {
    /** @param {{ reusePlaceholders?: boolean }} [options] */
    constructor(options = {}) {
        this.sanitizer = new BnGovernanceSanitizerSvc({
            reusePlaceholders: options.reusePlaceholders ?? true,
        });
        /** @type {import('@binom/sdk-governance/types').BnGovernanceFinding[]} */
        this.findings = [];
        this.sanitizedText = '';
    }

    /** @param {string} text */
    scan(text) {
        if (!text.trim()) {
            this.findings = [];
            this.sanitizedText = '';
            return { findings: [], sanitizedText: '', findingCount: 0 };
        }

        const result = this.sanitizer.sanitize(text);
        this.findings = result.findings;
        this.sanitizedText = result.text;

        return {
            findings: this.findings,
            sanitizedText: this.sanitizedText,
            findingCount: this.findings.length,
        };
    }

    hasFindings() {
        return this.findings.length > 0;
    }

    findingCount() {
        return this.findings.length;
    }

    /**
     * @param {(key: string) => string} tr
     * @returns {string}
     */
    renderHtml(tr) {
        if (!this.hasFindings()) {
            return `<p class="prompt-studio__pii-empty">${tr('promptStudio.pii.none')}</p>`;
        }

        const rows = this.findings
            .map((finding) => {
                const typeLabel = tr(`promptStudio.pii.types.${finding.type}`) || finding.type;
                const confidence =
                    typeof finding.confidence === 'number' ? `${Math.round(finding.confidence * 100)}%` : '—';
                return `<tr>
                    <td>${escapeHtml(typeLabel)}</td>
                    <td><code>${escapeHtml(finding.value)}</code></td>
                    <td>${confidence}</td>
                </tr>`;
            })
            .join('');

        return `<div class="prompt-studio__pii-summary">${this.findingCount()} ${tr('promptStudio.pii.summary')}</div>
            <table class="prompt-studio__pii-table">
                <thead>
                    <tr>
                        <th>${escapeHtml(tr('promptStudio.pii.type'))}</th>
                        <th>${escapeHtml(tr('promptStudio.pii.value'))}</th>
                        <th>${escapeHtml(tr('promptStudio.pii.confidence'))}</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
            <p class="prompt-studio__pii-hint">${escapeHtml(tr('promptStudio.pii.hint'))}</p>`;
    }
}

/**
 * @param {string} value
 * @returns {string}
 */
function escapeHtml(value) {
    return value
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;');
}

export function createPiiPreview(options) {
    return new PiiPreview(options);
}
