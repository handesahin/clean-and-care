<?php

namespace App\Validators;


use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class OrderValidator
{
    /**
     * @param Request $request
     * @return array
     */
    public static function createOrder(Request $request) : array {

        $validator = Validator::make($request->all(),
            [
                'car_id' => 'required',
                'service_id' => 'required',
                'price' => 'required',
                'payment_method' => 'required',
            ]);

        if ($validator->fails()) {
            return ["isValid" =>false, "errorMessage" => $validator->errors()];
        }

        return ["isValid" =>true, "errorMessage" => ""];
    }


}
