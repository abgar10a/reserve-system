<?php

namespace App\Http\Controllers;

use App\Actions\ResponseAction;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {

    }

    public function index(): ?JsonResponse
    {
        return ResponseAction::handleResponse($this->orderService->getOrdersForUser());
    }

    public function store(StoreOrderRequest $request): ?JsonResponse
    {
        $orderData = $request->validated();

        return ResponseAction::handleResponse($this->orderService->createOrder($orderData));
    }

    public function update(UpdateOrderRequest $request, $id): ?JsonResponse
    {
        $orderData = $request->validated();

        return ResponseAction::handleResponse($this->orderService->updateOrder($orderData['status'], $id));
    }

    public function show($id): ?JsonResponse
    {
        return ResponseAction::handleResponse($this->orderService->getOrder($id));
    }

    public function delete($id): ?JsonResponse
    {
        return ResponseAction::handleResponse($this->orderService->deleteOrder($id));
    }
}
