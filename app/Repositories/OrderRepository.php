<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Price;
use App\Repositories\Interfaces\IOrderRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrderRepository implements IOrderRepository
{
    public function find($id = null)
    {
        return Order::find($id);
    }

    public function create($data)
    {
        return Order::create($data);
    }

    public function update($id, $data)
    {
        $order = $this->find($id);
        $order->update($data);
        return $order->refresh();
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    public function all(): Collection
    {
        return Order::all();
    }

    public function query(): Builder
    {
        return Order::query();
    }

    public function getOrdersAt($dateTime)
    {
        $time = Carbon::parse($dateTime);

        return Order::where(static function ($query) use ($time) {
            $query->where('start', '<=', $time);
            $query->where('end', '>=', $time);
        });
    }

    public function getOrdersForDateRange($start, $end)
    {
        $startTime = Carbon::parse($start);
        $endTime = Carbon::parse($end);

        return Order::where(static function ($query) use ($startTime, $endTime) {
            $query->where('start', '>=', $startTime);
            $query->where('start', '<=', $endTime);
        })
            ->orWhere(static function ($query) use ($startTime, $endTime) {
                $query->where('end', '>=', $startTime);
                $query->where('end', '<=', $endTime);
            });
    }

    public function createWithPrice($data, $priceData)
    {
        if (!isset($priceData['amount']) || !isset($priceData['currency'])) {
            return null;
        }
        $order = $this->create($data);
        Price::create([
            'amount' => $priceData['amount'],
            'currency' => $priceData['currency'],
            'entity_id' => $order->id,
            'entity_type' => Order::class,
        ]);

        return $order;
    }
}
