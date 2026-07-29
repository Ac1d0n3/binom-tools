<?php

namespace App\Models\BnTools;

use Illuminate\Database\Eloquent\Model;

class BnUser extends Model
{
    protected $table = 'bn_users';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id', 'email', 'display_name', 'password_hash', 'team_ids',
        'can_manage_users', 'can_manage_teams', 'can_manage_content', 'content_areas',
        'active', 'pending_approval',
        'short_name', 'color_token', 'avatar_icon', 'preferred_role', 'must_change_password',
    ];

    protected function casts(): array
    {
        return [
            'team_ids' => 'array',
            'content_areas' => 'array',
            'can_manage_users' => 'boolean',
            'can_manage_teams' => 'boolean',
            'can_manage_content' => 'boolean',
            'active' => 'boolean',
            'pending_approval' => 'boolean',
            'must_change_password' => 'boolean',
        ];
    }
}
