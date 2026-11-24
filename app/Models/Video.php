<?php

namespace App\Models;

use App\Models\Concerns\HasDariTranslation;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasDariTranslation;

    protected $fillable = [
        'name',
        'name_fa',
        'video_link',
        'details',
        'details_fa',
        'thumbnail',
    ];
}
