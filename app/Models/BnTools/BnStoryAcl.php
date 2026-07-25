<?php

namespace App\Models\BnTools;

use Illuminate\Database\Eloquent\Model;

class BnStoryAcl extends Model
{
    protected $table = 'bn_story_acl';

    protected $primaryKey = 'slug';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['slug', 'visibility', 'user_ids', 'team_ids'];

    protected function casts(): array
    {
        return [
            'user_ids' => 'array',
            'team_ids' => 'array',
        ];
    }
}
