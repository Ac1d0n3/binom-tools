<?php

namespace App\Models\BnTools;

use Illuminate\Database\Eloquent\Model;

class BnGovernanceRadarFeedSync extends Model
{
    protected $table = 'bn_governance_radar_feed_syncs';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $primaryKey = 'source_id';

    protected $fillable = [
        'source_id',
        'feed_url',
        'last_synced_at',
        'last_status',
        'last_error',
        'item_count',
    ];

    protected function casts(): array
    {
        return [
            'last_synced_at' => 'datetime',
            'item_count' => 'integer',
        ];
    }
}
