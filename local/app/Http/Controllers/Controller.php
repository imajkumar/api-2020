<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function setSuccessResponse($data = [], $message_code = 'Success',$api_token="", $message_action = 0){
        return response()->json([
            "data" => $data,
            "api_token" =>$api_token,
            "log" => '',
            "code"=> 200,
            "message" => $message_code,
            "message_code" => $message_code,
            "message_action" => (int) $message_action,
            "status" => 1
        ]);
    }
}
