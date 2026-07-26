<?php

namespace App\Models\BnTools;

use Illuminate\Database\Eloquent\Model;

class BnGovernanceRadarSource extends Model
{
    protected $table = 'bn_governance_radar_sources';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'owner_user_id',
        'name',
        'feed_url',
        'source_url',
        'type',
        'region',
        'language',
        'cadence',
        'topics',
        'note',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'topics' => 'array',
            'active' => 'boolean',
        ];
    }
}
