<?php

namespace App\Models\BnTools;

use Illuminate\Database\Eloquent\Model;

class BnGlossaryQuizResult extends Model
{
    protected $table = 'bn_glossary_quiz_results';

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
