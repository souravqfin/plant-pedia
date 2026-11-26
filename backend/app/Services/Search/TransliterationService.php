<?php

namespace App\Services\Search;

class TransliterationService
{
    private const MAP = [
        'kh' => 'খ', 'gh' => 'ঘ', 'chh' => 'ছ', 'ch' => 'চ', 'jh' => 'ঝ',
        'th' => 'থ', 'dh' => 'ধ', 'ph' => 'ফ', 'bh' => 'ভ', 'sh' => 'শ',
        'ng' => 'ং', 'aa' => 'আ', 'ee' => 'ঈ', 'oo' => 'উ', 'ou' => 'ৌ',
        'oi' => 'ই', 'ai' => 'আই', 'a' => 'আ', 'b' => 'ব', 'c' => 'চ',
        'd' => 'দ', 'e' => 'এ', 'f' => 'ফ', 'g' => 'গ', 'h' => 'হ',
        'i' => 'ই', 'j' => 'জ', 'k' => 'ক', 'l' => 'ল', 'm' => 'ম',
        'n' => 'ন', 'o' => 'ও', 'p' => 'প', 'q' => 'ক', 'r' => 'র',
        's' => 'স', 't' => 'ত', 'u' => 'উ', 'v' => 'ভ', 'w' => 'ও',
        'x' => 'ক্স', 'y' => 'য', 'z' => 'জ',
    ];

    public function toBengali(string $query): ?string
    {
        $query = strtolower(trim($query));

        if ($query === '' || preg_match('/[\x{0980}-\x{09FF}]/u', $query)) {
            return null;
        }

        $out = '';
        $i = 0;
        $len = strlen($query);

        while ($i < $len) {
            $matched = false;

            foreach ([3, 2, 1] as $size) {
                $chunk = substr($query, $i, $size);

                if ($chunk !== '' && isset(self::MAP[$chunk])) {
                    $out .= self::MAP[$chunk];
                    $i += $size;
                    $matched = true;
                    break;
                }
            }

            if (! $matched) {
                $i++;
            }
        }

        return $out === '' ? null : $out;
    }

    public function variants(string $query): array
    {
        $variants = [$query];
        $bengali = $this->toBengali($query);

        if ($bengali) {
            $variants[] = $bengali;
        }

        return array_values(array_unique(array_filter($variants)));
    }
}
