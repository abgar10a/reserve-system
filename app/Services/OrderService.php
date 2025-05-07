<?php

namespace App\Services;

use App\Repositories\Interfaces\IOrderRepository;

class OrderService
{
    public function __construct(private readonly IOrderRepository $iOrderRepository)
    {

    }


}
