<?php

namespace App\Services;

use App\Actions\ResponseAction;
use App\Enums\TableStatus;
use App\Models\Table;
use App\Repositories\Interfaces\IHallRepository;
use App\Repositories\Interfaces\IOrderRepository;
use App\Repositories\Interfaces\ITableRepository;

readonly class HallService
{
    public function __construct(private IHallRepository  $hallRepository,
                                private IOrderRepository $orderRepository,
                                private ITableRepository $tableRepository)
    {

    }

    public function getHalls(): array
    {
        $halls = $this->hallRepository->query()
        ->with('tables')
        ->get()
        ->map(fn($hall) => [
            'id' => $hall->id,
            'name' => $hall->name,
            'tables' => count($hall->tables),
            'seats' => $hall->tables->sum('seats'),
        ]);

        return ResponseAction::build('Halls', [
            'halls' => $halls,
        ]);
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

    public function getHall($id): array
    {
        $hall = $this->hallRepository->query()
        ->where('id', $id)
        ->with('tables')
        ->first();

        if (!$hall) {
            return ResponseAction::build(error: 'Hall not found');
        }

        return ResponseAction::build('Hall', [
            'hall' => $hall,
        ]);
    }

    public function createHall(array $hallData): array
    {
        $hall = $this->hallRepository->create($hallData);

        if (isset($hallData['tables']) && !empty($hall)) {
            $hall->tables()->createMany($hallData['tables']);
            $hall->refresh()->load('tables');
        }

        return ResponseAction::build('Hall created', [
            'hall' => $hall,
        ]);
    }

    public function updateHall(array $hallData, $id): array
    {
        $hall = $this->hallRepository->update($id, $hallData);

        $oldTables = [];
        $newTables = [];

        if (isset($hallData['tables']) && !empty($hall)) {

            foreach ($hallData['tables'] as $table) {
                if (isset($table['id']) && in_array($table['id'], $hall->tables->pluck('id')->toArray(), true)) {
                    $this->tableRepository->update($table['id'], $table);
                    $oldTables[] = $this->tableRepository->find($table['id']);
                } else {
                    $newTables[] = $table;
                }
            }

            foreach ($hall->tables as $table) {
                if (!in_array($table->id, collect($oldTables)->pluck('id')->toArray(), true)) {
                    $table->delete();
                }
            }

            $hall->tables()->createMany($newTables);

            $hall->refresh()->load('tables');

            return ResponseAction::build('Hall updated', [
                'hall' => $hall
            ]);
        }

        return ResponseAction::build('Hall updated', [
            'hall' => $hall,
        ]);
    }

    public function deleteHall($id): array
    {
        $hall = $this->hallRepository->find($id);

        if (!$hall) {
            return ResponseAction::build(error: 'Hall not found');
        }

        $hall->tables()->delete();
        $hall->delete();

        return ResponseAction::build('Hall deleted');
    }

}
