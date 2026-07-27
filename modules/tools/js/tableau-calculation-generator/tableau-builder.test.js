import { describe, expect, it } from 'vitest';
import {
    buildCalculatedField,
    buildLodExpression,
    buildTableauCondition,
    buildTableauOutputs,
    parseBaseMeasures,
    parseDefinitions,
    parseFields,
    parseHierarchyLevels,
    parseHierarchyPaths,
} from './tableau-builder.js';

describe('tableau calculation builder', () => {
    it('parses app catalog fields and base measures', () => {
        expect(parseFields('field,type,tags\nRegion,dimension,geo\nSales,measure,currency')).toEqual([
            { name: 'Region', type: 'dimension', tags: 'geo' },
            { name: 'Sales', type: 'measure', tags: 'currency' },
        ]);

        expect(parseBaseMeasures('name,expression,description_de,description_en\nSales,SUM([Sales]),Umsatz,Sales')).toEqual([
            { name: 'Sales', expression: 'SUM([Sales])', descriptionDe: 'Umsatz', descriptionEn: 'Sales' },
        ]);
    });

    it('parses reusable definitions', () => {
        expect(parseDefinitions('name,dimensions,values,expression,description\nRegion DACH,Region,DACH,,DACH market')).toEqual([
            {
                name: 'Region DACH',
                dimensions: ['Region'],
                values: ['DACH'],
                expression: '',
                parameter: '',
                description: 'DACH market',
            },
        ]);
    });

    it('builds Tableau conditions for single and multiple values', () => {
        expect(buildTableauCondition('Region', ['DACH'])).toBe("[Region] = 'DACH'");
        expect(buildTableauCondition('Country', ['DE', 'AT', 'CH'])).toBe("[Country] IN ('DE', 'AT', 'CH')");
    });

    it('wraps aggregation expressions with conditional aggregation', () => {
        expect(buildCalculatedField('SUM([Sales])', {
            name: 'Region DACH',
            dimensions: ['Region'],
            values: ['DACH'],
            expression: '',
        })).toBe("SUM(IF [Region] = 'DACH' THEN [Sales] END)");
    });

    it('builds fixed lod expressions', () => {
        expect(buildLodExpression('SUM([Sales])', {
            name: 'Region DACH',
            dimensions: ['Region'],
            values: ['DACH'],
            expression: '',
        }, ['Region'])).toBe("{ FIXED [Region] : SUM(IF [Region] = 'DACH' THEN [Sales] END) }");
    });

    it('parses indented hierarchy paths and renders howto plus templates', () => {
        expect(parseHierarchyLevels('Region\nCountry\nCity')).toEqual(['Region', 'Country', 'City']);
        expect(parseHierarchyPaths('Region\n  Country\n    City')).toEqual([
            ['Region'],
            ['Region', 'Country'],
            ['Region', 'Country', 'City'],
        ]);

        const outputs = buildTableauOutputs({
            hierarchyText: 'Region\n  Country\n    City',
            baseExpression: 'SUM([Sales])',
            measureName: 'Sales',
            definitionsText: 'name,dimensions,values,expression,description\nRegion DACH,Region,DACH,,DACH market',
        });

        expect(outputs.hierarchy).toContain('Name: Region / Country / City');
        expect(outputs.hierarchy).toContain('[Region] > [Country] > [City]');
        expect(outputs.hierarchy).toContain('// Measure templates per hierarchy level');
        expect(outputs.hierarchy).toContain('Sales - Region / Country / City');
        expect(outputs.hierarchy).toContain("[Region] = '<Region value>' AND [Country] = '<Country value>' AND [City] = '<City value>'");
    });

    it('falls back to values with generation mode when definitions are empty', () => {
        const outputs = buildTableauOutputs({
            definitionsText: '',
            valuesText: 'dimension,value,label\nRegion,DACH,DACH\nCountry,DE,Germany',
            mode: 'dimension-group',
            baseExpression: 'SUM([Sales])',
            measureName: 'Sales',
        });

        expect(outputs.calculations).toContain('Sales - Region DACH');
        expect(outputs.calculations).toContain('Sales - Country Germany');
    });

    it('generates calculations from base measures and definitions', () => {
        const outputs = buildTableauOutputs({
            baseMeasuresText: 'name,expression,description_de,description_en\nSales,SUM([Sales]),Umsatz,Sales\nMargin,SUM([Margin]),Marge,Margin',
            definitionsText: 'name,dimensions,values,expression,description\nRegion DACH,Region,DACH,,DACH market',
        });

        expect(outputs.calculations).toContain('Sales - Region DACH');
        expect(outputs.calculations).toContain("SUM(IF [Region] = 'DACH' THEN [Sales] END)");
        expect(outputs.calculations).toContain('Margin - Region DACH');
        expect(outputs.csv).toContain('calculation_name,formula,description_de,description_en,definition,dimensions,values');
        expect(outputs.rows).toHaveLength(3);
    });
});
