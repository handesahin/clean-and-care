<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements IUserRepository
{

    /**
     * @param string $email
     * @return bool|null
     */
    public function getUserIdFromEmail(string $email) : ?bool {

        if($userId = $this->model->where("email","=",$email)->get("id")){

            return $userId;
        };

        return null;

    }
}
