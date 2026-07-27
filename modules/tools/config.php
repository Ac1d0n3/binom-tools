<?php

use App\Catalog\CatalogJsonLoader;
use App\Support\ToolLinks;

$catalog = CatalogJsonLoader::load('tools');

$catalog['version'] = env('BINOM_TOOLS_VERSION', '1.0.0');
$catalog['beta'] = filter_var(env('BINOM_TOOLS_BETA', false), FILTER_VALIDATE_BOOL);

$links = is_array($catalog['links'] ?? null) ? $catalog['links'] : [];
$links['website'] = env('BINOM_WEBSITE_URL', $links['website'] ?? 'https://binom.net');
$links['repository'] = env('BINOM_TOOLS_REPO_URL', $links['repository'] ?? 'https://github.com/Ac1d0n3/binom-tools');
$links['binom_ngx_docs'] = $links['binom_ngx_docs'] ?? ToolLinks::BINOM_NGX_DOCS;
$links['qlik_binom'] = $links['qlik_binom'] ?? ToolLinks::QLIK_BINOM;
$catalog['links'] = $links;

return $catalog;
