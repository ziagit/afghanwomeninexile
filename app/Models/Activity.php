<?php

namespace App\Models;

use App\Models\Concerns\HasDariTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Activity extends Model
{
    use HasDariTranslation;

    protected $fillable = [
        'name',
        'name_fa',
        'type',
        'title',
        'title_fa',
        'body',
        'body_fa',
        'image',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Activity $activity): void {
            $activity->type = $activity->type ?: 'general';
            $activity->published_at = $activity->published_at ?: now();
        });
    }

    /** @return array<int, string> */
    public function paragraphs(): array
    {
        $paragraphs = preg_split("/\R{2,}/", trim((string) $this->translated('body'))) ?: [];

        return array_values(array_filter(array_map('trim', $paragraphs)));
    }

    // Tag shown on the public site, e.g. "Joint Statement", in the visitor's language.
    public function typeLabel(): string
    {
        return __(Str::headline((string) $this->type));
    }
}
