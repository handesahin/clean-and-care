<?php

namespace App\Repositories\Order;

use App\Repositories\IRepository;

interface IOrderRepository extends IRepository
{

    public function getUserOrders($userId);
}
