<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | Company: YG | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/2/20 14:43
// +----------------------------------------------------------------------
// | TITLE: authorization server
// +----------------------------------------------------------------------


namespace app\apilib;

use think\Cache;
use  think\Request;
use think\Response;

class BaseAuth extends Common
{

    /**
     * 错误
     */
    const PARAMETERS_FAILED = 1001; //    parameters 参数错误
    const ACCESS_TOKEN_EXPIRED = 2001; //    accessToken过期
    const ACCESS_TOKEN_ERROR = 2002;    //   accessToken错误
    const NOT_INTERFACE_PRIVILEGES = 2003;    //   没有接口权限
    const NOT_CLIENT = 2004;    //   没有应用
    const SIGN_FAILED = 2005;    //  签名错误
    const REQUEST_EXPIRES = 2006;    //  请求过期
    /**
     * 过期时间秒数
     * @var int
     */
    public static $expires = 7200;
    /**
     * 请求过期时间
     * @var int
     */
    public static $requestExpiresTime = 30;
    /**
     * 默认错误
     * @var
     */
    public $error = self::NOT_CLIENT;
    /**
     * 是否开启签名验证 默认不启用
     * @var bool
     */
    public static $checkSign = true;
    /**
     * accessToken存储前缀
     * @var string
     */
    public static $accessTokenPrefix = 'accessToken_';

    /**
     * accessTokenAndClientPrefix存储前缀
     * @var string
     */
    public static $accessTokenAndClientPrefix = 'accessTokenAndClient_';//


    public function auth(Request $request)
    {
        //  1、获取accessToken
        $accessToken = $request->header('authorization');
        $accessToken = str_replace("token ", "", $accessToken);
        if (empty($accessToken) || strlen($accessToken) < 32 || $accessToken == false) {
            $this->error = self::ACCESS_TOKEN_ERROR;
            return false;
        }


        // 2、判断是否过期
        $accessTokenInfo = $this->getAccessTokenInfo($accessToken);

        //过期返回错误
        if (!$accessTokenInfo) {
            $this->error = self::ACCESS_TOKEN_EXPIRED;
            return false;
        }
        //验证权限
        if (!self::verifyPermissions($request, $accessTokenInfo)) {
            $this->error = self::NOT_INTERFACE_PRIVILEGES;
            return false;
        }
        return true;


    }

    /**
     * 获取 accessToken
     * @param Request $request
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public function accessToken(Request $request)
    {
        $grant_type = $request->param('grant_type');//认证类型  client_credentials
        $client_id = $request->param('client_id', '');//应用ID
        if (empty($client_id)) return $this->sendError(self::PARAMETERS_FAILED, 'client_id error', 400);

        // 1 、 获取应用信息
        $clientInfo = self::getClient($client_id);
        if (!$clientInfo) return $this->sendError(self::ACCESS_TOKEN_EXPIRED, 'not client', 401);
        // 2、 其他认证
        if ($this->otherAuth($request) == false) return $this->sendError($this->error, 'authentication Failed', 401);//authenticationFailed
        //3、下放access_token
        //判断是存在 access_token
        $access_token = $this->getAccessTokenAndClient($client_id);

        $access_token = (!$access_token) ? self::setAccessToken($clientInfo) : $access_token;

        return $this->sendSuccess([
            'access_token' => $access_token, //访问令牌
            'expires' => self::$expires,      //过期时间秒数
        ]);

    }


    /**
     * 更新 accessToken
     * @param Request $request
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public function refreshToken(Request $request)
    {
        // grant_type=refresh_token
        $grant_type = $request->param('grant_type');//认证类型  refresh_token
        $client_id = $request->param('client_id', '');//应用ID
        if (empty($client_id)) return $this->sendError(self::PARAMETERS_FAILED, 'client_id error', 400);

        // 1 、 获取应用信息
        $clientInfo = self::getClient($client_id);
        if (!$clientInfo) return $this->sendError(self::ACCESS_TOKEN_EXPIRED, 'not client', 401);
        // 2、 其他认证
        if ($this->otherAuth($request) == false) return $this->sendError($this->error, 'authentication Failed', 401);//authenticationFailed

        // 3、控制 refreshToken请求次数
        //todo
        // 4、无需判断原有accessToken唯一可用access_token和用户信息关联下放即可
        $access_token = self::setAccessToken($clientInfo);

        return $this->sendSuccess([
            'access_token' => $access_token, //访问令牌
            'expires' => self::$expires,      //过期时间秒数
        ]);

    }

    /**
     * 获取服务器时间戳
     * @return Response
     */
    public function getServerTime()
    {
        return $this->sendSuccess(time());
    }


// +----------------------------------------------------------------------+ \\
// +----------------------------------------------------------------------+ \\


