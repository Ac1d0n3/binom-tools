import { describe, expect, it } from 'vitest';
import { buildCurrentFormula, buildModifier, buildSetAnalysisOutputs, buildTimeVariables, injectSetAnalysis, parseBaseMeasures, parseCsv, parseDefinitions, parseFields, parseHierarchyLevels, parseVariables } from './set-analysis-builder.js';

describe('set analysis builder', () => {
    it('parses csv with quoted values', () => {
        expect(parseCsv('dimension,value,label\nRegion,"North, America",NA')).toEqual([
            { dimension: 'Region', value: 'North, America', label: 'NA', operator: '', assignment: '' },
        ]);
    });

    it('parses imported app fields and variables', () => {
        expect(parseFields('field,type,tags\nRegion,dimension,geo\nSales,measure,currency')).toEqual([
            { name: 'Region', type: 'dimension', tags: 'geo' },
            { name: 'Sales', type: 'measure', tags: 'currency' },
        ]);
        expect(parseVariables('name,definition,description\nvSales,Sum(Sales),Base measure')).toEqual([
            { name: 'vSales', definition: 'Sum(Sales)', description: 'Base measure' },
        ]);
        expect(parseVariables('name,definition,description\nvSetRegion,"[Region]={\'DACH\',\'AT\',\'CH\'}",Multi region set')).toEqual([
            { name: 'vSetRegion', definition: "[Region]={'DACH','AT','CH'}", description: 'Multi region set' },
        ]);
    });

    it('parses multiple base measures from csv', () => {
        expect(parseBaseMeasures('name,expression,prefix,description_de,description_en\nSales,Sum(Sales),vSales,Umsatz,Sales\nMargin,Sum(Margin),vMargin,Marge,Margin')).toEqual([
            { measureName: 'Sales', baseMeasure: 'Sum(Sales)', variablePrefix: 'vSales', baseDescription: 'Umsatz', baseDescriptionEn: 'Sales' },
            { measureName: 'Margin', baseMeasure: 'Sum(Margin)', variablePrefix: 'vMargin', baseDescription: 'Marge', baseDescriptionEn: 'Margin' },
        ]);
    });

    it('parses reusable set-analysis definitions from csv', () => {
        expect(parseDefinitions('name,modifier,variable,description,dimensions,values\nRegion DACH,[Region]={\'DACH\'},vDefRegionDACH,DACH filter,Region,DACH')).toEqual([
            {
                name: 'Region DACH',
                modifier: "[Region]={'DACH'}",
                variableName: 'vDefRegionDACH',
                description: 'DACH filter',
                dimensions: 'Region',
                values: 'DACH',
                items: [],
                source: 'definition',
            },
        ]);
    });

    it('parses hierarchy levels from lines or arrows', () => {
        expect(parseHierarchyLevels('Region\nSalesperson\nCustomer')).toEqual(['Region', 'Salesperson', 'Customer']);
        expect(parseHierarchyLevels('Region > Salesperson > Customer')).toEqual(['Region', 'Salesperson', 'Customer']);
    });

    it('injects set analysis into a base aggregation', () => {
        expect(injectSetAnalysis('Sum(Sales)', "[Region]={'DACH'}")).toBe("Sum({$<[Region]={'DACH'}>} Sales)");
    });

    it('merges set analysis into an existing aggregation modifier', () => {
        expect(injectSetAnalysis("Sum({$<[Year]={'$(vCurrentYear)'}>} Sales)", "[Region]={'DACH'}"))
            .toBe("Sum({$<[Year]={'$(vCurrentYear)'}, [Region]={'DACH'}>} Sales)");
    });

    it('injects set analysis into every aggregation of a complex KPI expression', () => {
        const yoyPercent = "(Sum({$<[Year]={'$(vCurrentYear)'}>} Sales) - Sum({$<[Year]={'$(vPreviousYear)'}>} Sales)) / Sum({$<[Year]={'$(vPreviousYear)'}>} Sales)";

        expect(injectSetAnalysis(yoyPercent, "[Region]={'DACH'}")).toBe(
            "(Sum({$<[Year]={'$(vCurrentYear)'}, [Region]={'DACH'}>} Sales) - Sum({$<[Year]={'$(vPreviousYear)'}, [Region]={'DACH'}>} Sales)) / Sum({$<[Year]={'$(vPreviousYear)'}, [Region]={'DACH'}>} Sales)",
        );
    });

    it('can generate outer set analysis', () => {
        expect(injectSetAnalysis('Sum(Sales)', "[Region]={'DACH'}", {
            setIdentifier: '1',
            expressionStyle: 'outer',
        })).toBe("{1<[Region]={'DACH'}>} Sum(Sales)");
    });

    it('keeps variable base measures usable with outer set analysis', () => {
        expect(injectSetAnalysis('$(vSales)', "[Region]={'DACH'}")).toBe("{$<[Region]={'DACH'}>} $(vSales)");
    });

    it('builds modifiers with assignment operators and search strings', () => {
        const modifier = buildModifier([
            { dimension: 'Region', value: '=Sum(Sales)>0', label: 'Selling', operator: 'search', assignment: '+=' },
        ], { assignment: '=', valueMode: 'literal' });

        expect(modifier).toBe('[Region]+={"=Sum(Sales)>0"}');
    });

    it('builds a current formula preview from the first dimension value', () => {
        expect(buildCurrentFormula({
            baseMeasure: 'Sum(Sales)',
            rowsText: 'dimension,value,label\nRegion,DACH,DACH\nChannel,Online,Online',
            mode: 'single',
            setIdentifier: '$',
            assignment: '=',
            expressionStyle: 'inner',
            valueMode: 'literal',
        })).toBe("Sum({$<[Region]={'DACH'}>} Sales)");
    });

    it('builds a set search formula preview from an expression value', () => {
        expect(buildCurrentFormula({
            baseMeasure: 'Sum(Sales)',
            rowsText: 'dimension,value,label,operator\nCustomer,=Sum(Sales)>1000,High value,search',
            mode: 'single',
            setIdentifier: '$',
            assignment: '=',
            expressionStyle: 'inner',
            valueMode: 'literal',
        })).toBe('Sum({$<[Customer]={"=Sum(Sales)>1000"}>} Sales)');
    });

    it('builds variables and child measures for dimension values', () => {
        const outputs = buildSetAnalysisOutputs({
            measureName: 'Sales',
            baseMeasure: 'Sum(Sales)',
            baseDescription: 'Umsatz basierend auf Sum(Sales).',
            variablePrefix: 'vSales',
            rowsText: 'dimension,value,label\nRegion,DACH,DACH\nChannel,Online,Online',
            variablesText: 'name,definition,description\nvBaseSales,Sum(Sales),Imported base variable',
            hierarchyText: 'Region\nSalesperson\nCustomer',
            mode: 'single',
            useVariables: true,
            setIdentifier: '$',
            assignment: '=',
            expressionStyle: 'inner',
            valueMode: 'literal',
        });

        expect(outputs.variables).toContain("SET vDefRegion_DACH = [Region]={'DACH'};");
        expect(outputs.variables).toContain('SET vSales_Region_DACH = Sum({$<$(vDefRegion_DACH)>} Sales);');
        expect(outputs.variables).toContain('SET vBaseSales = Sum(Sales); // Imported base variable');
        expect(outputs.hierarchy).toContain('Type: Drill-down dimension');
        expect(outputs.hierarchy).toContain('Sales - Region / Salesperson / Customer');
        expect(outputs.measures).toContain('Sales - Region DACH');
        expect(outputs.measures).toContain('$(vSales_Region_DACH)');
        expect(outputs.measures).toContain('Description:');
        expect(outputs.measures).toContain('Umsatz basierend auf Sum(Sales).');
        expect(outputs.measures).toContain('Erweiterung: Region DACH.');
        expect(outputs.modifiers).toContain("{$<[Region]={'DACH'}>}");
        expect(outputs.timeVariables).toContain('SET vSetYTD');
        expect(outputs.nestedIf).toContain("If([Region]='DACH', Sum(Sales))");
        expect(outputs.pickMatch).toContain('Pick(');
        expect(outputs.csv).toContain('measure_name,expression,description,definition_name,set_modifier,dimensions,values');
    });

    it('uses editable description templates with placeholders', () => {
        const outputs = buildSetAnalysisOutputs({
            measureName: 'Sales',
            baseMeasure: 'Sum(Sales)',
            baseDescription: 'Umsatz KPI.',
            baseDescriptionEn: 'Sales KPI.',
            childDescriptionTemplate: '{baseDescription} DE {measureName} {label} {dimensions} {values}',
            childDescriptionTemplateEn: '{baseDescription} EN {baseMeasure} {modifier}',
            descriptionLanguage: 'both',
            variablePrefix: 'vSales',
            rowsText: 'dimension,value,label\nRegion,DACH,DACH',
            mode: 'single',
            useVariables: false,
            setIdentifier: '$',
            assignment: '=',
            expressionStyle: 'inner',
            valueMode: 'literal',
        });

        expect(outputs.measures).toContain('DE:\nUmsatz KPI. DE Sales Region DACH Region DACH');
        expect(outputs.measures).toContain("EN:\nSales KPI. EN Sum(Sales) [Region]={'DACH'}");
        expect(outputs.csv).toContain('Umsatz KPI. DE Sales Region DACH Region DACH');
        expect(outputs.measureRows[1][2]).toContain('Sales KPI. EN Sum(Sales)');
    });

    it('generates child measures for multiple base measures with the same filters', () => {
        const outputs = buildSetAnalysisOutputs({
            measureName: 'Sales',
            baseMeasure: 'Sum(Sales)',
            variablePrefix: 'vSales',
            baseMeasuresText: 'name,expression,prefix,description_de,description_en\nSales,Sum(Sales),vSales,Umsatz,Sales\nMargin,Sum(Margin),vMargin,Marge,Margin',
            rowsText: 'dimension,value,label\nRegion,DACH,DACH',
            mode: 'single',
            useVariables: true,
            setIdentifier: '$',
            assignment: '=',
            expressionStyle: 'inner',
            valueMode: 'literal',
        });

        expect(outputs.measures).toContain('Sales - Region DACH');
        expect(outputs.measures).toContain('$(vSales_Region_DACH)');
        expect(outputs.measures).toContain('Margin - Region DACH');
        expect(outputs.measures).toContain('$(vMargin_Region_DACH)');
        expect(outputs.variables).toContain('SET vMargin_Region_DACH = Sum({$<$(vDefRegion_DACH)>} Margin);');
        expect(outputs.measureRows).toHaveLength(3);
    });

    it('uses saved definitions instead of raw dimension values when present', () => {
        const outputs = buildSetAnalysisOutputs({
            measureName: 'Sales',
            baseMeasure: 'Sum(Sales)',
            variablePrefix: 'vSales',
            definitionsText: 'name,modifier,variable,description,dimensions,values\nCurrent Year,[Year]={$(vCurrentYear)},vDefCurrentYear,Current year,Year,$(vCurrentYear)',
            rowsText: 'dimension,value,label\nRegion,DACH,DACH',
            mode: 'single',
            useVariables: false,
            setIdentifier: '$',
            assignment: '=',
            expressionStyle: 'inner',
            valueMode: 'literal',
        });

        expect(outputs.measures).toContain('Sales - Current Year');
        expect(outputs.measures).toContain('Sum({$<$(vDefCurrentYear)>} Sales)');
        expect(outputs.measures).not.toContain('Sales - Region DACH');
    });

    it('builds hierarchy output from sibling and child levels', () => {
        const outputs = buildSetAnalysisOutputs({
            measureName: 'Sales',
            baseMeasure: 'Sum(Sales)',
            variablePrefix: 'vSales',
            rowsText: '',
            hierarchyText: 'Region\nSalesperson\n  Customer',
            mode: 'single',
            useVariables: true,
            setIdentifier: '$',
            assignment: '=',
            expressionStyle: 'inner',
            valueMode: 'literal',
        });

        expect(outputs.hierarchy).toContain('Sales - Region');
        expect(outputs.hierarchy).toContain('Sales - Salesperson');
        expect(outputs.hierarchy).toContain('Sales - Salesperson / Customer');
        expect(outputs.hierarchy).not.toContain('Sales - Region / Salesperson');
    });

    it('can combine dimensions into set variants', () => {
        const outputs = buildSetAnalysisOutputs({
            measureName: 'Sales',
            baseMeasure: 'Sum(Sales)',
            variablePrefix: 'vSales',
            rowsText: 'dimension,value\nRegion,DACH\nRegion,NA\nChannel,Online',
            mode: 'combined',
            useVariables: false,
            setIdentifier: '$',
            assignment: '=',
            expressionStyle: 'inner',
            valueMode: 'literal',
        });

        expect(outputs.count).toBe(2);
        expect(outputs.measures).toContain("[Region]={'DACH'}, [Channel]={'Online'}");
    });

    it('can build one measure per dimension group', () => {
        const outputs = buildSetAnalysisOutputs({
            measureName: 'Sales',
            baseMeasure: 'Sum(Sales)',
            variablePrefix: 'vSales',
            rowsText: 'dimension,value\nRegion,DACH\nRegion,NA\nChannel,Online',
            mode: 'dimension-group',
            useVariables: false,
            setIdentifier: '$',
            assignment: '=',
            expressionStyle: 'inner',
            valueMode: 'literal',
        });

        expect(outputs.count).toBe(2);
        expect(outputs.measures).toContain("[Region]={'DACH','NA'}");
        expect(outputs.measures).toContain("[Channel]={'Online'}");
    });

    it('builds standard time variables for selected date fields', () => {
        const output = buildTimeVariables({ dateField: 'Order Date', yearField: 'Fiscal Year' });

        expect(output).toContain("SET vSetCY = [Fiscal Year]={'$(vCurrentYear)'};");
        expect(output).toContain('SET vSetYTD = [Order Date]={">=$(=YearStart(Today()))<=$(=Today())"};');
        expect(output).toContain('Sum({$<$(vSetLast12M)>} Sales)');
    });
});
