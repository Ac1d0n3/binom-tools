<?php

/**
 * Supplier Library Hub — loaded from content/catalogs/suppliers/*.json
 */
use App\Catalog\CatalogJsonLoader;

return CatalogJsonLoader::load('suppliers');
