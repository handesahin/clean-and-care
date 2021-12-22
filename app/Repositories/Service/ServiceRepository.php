<?php

namespace App\Repositories\Service;

use App\Repositories\BaseRepository;

class ServiceRepository extends BaseRepository implements IServiceRepository
{

    public function getPublishedServices(): object
    {
        $where["published"] =true;

        return self::getManyByAttributes($where);
    }
}
