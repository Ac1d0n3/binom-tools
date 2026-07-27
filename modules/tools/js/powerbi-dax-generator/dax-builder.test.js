import { describe, expect, it } from 'vitest';
import {
    buildDaxCondition,
    buildDaxMeasure,
    buildPowerBiOutputs,
    buildTimeMeasures,
    parseBaseMeasures,
    parseDefinitions,
    parseFields,
    parseHierarchyLevels,
    parseHierarchyPaths,
} from './dax-builder.js';

describe('power bi dax builder', () => {
    it('parses catalog fields and base measures', () => {
        expect(parseFields('table,field,type,tags\nSales,Region,dimension,geo\nSales,Sales,measure,currency')).toEqual([
            { table: 'Sales', name: 'Region', type: 'dimension', tags: 'geo' },
            { table: 'Sales', name: 'Sales', type: 'measure', tags: 'currency' },
        ]);

        expect(parseBaseMeasures('name,expression,description_de,description_en\nSales,SUM(Sales[Sales]),Umsatz,Sales')).toEqual([
            { name: 'Sales', expression: 'SUM(Sales[Sales])', descriptionDe: 'Umsatz', descriptionEn: 'Sales' },
        ]);
    });

    it('builds DAX filter conditions', () => {
        expect(buildDaxCondition({ table: 'Sales', column: 'Region', values: ['DACH'], expression: '' })).toBe('Sales[Region] = "DACH"');
        expect(buildDaxCondition({ table: 'Sales', column: 'Country', values: ['DE', 'AT', 'CH'], expression: '' })).toBe('Sales[Country] IN { "DE", "AT", "CH" }');
    });

    it('parses reusable definitions', () => {
        expect(parseDefinitions('name,table,column,values,expression,description\nRegion DACH,Sales,Region,DACH,,DACH market')).toEqual([
            {
                name: 'Region DACH',
                table: 'Sales',
                column: 'Region',
                values: ['DACH'],
                expression: '',
                description: 'DACH market',
            },
        ]);
    });

    it('wraps base expressions with calculate', () => {
        expect(buildDaxMeasure('SUM(Sales[Sales])', {
            table: 'Sales',
            column: 'Region',
            values: ['DACH'],
            expression: '',
        })).toBe('CALCULATE(\n    SUM(Sales[Sales]),\n    Sales[Region] = "DACH"\n)');
    });

    it('builds time intelligence snippets', () => {
        expect(buildTimeMeasures('Sales')).toContain('Sales YTD =');
        expect(buildTimeMeasures('Sales')).toContain('SAMEPERIODLASTYEAR');
    });

    it('parses indented hierarchy paths and renders howto plus templates', () => {
        expect(parseHierarchyLevels('Sales[Region]\nSales[Country]\nSales[City]')).toEqual([
            'Sales[Region]',
            'Sales[Country]',
            'Sales[City]',
        ]);
        expect(parseHierarchyPaths('Sales[Region]\n  Sales[Country]\n    Sales[City]')).toEqual([
            ['Sales[Region]'],
            ['Sales[Region]', 'Sales[Country]'],
            ['Sales[Region]', 'Sales[Country]', 'Sales[City]'],
        ]);

        const outputs = buildPowerBiOutputs({
            hierarchyText: 'Sales[Region]\n  Sales[Country]\n    Sales[City]',
            baseExpression: 'SUM(Sales[Sales])',
            measureName: 'Sales',
            definitionsText: 'name,table,column,values,expression,description\nRegion DACH,Sales,Region,DACH,,DACH market',
        });

        expect(outputs.hierarchy).toContain('Sales[Region] > Sales[Country] > Sales[City]');
        expect(outputs.hierarchy).toContain('// Measure templates per hierarchy level');
        expect(outputs.hierarchy).toContain('Sales - Sales[Region] / Sales[Country] / Sales[City]');
        expect(outputs.hierarchy).toContain('Sales[Region] = "<Region value>"');
    });

    it('falls back to values with generation mode when definitions are empty', () => {
        const outputs = buildPowerBiOutputs({
            definitionsText: '',
            valuesText: 'table,dimension,value,label\nSales,Region,DACH,DACH\nSales,Country,DE,Germany',
            mode: 'dimension-group',
            baseExpression: 'SUM(Sales[Sales])',
            measureName: 'Sales',
        });

        expect(outputs.measures).toContain('Sales - Region DACH');
        expect(outputs.measures).toContain('Sales - Country Germany');
    });

    it('generates measures, hierarchy help and csv', () => {
        const outputs = buildPowerBiOutputs({
            baseMeasuresText: 'name,expression,description_de,description_en\nSales,SUM(Sales[Sales]),Umsatz,Sales\nMargin,SUM(Sales[Margin]),Marge,Margin',
            definitionsText: 'name,table,column,values,expression,description\nRegion DACH,Sales,Region,DACH,,DACH market',
            hierarchyText: 'Sales[Region]\nSales[Country]\nSales[City]',
        });

        expect(outputs.measures).toContain('Sales - Region DACH =');
        expect(outputs.measures).toContain('CALCULATE(');
        expect(outputs.measures).toContain('Margin - Region DACH =');
        expect(outputs.hierarchy).toContain('Sales[Region] > Sales[Country] > Sales[City]');
        expect(outputs.csv).toContain('measure_name,formula,description_de,description_en,definition,table,column,values');
        expect(outputs.rows).toHaveLength(3);
        expect(parseHierarchyLevels('A > B > C')).toEqual(['A', 'B', 'C']);
    });
});
