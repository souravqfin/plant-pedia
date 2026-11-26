<?php

namespace App\Support;

use Illuminate\Support\Str;

class Slug
{
    public static function make(string $value, ?string $fallback = null): string
    {
        $slug = Str::slug($value);

        if ($slug === '') {
            $slug = Str::slug($fallback ?: 'item');
        }

        return $slug ?: 'item';
    }

    public static function unique(string $modelClass, string $value, ?int $ignoreId = null, string $column = 'slug'): string
    {
        $base = self::make($value);
        $slug = $base;
        $i = 2;

        while ($modelClass::withTrashed()
            ->where($column, $slug)
            ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
            ->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
