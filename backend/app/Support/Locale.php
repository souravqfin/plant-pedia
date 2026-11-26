<?php

namespace App\Support;

class Locale
{
    public static function resolve(?string $requested = null): string
    {
        $supported = config('api.locales', ['bn', 'en']);
        $candidate = $requested ?: request()->header('Accept-Language');
        $candidate = $candidate ? strtolower(substr(trim(explode(',', $candidate)[0]), 0, 2)) : null;

        if ($candidate && in_array($candidate, $supported, true)) {
            return $candidate;
        }

        return config('api.default_locale', 'bn');
    }

    public static function other(string $locale): string
    {
        return $locale === 'bn' ? 'en' : 'bn';
    }
}
