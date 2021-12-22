<?php

namespace App\Validators;


use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class AuthValidator
{
    /**
     * @param Request $request
     * @return array
     */
    public static function register(Request $request) : array {

        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
            ]);

        if ($validator->fails()) {
            return ["isValid" =>false, "errorMessage" => $validator->errors()];
        }

        return ["isValid" =>true, "errorMessage" => ""];
    }

    /**
     * @param Request $request
     * @return array
     */
    public static function login( Request $request) : array {

        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
            ]);

        if ($validator->fails()) {
            return ["isValid" =>false, "errorMessage" => $validator->errors()];
        }

        return ["isValid" =>true, "errorMessage" => ""];
    }

}
