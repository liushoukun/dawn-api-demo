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


    const ACCESS_TOKEN_EXPIRED = 2001; // 1   accessToken过期
    const ACCESS_TOKEN_ERROR = 2002;    // 2   accessToken错误
    const NOT_INTERFACE_PRIVILEGES = 2003;    // 3  没有接口权限
    const NOT_CLIENT = 2004;    // 4  没有应用
    public static $expires = 7200; //过期时间秒数
    public $error;
    public static $accessTokenPrefix = 'accessToken_';//accessToken存储前缀
    public static $accessTokenAndClientPrefix = 'accessTokenAndClient_';//accessTokenAndClientPrefix存储前缀


    public function auth(Request $request)
    {
        //  1、获取accessToken
        $accessToken = $request->header('authorization');
        $accessToken =  str_replace("token ","", $accessToken);
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

//        dump(self::makeSign('11111111',1487664463,'qwekjznc120cnsdkjhad'));
//        die();
        // grant_type=client_credentials
        $grant_type = $request->param('grant_type');//认证类型  client_credentials
        $client_id = $request->param('client_id', '');//应用ID
        if (empty($client_id)) return $this->sendError('1001', 'client_id error', 400);


        // 1 、 获取应用信息
        $clientInfo = self::getClient($client_id);
        if (!$clientInfo) return $this->sendError(2001, 'not client', 401);
        // 2、 认证
        if (self::verifySign($request, $clientInfo) == false) return $this->sendError(2002, 'authentication Failed', 401);//authenticationFailed
        //3、下放access_token
        //判断是存在 access_token
        $access_token = $this->getAccessTokenAndClient($client_id);
        $access_token = (!$access_token) ? self::setAccessToken($clientInfo) : $access_token;
        return Response::create([
            'access_token' => $access_token, //访问令牌
            'expires' => self::$expires,      //过期时间秒数
        ], $this->type, 200);

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
        if (empty($client_id)) return $this->sendError('1001', 'client_id error', 400);

        // 1 、 获取应用信息
        $clientInfo = self::getClient($client_id);
        if (!$clientInfo) return $this->sendError(2001, 'not client', 401);

        // 2、 认证
        if (self::verifySign($request, $clientInfo) == false) return $this->sendError(2002, 'authentication Failed', 401);//authenticationFailed
        // 3、控制 refreshToken请求次数
        //todo
        // 4、无需情况原有 accessToken、能使用的access_token 和用户信息关联了 下放即可
        $access_token = self::setAccessToken($clientInfo);
        return Response::create([
            'access_token' => $access_token, //访问令牌
            'expires' => self::$expires,      //过期时间秒数
        ], $this->type, 200);

    }

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
     * 包括不限于验证签名
     * @param Request $request
     * @param $clientInfo
     * @return bool
     */
    public function verifySign(Request $request, $clientInfo)
    {
        $requestTime = $request->param('time', '', 'int');//请求时间
        //$ip = $request->ip();//ip
        if (empty($requestTime)) return false;
        $sign = $request->param('sign');//签名
        if (empty($sign)) return false;
        //todo  签名验证、ip白黑名单等
        //签名 hash(md5( client_id + time + secret  ));
        $signS = self::makeSign($request->param('client_id'), $request->param('time'), $clientInfo['secret']);
        if ($sign !== $signS) return false;
        return true;
    }

    /**
     * 获取应用信息
     * @param $client_id 应用ID
     * @return array
     */
    public function getClient($client_id)
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

    public function getAccessTokenInfo($accessToken)
    {
        $keys = self::$accessTokenPrefix . $accessToken;
        $info = Cache::get($keys);
        if ($info == false || $info['expires_time'] < time()) return false;
        //验证索引是否正确
        $client_id = $info['client']['client_id'];
        if ($this->getAccessTokenAndClient($client_id) != $accessToken) return false;
        return $info;
    }


    public static function buildAccessToken()
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
    public static function saveAccessToken($accessToken, $accessTokenInfo)
    {
        //存储accessToken
        Cache::set(self::$accessTokenPrefix . $accessToken, $accessTokenInfo, 7200);
        //存储用户与信息索引 用于比较
        Cache::set(self::$accessTokenAndClientPrefix . $accessTokenInfo['client']['client_id'], $accessToken, 7200);

    }

    public function getAccessTokenAndClient($client_id)
    {
        return Cache::get(self::$accessTokenAndClientPrefix . $client_id);
    }

    /**
     * 权限验证
     * @param $request
     * @param $accessTokenInfo
     * @return bool
     */
    public static function verifyPermissions($request, $accessTokenInfo)
    {
        //获取allModule
        $dispatch = $request->dispatch();
        $allModule = implode('/', $dispatch['module']);
        $authorization_list = explode(',', $accessTokenInfo['client']['authorization_list']);
        return (in_array($allModule, $authorization_list)) ? true : false;
    }


}