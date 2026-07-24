/** @param {string} text */
function csvRows(text) {
    const rows = [];
    let cell = '';
    let row = [];
    let quoted = false;

    for (let i = 0; i < text.length; i += 1) {
        const char = text[i];
        const next = text[i + 1];
        if (char === '"' && quoted && next === '"') {
            cell += '"';
            i += 1;
        } else if (char === '"') {
            quoted = !quoted;
        } else if (char === ',' && !quoted) {
            row.push(cell.trim());
            cell = '';
        } else if ((char === '\n' || char === '\r') && !quoted) {
            if (char === '\r' && next === '\n') i += 1;
            row.push(cell.trim());
            if (row.some(Boolean)) rows.push(row);
            row = [];
            cell = '';
        } else {
            cell += char;
        }
    }

    row.push(cell.trim());
    if (row.some(Boolean)) rows.push(row);
    return rows;
}

/** @param {string} text */
export function parseCsv(text) {
    const rows = csvRows(text);
    if (rows.length === 0) return [];

    const first = rows[0].map((value) => value.toLowerCase());
    const hasHeader = first.includes('dimension') || first.includes('field') || first.includes('value');
    const dataRows = hasHeader ? rows.slice(1) : rows;
    const columnIndex = (...names) => {
        for (const name of names) {
            const index = first.indexOf(name);
            if (index >= 0) return index;
        }
        return -1;
    };

    const dimensionIndex = hasHeader ? columnIndex('dimension', 'field') : 0;
    const valueIndex = hasHeader ? columnIndex('value', 'values') : 1;
    const labelIndex = hasHeader ? columnIndex('label', 'name') : 2;
    const operatorIndex = hasHeader ? columnIndex('operator', 'searchmode') : 3;
    const assignmentIndex = hasHeader ? columnIndex('assignment', 'assign') : 4;

    return dataRows.map((cols) => ({
        dimension: cols[dimensionIndex] || '',
        value: cols[valueIndex] || '',
        label: labelIndex >= 0 ? (cols[labelIndex] || cols[valueIndex] || '') : (cols[valueIndex] || ''),
        operator: operatorIndex >= 0 ? (cols[operatorIndex] || '') : '',
        assignment: assignmentIndex >= 0 ? (cols[assignmentIndex] || '') : '',
    })).filter((item) => item.dimension && item.value);
}

/** @param {string} text */
export function parseFields(text) {
    const rows = csvRows(text);
    if (rows.length === 0) return [];
    const first = rows[0].map((value) => value.toLowerCase());
    const hasHeader = first.includes('field') || first.includes('name');
    const dataRows = hasHeader ? rows.slice(1) : rows;
    const nameIndex = hasHeader ? Math.max(first.indexOf('field'), first.indexOf('name')) : 0;
    const typeIndex = hasHeader ? first.indexOf('type') : 1;
    const tagsIndex = hasHeader ? first.indexOf('tags') : 2;

    return dataRows.map((cols) => ({
        name: cols[nameIndex] || '',
        type: typeIndex >= 0 ? (cols[typeIndex] || '') : '',
        tags: tagsIndex >= 0 ? (cols[tagsIndex] || '') : '',
    })).filter((item) => item.name);
}

/** @param {string} text */
export function parseVariables(text) {
    const rows = csvRows(text);
    if (rows.length === 0) return [];
    const first = rows[0].map((value) => value.toLowerCase());
    const hasHeader = first.includes('variable') || first.includes('name') || first.includes('definition');
    const dataRows = hasHeader ? rows.slice(1) : rows;
    const columnIndex = (...names) => {
        for (const name of names) {
            const index = first.indexOf(name);
            if (index >= 0) return index;
        }
        return -1;
    };
    const nameIndex = hasHeader ? columnIndex('name', 'variable') : 0;
    const definitionIndex = hasHeader ? columnIndex('definition', 'expression', 'value') : 1;
    const descriptionIndex = hasHeader ? columnIndex('description', 'comment') : 2;

    return dataRows.map((cols) => ({
        name: cols[nameIndex] || '',
        definition: cols[definitionIndex] || '',
        description: descriptionIndex >= 0 ? (cols[descriptionIndex] || '') : '',
    })).filter((item) => item.name && item.definition);
}

/** @param {string} text */
export function parseHierarchyLevels(text) {
    return text
        .split(/\r?\n|>|,/)
        .map((value) => value.trim())
        .filter(Boolean)
        .filter((value, index, values) => values.indexOf(value) === index);
}

