<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    /** @return array<int, string> */
    public function paragraphs(): array
    {
        $paragraphs = preg_split("/\R{2,}/", trim((string) $this->body)) ?: [];

        return array_values(array_filter(array_map('trim', $paragraphs)));
    }

    // Tag shown on the public site, e.g. "Joint Statement", in the visitor's language.
    public function typeLabel(): string
    {
        return __(Str::headline((string) $this->type));
    }
}
