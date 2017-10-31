<?php

namespace App\Helper;

use Illuminate\Http\Request;

class CurlHelper
{
    public static function request( $url , $params = array(), $method = 'GET',$timeout=10,$useCert=array())
    {
        $params= is_array($params) ? http_build_query($params) : $params;
        $starttime = self::microtime_float();
        $method = strtoupper($method);
        $ci = curl_init();
//        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ci, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);

        switch ($method)
        {
            case 'POST':
                if (!empty($params))
                {
                    curl_setopt($ci, CURLOPT_POST, TRUE);
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $params);
                }
                break;
            case 'DELETE':
            case 'GET':
                $method == 'DELETE' && curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if (!empty($params))
                {
                    $url = $url . (strpos($url, '?') ? '&' : '?') . $params;
                }
                break;
        }
        if($useCert){
            curl_setopt($ci,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ci,CURLOPT_SSLCERT, $useCert['cert']);
            curl_setopt($ci,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ci,CURLOPT_SSLKEY, $useCert['key']);
            curl_setopt($ci,CURLOPT_CAINFO, $useCert['ca']);
            curl_setopt($ci,CURLOPT_SSL_VERIFYPEER,TRUE);
            curl_setopt($ci,CURLOPT_SSL_VERIFYHOST,2);//严格校验
            curl_setopt($ci,CURLOPT_HEADER, FALSE);
        }else{
            curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($ci, CURLOPT_URL, $url);
        $response = curl_exec($ci);
        $errorNum=curl_errno($ci);
        $endtime = self::microtime_float();
        $runtime = number_format(($endtime-$starttime), 4).'s';
        $parseUrl=parse_url($url);
        $code=str_replace(".", "_", $parseUrl['host']);
        $code=str_replace("\\", "", $code);
        if($errorNum){
//            Log::error($url, ["result"=>$errorNum,"runtime"=>$runtime,"response"=>$response]);
            return NULL;
        }
        curl_close ($ci);
//        Log::info($url, ["runtime"=>$runtime,"response"=>$response]);
        return $response;
    }

    public static function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}