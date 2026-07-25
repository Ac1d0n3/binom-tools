<?php

namespace App\Models\BnTools;

use Illuminate\Database\Eloquent\Model;

class BnPlaybookStat extends Model
{
    protected $table = 'bn_playbook_stats';

    protected $primaryKey = 'slug';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['slug', 'views', 'likes'];
}
