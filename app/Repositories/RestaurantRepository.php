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
        $restaurant = $this->find($id);

        if (!$restaurant->exists()) {
            $restaurant->update($data);
        }

        return $restaurant->refresh();
    }

    public function updateMeta($data)
    {
        $restaurant = $this->find();

        if ($restaurant->exists()) {
            $restaurant->update($data);
        }

        return $restaurant->refresh();
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
