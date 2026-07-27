<?php

namespace App\Models\BnTools;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property array<string, mixed>|null $payload
 */
class BnLinkCheck extends Model
{
    protected $table = 'bn_link_checks';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
