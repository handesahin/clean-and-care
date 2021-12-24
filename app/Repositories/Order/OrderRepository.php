<?php

namespace App\Repositories\Order;

use App\Repositories\BaseRepository;

class OrderRepository extends BaseRepository implements IOrderRepository
{
    /**
     * @param $userId
     * @return false|mixed
     */
    public function getUserOrders($userId){

        $where["user_id"] = $userId;

        return self::getManyByAttributes($where);
    }

}
