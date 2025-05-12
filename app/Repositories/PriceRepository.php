<?php

namespace App\Repositories;

use App\Models\Price;
use App\Repositories\Interfaces\IPriceRepository;
use Illuminate\Database\Eloquent\Builder;

class PriceRepository implements IPriceRepository
{
    public function find($id = null)
    {
        return Price::find($id);
    }

    public function create($data)
    {
        return Price::create($data);
    }

    public function update($id, $data)
    {
        $price = $this->find($id);
        $price->update($data);
        return $price->refresh();
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    public function query(): Builder
    {
        return Price::query();
    }
}
