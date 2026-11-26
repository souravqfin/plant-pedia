<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Country;
use App\Models\Language;
use App\Models\Menu;
use App\Models\PlantCategory;
use App\Support\Locale;
use Illuminate\Http\JsonResponse;

class ConfigController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $locale = Locale::resolve(request('locale'));

        return ApiResponse::data([
            'locale' => $locale,
            'locales' => Language::where('is_active', true)
                ->get()
                ->map(fn (Language $l) => [
                    'code' => $l->code,
                    'name' => $l->name,
                    'native_name' => $l->native_name,
                    'direction' => $l->direction,
                ]),
            'default_country' => Country::where('is_default', true)->first()?->only(['code', 'name', 'default_locale']),
            'categories' => PlantCategory::where('is_active', true)
                ->whereNull('parent_id')
                ->with('translations')
                ->orderBy('sort')
                ->get()
                ->map(fn (PlantCategory $c) => [
                    'slug' => $c->slug,
                    'name' => $c->translation($locale)?->name,
                ]),
            'menus' => Menu::with('items.children')
                ->where('locale', $locale)
                ->get()
                ->mapWithKeys(fn (Menu $m) => [$m->location => $this->menuItems($m)]),
            'feature_flags' => [
                'community' => true,
                'ai_assist' => true,
                'public_api' => false,
            ],
        ]);
    }

    private function menuItems(Menu $menu): array
    {
        return $menu->items->whereNull('parent_id')->map(fn ($item) => [
            'label' => $item->label,
            'url' => $item->url,
            'children' => $item->children->map(fn ($child) => [
                'label' => $child->label,
                'url' => $child->url,
            ])->values()->all(),
        ])->values()->all();
    }
}
