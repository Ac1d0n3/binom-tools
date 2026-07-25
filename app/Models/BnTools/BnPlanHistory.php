<?php

namespace App\Models\BnTools;

use Illuminate\Database\Eloquent\Model;

class BnPlanHistory extends Model
{
    protected $table = 'bn_plan_history';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id', 'plan_id', 'actor_user_id', 'actor_label',
        'action', 'summary', 'snapshot', 'created_at',
    ];

    protected function casts(): array
    {
        return [
            'snapshot' => 'array',
            'created_at' => 'datetime',
        ];
    }
}
