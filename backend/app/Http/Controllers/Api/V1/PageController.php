<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Page;
use App\Support\Locale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $locale = Locale::resolve($request->query('locale'));

        $pages = Page::query()
            ->published()
            ->where('type', $request->query('type', 'article'))
            ->with('translations')
            ->orderByDesc('published_at')
            ->paginate(min((int) $request->query('per_page', 20), 50));

        return ApiResponse::paginated($pages->through(fn (Page $p) => $this->summary($p, $locale)));
    }

    public function show(Request $request, string $slug): JsonResponse
    {
        $locale = Locale::resolve($request->query('locale'));

        $page = Page::query()
            ->published()
            ->where('slug', $slug)
            ->with(['translations', 'sections.translations'])
            ->firstOrFail();

        $translation = $page->translation($locale);

        return ApiResponse::data([
            'id' => $page->id,
            'slug' => $page->slug,
            'type' => $page->type,
            'template' => $page->template,
            'title' => $translation?->title,
            'excerpt' => $translation?->excerpt,
            'published_at' => $page->published_at?->toIso8601String(),
            'seo' => [
                'title' => $translation?->seo_title,
                'description' => $translation?->seo_description,
            ],
            'sections' => $page->sections->map(fn ($section) => [
                'id' => $section->id,
                'type' => $section->type,
                'sort' => $section->sort,
                'settings' => $section->localizedSettings($locale),
            ])->values()->all(),
        ]);
    }

    private function summary(Page $page, string $locale): array
    {
        $translation = $page->translation($locale);

        return [
            'id' => $page->id,
            'slug' => $page->slug,
            'type' => $page->type,
            'title' => $translation?->title,
            'excerpt' => $translation?->excerpt,
            'published_at' => $page->published_at?->toIso8601String(),
        ];
    }
}
