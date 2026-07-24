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
            table: row.table || '',
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
            table: row.table || '',
            column: row.column || row.dimension || '',
            values: splitList(row.values || row.value || ''),
            expression: row.expression || '',
            description: row.description || '',
        }))
        .filter((definition) => definition.name !== '' && (definition.expression !== '' || definition.column !== ''));
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

export function buildDaxCondition(definition) {
    if (definition.expression) {
        return definition.expression;
    }

    const column = daxColumn(definition.table, definition.column);
    const values = definition.values.map((value) => String(value ?? '').trim()).filter(Boolean);

    if (!column || values.length === 0) {
        return '';
    }

    if (values.length === 1) {
        return `${column} = ${daxLiteral(values[0])}`;
    }

    return `${column} IN { ${values.map(daxLiteral).join(', ')} }`;
}

export function definitionFromDimensionValues(table, column, values, name = '') {
    const cleanValues = values.map((value) => String(value ?? '').trim()).filter(Boolean);
    const label = name || [column, cleanValues.join(' + ')].filter(Boolean).join(' ');

    return {
        name: label,
        table,
        column,
        values: cleanValues,
        expression: buildDaxCondition({ table, column, values: cleanValues, expression: '' }),
        description: label,
    };
}

export function buildDaxMeasure(baseExpression, definition) {
    const expression = String(baseExpression ?? '').trim();
    const condition = buildDaxCondition(definition);

    if (expression === '') {
        return '';
    }

    if (condition === '') {
        return expression;
    }

    return `CALCULATE(\n    ${expression},\n    ${condition}\n)`;
}

export function buildTimeMeasures(baseName, dateColumn = "'Date'[Date]") {
    const name = String(baseName ?? '').trim() || 'Sales';

    return [
        `${name} YTD =\nCALCULATE(\n    [${name}],\n    DATESYTD(${dateColumn})\n)`,
        `${name} PY =\nCALCULATE(\n    [${name}],\n    SAMEPERIODLASTYEAR(${dateColumn})\n)`,
        `${name} YoY =\n[${name}] - [${name} PY]`,
        `${name} YoY % =\nDIVIDE([${name} YoY], [${name} PY])`,
    ].join('\n\n');
}

export function buildPowerBiOutputs(state = {}) {
    const bases = collectBaseMeasures(state);
    const effectiveDefinitions = resolveDefinitions(state);
    const descriptionTemplateDe = state.descriptionTemplateDe || DEFAULT_DESCRIPTION_DE;
    const descriptionTemplateEn = state.descriptionTemplateEn || DEFAULT_DESCRIPTION_EN;
    const rows = [['measure_name', 'formula', 'description_de', 'description_en', 'definition', 'table', 'column', 'values']];

    const measures = [];
    const calculationGroups = [];
    const paths = parseHierarchyPaths(state.hierarchyText || '');

    bases.forEach((base) => {
        effectiveDefinitions.forEach((definition) => {
            const name = `${base.name} - ${definition.name}`;
            const formula = `${name} =\n${indent(buildDaxMeasure(base.expression, definition))}`;
            const descriptionDe = renderTemplate(descriptionTemplateDe, base, definition);
            const descriptionEn = renderTemplate(descriptionTemplateEn, base, definition);

            measures.push(`${formula}\n\n// DE: ${descriptionDe}\n// EN: ${descriptionEn}`);
            rows.push([
                name,
                formula,
                descriptionDe,
                descriptionEn,
                definition.name,
                definition.table,
                definition.column,
                definition.values.join('|'),
            ]);
        });

        calculationGroups.push(buildTimeMeasures(base.name, state.dateColumn || "'Date'[Date]"));
    });

    const primaryBase = bases[0] || {
        name: state.measureName || 'Sales',
        expression: state.baseExpression || 'SUM(Sales[Sales])',
    };

    return {
        measures: measures.join('\n\n---\n\n'),
        time: calculationGroups.join('\n\n---\n\n'),
        hierarchy: buildHierarchyOutput(paths, primaryBase),
        definitions: effectiveDefinitions.map((definition) => `${definition.name}\n${buildDaxCondition(definition)}`).join('\n\n---\n\n'),
        csv: rows.map(csvLine).join('\n'),
        rows,
    };
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
            table: row.table || 'Sales',
            column: row.dimension || row.column || '',
            value: row.value || '',
            label: row.label || row.value || '',
        }))
        .filter((item) => item.column && item.value);

    if (items.length === 0) {
        return [];
    }

    const grouped = Object.values(groupByColumn(items));

    if (mode === 'combined') {
        return cartesian(grouped).map((variant) => definitionFromVariant(variant));
    }

    if (mode === 'dimension-group') {
        return grouped.map((group) => definitionFromDimensionValues(
            group[0].table,
            group[0].column,
            group.map((item) => item.value),
            `${group[0].column} ${group.map((item) => item.label || item.value).join(' + ')}`,
        ));
    }

    return items.map((item) => definitionFromDimensionValues(item.table, item.column, [item.value], item.label || ''));
}

