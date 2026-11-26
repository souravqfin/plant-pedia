<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Plant;
use App\Models\PlantCategory;
use App\Support\Locale;
use App\Support\PlantPresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $locale = Locale::resolve($request->query('locale'));

        $categories = PlantCategory::where('is_active', true)
            ->with('translations')
            ->withCount(['plants' => fn ($q) => $q->published()])
            ->orderBy('sort')
            ->get()
            ->map(fn (PlantCategory $c) => [
                'slug' => $c->slug,
                'name' => $c->translation($locale)?->name,
                'description' => $c->translation($locale)?->description,
                'plants_count' => $c->plants_count,
                'parent_id' => $c->parent_id,
            ]);

        return ApiResponse::data($categories);
    }

    public function show(Request $request, string $slug): JsonResponse
    {
        $locale = Locale::resolve($request->query('locale'));
        $category = PlantCategory::where('slug', $slug)->where('is_active', true)->with('translations')->firstOrFail();

        $plants = Plant::query()
            ->published()
            ->whereHas('categories', fn ($q) => $q->where('plant_categories.id', $category->id))
            ->with(['taxon.parent', 'translations', 'categories.translations', 'media.attribution.license'])
            ->paginate(min((int) $request->query('per_page', 20), 100));

        return ApiResponse::paginated($plants->through(fn (Plant $p) => PlantPresenter::card($p, $locale)), [
            'category' => [
                'slug' => $category->slug,
                'name' => $category->translation($locale)?->name,
                'description' => $category->translation($locale)?->description,
            ],
        ]);
    }
}
