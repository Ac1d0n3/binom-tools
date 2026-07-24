const DEFAULT_DESCRIPTION_DE = '{baseDescription} Erweiterung: {definition}.';
const DEFAULT_DESCRIPTION_EN = '{baseDescription} Extension: {definition}.';

export function parseCsv(text) {
    const rows = [];
    let current = '';
    let row = [];
    let quoted = false;

    for (let index = 0; index < String(text ?? '').length; index += 1) {
        const char = text[index];
        const next = text[index + 1];

        if (char === '"' && quoted && next === '"') {
            current += '"';
            index += 1;
            continue;
        }

        if (char === '"') {
            quoted = !quoted;
            continue;
        }

        if (char === ',' && !quoted) {
            row.push(current.trim());
            current = '';
            continue;
        }

        if ((char === '\n' || char === '\r') && !quoted) {
            if (char === '\r' && next === '\n') {
                index += 1;
            }
            row.push(current.trim());
            if (row.some((value) => value !== '')) {
                rows.push(row);
            }
            row = [];
            current = '';
            continue;
        }

        current += char;
    }

    row.push(current.trim());
    if (row.some((value) => value !== '')) {
        rows.push(row);
    }

    if (rows.length === 0) {
        return [];
    }

    const headers = rows[0].map((header) => header.toLowerCase());
    return rows.slice(1).map((values) => Object.fromEntries(headers.map((header, index) => [header, values[index] ?? ''])));
}

export function parseFields(text) {
    return parseCsv(text)
        .map((row) => ({
            name: row.field || row.name || '',
            type: row.type || 'dimension',
            tags: row.tags || '',
        }))
        .filter((field) => field.name !== '');
}

export function parseBaseMeasures(text) {
    return parseCsv(text)
        .map((row) => ({
            name: row.name || '',
            expression: row.expression || '',
            descriptionDe: row.description_de || row.description || '',
            descriptionEn: row.description_en || row.description || '',
        }))
        .filter((measure) => measure.name !== '' && measure.expression !== '');
}

export function parseDefinitions(text) {
    return parseCsv(text)
        .map((row) => ({
            name: row.name || '',
            dimensions: splitList(row.dimensions || row.dimension || ''),
            values: splitList(row.values || row.value || ''),
            expression: row.expression || '',
            parameter: row.parameter || '',
            description: row.description || '',
        }))
        .filter((definition) => definition.name !== '' && (definition.expression !== '' || definition.dimensions.length > 0));
}

export function definitionFromDimensionValues(dimension, values, name = '') {
    const cleanDimension = String(dimension ?? '').trim();
    const cleanValues = values.map((value) => String(value ?? '').trim()).filter(Boolean);
    const label = name || [cleanDimension, cleanValues.join(' + ')].filter(Boolean).join(' ');

    return {
        name: label,
        dimensions: cleanDimension ? [cleanDimension] : [],
        values: cleanValues,
        expression: buildTableauCondition(cleanDimension, cleanValues),
        parameter: '',
        description: label,
    };
}

export function buildTableauCondition(dimension, values) {
    const cleanDimension = String(dimension ?? '').trim();
    const cleanValues = values.map((value) => String(value ?? '').trim()).filter(Boolean);

    if (cleanDimension === '' || cleanValues.length === 0) {
        return '';
    }

    if (cleanValues.length === 1) {
        return `[${cleanDimension}] = ${tableauLiteral(cleanValues[0])}`;
    }

    return `[${cleanDimension}] IN (${cleanValues.map(tableauLiteral).join(', ')})`;
}

export function buildCalculatedField(baseExpression, definition, options = {}) {
    const expression = String(baseExpression ?? '').trim();
    const condition = definition.expression || buildTableauCondition(definition.dimensions[0] || '', definition.values);

    if (expression === '') {
        return '';
    }

    if (condition === '') {
        return expression;
    }

    const aggregation = detectAggregation(expression);
    if (aggregation) {
        return `${aggregation.fn}(IF ${condition} THEN ${aggregation.inner} END)`;
    }

    if (options.useCalculatedBoolean === true) {
        return `IF ${condition} THEN ${expression} END`;
    }

    return `SUM(IF ${condition} THEN ${expression} END)`;
}

