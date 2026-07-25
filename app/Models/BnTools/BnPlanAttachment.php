<?php

namespace App\Models\BnTools;

use Illuminate\Database\Eloquent\Model;

class BnPlanAttachment extends Model
{
    protected $table = 'bn_plan_attachments';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    protected $fillable = ['id', 'plan_id', 'meta'];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }
}
