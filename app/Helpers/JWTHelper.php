<?php

namespace App\Helpers;

use Firebase\JWT\JWT;

class JWTHelper
{

    const JWT_SECRET = "NdedxQFxCMrBgVPXqLKRK5gcSDw9FWDY";
    /**
     * @param string $email
     * @param string $password
     * @return string
     */
    public static function createJwt(string $email, string $password, int $userId) : string {

        $payload = array(
            "email" => $email,
            "password" => bcrypt($password),
            "userId" =>$userId
        );

        return JWT::encode($payload, self::JWT_SECRET, 'HS256');
    }


    /**
     * @param $token
     * @return object
     */
    public static function decodeJwt($token) : object {

       return JWT::decode($token,self::JWT_SECRET, ['HS256']);

    }


}
