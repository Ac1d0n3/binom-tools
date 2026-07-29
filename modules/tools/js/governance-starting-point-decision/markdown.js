/**
 * @param {ReturnType<import('./model.js').createEmptyState>} state
 * @param {(key: string) => string} t
 * @param {(id: string) => string} productLabel
 */
export function buildMarkdown(state, t, productLabel) {
    const lines = [];
    const push = (s = '') => lines.push(s);
    const field = (label, value) => {
        const v = String(value ?? '').trim();
        if (!v) return;
        push(`- **${label}:** ${v}`);
    };
    const section = (title) => {
        push('');
        push(`## ${title}`);
        push('');
    };

    push(`# ${t('gspd.md.title')}`);
    push('');
    push(`- **${t('gspd.product')}:** ${state.product ? productLabel(state.product) : '—'}`);
    push(`- **${t('gspd.md.generated')}:** ${new Date().toISOString().slice(0, 10)}`);

    section(t('gspd.section.context'));
    field(t('gspd.context.title'), state.context.title);
    field(t('gspd.context.firstUseCase'), state.context.firstUseCase);
    field(t('gspd.context.existingContext'), state.context.existingContext);
    field(t('gspd.context.decisionGoal'), state.context.decisionGoal);
    field(t('gspd.context.decisionOwner'), state.context.decisionOwner);
    field(t('gspd.context.dataOwner'), state.context.dataOwner);
    field(t('gspd.context.dataSteward'), state.context.dataSteward);
    field(t('gspd.context.technicalOwner'), state.context.technicalOwner);
    field(t('gspd.context.reviewDate'), state.context.reviewDate);

    section(t('gspd.section.design'));
    for (const [areaId, area] of Object.entries(state.areas)) {
        push(`### ${t(`gspd.area.${areaId}`)}`);
        push('');
        field(t('gspd.area.status'), t(`gspd.areaStatus.${area.status}`));
        field(t('gspd.area.description'), area.description);
        field(t('gspd.area.owner'), area.owner);
        field(t('gspd.area.evidence'), area.evidence);
        field(t('gspd.area.gap'), area.gap);
        push('');
    }

    if (state.product && state.product !== 'multiple') {
        section(t('gspd.section.productFields'));
        const fields = state.productFields[state.product] || {};
        for (const [key, value] of Object.entries(fields)) {
            field(t(`gspd.productField.${state.product}.${key}`), value);
        }
    }

    if (state.product === 'multiple') {
        section(t('gspd.section.multiple'));
        if (state.multipleRows.length === 0) {
            push(`_${t('gspd.multiple.empty')}_`);
        } else {
            state.multipleRows.forEach((row, index) => {
                push(`### ${t('gspd.multiple.row')} ${index + 1}`);
                push('');
                for (const [key, value] of Object.entries(row)) {
                    field(t(`gspd.multiple.${key}`), value);
                }
                push('');
            });
        }
    }

    section(t('gspd.section.gaps'));
    const listKeys = [
        ['knownGaps', 'gspd.list.knownGaps'],
        ['requiredTests', 'gspd.list.requiredTests'],
        ['openQuestions', 'gspd.list.openQuestions'],
        ['blockers', 'gspd.list.blockers'],
        ['exceptions', 'gspd.list.exceptions'],
        ['evidenceLinks', 'gspd.list.evidenceLinks'],
    ];
    for (const [key, labelKey] of listKeys) {
        const items = state.lists[key] || [];
        const filled = items.map((i) => String(i).trim()).filter(Boolean);
        if (filled.length === 0) continue;
        push(`### ${t(labelKey)}`);
        push('');
        filled.forEach((item) => push(`- ${item}`));
        push('');
    }
    field(t('gspd.exception.owner'), state.exceptionMeta.exceptionOwner);
    field(t('gspd.exception.expiry'), state.exceptionMeta.expiryDate);

    section(t('gspd.section.decision'));
    field(t('gspd.decision.status'), t(`gspd.decisionStatus.${state.decision.status}`));
    field(t('gspd.decision.preferredStartingPattern'), state.decision.preferredStartingPattern);
    field(t('gspd.decision.conditionalAlternative'), state.decision.conditionalAlternative);
    field(t('gspd.decision.noNewPlatformAlternative'), state.decision.noNewPlatformAlternative);
    field(t('gspd.decision.blockers'), state.decision.blockers);
    field(t('gspd.decision.validationPlan'), state.decision.validationPlan);
    field(t('gspd.decision.noRegretNextStep'), state.decision.noRegretNextStep);
    field(t('gspd.decision.decisionRationale'), state.decision.decisionRationale);
    field(t('gspd.decision.implementationOwner'), state.decision.implementationOwner);
    field(t('gspd.decision.approvalOwner'), state.decision.approvalOwner);
    field(t('gspd.decision.reviewDate'), state.decision.reviewDate);

    return `${lines.join('\n').trim()}\n`;
}

/**
 * Compact report block for Hub Gesamt-Report / session payload.
 * @param {ReturnType<import('./model.js').createEmptyState>} state
 * @param {(key: string) => string} t
 * @param {(id: string) => string} productLabel
 * @returns {Record<string, unknown>|null}
 */
export function buildReportBlock(state, t, productLabel) {
    const list = (key) => (state.lists[key] || []).map((item) => String(item).trim()).filter(Boolean);
    const knownGaps = list('knownGaps');
    const openQuestions = list('openQuestions');
    const blockers = list('blockers');
    const product = String(state.product || '').trim();
    const title = String(state.context.title || '').trim();
    const preferred = String(state.decision.preferredStartingPattern || '').trim();
    const rationale = String(state.decision.decisionRationale || '').trim();
    const nextStep = String(state.decision.noRegretNextStep || '').trim();
    const meaningful = Boolean(
        product
        || title
        || preferred
        || rationale
        || nextStep
        || knownGaps.length
        || openQuestions.length
        || blockers.length,
    );
    if (!meaningful) {
        return null;
    }

    return {
        product,
        productLabel: product ? productLabel(product) : '',
        title,
        firstUseCase: String(state.context.firstUseCase || '').trim(),
        decisionStatus: String(state.decision.status || 'draft'),
        preferredStartingPattern: preferred,
        decisionRationale: rationale,
        noRegretNextStep: nextStep,
        knownGaps,
        openQuestions,
        blockers,
        markdown: buildMarkdown(state, t, productLabel),
        updatedAt: new Date().toISOString(),
    };
}
