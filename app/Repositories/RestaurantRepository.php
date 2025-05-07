<?php

namespace App\Repositories;

use App\Models\Restaurant;
use App\Repositories\Interfaces\IRestaurantRepository;
use Illuminate\Database\Eloquent\Builder;

class RestaurantRepository implements IRestaurantRepository
{
    public function find($id = null)
    {
        return Restaurant::first();
    }

    public function create($data)
    {
        return Restaurant::create($data);
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

    public function query(): Builder
    {
        return Restaurant::query();
    }
}
