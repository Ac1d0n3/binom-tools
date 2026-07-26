<?php

namespace App\Models\BnTools;

use Illuminate\Database\Eloquent\Model;

class BnGovernanceSession extends Model
{
    protected $table = 'bn_governance_sessions';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'owner_user_id',
        'title',
        'company_name',
        'project_name',
        'scenario',
        'status',
        'current_step',
        'payload',
        'validation_summary',
        'report_snapshot',
        'archived_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'validation_summary' => 'array',
            'report_snapshot' => 'array',
            'archived_at' => 'datetime',
        ];
    }
}