/** @param {string} value */
function sanitizeName(value) {
    return value.replace(/[^A-Za-z0-9]+/g, '_').replace(/^_+|_+$/g, '') || 'Value';
}

/** @param {string} value */
function fieldName(value) {
    return `[${value.replaceAll(']', ']]')}]`;
}

/** @param {string} value */
function singleQuoted(value) {
    return `'${value.replaceAll("'", "''")}'`;
}

/** @param {string} value */
function doubleQuoted(value) {
    return `"${value.replaceAll('"', '""')}"`;
}

/** @param {string} value @param {string} mode */
function qlikValue(value, mode) {
    if (mode === 'numeric') return value;
    if (mode === 'search') return doubleQuoted(value);
    return singleQuoted(value);
}

/** @param {Array<string>} values @param {string} mode */
function qlikValueList(values, mode) {
    return values.map((value) => qlikValue(value, mode)).join(',');
}

/** @param {string} identifier */
function setPrefix(identifier) {
    return identifier ? `${identifier}<` : '<';
}

const aggregationFunctions = new Set([
    'avg',
    'count',
    'firstsortedvalue',
    'fractile',
    'max',
    'median',
    'min',
    'mode',
    'only',
    'stdev',
    'sum',
]);

/** @param {{ dimension: string, value: string, assignment?: string, operator?: string }} item @param {{ assignment: string, valueMode: string }} state */
function modifierForItem(item, state) {
    const assignment = item.assignment || state.assignment || '=';
    const mode = item.operator || state.valueMode || 'literal';
    return `${fieldName(item.dimension)}${assignment}{${qlikValue(item.value, mode)}}`;
}

/** @param {Array<{ dimension: string, value: string, label: string, assignment?: string, operator?: string }>} items */
function groupByDimension(items) {
    return items.reduce((groups, item) => {
        groups[item.dimension] = groups[item.dimension] || [];
        groups[item.dimension].push(item);
        return groups;
    }, {});
}

/** @param {Array<Array<{ dimension: string, value: string, label: string, assignment?: string, operator?: string }>>} groups */
function cartesian(groups) {
    return groups.reduce((acc, group) => acc.flatMap((prefix) => group.map((item) => [...prefix, item])), [[]]);
}

/** @param {Array<{ dimension: string, value: string, label: string, assignment?: string, operator?: string }>} rows @param {{ mode: string, assignment: string, valueMode: string }} state */
function variantsFromRows(rows, state) {
    const grouped = Object.values(groupByDimension(rows));
    if (state.mode === 'combined') return cartesian(grouped);
    if (state.mode === 'dimension-group') {
        return grouped.map((items) => {
            const first = items[0];
            return [{
                ...first,
                value: items.map((item) => item.value).join('\u0000'),
                label: items.map((item) => item.label || item.value).join(' + '),
                operator: first.operator || state.valueMode,
                assignment: first.assignment || state.assignment,
            }];
        });
    }
    return rows.map((item) => [item]);
}

/** @param {{ rowsText: string, baseMeasure: string, mode?: string, setIdentifier?: string, assignment?: string, expressionStyle?: string, valueMode?: string }} state */
export function buildCurrentFormula(state) {
    const rows = parseCsv(state.rowsText || '');
    const variants = variantsFromRows(rows, {
        mode: state.mode || 'single',
        assignment: state.assignment || '=',
        valueMode: state.valueMode || 'literal',
    });
    const first = variants[0];
    if (!first) return state.baseMeasure || '';
    const modifier = buildModifier(first, {
        setIdentifier: state.setIdentifier ?? '$',
        assignment: state.assignment || '=',
        valueMode: state.valueMode || 'literal',
    });
    return injectSetAnalysis(state.baseMeasure || 'Sum(Sales)', modifier, {
        setIdentifier: state.setIdentifier ?? '$',
        expressionStyle: state.expressionStyle || 'inner',
    });
}

/** @param {Array<{ dimension: string, value: string, label: string, assignment?: string, operator?: string }>} variant @param {{ setIdentifier: string, assignment: string, valueMode: string }} state */
export function buildModifier(variant, state) {
    return variant.map((item) => {
        if (item.value.includes('\u0000')) {
            const values = item.value.split('\u0000');
            const assignment = item.assignment || state.assignment || '=';
            const mode = item.operator || state.valueMode || 'literal';
            return `${fieldName(item.dimension)}${assignment}{${qlikValueList(values, mode)}}`;
        }
        return modifierForItem(item, state);
    }).join(', ');
}