export function buildLodExpression(baseExpression, definition, dimensions = []) {
    const calc = buildCalculatedField(baseExpression, definition);
    const cleanDimensions = dimensions.map((dimension) => String(dimension ?? '').trim()).filter(Boolean);

    if (cleanDimensions.length === 0) {
        return `{ FIXED : ${calc} }`;
    }

    return `{ FIXED ${cleanDimensions.map((dimension) => `[${dimension}]`).join(', ')} : ${calc} }`;
}

export function buildTableauOutputs(state = {}) {
    const bases = parseBaseMeasures(state.baseMeasuresText).length > 0
        ? parseBaseMeasures(state.baseMeasuresText)
        : [{
            name: state.measureName || 'Sales',
            expression: state.baseExpression || 'SUM([Sales])',
            descriptionDe: state.baseDescriptionDe || 'Umsatz basierend auf SUM([Sales]).',
            descriptionEn: state.baseDescriptionEn || 'Sales based on SUM([Sales]).',
        }];

    const definitions = parseDefinitions(state.definitionsText);
    const effectiveDefinitions = definitions.length > 0
        ? definitions
        : parseCsv(state.valuesText).map((row) => definitionFromDimensionValues(row.dimension, [row.value], row.label || ''));

    const descriptionTemplateDe = state.descriptionTemplateDe || DEFAULT_DESCRIPTION_DE;
    const descriptionTemplateEn = state.descriptionTemplateEn || DEFAULT_DESCRIPTION_EN;
    const rows = [['calculation_name', 'formula', 'description_de', 'description_en', 'definition', 'dimensions', 'values']];

    const calculations = [];
    const lod = [];

    bases.forEach((base) => {
        effectiveDefinitions.forEach((definition) => {
            const name = `${base.name} - ${definition.name}`;
            const formula = buildCalculatedField(base.expression, definition);
            const descriptionDe = renderTemplate(descriptionTemplateDe, base, definition);
            const descriptionEn = renderTemplate(descriptionTemplateEn, base, definition);

            calculations.push(`${name}\n${formula}\n\nDE: ${descriptionDe}\nEN: ${descriptionEn}`);
            lod.push(`${name} LOD\n${buildLodExpression(base.expression, definition, definition.dimensions)}`);
            rows.push([
                name,
                formula,
                descriptionDe,
                descriptionEn,
                definition.name,
                definition.dimensions.join('|'),
                definition.values.join('|'),
            ]);
        });
    });

    return {
        calculations: calculations.join('\n\n---\n\n'),
        lod: lod.join('\n\n---\n\n'),
        definitions: effectiveDefinitions.map((definition) => `${definition.name}\n${definition.expression || buildTableauCondition(definition.dimensions[0] || '', definition.values)}`).join('\n\n---\n\n'),
        csv: rows.map(csvLine).join('\n'),
        rows,
    };
}

function detectAggregation(expression) {
    const match = String(expression).trim().match(/^(SUM|AVG|MIN|MAX|COUNT|COUNTD)\s*\(([\s\S]+)\)$/i);

    if (!match) {
        return null;
    }

    return {
        fn: match[1].toUpperCase(),
        inner: match[2].trim(),
    };
}

function tableauLiteral(value) {
    if (/^-?\d+(\.\d+)?$/.test(value)) {
        return value;
    }

    if (/^\[.+]$/.test(value) || /^\[.+ Parameter]$/.test(value)) {
        return value;
    }

    return `'${value.replace(/'/g, "''")}'`;
}

function splitList(value) {
    return String(value ?? '')
        .split(/[|;]/)
        .flatMap((part) => part.split(/\s*,\s*/))
        .map((part) => part.trim())
        .filter(Boolean);
}

function renderTemplate(template, base, definition) {
    return String(template ?? '').replace(/\{([A-Za-z0-9_]+)}/g, (_, key) => ({
        baseName: base.name,
        baseExpression: base.expression,
        baseDescription: base.descriptionDe || base.descriptionEn || '',
        definition: definition.name,
        condition: definition.expression || buildTableauCondition(definition.dimensions[0] || '', definition.values),
        dimensions: definition.dimensions.join(', '),
        values: definition.values.join(', '),
    })[key] ?? '');
}

function csvLine(values) {
    return values.map((value) => {
        const text = String(value ?? '');

        if (/[",\n\r]/.test(text)) {
            return `"${text.replace(/"/g, '""')}"`;
        }

        return text;
    }).join(',');
}
