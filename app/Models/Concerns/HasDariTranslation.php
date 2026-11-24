<?php

namespace App\Models\Concerns;

// Content entered in English plus an optional Dari copy in "<field>_fa".
trait HasDariTranslation
{
    // The field in the visitor's language, falling back to English when no Dari copy was entered.
    public function translated(string $attribute): ?string
    {
        if (app()->getLocale() === 'fa' && filled($this->getAttribute($attribute.'_fa'))) {
            return $this->getAttribute($attribute.'_fa');
        }

        return $this->getAttribute($attribute);
    }
}
