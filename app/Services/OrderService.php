<?php

namespace App\Services;

use App\Actions\ResponseAction;
use App\Enums\OrderStatus;
use App\Enums\ResponseStatus;
use App\Models\Hall;
use App\Models\Table;
use App\Repositories\Interfaces\IHallRepository;
use App\Repositories\Interfaces\IOrderRepository;
use App\Repositories\Interfaces\IPriceRepository;
use Carbon\Carbon;

readonly class OrderService
{
    public function __construct(private IOrderRepository $orderRepository,
                                private IHallRepository  $hallRepository,
                                private IPriceRepository $priceRepository)
    {

    }

    public function createOrder(array $orderData): array
    {
        $user = auth()->user();

        $orderData['user'] = $user->id;
        $orderData['status'] = OrderStatus::PAYMENT;
        $entityType = match ($orderData['entity_type']) {
            'table' => Table::class,
            'hall' => Hall::class,
        };
        $orderData['entity_type'] = $entityType;

        $tableIds = $this->getTablesForEntity($entityType, $orderData['entity_id']);

        $registeredOrders = $this->orderRepository->getOrdersForDateRange(
            Carbon::parse($orderData['start']),
            Carbon::parse($orderData['end'])
        )->get();

        $isEntityFree = true;

        foreach ($registeredOrders as $order) {
            $checkedEntities = match ($order->entity_type) {
                Table::class => array_intersect($tableIds, [$order->entity_id]),
                Hall::class => array_intersect($tableIds, $this->hallRepository->find($order->entity_id)->tables->pluck('id')->toArray()),
                default => [],
            };

            $isEntityFree = empty($checkedEntities);

            if (!$isEntityFree) {
                break;
            }
        }

        if (!$isEntityFree) {
            return ResponseAction::build(status: ResponseStatus::CONFLICT->code(), error: class_basename($entityType) . ' is not free for the selected time period.');
        }

        $price = $this->priceRepository->getPriceForEntity($entityType, $orderData['entity_id']);

        if (!$price) {
            return ResponseAction::build(status: ResponseStatus::NOT_FOUND->code(), error: 'Price not found');
        }

        $order = $this->orderRepository->createWithPrice($orderData, [
            'amount' => $price->amount,
            'currency' => $price->currency,
        ]);

        return ResponseAction::build('Order created successfully', [
            'order' => $order,
            'price' => $order->price,
        ], ResponseStatus::CREATED->code());
    }

    public function updateOrder(string $status, int $id): array
    {
        $order = $this->orderRepository->find($id);

        if (!$order) {
            return ResponseAction::build(status: ResponseStatus::NOT_FOUND->code(), error: 'Order not found');
        }

        if ($order->user !== auth()->id()) {
            return ResponseAction::build(status: ResponseStatus::FORBIDDEN->code(), error: 'No permission');
        }

        $this->orderRepository->update($id, [
            'status' => $status,
        ]);

        return ResponseAction::build('Order updated successfully', [
            'order' => $order->refresh(),
        ], ResponseStatus::UPDATED->code());
    }

    public function getOrder(int $id): array
    {
        $order = $this->orderRepository->find($id);

        if (!$order) {
            return ResponseAction::build(status: ResponseStatus::NOT_FOUND->code(), error: 'Order not found');
        }

        if ($order->user !== auth()->id()) {
            return ResponseAction::build(status: ResponseStatus::FORBIDDEN->code(), error: 'No permission');
        }

        $entityType = match ($order->entity_type) {
            Table::class => 'table',
            Hall::class => 'hall',
        };

        $price = $order->price->toArray();

        $orderData = [
            'id' => $order->id,
            'start' => $order->start,
            'end' => $order->end,
            $entityType => $order->entity,
            'status' => $order->status,
            'price' => [
                'id' => $price['id'],
                'amount' => $price['amount'],
                'currency' => $price['currency'],
            ],
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
        ];

        return ResponseAction::build('Order', [
            'order' => $orderData,
        ], ResponseStatus::OK->code());
    }

    public function deleteOrder(int $id): array
    {
        $order = $this->orderRepository->find($id);

        if (!$order) {
            return ResponseAction::build(status: ResponseStatus::NOT_FOUND->code(), error: 'Order not found');
        }

        if ($order->user !== auth()->id()) {
            return ResponseAction::build(status: ResponseStatus::FORBIDDEN->code(), error: 'No permission');
        }

        $order->delete();

        return ResponseAction::build('Order deleted successfully', status: ResponseStatus::DELETED->code());
    }

    public function getTablesForEntity(string $entityType, int $entityId): array
    {
        if ($entityType === Table::class) {
            return [$entityId];
        }

        if ($entityType === Hall::class) {
            $hall = $this->hallRepository->find($entityId);

            if (!$hall) {
                return [];
            }

            return $hall->tables->pluck('id')->toArray();
        }

        return [];
    }

    public function getOrdersForUser(): array
    {
        $user = auth()->user();

        $orders = $this->orderRepository->query()
            ->where('user', $user->id)
            ->get()
            ->map(fn($order) => $this->getOrder($order->id));

        return ResponseAction::build('Orders for user', [
            'orders' => $orders,
        ], ResponseStatus::OK->code());
    }

}
