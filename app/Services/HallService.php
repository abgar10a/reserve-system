<?php

namespace App\Services;

use App\Actions\ResponseAction;
use App\Enums\OrderStatus;
use App\Enums\TableStatus;
use App\Models\Hall;
use App\Models\Table;
use App\Repositories\Interfaces\IHallRepository;
use App\Repositories\Interfaces\IOrderRepository;
use Illuminate\Database\Query\Builder;

readonly class HallService
{
    public function __construct(private IHallRepository  $hallRepository,
                                private IOrderRepository $orderRepository)
    {

    }

    public function getHallsInfo(): array
    {
        $occupiedTableIds = $this->orderRepository->getOrdersAt(now()->toDateTimeString())
            ->select('entity_id')
            ->active()
            ->ofType(Table::class)
            ->get()
            ->map(fn($order) => $order->entity_id);


        $hallsInfo = $this->hallRepository->query()
            ->with('tables')
            ->get()
            ->map(fn($hall) => [
                'id' => $hall->id,
                'name' => $hall->name,
                'tables' => $hall->tables->map(fn($table) => [
                    'id' => $table->id,
                    'name' => $table->name,
                    'seats' => $table->seats,
                    'status' => $occupiedTableIds->contains($table->id) ? TableStatus::OCCUPIED : TableStatus::FREE,
                ]),
            ]);

        return ResponseAction::build('Halls info', [
            'halls' => $hallsInfo,
        ]);
    }

}
