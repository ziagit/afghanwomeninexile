<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'dob',
        'location',
        'joined_at',
        'expirs_at',
        'note',
        'picture',
        'member_id',
    ];

    protected $casts = [
        'dob' => 'date',
        'joined_at' => 'date',
        'expirs_at' => 'date',
    ];
}
