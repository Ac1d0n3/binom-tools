<?php

namespace App\Models\BnTools;

use Illuminate\Database\Eloquent\Model;

class BnReadState extends Model
{
    protected $table = 'bn_read_state';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['user_id', 'slug', 'read_at'];
}
