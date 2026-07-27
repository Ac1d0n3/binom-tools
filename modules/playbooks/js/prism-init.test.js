import { describe, expect, it } from 'vitest';

import './prism-setup.js';
import Prism from 'prismjs';

import 'prismjs/components/prism-markup';
import 'prismjs/components/prism-markup-templating';
import 'prismjs/components/prism-yaml';
import 'prismjs/components/prism-bash';
import 'prismjs/components/prism-php';
import 'prismjs/components/prism-properties';

describe('playbook prism languages', () => {
    it('highlights yaml and bash without markup-templating errors', () => {
        expect(Prism.languages['markup-templating']?.tokenizePlaceholders).toBeTypeOf('function');

        expect(() => {
            Prism.highlight('key: value\n', Prism.languages.yaml, 'yaml');
        }).not.toThrow();

        expect(() => {
            Prism.highlight('npm install\n', Prism.languages.bash, 'bash');
        }).not.toThrow();
    });
});
