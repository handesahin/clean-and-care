<?php

namespace App\Repositories\Balance;


use App\Helpers\RedisKeys;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Redis;

class BalanceRepository extends BaseRepository implements IBalanceRepository
{
    const REDIS_KEY = "_balance";

    /**
     * @param int $userId
     * @param float $amount
     * @return bool
     */
    public function insertBalanceTransaction(int $userId, float $amount) : bool {

        if(self::create(["user_id" => $userId, "amount" => $amount])){
            $currentBalance = self::getTotalBalanceByUserId($userId);
            Redis::set($userId . RedisKeys::BALANCE_KEY, $currentBalance);

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
