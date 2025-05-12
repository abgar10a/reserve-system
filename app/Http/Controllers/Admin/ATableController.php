<?php

namespace App\Http\Controllers\Admin;

use App\Actions\ResponseAction;
use App\Http\Controllers\Controller;
use App\Services\HallService;
use App\Services\TableService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ATableController extends Controller
{
    public function __construct(private readonly TableService $tableService){

    }

    public function store(Request $request): ?JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'seats' => 'required|numeric|min:1|max:1000',
                'hall' => 'required|exists:halls,id'
            ]);

            $tableResponse = $this->tableService->createTable($validated);

            if (isset($tableResponse['error'])) {
                return ResponseAction::error($tableResponse['error']);
            }

            return ResponseAction::successData($tableResponse['message'], $tableResponse);
        } catch (\Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id): ?JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'seats' => 'required|numeric|min:1|max:1000',
                'hall' => 'required|exists:halls,id'
            ]);

            $tableResponse = $this->tableService->updateTable($validated, $id);

            if (isset($tableResponse['error'])) {
                return ResponseAction::error($tableResponse['error']);
            }

            return ResponseAction::successData($tableResponse['message'], $tableResponse);
        } catch (\Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id): ?JsonResponse
    {
        try {
            $tableResponse = $this->tableService->deleteTable($id);

            if (isset($tableResponse['error'])) {
                return ResponseAction::error($tableResponse['error']);
            }

            return ResponseAction::success($tableResponse['message']);
        } catch (\Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
