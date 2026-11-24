<?php

namespace App\Models;

use App\Models\Concerns\HasDariTranslation;
use Illuminate\Database\Eloquent\Model;

class Galery extends Model
{
    use HasDariTranslation;

    protected $fillable = [
        'name',
        'name_fa',
        'details',
        'details_fa',
        'picture',
    ];
}
