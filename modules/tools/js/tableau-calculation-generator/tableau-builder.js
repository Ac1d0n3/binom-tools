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
    const bases = collectBaseMeasures(state);
    const effectiveDefinitions = resolveDefinitions(state);
    const descriptionTemplateDe = state.descriptionTemplateDe || DEFAULT_DESCRIPTION_DE;
    const descriptionTemplateEn = state.descriptionTemplateEn || DEFAULT_DESCRIPTION_EN;
    const lodDimensions = splitList(state.lodDimensionsText || '');
    const rows = [['calculation_name', 'formula', 'description_de', 'description_en', 'definition', 'dimensions', 'values']];

    const calculations = [];
    const lod = [];
    const paths = parseHierarchyPaths(state.hierarchyText || '');

    bases.forEach((base) => {
        effectiveDefinitions.forEach((definition) => {
            const name = `${base.name} - ${definition.name}`;
            const formula = buildCalculatedField(base.expression, definition);
            const descriptionDe = renderTemplate(descriptionTemplateDe, base, definition);
            const descriptionEn = renderTemplate(descriptionTemplateEn, base, definition);
            const fixedDimensions = lodDimensions.length > 0 ? lodDimensions : definition.dimensions;

            calculations.push(`${name}\n${formula}\n\nDE: ${descriptionDe}\nEN: ${descriptionEn}`);
            lod.push(`${name} LOD\n${buildLodExpression(base.expression, definition, fixedDimensions)}`);
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

    const primaryBase = bases[0] || {
        name: state.measureName || 'Sales',
        expression: state.baseExpression || 'SUM([Sales])',
    };

    return {
        calculations: calculations.join('\n\n---\n\n'),
        lod: lod.join('\n\n---\n\n'),
        hierarchy: buildHierarchyOutput(paths, primaryBase),
        definitions: effectiveDefinitions.map((definition) => `${definition.name}\n${definition.expression || buildTableauCondition(definition.dimensions[0] || '', definition.values)}`).join('\n\n---\n\n'),
        csv: rows.map(csvLine).join('\n'),
        rows,
    };
}

export function parseHierarchyLevels(text) {
    return String(text ?? '')
        .split(/\n|>|→|->/)
        .map((level) => level.trim())
        .filter(Boolean);
}

export function parseHierarchyPaths(text) {
    const source = String(text ?? '');
    const hasIndentedTree = /^\s{2,}\S/m.test(source);

    if (!hasIndentedTree) {
        const levels = parseHierarchyLevels(source);
        return levels.map((_, index) => levels.slice(0, index + 1));
    }

    const stack = [];
    return source
        .split(/\r?\n/)
        .map((line) => {
            const name = line.trim();
            if (!name) {
                return null;
            }
            const depth = Math.max(0, Math.floor((line.match(/^\s*/)?.[0].length || 0) / 2));
            stack.length = depth;
            stack[depth] = name;
            return stack.slice(0, depth + 1);
        })
        .filter(Boolean);
}

function resolveDefinitions(state) {
    const definitions = parseDefinitions(state.definitionsText);
    if (definitions.length > 0) {
        return definitions;
    }

    return definitionsFromValues(parseCsv(state.valuesText), state.mode || 'single');
}

function definitionsFromValues(rows, mode) {
    const items = rows
        .map((row) => ({
            dimension: row.dimension || '',
            value: row.value || '',
            label: row.label || row.value || '',
        }))
        .filter((item) => item.dimension && item.value);

    if (items.length === 0) {
        return [];
    }

    const grouped = Object.values(groupByDimension(items));

    if (mode === 'combined') {
        return cartesian(grouped).map((variant) => definitionFromVariant(variant));
    }

    if (mode === 'dimension-group') {
        return grouped.map((group) => definitionFromDimensionValues(
            group[0].dimension,
            group.map((item) => item.value),
            `${group[0].dimension} ${group.map((item) => item.label || item.value).join(' + ')}`,
        ));
    }

    return items.map((item) => definitionFromDimensionValues(item.dimension, [item.value], item.label || ''));
}

function definitionFromVariant(variant) {
    const name = variant.map((item) => `${item.dimension} ${item.label || item.value}`).join(' + ');
    const dimensions = variant.map((item) => item.dimension);
    const values = variant.map((item) => item.value);
    const expression = variant
        .map((item) => buildTableauCondition(item.dimension, [item.value]))
        .filter(Boolean)
        .join(' AND ');

    return {
        name,
        dimensions,
        values,
        expression,
        parameter: '',
        description: name,
    };
}

function groupByDimension(items) {
    return items.reduce((groups, item) => {
        groups[item.dimension] = groups[item.dimension] || [];
        groups[item.dimension].push(item);
        return groups;
    }, {});
}

function cartesian(groups) {
    return groups.reduce((acc, group) => acc.flatMap((prefix) => group.map((item) => [...prefix, item])), [[]]);
}

function collectBaseMeasures(state) {
    const parsedBases = parseBaseMeasures(state.baseMeasuresText);
    const activeExpression = String(state.baseExpression || '').trim();
    const activeName = String(state.measureName || '').trim() || 'Sales';

    if (!activeExpression) {
        return parsedBases.length > 0
            ? parsedBases
            : [{
                name: 'Sales',
                expression: 'SUM([Sales])',
                descriptionDe: 'Umsatz basierend auf SUM([Sales]).',
                descriptionEn: 'Sales based on SUM([Sales]).',
            }];
    }

    const activeBase = {
        name: activeName,
        expression: activeExpression,
        descriptionDe: state.baseDescriptionDe || activeName,
        descriptionEn: state.baseDescriptionEn || activeName,
    };

    return [activeBase, ...parsedBases.filter((base) => base.name !== activeBase.name)];
}

function buildHierarchyOutput(paths, base) {
    if (!paths.length) {
        return [
            'Tableau hierarchy',
            '',
            'No hierarchy levels configured.',
            'Add one field per line, for example:',
            'Region',
            '  Country',
            '    City',
        ].join('\n');
    }

    const uniqueLevels = [...new Set(paths.flat())];
    const measureName = base?.name || 'Measure';
    const baseExpression = base?.expression || 'SUM([Sales])';

    const howto = [
        'Tableau hierarchy',
        '',
        `Name: ${uniqueLevels.join(' / ')}`,
        `Levels: ${uniqueLevels.map((level) => `[${level}]`).join(' > ')}`,
        '',
        'Where to create it:',
        '1. In Tableau Desktop, go to the Data pane.',
        '2. Drag the second level onto the first field and choose Hierarchy.',
        '3. Add the remaining levels in order.',
        '4. Use the hierarchy on rows, columns or marks for drill-down analysis.',
    ].join('\n');

    const templates = paths.map((path) => {
        const condition = path
            .map((dimension) => `[${dimension}] = '<${dimension} value>'`)
            .join(' AND ');
        const formula = buildCalculatedField(baseExpression, {
            name: path.join(' / '),
            dimensions: path,
            values: path.map((dimension) => `<${dimension} value>`),
            expression: condition,
        });

        return `${measureName} - ${path.join(' / ')}\n${formula}`;
    }).join('\n\n');

    return `${howto}\n\n// Measure templates per hierarchy level\n${templates}`;
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
