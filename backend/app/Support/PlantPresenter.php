<?php

namespace App\Support;

use App\Models\Plant;

class PlantPresenter
{
    public static function card(Plant $plant, string $locale): array
    {
        $translation = $plant->translation($locale);
        $primary = $plant->media->firstWhere('pivot.is_primary', true) ?? $plant->media->first();

        return [
            'id' => $plant->id,
            'slug' => $plant->slug,
            'name' => $translation?->common_name ?? $plant->taxon?->scientific_name,
            'scientific_name' => $plant->taxon?->scientific_name,
            'family' => $plant->taxon?->parent?->scientific_name,
            'thumbnail' => $primary?->url,
            'verification_status' => $plant->verification_status,
            'is_featured' => $plant->is_featured,
            'categories' => $plant->categories->map(fn ($c) => [
                'slug' => $c->slug,
                'name' => $c->translation($locale)?->name,
            ])->values()->all(),
        ];
    }

    public static function detail(Plant $plant, string $locale): array
    {
        $translation = $plant->translation($locale);
        $taxon = $plant->taxon;
        $rank = $taxon?->rank?->name;

        return array_merge(self::card($plant, $locale), [
            'author_citation' => $taxon?->author_citation,
            'taxon' => [
                'id' => $taxon?->id,
                'rank' => $rank,
                'family' => $taxon?->parent?->scientific_name,
                'genus' => $rank === 'genus' ? $taxon?->scientific_name : null,
            ],
            'names' => $plant->translations->mapWithKeys(fn ($t) => [$t->locale => $t->common_name])->filter()->all(),
            'local_names' => $plant->names->map(fn ($n) => [
                'name' => $n->name,
                'locale' => $n->locale,
                'region' => $n->region?->name,
                'source_id' => $n->source_id,
            ])->values()->all(),
            'description' => $translation?->description,
            'habitat' => $translation?->habitat,
            'uses' => $translation?->uses,
            'care_summary' => $translation?->care_summary,
            'characteristics' => $plant->characteristics ? $plant->characteristics->only([
                'habit', 'height_min_cm', 'height_max_cm', 'leaf_type', 'leaf_arrangement',
                'flower_color', 'flower_shape', 'fruit_type', 'bark_texture', 'root_type',
            ]) : null,
            'care' => $plant->careGuide?->only([
                'light', 'water', 'soil', 'temperature_min_c', 'temperature_max_c', 'humidity', 'notes',
            ]),
            'seasonality' => $plant->seasonality->map(fn ($s) => [
                'event' => $s->event,
                'start_month' => $s->start_month,
                'end_month' => $s->end_month,
            ])->values()->all(),
            'distribution' => $plant->distributions->map(fn ($d) => [
                'country' => $d->country?->code,
                'region' => $d->is_sensitive || $plant->is_sensitive ? null : $d->region?->name,
                'establishment_means' => $d->establishment_means,
                'occurrence_status' => $d->occurrence_status,
                'is_sensitive' => $d->is_sensitive,
            ])->values()->all(),
            'conservation' => $plant->conservation->first()?->only(['iucn_category', 'national_status', 'assessed_at']),
            'media' => $plant->media->map(fn ($m) => [
                'id' => $m->id,
                'url' => $m->url,
                'type' => $m->pivot->type,
                'is_primary' => (bool) $m->pivot->is_primary,
                'alt' => $m->alt_text,
                'license' => $m->attribution?->license?->code,
                'attribution' => $m->attribution?->attribution_text,
            ])->values()->all(),
            'sources' => $plant->references->map(fn ($r) => [
                'id' => $r->source_id,
                'title' => $r->source?->title,
                'url' => $r->url ?: $r->source?->url,
            ])->values()->all(),
            'verification_status' => $plant->verification_status,
            'is_sensitive' => $plant->is_sensitive,
            'published_at' => $plant->published_at?->toIso8601String(),
            'seo' => [
                'title' => $translation?->seo_title,
                'description' => $translation?->seo_description,
            ],
        ]);
    }
}
