<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseFormatter
{
    /**
     * Format respons sukses standar.
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @param array|null $meta
     * @return JsonResponse
     */
    protected function successResponse($data = null, string $message = 'Operasi berhasil', int $code = 200, ?array $meta = null): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ];

        if ($meta !== null) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $code);
    }

    /**
     * Format respons gagal/error standar.
     *
     * @param string $message
     * @param int $code
     * @param mixed $errors
     * @return JsonResponse
     */
    protected function errorResponse(string $message = 'Terjadi kesalahan', int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Format respons pagination standar.
     *
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $paginator
     * @param mixed $data
     * @param string $message
     * @return JsonResponse
     */
    protected function paginatedResponse($paginator, $data = null, string $message = 'Data berhasil dimuat'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data ?? $paginator->items(),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'from'         => $paginator->firstItem(),
                'to'           => $paginator->lastItem(),
            ],
        ], 200);
    }
}

