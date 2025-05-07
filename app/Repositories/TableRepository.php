<?php

namespace App\Repositories;

use App\Models\Table;
use App\Repositories\Interfaces\ITableRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TableRepository implements ITableRepository
{
    public function find($id = null)
    {
        return Table::find($id);
    }

    public function create($data)
    {
        return Table::create($data);
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
        return Table::all();
    }

    public function query(): Builder
    {
        return Table::query();
    }
}
