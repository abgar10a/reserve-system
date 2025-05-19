<?php

namespace App\Actions;

use Illuminate\Http\JsonResponse;

class ResponseAction
{
    public static function successData(string $message, array $data = null, int $status = 200): JsonResponse
    {
        unset($data['message']);

        return response()->json(array_filter([
            'message' => $message,
            'error' => false,
            'data' => $data
        ], static fn($value) => $value !== null), $status);
    }

    public static function success(string $message, int $status = 200): JsonResponse
    {
        return self::successData($message, null, $status);
    }

    public static function error(string $message, int $status = 400): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'error' => true,
        ], $status);
    }

    public static function build(string $message = null, array $data = [], int $status = 200, string $error = null): array
    {
        if ($message) {
            return [
                'message' => $message,
                'status' => $status,
                ...$data,
            ];
        }

        return [
            'error' => $error ?: 'Something went wrong',
        ];
    }

    public static function handleResponse(array $responseData): ?JsonResponse
    {
        $status = $responseData['status'] ?? 200;
        unset($responseData['status']);

        if (isset($responseData['error'])) {
            return self::error($responseData['error'], $status ?? 400);
        }

        $dataKeys = array_diff_key($responseData, array_flip(['status', 'message', 'error']));

        if (count($dataKeys)) {
            return self::successData($responseData['message'], $responseData, $status ?? 200);
        }

        return self::success($responseData['message'], $status ?? 200);
    }
}
