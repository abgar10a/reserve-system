<?php

namespace App\Http\Controllers\Admin;

use App\Actions\ResponseAction;
use App\Http\Controllers\Controller;
use App\Services\HallService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AHallController extends Controller
{
    public function __construct(private readonly HallService $hallService)
    {

    }

    public function store(Request $request): ?JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'tables' => 'nullable|array'
            ]);

            $hallResponse = $this->hallService->createHall($validated);

            if (isset($hallResponse['error'])) {
                return ResponseAction::error($hallResponse['error']);
            }

            return ResponseAction::successData($hallResponse['message'], $hallResponse);
        } catch (\Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id): ?JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'tables' => 'nullable|array'
            ]);

            $hallResponse = $this->hallService->updateHall($validated, $id);

            if (isset($hallResponse['error'])) {
                return ResponseAction::error($hallResponse['error']);
            }

            return ResponseAction::successData($hallResponse['message'], $hallResponse);
        } catch (\Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id): ?JsonResponse
    {
        try {
            $hallResponse = $this->hallService->deleteHall($id);

            if (isset($hallResponse['error'])) {
                return ResponseAction::error($hallResponse['error']);
            }

            return ResponseAction::success($hallResponse['message']);
        } catch (\Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
