<?php

namespace App\Repositories\Car;

use App\Repositories\BaseRepository;

class CarRepository extends BaseRepository implements ICarRepository
{

    public function getCars($request) : object {

       return  self::getManyByAttributes(["limit"=>$request["limit"], "offset"=>$request["offset"]]);
    }


    /**
     * @return int
     */
    public function getTotalCarCount() : int {

        if($count = $this->model->count("id")){

            return $count;
        };

        return 0;

    }
}
