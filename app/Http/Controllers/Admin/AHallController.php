<?php

namespace App\Http\Controllers\Admin;

use App\Actions\ResponseAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHallRequest;
use App\Http\Requests\UpdateHallRequest;
use App\Services\HallService;
use Illuminate\Http\JsonResponse;

class AHallController extends Controller
{
    public function __construct(private readonly HallService $hallService)
    {

    }

    public function store(StoreHallRequest $request): ?JsonResponse
    {
        $validated = $request->validated();

        return ResponseAction::handleResponse($this->hallService->createHall($validated));
    }

    public function update(UpdateHallRequest $request, $id): ?JsonResponse
    {
        $validated = $request->validated();

        return ResponseAction::handleResponse($this->hallService->updateHall($validated, $id));
    }

    public function delete($id): ?JsonResponse
    {
        return ResponseAction::handleResponse($this->hallService->deleteHall($id));
    }
}
