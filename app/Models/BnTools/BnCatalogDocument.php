<?php

namespace App\Models\BnTools;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $catalog
 * @property string $facet
 * @property string $checksum
 * @property array<mixed>|null $payload
 */
class BnCatalogDocument extends Model
{
    protected $table = 'bn_catalog_documents';

    protected $fillable = [
        'catalog',
        'facet',
        'checksum',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
