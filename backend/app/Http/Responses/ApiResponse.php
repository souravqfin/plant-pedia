<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function data(mixed $data, array $meta = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'meta' => array_merge(['request_id' => self::requestId()], $meta),
        ], $status);
    }

    public static function paginated(LengthAwarePaginator $paginator, array $meta = []): JsonResponse
    {
        return self::data($paginator->items(), array_merge([
            'pagination' => [
                'page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ], $meta));
    }

    public static function error(string $code, string $message, int $status = 400, ?string $field = null, array $meta = []): JsonResponse
    {
        $error = ['code' => $code, 'message' => $message];

        if ($field !== null) {
            $error['field'] = $field;
        }

        return response()->json([
            'errors' => [$error],
            'meta' => array_merge(['request_id' => self::requestId()], $meta),
        ], $status);
    }

    public static function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }

    public static function requestId(): string
    {
        return request()->attributes->get('request_id', 'req_'.bin2hex(random_bytes(8)));
    }
}
