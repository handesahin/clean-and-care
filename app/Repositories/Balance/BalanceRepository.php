<?php

namespace App\Repositories\Balance;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Redis;

class BalanceRepository extends BaseRepository implements IBalanceRepository
{
    /**
     * @param int $userId
     * @param float $amount
     * @return bool
     */
    public function insertBalanceTransaction(int $userId, float $amount) : bool {

        if(self::create(["user_id" => $userId, "amount" => $amount])){

            return true;
        };

       return false;

    }

    /**
     * @param int $userId
     * @return float|null
     */
    public function getTotalBalanceByUserId(int $userId) : ?float {

        if($balance = $this->model->where("user_id",'=',$userId)->sum("amount")){

            return $balance;
        };

       return null;

    }

}
