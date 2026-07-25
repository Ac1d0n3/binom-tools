<?php

namespace App\Models\BnTools;

use Illuminate\Database\Eloquent\Model;

class BnPromptStudioLibrary extends Model
{
    protected $table = 'bn_prompt_studio_library';

    protected $primaryKey = 'owner_user_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['owner_user_id', 'payload'];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }
}
