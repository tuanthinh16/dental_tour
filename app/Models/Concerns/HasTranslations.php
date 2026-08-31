<?php

namespace App\Models\Concerns;

use App\Support\LocaleOptions;
use Illuminate\Support\Arr;

trait HasTranslations
{
    public function translated(string $attribute, ?string $locale = null): mixed
    {
        $locale ??= app()->getLocale();
        $value = $this->getAttributeValue($attribute);

        if ($locale === LocaleOptions::DEFAULT) {
            return $value;
        }

        $translations = $this->getAttributeValue('translations') ?? [];

        return Arr::get($translations, $locale.'.'.$attribute, $value) ?: $value;
    }

    public function replaceTranslation(string $locale, array $values): void
    {
        $translations = $this->getAttributeValue('translations') ?? [];
        $translations[$locale] = array_merge($translations[$locale] ?? [], $values);

        $this->forceFill(['translations' => $translations])->save();
    }
}
