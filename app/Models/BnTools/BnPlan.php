<?php

namespace App\Models\BnTools;

use Illuminate\Database\Eloquent\Model;

class BnPlan extends Model
{
    protected $table = 'bn_plans';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'owner_user_id', 'template_slug', 'payload'];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }
}
