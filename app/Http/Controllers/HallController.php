<?php

namespace App\Http\Controllers;

use App\Actions\ResponseAction;
use App\Services\HallService;
use Illuminate\Http\JsonResponse;

class HallController extends Controller
{
    public function __construct(private readonly HallService $hallService)
    {

    }

    public function index(): ?JsonResponse
    {
        return ResponseAction::handleResponse($this->hallService->getHalls());
    }

    public function show($id): ?JsonResponse
    {
        return ResponseAction::handleResponse($this->hallService->getHall($id));
    }
}
