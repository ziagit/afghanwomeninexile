<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'name',
        'type',
        'title',
        'body',
        'image',
    ];

    protected static function booted(): void
    {
        static::creating(function (Activity $activity): void {
            $activity->type = $activity->type ?: 'general';
        });
    }
}