/** @param {string} baseMeasure @param {string} modifier @param {{ setIdentifier?: string, expressionStyle?: string }} options */
export function injectSetAnalysis(baseMeasure, modifier, options = {}) {
    const trimmed = baseMeasure.trim();
    const identifier = options.setIdentifier ?? '$';
    const setExpression = `{${setPrefix(identifier)}${modifier}>}`;

    if (options.expressionStyle === 'outer') {
        return `${setExpression} ${trimmed}`;
    }

    if (trimmed.startsWith('$(')) {
        return `${setExpression} ${trimmed}`;
    }

    const injected = injectIntoAggregations(trimmed, modifier, identifier);
    if (injected.changed) {
        return injected.expression;
    }

    return `/* Review manually: could not inject set analysis into '${trimmed}' */ ${setExpression} ${trimmed}`;
}

function injectIntoAggregations(expression, modifier, identifier) {
    let output = '';
    let index = 0;
    let changed = false;

    while (index < expression.length) {
        const char = expression[index];
        const nameMatch = expression.slice(index).match(/^([A-Za-z][A-Za-z0-9]*)\s*\(/);
        if (!nameMatch) {
            output += char;
            index += 1;
            continue;
        }

        const name = nameMatch[1];
        const openIndex = index + nameMatch[0].lastIndexOf('(');
        if (!aggregationFunctions.has(name.toLowerCase())) {
            output += expression.slice(index, openIndex + 1);
            index = openIndex + 1;
            continue;
        }

        const closeIndex = findMatching(expression, openIndex, '(', ')');
        if (closeIndex < 0) {
            output += char;
            index += 1;
            continue;
        }

        const body = expression.slice(openIndex + 1, closeIndex);
        output += `${expression.slice(index, openIndex + 1)}${injectIntoAggregationBody(body, modifier, identifier)})`;
        index = closeIndex + 1;
        changed = true;
    }

    return { expression: output, changed };
}

function findMatching(text, openIndex, openChar, closeChar) {
    let depth = 0;
    let quote = '';
    let bracketDepth = 0;

    for (let index = openIndex; index < text.length; index += 1) {
        const char = text[index];
        const next = text[index + 1];

        if (quote) {
            if (char === quote && next === quote) {
                index += 1;
            } else if (char === quote) {
                quote = '';
            }
            continue;
        }

        if (char === "'" || char === '"') {
            quote = char;
            continue;
        }

        if (char === '[') {
            bracketDepth += 1;
            continue;
        }

        if (char === ']' && bracketDepth > 0) {
            bracketDepth -= 1;
            continue;
        }

        if (bracketDepth > 0) continue;

        if (char === openChar) depth += 1;
        if (char === closeChar) {
            depth -= 1;
            if (depth === 0) return index;
        }
    }

    return -1;
}

function injectIntoAggregationBody(body, modifier, identifier) {
    const leading = body.match(/^\s*/)?.[0] || '';
    const rest = body.slice(leading.length);

    if (!rest.startsWith('{')) {
        return `${leading}{${setPrefix(identifier)}${modifier}>} ${rest.trimStart()}`;
    }

    const setEnd = findMatching(rest, 0, '{', '}');
    if (setEnd < 0) {
        return `${leading}{${setPrefix(identifier)}${modifier}>} ${rest.trimStart()}`;
    }

    const existingSet = rest.slice(0, setEnd + 1);
    const remainingExpression = rest.slice(setEnd + 1);
    return `${leading}${mergeSetModifier(existingSet, modifier, identifier)}${remainingExpression}`;
}

function mergeSetModifier(existingSet, modifier, identifier) {
    if (existingSet.includes(modifier)) return existingSet;

    const open = existingSet.indexOf('<');
    const close = existingSet.lastIndexOf('>');
    if (open >= 0 && close > open) {
        const before = existingSet.slice(0, close);
        const separator = before.endsWith('<') ? '' : ', ';
        return `${before}${separator}${modifier}${existingSet.slice(close)}`;
    }

    const inner = existingSet.slice(1, -1).trim() || identifier;
    return `{${inner}<${modifier}>}`;
}

/** @param {Array<{ dimension: string, value: string, label: string }>} variant @param {string} expression */
function nestedIfForVariant(variant, expression) {
    const condition = variant.map((item) => `${fieldName(item.dimension)}=${singleQuoted(item.value)}`).join(' and ');
    return `If(${condition}, ${expression})`;
}

/** @param {Array<{ dimension: string, value: string, label: string }>} rows @param {string} baseMeasure */
function buildPickMatch(rows, baseMeasure) {
    const firstDimension = rows[0]?.dimension;
    if (!firstDimension) return '';
    const values = rows.filter((item) => item.dimension === firstDimension);
    return `Pick(
    Match(${fieldName(firstDimension)}, ${values.map((item) => singleQuoted(item.value)).join(', ')}),
${values.map((item) => `    ${injectSetAnalysis(baseMeasure, modifierForItem(item, { assignment: '=', valueMode: 'literal' }), { setIdentifier: '$', expressionStyle: 'inner' })}`).join(',\n')}
)`;
}

/** @param {{ dateField?: string, yearField?: string }} state */
export function buildTimeVariables(state) {
    const date = fieldName(state.dateField || 'Date');
    const year = fieldName(state.yearField || 'Year');

    return `// Standard time variables for Qlik set analysis
// Add in the Data load editor and reload the app, or create as UI variables where appropriate.
// Use modifier variables inside expressions like: Sum({$<$(vSetYTD)>} Sales)

LET vTodayNum = Num(Today());
LET vYesterdayNum = Num(Today() - 1);
LET vCurrentYear = Year(Today());
LET vPreviousYear = Year(AddYears(Today(), -1));
LET vCurrentMonthStartNum = Num(MonthStart(Today()));
LET vCurrentMonthEndNum = Num(MonthEnd(Today()));
LET vLast12MonthsStartNum = Num(AddMonths(MonthStart(Today()), -11));

SET vSetCY = ${year}={'$(vCurrentYear)'};
SET vSetPY = ${year}={'$(vPreviousYear)'};
SET vSetYTD = ${date}={">=$(=YearStart(Today()))<=$(=Today())"};
SET vSetMTD = ${date}={">=$(=MonthStart(Today()))<=$(=Today())"};
SET vSetLast12M = ${date}={">=$(=AddMonths(MonthStart(Today()),-11))<=$(=MonthEnd(Today()))"};

// Copy-paste examples
// Current year:     ${injectSetAnalysis('Sum(Sales)', '$(vSetCY)', { setIdentifier: '$', expressionStyle: 'inner' })}
// Previous year:    ${injectSetAnalysis('Sum(Sales)', '$(vSetPY)', { setIdentifier: '$', expressionStyle: 'inner' })}
// Year to date:     ${injectSetAnalysis('Sum(Sales)', '$(vSetYTD)', { setIdentifier: '$', expressionStyle: 'inner' })}
// Month to date:    ${injectSetAnalysis('Sum(Sales)', '$(vSetMTD)', { setIdentifier: '$', expressionStyle: 'inner' })}
// Last 12 months:   ${injectSetAnalysis('Sum(Sales)', '$(vSetLast12M)', { setIdentifier: '$', expressionStyle: 'inner' })}`;
}

/** @param {string} value */
function csvEscape(value) {
    return `"${value.replaceAll('"', '""')}"`;
}

/** @param {string} baseDescription @param {string} label @param {string} modifier */
function childDescription(baseDescription, label, modifier) {
    const base = baseDescription?.trim() || 'Generated child measure.';
    return `${base}\nErweiterung: ${label}.\nSet Modifier: ${modifier}`;
}

/** @param {{ baseMeasure: string, measureName: string, setIdentifier?: string, expressionStyle?: string, hierarchyText?: string }} state */
export function buildHierarchyOutput(state) {
    const levels = parseHierarchyLevels(state.hierarchyText || '');
    if (levels.length === 0) return '';

    const options = {
        setIdentifier: state.setIdentifier ?? '$',
        expressionStyle: state.expressionStyle || 'inner',
    };
    const name = `${state.measureName || 'Measure'} Hierarchy`;
    const masterDimension = [
        '// Qlik drill-down master dimension',
        `Name: ${name}`,
        'Type: Drill-down dimension',
        'Fields:',
        ...levels.map((level, index) => `${index + 1}. ${fieldName(level)}`),
    ].join('\n');

    const templates = levels.map((level, index) => {
        const path = levels.slice(0, index + 1);
        const modifier = path.map((dimension) => `${fieldName(dimension)}={'<${dimension} value>'}`).join(', ');
        return [
            `${state.measureName || 'Measure'} - ${path.join(' / ')}`,
            injectSetAnalysis(state.baseMeasure || 'Sum(Sales)', modifier, options),
        ].join('\n');
    }).join('\n\n');

    const bridge = [
        '// Optional helper table for documentation / governance',
        'HierarchyFields:',
        'LOAD * INLINE [',
        'level,parent,field,path',
        ...levels.map((level, index) => `${index + 1},${index === 0 ? 'Base' : levels[index - 1]},${level},${levels.slice(0, index + 1).join(' > ')}`),
        '];',
    ].join('\n');

    return `${masterDimension}\n\n// Measure templates per hierarchy level\n${templates}\n\n${bridge}`;
}

/** @param {{ baseMeasure: string, baseDescription?: string, measureName: string, variablePrefix: string, rowsText: string, mode: string, useVariables: boolean, setIdentifier?: string, assignment?: string, expressionStyle?: string, valueMode?: string, hierarchyText?: string }} state */
export function buildSetAnalysisOutputs(state) {
    const normalized = {
        setIdentifier: state.setIdentifier ?? '$',
        assignment: state.assignment || '=',
        expressionStyle: state.expressionStyle || 'inner',
        valueMode: state.valueMode || 'literal',
        mode: state.mode || 'single',
    };
    const rows = parseCsv(state.rowsText);
    const customVariables = parseVariables(state.variablesText || '');
    const variants = variantsFromRows(rows, normalized);
    const limited = variants.slice(0, 250);

    const measures = [];
    const modifiers = [];
    const nestedIfRows = [];
    const variables = [
        '// Custom/base variables',
        ...customVariables.map((variable) => `SET ${variable.name} = ${variable.definition};${variable.description ? ` // ${variable.description}` : ''}`),
        `SET ${state.variablePrefix}_Base = ${state.baseMeasure};`,
        '',
        '// Generated child measure variables',
    ];
    const csvOutput = [['measure_name', 'expression', 'description', 'set_modifier', 'dimensions', 'values'].join(',')];

    limited.forEach((variant) => {
        const suffix = variant.map((item) => `${sanitizeName(item.dimension)}_${sanitizeName(item.label || item.value.replaceAll('\u0000', '_'))}`).join('_');
        const label = variant.map((item) => `${item.dimension} ${item.label || item.value.replaceAll('\u0000', ' + ')}`).join(' + ');
        const modifier = buildModifier(variant, normalized);
        const expression = injectSetAnalysis(state.baseMeasure, modifier, normalized);
        const variableName = `${state.variablePrefix}_${suffix}`;
        const measureName = `${state.measureName} - ${label}`;
        const finalExpression = state.useVariables ? `$(${variableName})` : expression;
        const description = childDescription(state.baseDescription || '', label, modifier);

        variables.push(`SET ${variableName} = ${expression};`);
        modifiers.push(`${measureName}\n{${setPrefix(normalized.setIdentifier)}${modifier}>}`);
        measures.push(`${measureName}\n${finalExpression}\n\nDescription:\n${description}`);
        nestedIfRows.push(nestedIfForVariant(variant.map((item) => ({
            ...item,
            value: item.value.split('\u0000')[0],
        })), state.baseMeasure));
        csvOutput.push([
            csvEscape(measureName),
            csvEscape(finalExpression),
            csvEscape(description),
            csvEscape(modifier),
            csvEscape(variant.map((item) => item.dimension).join(' | ')),
            csvEscape(variant.map((item) => item.value.replaceAll('\u0000', ' | ')).join(' | ')),
        ].join(','));
    });

    return {
        measures: measures.join('\n\n'),
        variables: variables.join('\n'),
        hierarchy: buildHierarchyOutput(state),
        timeVariables: buildTimeVariables(state),
        modifiers: modifiers.join('\n\n'),
        nestedIf: nestedIfRows.length
            ? `// Useful as a comparison or migration aid. Prefer set analysis for selection-style filters.\n${nestedIfRows.join(',\n')}`
            : '',
        pickMatch: buildPickMatch(rows, state.baseMeasure),
        csv: csvOutput.join('\n'),
        count: limited.length,
    };
}
