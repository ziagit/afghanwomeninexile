<?php

namespace App\Models;

use App\Models\Concerns\HasDariTranslation;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasDariTranslation;

    protected $fillable = [
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

    /** @return array<int, string> */
    public function paragraphs(): array
    {
        $paragraphs = preg_split("/\R{2,}/", trim((string) $this->translated('body'))) ?: [];

        return array_values(array_filter(array_map('trim', $paragraphs)));
    }
}
