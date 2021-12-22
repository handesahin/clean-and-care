<?php

namespace App\Repositories\User;

use App\Repositories\IRepository;

interface IUserRepository extends IRepository
{
    public function getUserIdFromEmail(string $email) : ?bool;

}
