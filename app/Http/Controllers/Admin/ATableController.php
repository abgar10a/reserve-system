<?php

namespace App\Http\Controllers\Admin;

use App\Actions\ResponseAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTableRequest;
use App\Http\Requests\UpdateTableRequest;
use App\Services\TableService;
use Illuminate\Http\JsonResponse;

class ATableController extends Controller
{
    public function __construct(private readonly TableService $tableService)
    {

    }

    public function store(StoreTableRequest $request): ?JsonResponse
    {
        $validated = $request->validated();

        return ResponseAction::handleResponse($this->tableService->createTable($validated));
    }

    public function update(UpdateTableRequest $request, $id): ?JsonResponse
    {
        $validated = $request->validated();

        return ResponseAction::handleResponse($this->tableService->updateTable($validated, $id));
    }

    public function delete($id): ?JsonResponse
    {
        return ResponseAction::handleResponse($this->tableService->deleteTable($id));
    }
}
