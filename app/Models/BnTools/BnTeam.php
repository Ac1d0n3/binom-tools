<?php

namespace App\Models\BnTools;

use Illuminate\Database\Eloquent\Model;

class BnTeam extends Model
{
    protected $table = 'bn_teams';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id', 'name', 'description', 'member_ids', 'member_roles',
        'archived', 'short_name', 'color_token', 'avatar_icon',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'description' => 'array',
            'member_ids' => 'array',
            'member_roles' => 'array',
            'archived' => 'boolean',
        ];
    }
}
