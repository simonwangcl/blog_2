<?php

namespace App\Helper\Qq;

session_start();

class QqLoginHelper
{
    private $APIMap = array(
        "get_user_info" => array(   //获取用户资料
            "https://graph.qq.com/user/get_user_info",
            array("format" => "json"),
        )
    );
    private $keysArr;

    private static $scope = 'get_user_info';
    private static $getAuthCodeUrl = 'https://graph.qq.com/oauth2.0/authorize';
    private static $getAccessTokenUrl = 'https://graph.qq.com/oauth2.0/token';
    private static $getOpenIdUrl = 'https://graph.qq.com/oauth2.0/me';

    function __construct()
    {
        if (!empty($_SESSION["openid"])) {
            $this->keysArr = array(
                "oauth_consumer_key" => config('qq.APPID'),
                "access_token" => $_SESSION['access_token'],
                "openid" => $_SESSION["openid"]
            );
        } else {
            $this->keysArr = array(
                "oauth_consumer_key" => config('qq.APPID')
            );
        }
    }

    public function qqLogin()
    {
        //-------生成唯一随机串防CSRF攻击
        $_SESSION['state'] = md5(uniqid(rand(), TRUE));
        $keysArr = array(
            "response_type" => "code",
            "client_id" => config('qq.APPID'),
            "redirect_uri" => config('qq.CALLBACK'),
            "state" => $_SESSION['state'],
            "scope" => self::$scope
        );
        $login_url = self::$getAuthCodeUrl . '?' . http_build_query($keysArr);
        header("Location:$login_url");
    }

    public function getAccessToken()
    {
        //--------验证state防止CSRF攻击
        if ($_GET['state'] != $_SESSION['state']) {
            return false;
        }
        //-------请求参数列表
        $keysArr = array(
            "grant_type" => "authorization_code",
            "client_id" => config('qq.APPID'),
            "redirect_uri" => config('qq.CALLBACK'),
            "client_secret" => config('qq.APPKEY'),
            "code" => $_GET['code']
        );
        //------构造请求access_token的url
        $token_url = self::$getAccessTokenUrl . '?' . http_build_query($keysArr);
        $response = $this->getContents($token_url);
        if (strpos($response, "callback") !== false) {
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
            $msg = json_decode($response);
            if (isset($msg->error)) {
                $this->showError($msg->error, $msg->error_description);
            }
        }
        $params = array();
        parse_str($response, $params);
        $_SESSION["access_token"] = $params["access_token"];
        $this->keysArr['access_token'] = $params['access_token'];
        return $params["access_token"];
    }

    public function getContents($url)
    {
        if (ini_get("allow_url_fopen") == "1") {
            $response = file_get_contents($url);
        } else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $url);
            $response = curl_exec($ch);
            curl_close($ch);
        }
        if (empty($response)) {
            return false;
        }
        return $response;
    }

    public function getOpenid()
    {
        //-------请求参数列表
        $keysArr = array(
            "access_token" => $_SESSION["access_token"]
        );
        $graph_url = self::$getOpenIdUrl . '?' . http_build_query($keysArr);
        $response = $this->getContents($graph_url);
        //--------检测错误是否发生
        if (strpos($response, "callback") !== false) {
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
        }
        $user = json_decode($response);
        if (isset($user->error)) {
            $this->showError($user->error, $user->error_description);
        }
        //------记录openid
        $_SESSION['openid'] = $user->openid;
        $this->keysArr['openid'] = $user->openid;
        return $user->openid;
    }

    /**
     * showError
     * 显示错误信息
     * @param int $code 错误代码
     * @param string $description 描述信息（可选）
     */
    public function showError($code, $description = '$')
    {
        echo "<meta charset=\"UTF-8\">";
        echo "<h3>error:</h3>$code";
        echo "<h3>msg :</h3>$description";
        exit();
    }

    /**
     * _call
     * 魔术方法，做api调用转发
     * @param string $name 调用的方法名称
     * @param array $arg 参数列表数组
     * @since 5.0
     * @return array   返加调用结果数组
     */
    public function __call($name, $arg)
    {
        //如果APIMap不存在相应的api
        if (empty($this->APIMap[$name])) {
            $this->showError("api调用名称错误", "不存在的API: <span style='color:red;'>$name</span>");
        }
        //从APIMap获取api相应参数
        $baseUrl = $this->APIMap[$name][0];
        $argsList = $this->APIMap[$name][1];
        $method = isset($this->APIMap[$name][2]) ? $this->APIMap[$name][2] : "GET";
        if (empty($arg)) {
            $arg[0] = null;
        }
        $responseArr = json_decode($this->_applyAPI($arg[0], $argsList, $baseUrl, $method), true);
        //检查返回ret判断api是否成功调用
        if ($responseArr['ret'] == 0) {
            return $responseArr;
        } else {
            $this->showError($responseArr['ret'], $responseArr['msg']);
        }
    }

    //调用相应api
    private function _applyAPI($arr, $argsList, $baseUrl, $method)
    {
        $pre = "#";
        $keysArr = $this->keysArr;
        $optionArgList = array();//一些多项选填参数必选一的情形
        foreach ($argsList as $key => $val) {
            $tmpKey = $key;
            $tmpVal = $val;
            if (!is_string($key)) {
                $tmpKey = $val;
                if (strpos($val, $pre) === 0) {
                    $tmpVal = $pre;
                    $tmpKey = substr($tmpKey, 1);
                    if (preg_match("/-(\d$)/", $tmpKey, $res)) {
                        $tmpKey = str_replace($res[0], "", $tmpKey);
                        $optionArgList[] = $tmpKey;
                    }
                } else {
                    $tmpVal = null;
                }
            }
            //-----如果没有设置相应的参数
            if (!isset($arr[$tmpKey]) || $arr[$tmpKey] === "") {
                if ($tmpVal == $pre) {
                    continue;
                } else if ($tmpVal) {//则使用默认的值
                    $arr[$tmpKey] = $tmpVal;
                } else {
                    $this->showError("api调用参数错误", "未传入参数$tmpKey");
                }
            }
            $keysArr[$tmpKey] = $arr[$tmpKey];
        }
        //检查选填参数必填一的情形
        if (count($optionArgList) != 0) {
            $n = 0;
            foreach ($optionArgList as $val) {
                if (in_array($val, array_keys($keysArr))) {
                    $n++;
                }
            }
            if (!$n) {
                $str = implode(",", $optionArgList);
                $this->showError("api调用参数错误", $str . "必填一个");
            }
        }
        if ($method == "POST") {
            $response = $this->post($baseUrl, $keysArr, 0);
        } else if ($method == "GET") {
            $baseUrl = $baseUrl . '?' . http_build_query($keysArr);
            $response = $this->getContents($baseUrl);
        }
        return $response;
    }

    public function post($url, $keysArr, $flag = 0)
    {
        $ch = curl_init();
        if (!$flag) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $keysArr);
        curl_setopt($ch, CURLOPT_URL, $url);
        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
    }
}