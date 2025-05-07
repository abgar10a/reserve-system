<?php

namespace App\Repositories;

use App\Models\Hall;
use App\Repositories\Interfaces\IHallRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class HallRepository implements IHallRepository
{
    public function find($id = null)
    {
        return Hall::find($id);
    }

    public function create($data)
    {
        return Hall::create($data);
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
        return Hall::all();
    }

    public function query(): Builder
    {
        return Hall::query();
    }
}
