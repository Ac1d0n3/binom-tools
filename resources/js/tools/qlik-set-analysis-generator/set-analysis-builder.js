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
export function parseBaseMeasures(text) {
    const rows = csvRows(text);
    if (rows.length === 0) return [];
    const first = rows[0].map((value) => value.toLowerCase());
    const hasHeader = first.includes('name') || first.includes('measure') || first.includes('expression');
    const dataRows = hasHeader ? rows.slice(1) : rows;
    const columnIndex = (...names) => {
        for (const name of names) {
            const index = first.indexOf(name);
            if (index >= 0) return index;
        }
        return -1;
    };
    const nameIndex = hasHeader ? columnIndex('name', 'measure', 'measure_name') : 0;
    const expressionIndex = hasHeader ? columnIndex('expression', 'formula', 'base_measure') : 1;
    const prefixIndex = hasHeader ? columnIndex('prefix', 'variable_prefix', 'var_prefix') : 2;
    const descriptionDeIndex = hasHeader ? columnIndex('description_de', 'description', 'beschreibung_de') : 3;
    const descriptionEnIndex = hasHeader ? columnIndex('description_en', 'beschreibung_en') : 4;

    return dataRows.map((cols) => ({
        measureName: cols[nameIndex] || '',
        baseMeasure: cols[expressionIndex] || '',
        variablePrefix: prefixIndex >= 0 ? (cols[prefixIndex] || '') : '',
        baseDescription: descriptionDeIndex >= 0 ? (cols[descriptionDeIndex] || '') : '',
        baseDescriptionEn: descriptionEnIndex >= 0 ? (cols[descriptionEnIndex] || '') : '',
    })).filter((item) => item.measureName && item.baseMeasure);
}

/** @param {string} text */
export function parseDefinitions(text) {
    const rows = csvRows(text);
    if (rows.length === 0) return [];
    const first = rows[0].map((value) => value.toLowerCase());
    const hasHeader = first.includes('name') || first.includes('modifier') || first.includes('definition');
    const dataRows = hasHeader ? rows.slice(1) : rows;
    const columnIndex = (...names) => {
        for (const name of names) {
            const index = first.indexOf(name);
            if (index >= 0) return index;
        }
        return -1;
    };
    const nameIndex = hasHeader ? columnIndex('name', 'definition') : 0;
    const modifierIndex = hasHeader ? columnIndex('modifier', 'set_modifier', 'set') : 1;
    const variableIndex = hasHeader ? columnIndex('variable', 'variable_name', 'var') : 2;
    const descriptionIndex = hasHeader ? columnIndex('description', 'comment') : 3;
    const dimensionsIndex = hasHeader ? columnIndex('dimensions', 'dimension') : 4;
    const valuesIndex = hasHeader ? columnIndex('values', 'value') : 5;

    return dataRows.map((cols) => ({
        name: cols[nameIndex] || '',
        modifier: cols[modifierIndex] || '',
        variableName: variableIndex >= 0 ? (cols[variableIndex] || '') : '',
        description: descriptionIndex >= 0 ? (cols[descriptionIndex] || '') : '',
        dimensions: dimensionsIndex >= 0 ? (cols[dimensionsIndex] || '') : '',
        values: valuesIndex >= 0 ? (cols[valuesIndex] || '') : '',
        items: [],
        source: 'definition',
    })).filter((item) => item.name && item.modifier);
}

/** @param {string} text */
export function parseHierarchyLevels(text) {
    return text
        .split(/\r?\n|>|,/)
        .map((value) => value.trim())
        .filter(Boolean)
        .filter((value, index, values) => values.indexOf(value) === index);
}