function definitionFromVariant(variant) {
    const name = variant.map((item) => `${item.column} ${item.label || item.value}`).join(' + ');
    const conditions = variant
        .map((item) => buildDaxCondition({ table: item.table, column: item.column, values: [item.value], expression: '' }))
        .filter(Boolean);

    return {
        name,
        table: variant[0]?.table || '',
        column: variant.map((item) => item.column).join('|'),
        values: variant.map((item) => item.value),
        expression: conditions.join(',\n    '),
        description: name,
    };
}

function groupByColumn(items) {
    return items.reduce((groups, item) => {
        const key = `${item.table}|${item.column}`;
        groups[key] = groups[key] || [];
        groups[key].push(item);
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
                expression: 'SUM(Sales[Sales])',
                descriptionDe: 'Umsatz basierend auf SUM(Sales[Sales]).',
                descriptionEn: 'Sales based on SUM(Sales[Sales]).',
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
            'Power BI hierarchy',
            '',
            'No hierarchy levels configured.',
            'Add one field per line, for example:',
            'Sales[Region]',
            '  Sales[Country]',
            '    Sales[City]',
        ].join('\n');
    }

    const uniqueLevels = [...new Set(paths.flat())];
    const measureName = base?.name || 'Measure';
    const baseExpression = base?.expression || 'SUM(Sales[Sales])';

    const howto = [
        'Power BI hierarchy',
        '',
        `Name: ${uniqueLevels.join(' / ')}`,
        `Levels: ${uniqueLevels.join(' > ')}`,
        '',
        'Where to create it:',
        '1. In Power BI Desktop, open Model view or Data pane.',
        '2. Right-click the first field and choose Create hierarchy.',
        '3. Drag the remaining fields into the hierarchy in order.',
        '4. Use the hierarchy on Axis, Rows, Columns or drill-down visuals.',
    ].join('\n');

    const templates = paths.map((path) => {
        const filters = path
            .map((level) => {
                const { table, column } = parseLevel(level);
                return `${daxColumn(table, column)} = "<${column || level} value>"`;
            })
            .join(',\n    ');

        return `${measureName} - ${path.join(' / ')} =\nCALCULATE(\n    ${baseExpression},\n    ${filters}\n)`;
    }).join('\n\n');

    return `${howto}\n\n// Measure templates per hierarchy level\n${templates}`;
}

function parseLevel(level) {
    const match = String(level ?? '').trim().match(/^(?:'?([^'\[]+)'?)?\[([^\]]+)\]$/);
    if (match) {
        return { table: match[1] || '', column: match[2] };
    }

    return { table: '', column: String(level ?? '').trim() };
}

function daxColumn(table, column) {
    const cleanColumn = String(column ?? '').trim();
    const cleanTable = String(table ?? '').trim();

    if (!cleanColumn) {
        return '';
    }

    if (cleanColumn.includes('[')) {
        return cleanColumn;
    }

    if (!cleanTable) {
        return `[${cleanColumn}]`;
    }

    return `${cleanTable}[${cleanColumn}]`;
}

function daxLiteral(value) {
    if (/^-?\d+(\.\d+)?$/.test(value)) {
        return value;
    }

    if (/^(TRUE|FALSE)$/i.test(value)) {
        return value.toUpperCase();
    }

    if (/^\[.+]$/.test(value)) {
        return value;
    }

    return `"${value.replace(/"/g, '""')}"`;
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
        condition: buildDaxCondition(definition),
        table: definition.table,
        column: definition.column,
        values: definition.values.join(', '),
    })[key] ?? '');
}

function indent(text) {
    return String(text ?? '').split('\n').map((line) => `    ${line}`).join('\n');
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
