<?php

namespace App\Models\BnTools;

use Illuminate\Database\Eloquent\Model;

class BnGovernanceRadarFeedItem extends Model
{
    protected $table = 'bn_governance_radar_feed_items';

    protected $fillable = [
        'source_id',
        'guid',
        'title',
        'summary',
        'url',
        'published_at',
        'language',
        'raw_topics',
        'fetched_at',
    ];

    protected function casts(): array
    {
        return [
            'raw_topics' => 'array',
            'published_at' => 'datetime',
            'fetched_at' => 'datetime',
        ];
    }
}