/** @param {string} text */
function parseHierarchyPaths(text) {
    const hasIndentedTree = /^\s{2,}\S/m.test(text || '');
    if (!hasIndentedTree) {
        const levels = parseHierarchyLevels(text);
        return levels.map((_, index) => levels.slice(0, index + 1));
    }

    const stack = [];
    return text
        .split(/\r?\n/)
        .map((line) => {
            const name = line.trim();
            if (!name) return null;
            const depth = Math.max(0, Math.floor((line.match(/^\s*/)?.[0].length || 0) / 2));
            stack.length = depth;
            stack[depth] = name;
            return stack.slice(0, depth + 1);
        })
        .filter(Boolean);
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

function definitionNameFromVariant(variant) {
    return variant.map((item) => `${item.dimension} ${item.label || item.value.replaceAll('\u0000', ' + ')}`).join(' + ');
}

function definitionVariableName(name) {
    return `vDef${sanitizeName(name).replace(/^./, (char) => char.toUpperCase())}`;
}

function definitionFromVariant(variant, state) {
    const name = definitionNameFromVariant(variant);
    const modifier = buildModifier(variant, {
        setIdentifier: state.setIdentifier ?? '$',
        assignment: state.assignment || '=',
        valueMode: state.valueMode || 'literal',
    });
    return {
        name,
        modifier,
        variableName: definitionVariableName(name),
        description: `Set modifier for ${name}`,
        dimensions: variant.map((item) => item.dimension).join(' | '),
        values: variant.map((item) => item.value.replaceAll('\u0000', ' | ')).join(' | '),
        items: variant,
        source: 'dimension-values',
    };
}

function stateDefinitions(state, normalized) {
    const parsed = parseDefinitions(state.definitionsText || '');
    if (parsed.length > 0) return parsed;
    const rows = parseCsv(state.rowsText || '');
    return variantsFromRows(rows, normalized).map((variant) => definitionFromVariant(variant, normalized));
}

/** @param {{ rowsText: string, baseMeasure: string, mode?: string, setIdentifier?: string, assignment?: string, expressionStyle?: string, valueMode?: string }} state */
export function buildCurrentFormula(state) {
    const normalized = {
        mode: state.mode || 'single',
        assignment: state.assignment || '=',
        valueMode: state.valueMode || 'literal',
        setIdentifier: state.setIdentifier ?? '$',
    };
    const first = stateDefinitions(state, normalized)[0];
    if (!first) return state.baseMeasure || '';
    return injectSetAnalysis(state.baseMeasure || 'Sum(Sales)', first.modifier, {
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

/** @param {string} template @param {Record<string, string>} values */
function renderTemplate(template, values) {
    return template.replace(/\{([A-Za-z0-9_]+)\}/g, (match, key) => values[key] ?? match);
}

/** @param {{ baseDescription?: string, baseDescriptionEn?: string, childDescriptionTemplate?: string, childDescriptionTemplateEn?: string, descriptionLanguage?: string, measureName?: string, baseMeasure?: string }} state @param {{ name: string, modifier: string, dimensions?: string, values?: string, description?: string, items?: Array<{ dimension: string, value: string }> }} definition */
function childDescription(state, definition) {
    const dimensions = definition.dimensions || definition.items?.map((item) => item.dimension).join(' | ') || '';
    const values = definition.values || definition.items?.map((item) => item.value.replaceAll('\u0000', ' | ')).join(' | ') || '';
    const baseValues = {
        baseMeasure: state.baseMeasure || 'Sum(Sales)',
        measureName: state.measureName || 'Measure',
        label: definition.name,
        definition: definition.name,
        modifier: definition.modifier,
        dimensions,
        values,
    };

    const deTemplate = state.childDescriptionTemplate?.trim()
        || '{baseDescription}\nErweiterung: {label}.\nSet Modifier: {modifier}';
    const enTemplate = state.childDescriptionTemplateEn?.trim()
        || '{baseDescription}\nExtension: {label}.\nSet modifier: {modifier}';

    const de = renderTemplate(deTemplate, {
        ...baseValues,
        baseDescription: state.baseDescription?.trim() || 'Generiertes Child Measure.',
    });
    const en = renderTemplate(enTemplate, {
        ...baseValues,
        baseDescription: state.baseDescriptionEn?.trim() || 'Generated child measure.',
    });

    if (state.descriptionLanguage === 'en') return en;
    if (state.descriptionLanguage === 'both') return `DE:\n${de}\n\nEN:\n${en}`;
    return de;
}

/** @param {{ baseMeasure: string, measureName: string, setIdentifier?: string, expressionStyle?: string, hierarchyText?: string }} state */
export function buildHierarchyOutput(state) {
    const paths = parseHierarchyPaths(state.hierarchyText || '');
    if (paths.length === 0) return '';
    const levels = paths.map((path) => path[path.length - 1]);

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

    const templates = paths.map((path) => {
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
        ...paths.map((path) => `${path.length},${path.length === 1 ? 'Base' : path[path.length - 2]},${path[path.length - 1]},${path.join(' > ')}`),
        '];',
    ].join('\n');

    return `${masterDimension}\n\n// Measure templates per hierarchy level\n${templates}\n\n${bridge}`;
}

function stateBaseMeasures(state) {
    const parsed = parseBaseMeasures(state.baseMeasuresText || '');
    if (parsed.length > 0) {
        return parsed.map((measure) => ({
            measureName: measure.measureName,
            baseMeasure: measure.baseMeasure,
            variablePrefix: measure.variablePrefix || state.variablePrefix || `v${sanitizeName(measure.measureName)}`,
            baseDescription: measure.baseDescription || state.baseDescription || '',
            baseDescriptionEn: measure.baseDescriptionEn || state.baseDescriptionEn || '',
        }));
    }
    return [{
        measureName: state.measureName || 'Measure',
        baseMeasure: state.baseMeasure || 'Sum(Sales)',
        variablePrefix: state.variablePrefix || 'vMeasure',
        baseDescription: state.baseDescription || '',
        baseDescriptionEn: state.baseDescriptionEn || '',
    }];
}

/** @param {{ baseMeasure: string, baseDescription?: string, baseDescriptionEn?: string, childDescriptionTemplate?: string, childDescriptionTemplateEn?: string, descriptionLanguage?: string, measureName: string, variablePrefix: string, rowsText: string, definitionsText?: string, mode: string, useVariables: boolean, setIdentifier?: string, assignment?: string, expressionStyle?: string, valueMode?: string, hierarchyText?: string, baseMeasuresText?: string }} state */
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
    const definitions = stateDefinitions(state, normalized);
    const limited = definitions.slice(0, 250);

    const baseMeasures = stateBaseMeasures(state);
    const measures = [];
    const modifiers = [];
    const nestedIfRows = [];
    const variables = [
        '// Custom/base variables',
        ...customVariables.map((variable) => `SET ${variable.name} = ${variable.definition};${variable.description ? ` // ${variable.description}` : ''}`),
        ...baseMeasures.map((measure) => `SET ${measure.variablePrefix}_Base = ${measure.baseMeasure};`),
        '',
        '// Definition variables (reusable set modifiers)',
        ...limited
            .filter((definition) => definition.variableName)
            .map((definition) => `SET ${definition.variableName} = ${definition.modifier};${definition.description ? ` // ${definition.description}` : ''}`),
        '',
        '// Generated child measure variables',
    ];
    const csvOutput = [['measure_name', 'expression', 'description', 'definition_name', 'set_modifier', 'dimensions', 'values'].join(',')];
    const measureRows = [['measure_name', 'expression', 'description', 'definition_name', 'set_modifier', 'dimensions', 'values']];
    const variableRows = [['variable_name', 'definition', 'description', 'source']];

    customVariables.forEach((variable) => {
        variableRows.push([variable.name, variable.definition, variable.description || '', 'custom']);
    });
    baseMeasures.forEach((base) => {
        variableRows.push([`${base.variablePrefix}_Base`, base.baseMeasure, 'Base measure expression', 'base']);
    });
    limited
        .filter((definition) => definition.variableName)
        .forEach((definition) => {
            variableRows.push([definition.variableName, definition.modifier, definition.description || `Definition ${definition.name}`, 'definition']);
        });

    baseMeasures.forEach((base) => {
        const baseState = { ...state, ...base };
        limited.forEach((definition) => {
            const suffix = sanitizeName(definition.name);
            const modifierForExpression = definition.variableName ? `$(${definition.variableName})` : definition.modifier;
            const expression = injectSetAnalysis(base.baseMeasure, modifierForExpression, normalized);
            const variableName = `${base.variablePrefix}_${suffix}`;
            const measureName = `${base.measureName} - ${definition.name}`;
            const finalExpression = state.useVariables ? `$(${variableName})` : expression;
            const description = childDescription(baseState, definition);

            variables.push(`SET ${variableName} = ${expression};`);
            variableRows.push([variableName, expression, `Generated variable for ${base.measureName} / ${definition.name}`, 'generated']);
            modifiers.push(`${definition.name}\n{${setPrefix(normalized.setIdentifier)}${definition.modifier}>}`);
            measures.push(`${measureName}\n${finalExpression}\n\nDescription:\n${description}`);
            if (definition.items?.length) {
                nestedIfRows.push(nestedIfForVariant(definition.items.map((item) => ({
                    ...item,
                    value: item.value.split('\u0000')[0],
                })), base.baseMeasure));
            }
            csvOutput.push([
                csvEscape(measureName),
                csvEscape(finalExpression),
                csvEscape(description),
                csvEscape(definition.name),
                csvEscape(definition.modifier),
                csvEscape(definition.dimensions || ''),
                csvEscape(definition.values || ''),
            ].join(','));
            measureRows.push([
                measureName,
                finalExpression,
                description,
                definition.name,
                definition.modifier,
                definition.dimensions || '',
                definition.values || '',
            ]);
        });
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
        pickMatch: buildPickMatch(rows, baseMeasures[0]?.baseMeasure || state.baseMeasure),
        csv: csvOutput.join('\n'),
        measureRows,
        variableRows,
        measuresCsv: csvOutput.join('\n'),
        variablesCsv: variableRows.map((row) => row.map(csvEscape).join(',')).join('\n'),
        count: limited.length,
    };
}
