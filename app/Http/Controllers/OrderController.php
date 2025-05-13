<?php

namespace App\Http\Controllers;

use App\Actions\ResponseAction;
use App\Enums\OrderStatus;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService)
    {

    }

    public function index(): ?JsonResponse
    {
        try {
            $orderResponse = $this->orderService->getOrdersForUser();

            if (isset($orderResponse['error'])) {
                return ResponseAction::error($orderResponse['error']);
            }

            return ResponseAction::successData($orderResponse['message'], $orderResponse);
        } catch (\Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request): ?JsonResponse
    {
        try {
            $orderData = $request->validate([
                'start' => 'required|date',
                'end' => 'required|date',
                'entity_type' => 'required|string|in:table,hall',
                'entity_id' => 'required|numeric'
            ]);

            $orderResponse = $this->orderService->createOrder($orderData);

            if (isset($orderResponse['error'])) {
                return ResponseAction::error($orderResponse['error']);
            }

            return ResponseAction::successData($orderResponse['message'], $orderResponse);
        } catch (\Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id): ?JsonResponse
    {
        try {
            $orderData = $request->validate([
                'status' => 'required|in:'.implode(',', OrderStatus::all())
            ]);

            $orderResponse = $this->orderService->updateOrder($orderData['status'], $id);

            if (isset($orderResponse['error'])) {
                return ResponseAction::error($orderResponse['error']);
            }

            return ResponseAction::successData($orderResponse['message'], $orderResponse);
        } catch (\Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id): ?JsonResponse
    {
        try {
            $orderResponse = $this->orderService->getOrder($id);

            if (isset($orderResponse['error'])) {
                return ResponseAction::error($orderResponse['error']);
            }

            return ResponseAction::successData($orderResponse['message'], $orderResponse);
        } catch (\Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id): ?JsonResponse
    {
        try {
            $orderResponse = $this->orderService->deleteOrder($id);

            if (isset($orderResponse['error'])) {
                return ResponseAction::error($orderResponse['error']);
            }

            return ResponseAction::success($orderResponse['message']);
        } catch (\Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
