<?php

namespace App\Repositories;

use App\Models\Currency;
use App\Repositories\Interfaces\ICurrencyRepository;
use Illuminate\Database\Eloquent\Collection;

class CurrencyRepository implements ICurrencyRepository
{
    public function find($id = null)
    {
        return Currency::find($id);
    }

    public function create($data)
    {
        return Currency::create($data);
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
        return Currency::all();
    }
}
