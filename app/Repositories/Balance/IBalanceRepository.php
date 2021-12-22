<?php

namespace App\Repositories\Balance;

use App\Repositories\IRepository;

interface IBalanceRepository extends IRepository
{
    public function insertBalanceTransaction(int $userId, float $amount);

    public function getTotalBalanceByUserId(int $userId) : ?float;
}
