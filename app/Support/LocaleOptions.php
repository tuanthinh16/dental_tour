<?php

namespace App\Support;

class LocaleOptions
{
    public const DEFAULT = 'vi';

    public const SUPPORTED = ['vi', 'en'];

    public static function isSupported(string $locale): bool
    {
        return in_array($locale, self::SUPPORTED, true);
    }

    public static function settingKey(string $key, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return $locale === self::DEFAULT ? $key : $key.'_'.$locale;
    }
}
