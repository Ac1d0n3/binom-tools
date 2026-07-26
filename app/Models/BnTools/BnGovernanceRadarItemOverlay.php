<?php

namespace App\Models\BnTools;

use Illuminate\Database\Eloquent\Model;

class BnGovernanceRadarItemOverlay extends Model
{
    protected $table = 'bn_governance_radar_item_overlays';

    public $incrementing = false;

    protected $primaryKey = 'item_id';

    protected $keyType = 'string';

    protected $fillable = [
        'item_id',
        'updated_by_user_id',
        'title_de',
        'summary_de',
        'recommended_action_de',
        'editorial_note',
        'impact',
    ];
}
