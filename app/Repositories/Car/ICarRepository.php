<?php

namespace App\Repositories\Car;

use App\Repositories\IRepository;

interface ICarRepository extends IRepository
{

    public function getCars($request) : object ;

    public function getTotalCarCount() : int ;
}
