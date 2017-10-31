<?php

namespace App\Helper;



class ResponseHelper
{

    public static function success($result = NULL){
        return response()->json($result, 200);
    }
    
    public static function error($message = NULL, $error = NULL, $code= NULL , $status){
        $result=[
            "message"=>$message,
            "error"=>$error,
            "code"=>$code,
        ];
        return response()->json($result,$status);
    }
}
