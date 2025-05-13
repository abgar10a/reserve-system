<?php

namespace App\Repositories\Interfaces;

interface IPriceRepository extends IRepository
{
    public function getPriceForEntity($type, $id);
}
