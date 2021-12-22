<?php

namespace App\Repositories\Service;

use App\Repositories\IRepository;

interface IServiceRepository extends IRepository
{

    public function getPublishedServices():object;
}
