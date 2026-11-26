<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Plant;
use App\Services\Search\SearchService;
use App\Support\Locale;
use App\Support\PlantPresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(private readonly SearchService $search) {}

    public function index(Request $request): JsonResponse
    {
        $data = $request->validate([
            'q' => ['required', 'string', 'min:1', 'max:120'],
            'locale' => ['nullable', 'in:bn,en'],
            'filter' => ['nullable', 'array'],
        ]);

        $locale = Locale::resolve($data['locale'] ?? null);
        $filters = array_filter($data['filter'] ?? []);
        $results = $this->search->search($data['q'], $locale, $filters, min((int) $request->query('per_page', 20), 50));

        $items = collect($results->items())->map(function (Plant $plant) use ($locale) {
            return array_merge(PlantPresenter::card($plant, $locale), ['score' => 1.0]);
        });

        return ApiResponse::data($items, [
            'pagination' => [
                'page' => $results->currentPage(),
                'per_page' => $results->perPage(),
                'total' => $results->total(),
                'last_page' => $results->lastPage(),
            ],
            'query' => $data['q'],
            'locale' => $locale,
        ]);
    }

    public function suggest(Request $request): JsonResponse
    {
        $data = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:120'],
            'locale' => ['nullable', 'in:bn,en'],
        ]);

        $locale = Locale::resolve($data['locale'] ?? null);

        return ApiResponse::data($this->search->suggest($data['q'], $locale));
    }
}
