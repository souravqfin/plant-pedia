<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Plant;
use App\Support\Locale;
use App\Support\PlantPresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $locale = Locale::resolve($request->query('locale'));
        $perPage = min((int) $request->query('per_page', 20), 100);

        $query = Plant::query()
            ->published()
            ->with(['taxon.rank', 'taxon.parent', 'translations', 'names', 'categories.translations', 'media.attribution.license', 'careGuide']);

        if ($request->filled('category')) {
            $query->whereHas('categories', fn ($q) => $q->where('slug', $request->query('category')));
        }

        if ($request->filled('family')) {
            $query->whereHas('taxon.parent', fn ($q) => $q->where('scientific_name', $request->query('family')));
        }

        if ($request->filled('habit')) {
            $query->whereHas('characteristics', fn ($q) => $q->where('habit', $request->query('habit')));
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->filled('q')) {
            $term = '%'.$request->query('q').'%';
            $query->where(fn ($q) => $q
                ->whereHas('names', fn ($n) => $n->where('name', 'like', $term))
                ->orWhereHas('translations', fn ($t) => $t->where('common_name', 'like', $term))
                ->orWhereHas('taxon', fn ($t) => $t->where('scientific_name', 'like', $term)));
        }

        match ($request->query('sort', 'featured')) {
            'name' => $query->orderBy('id'),
            'newest' => $query->orderByDesc('published_at'),
            default => $query->orderByDesc('is_featured')->orderBy('id'),
        };

        $plants = $query->paginate($perPage);

        return ApiResponse::paginated($plants->through(fn (Plant $p) => PlantPresenter::card($p, $locale)));
    }

    public function show(Request $request, string $slug): JsonResponse
    {
        $locale = Locale::resolve($request->query('locale'));

        $plant = Plant::query()
            ->published()
            ->where('slug', $slug)
            ->with([
                'taxon.rank', 'taxon.parent', 'translations', 'names.region',
                'categories.translations', 'characteristics', 'careGuide',
                'seasonality', 'distributions.country', 'distributions.region',
                'conservation', 'media.attribution.license', 'references.source',
            ])
            ->firstOrFail();

        return ApiResponse::data(PlantPresenter::detail($plant, $locale));
    }

    public function related(Request $request, string $slug): JsonResponse
    {
        $locale = Locale::resolve($request->query('locale'));
        $plant = Plant::published()->where('slug', $slug)->firstOrFail();

        $familyId = $plant->taxon?->parent_id;

        $related = Plant::query()
            ->published()
            ->whereKeyNot($plant->id)
            ->when($familyId, fn ($q) => $q->whereHas('taxon', fn ($t) => $t->where('parent_id', $familyId)))
            ->with(['taxon.parent', 'translations', 'media.attribution.license', 'categories.translations'])
            ->limit(8)
            ->get();

        if ($related->count() < 4) {
            $related = Plant::query()
                ->published()
                ->whereKeyNot($plant->id)
                ->with(['taxon.parent', 'translations', 'media.attribution.license', 'categories.translations'])
                ->orderByDesc('is_featured')
                ->limit(8)
                ->get();
        }

        return ApiResponse::data($related->map(fn (Plant $p) => PlantPresenter::card($p, $locale)));
    }
}
