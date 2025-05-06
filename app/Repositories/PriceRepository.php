<?php

namespace App\Repositories;

use App\Models\Price;
use App\Repositories\Interfaces\IPriceRepository;

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
        $currency = $this->find($id);
        $currency->update($data);
        return $currency;
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }
}
