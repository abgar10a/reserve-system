<?php

namespace App\Repositories\Interfaces;

interface IOrderRepository extends IRepository
{
    public function getOrdersAt($dateTime);
}
