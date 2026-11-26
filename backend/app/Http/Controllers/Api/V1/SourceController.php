<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Source;
use Illuminate\Http\JsonResponse;

class SourceController extends Controller
{
    public function show(int $id): JsonResponse
    {
        $source = Source::findOrFail($id);

        return ApiResponse::data($source->only([
            'id', 'title', 'publisher', 'url', 'license', 'language', 'accessed_at',
        ]));
    }
}
