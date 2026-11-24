<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Galery;
use App\Models\Video;
use Illuminate\Database\Seeder;

// Dari translations of the content published before the site was bilingual.
// Run with: php artisan db:seed --class=DariContentSeeder
class DariContentSeeder extends Seeder
{
    public function run(): void
    {
        $content = json_decode(file_get_contents(__DIR__.'/data/dari_content.json'), true);

        $this->fill(Activity::class, $content['activities'], 'title', ['name_fa', 'title_fa', 'body_fa']);
        $this->fill(Galery::class, $content['galeries'], 'name', ['name_fa', 'details_fa']);
        $this->fill(Video::class, $content['videos'], 'name', ['name_fa', 'details_fa']);
    }

    // Only rows whose English still matches get the translation, and Dari an admin already entered is kept.
    private function fill(string $model, array $rows, string $matchField, array $fields): void
    {
        $updated = 0;

        foreach ($rows as $row) {
            $record = $model::find($row['id']);

            if (! $record || $record->{$matchField} !== $row[$matchField]) {
                $this->command?->warn(class_basename($model)." #{$row['id']} skipped: English text differs.");

                continue;
            }

            $missing = collect($fields)
                ->filter(fn (string $field) => blank($record->{$field}))
                ->mapWithKeys(fn (string $field) => [$field => $row[$field]]);

            if ($missing->isNotEmpty()) {
                $record->update($missing->all());
                $updated++;
            }
        }

        $this->command?->info(class_basename($model).": {$updated} filled in Dari.");
    }
}
