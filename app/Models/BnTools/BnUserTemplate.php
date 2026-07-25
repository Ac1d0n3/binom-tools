<?php

namespace App\Models\BnTools;

use Illuminate\Database\Eloquent\Model;

class BnUserTemplate extends Model
{
    protected $table = 'bn_user_templates';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'owner_user_id', 'payload'];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }
}
