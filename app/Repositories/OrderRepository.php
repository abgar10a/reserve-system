<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Interfaces\IOrderRepository;
use Illuminate\Database\Eloquent\Collection;

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
        $currency = $this->find($id);
        $currency->update($data);
        return $currency;
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    public function all(): Collection
    {
        return Order::all();
    }
}