    /**
     * 生成签名
     * @param $client_id
     * @param $requestTime
     * @param $secret
     * @return string
     */
    public static function makeSign($client_id, $requestTime, $secret)
    {
        return md5(md5($client_id . $requestTime . $secret));
    }


    /**
     * 其他验证 可以重写
     * @param Request $request
     * @return bool
     */
    protected function otherAuth(Request $request)
    {
        $client_id = $request->param('client_id', '');//应用ID
        $clientInfo = self::getClient($client_id);
        //默认签名认证 可以关闭
        if (self::$checkSign) {
            if (self::verifySign($request, $clientInfo) == false) {
                return false;//authenticationFailed
            }
        }
        return true;
    }

    /**
     * 包括不限于验证签名
     * @param Request $request
     * @param $clientInfo
     * @return bool
     */
    protected function verifySign(Request $request, $clientInfo)
    {
        $requestTime = $request->param('time', '', 'int');//请求时间
        //判断请求是否过期
//        if ((time() - $requestTime) > self::$requestExpiresTime) {
//            $this->error = self::REQUEST_EXPIRES;
//            return false;
//        }
        if (empty($requestTime)) return false;
        $sign = $request->param('sign');//签名
        if (empty($sign)) return false;
        //签名 hash(md5( client_id + time + secret  ));
        $signS = self::makeSign($request->param('client_id'), $request->param('time'), $clientInfo['secret']);
        if ($sign == $signS) return true;
        $this->error = self::SIGN_FAILED;
        return false;
    }

    /**
     * 获取应用信息
     * @param $client_id 应用ID
     * @return array
     */
    protected function getClient($client_id)
    {
        return [
            'client_name' => 'test',//客户端账户名称
            'client_id' => '11111111',//客户端账户id
            'secret' => 'qwekjznc120cnsdkjhad',  //加密秘钥
            'authorization_list' => 'test/Test/init,',//权限列表
        ];
    }


    /**
     * 设置AccessToken
     * @param $clientInfo
     * @return int
     */
    protected function setAccessToken($clientInfo)
    {

        //生成令牌
        $accessToken = self::buildAccessToken();
        $accessTokenInfo = [
            'access_token' => $accessToken,//访问令牌
            'expires_time' => time() + self::$expires,      //过期时间时间戳
            'client' => $clientInfo,//用户信息
        ];
        self::saveAccessToken($accessToken, $accessTokenInfo);
        return $accessToken;
    }

    protected function getAccessTokenInfo($accessToken)
    {
        $keys = self::$accessTokenPrefix . $accessToken;
        $info = Cache::get($keys);
        if ($info == false || $info['expires_time'] < time()) return false;
        //验证索引是否正确
        $client_id = $info['client']['client_id'];
        if ($this->getAccessTokenAndClient($client_id) != $accessToken) return false;
        return $info;
    }

    /**
     * 生成AccessToken
     * @return string
     */
    protected static function buildAccessToken()
    {
        //生成AccessToken
        $factory = new \RandomLib\Factory();
        $generator = $factory->getMediumStrengthGenerator();
        return $generator->generateString(32, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

    }

    /**
     * 存储
     * @param $accessToken
     * @param $accessTokenInfo
     */
    protected static function saveAccessToken($accessToken, $accessTokenInfo)
    {
        //存储accessToken
        Cache::set(self::$accessTokenPrefix . $accessToken, $accessTokenInfo, 7200);
        //存储用户与信息索引 用于比较
        Cache::set(self::$accessTokenAndClientPrefix . $accessTokenInfo['client']['client_id'], $accessToken, 7200);

    }

    protected function getAccessTokenAndClient($client_id)
    {
        return Cache::get(self::$accessTokenAndClientPrefix . $client_id);
    }

    /**
     * 权限验证
     * @param $request
     * @param $accessTokenInfo
     * @return bool
     */
    protected static function verifyPermissions($request, $accessTokenInfo)
    {
        //获取allModule
        $dispatch = $request->dispatch();
        $allModule = implode('/', $dispatch['module']);
        $authorization_list = explode(',', $accessTokenInfo['client']['authorization_list']);
        return (in_array($allModule, $authorization_list)) ? true : false;
    }


}