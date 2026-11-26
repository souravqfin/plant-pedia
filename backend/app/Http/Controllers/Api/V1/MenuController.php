<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Menu;
use App\Support\Locale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function show(Request $request, string $location): JsonResponse
    {
        $locale = Locale::resolve($request->query('locale'));

        $menu = Menu::with('items.children')
            ->where('location', $location)
            ->where('locale', $locale)
            ->first();

        if (! $menu) {
            return ApiResponse::data([]);
        }

        return ApiResponse::data($menu->items->whereNull('parent_id')->map(fn ($item) => [
            'label' => $item->label,
            'url' => $item->url,
            'children' => $item->children->map(fn ($child) => [
                'label' => $child->label,
                'url' => $child->url,
            ])->values()->all(),
        ])->values());
    }
}
