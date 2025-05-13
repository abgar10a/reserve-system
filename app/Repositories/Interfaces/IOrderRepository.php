<?php

namespace App\Repositories\Interfaces;

interface IOrderRepository extends IRepository
{
    public function getOrdersAt($dateTime);

    public function getOrdersForDateRange($start, $end);

    public function createWithPrice($data, $priceData);
}
