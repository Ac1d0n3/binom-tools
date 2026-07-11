#!/usr/bin/env node
/**
 * Build assets + pack the minimum upload set for governance.binom.net.
 * Does NOT modify public/.htaccess in the working tree (local dev stays intact).
 *
 * Usage: npm run deploy:pack
 */
import { cpSync, existsSync, mkdirSync, rmSync, writeFileSync } from 'node:fs';
import { execSync } from 'node:child_process';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const outDir = join(root, 'deploy-upload');

/** Paths relative to project root — upload these into the existing server tree. */
const deployPaths = [
    'public/build',
    'public/images',
    'resources/views',
    'content',
    'app/Playbooks',
    'app/Support',
    'app/Catalog',
    'app/Http/Controllers/Tools',
    'config/tools.php',
    'routes/web.php',
];

console.log('Building assets (local .htaccess unchanged)…');
execSync('vite build && node scripts/rewrite-build-asset-urls.mjs', {
    cwd: root,
    stdio: 'inherit',
});

if (existsSync(outDir)) {
    rmSync(outDir, { recursive: true, force: true });
}
mkdirSync(outDir, { recursive: true });

for (const rel of deployPaths) {
    const src = join(root, rel);
    const dest = join(outDir, rel);

    if (!existsSync(src)) {
        console.warn(`Skip missing: ${rel}`);
        continue;
    }

    mkdirSync(dirname(dest), { recursive: true });
    cpSync(src, dest, { recursive: true });
}

mkdirSync(join(outDir, 'public'), { recursive: true });
cpSync(join(root, 'public/.htaccess.production'), join(outDir, 'public/.htaccess'));

writeFileSync(
    join(outDir, 'UPLOAD.txt'),
    `Deploy upload for governance.binom.net
=====================================

0. FIRST FIX if /tools returns Apache 500:
   Upload deploy-upload/public/.htaccess → server public/.htaccess
   (must be RewriteBase / — NOT the local /binom-tools/ variant)

1. Merge deploy-upload/ into your server webroot (keep folder structure).

2. Required folders in this pack:
   - public/build/          compiled JS/CSS
   - public/images/         logos, playbook art, dbt badge
   - public/.htaccess       production rewrite rules (domain root)
   - resources/views/       Blade templates + components
   - content/               playbook markdown
   - app/Support/           ToolsNav, ToolWorkflow (required with view changes!)
   - app/Playbooks/         playbook renderer
   - app/Catalog/           landing catalog
   - app/Http/Controllers/Tools/
   - config/tools.php       tool nav + dbt flags
   - routes/web.php

   IMPORTANT: Upload the whole deploy-upload/ tree — not only public/.
   Views often depend on matching PHP under app/ and config/.

3. Optional cache clear on server (if views look stale):
   - storage/framework/views/*.php

4. Local dev: npm run build  (syncs .htaccess from APP_URL automatically)

Build: ${new Date().toISOString()}
`,
);

console.log(`\nReady: ${outDir}`);
console.log('Upload deploy-upload/ to the server. Local public/.htaccess was not changed.');
