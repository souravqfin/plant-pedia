<?php

namespace App\Services\Search;

use App\Models\Plant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SearchService
{
    public function __construct(private readonly TransliterationService $transliteration) {}

    public function search(string $query, string $locale, array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $variants = $this->transliteration->variants(trim($query));

        $builder = Plant::query()
            ->published()
            ->with(['taxon.rank', 'taxon.parent', 'translations', 'names', 'categories.translations', 'media', 'careGuide'])
            ->where(function ($q) use ($variants, $locale) {
                foreach ($variants as $variant) {
                    $v = '%'.$this->escapeLike($variant).'%';
                    $q->orWhereHas('names', fn ($n) => $n->where('name', 'like', $v))
                        ->orWhereHas('translations', fn ($t) => $t->where('locale', $locale)->where('common_name', 'like', $v))
                        ->orWhereHas('translations', fn ($t) => $t->where('common_name', 'like', $v))
                        ->orWhereHas('taxon', fn ($t) => $t->where('scientific_name', 'like', $v))
                        ->orWhereHas('taxon.names', fn ($n) => $n->where('name', 'like', $v));
                }
            });

        if (! empty($filters['category'])) {
            $builder->whereHas('categories', fn ($q) => $q->where('slug', $filters['category']));
        }

        if (! empty($filters['family'])) {
            $builder->whereHas('taxon.parent', fn ($q) => $q->where('scientific_name', $filters['family']));
        }

        if (! empty($filters['habit'])) {
            $builder->whereHas('characteristics', fn ($q) => $q->where('habit', $filters['habit']));
        }

        return $builder->orderByDesc('is_featured')->orderBy('id')->paginate($perPage)->withQueryString();
    }

    public function suggest(string $query, string $locale, int $limit = 8): array
    {
        if (mb_strlen(trim($query)) < 2) {
            return [];
        }

        return $this->search($query, $locale, [], $limit)
            ->getCollection()
            ->map(function (Plant $plant) use ($locale) {
                $translation = $plant->translation($locale);

                return [
                    'id' => $plant->id,
                    'slug' => $plant->slug,
                    'name' => $translation?->common_name ?? $plant->taxon?->scientific_name,
                    'scientific_name' => $plant->taxon?->scientific_name,
                ];
            })
            ->all();
    }

    private function escapeLike(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
    }
}
