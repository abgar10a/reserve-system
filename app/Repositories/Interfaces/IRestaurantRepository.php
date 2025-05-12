<?php

namespace App\Repositories\Interfaces;

interface IRestaurantRepository extends IRepository
{
    public function updateMeta($data);
}
