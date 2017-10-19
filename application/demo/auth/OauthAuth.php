<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/3/26 10:01
// +----------------------------------------------------------------------
// | TITLE: 简单的Oauth客户端模式
// +----------------------------------------------------------------------

namespace app\demo\auth;


use DawnApi\auth\OAuth;
use DawnApi\exception\UnauthorizedException;
use RandomLib\Factory;
use think\Cache;
use think\Request;

class OauthAuth extends OAuth
{

    /**
     * 客户端获取access_token
     * @param Request $request
     * @return \think\Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\Xml
     */
    public function accessToken(Request $request)
    {
        //获客户端信息
        try {
            $this->getClient($request);
        } catch (UnauthorizedException $e) {
            //错误则返回给客户端
            return $this->sendError(401, $e->getMessage(), 401, [], $e->getHeaders(),['WWW-Authenticate' => 'Basic']);
        } catch (Exception $e) {
            return $this->sendError(500, $e->getMessage(), 500);
        }
        //校验信息

        if ($this->getClientInfo($this->client_id)->checkSecret()) {
            //通过下放令牌
            $access_token = $this->setAccessToken($this->clientInfo);
        } else {
            return $this->sendError(401, 'authentication Failed', 401, [], ['WWW-Authenticate' => 'Basic']);
        }
        return $this->sendSuccess([], 'success', 200, [], [
            'access_token' => $access_token, //访问令牌
            'expires' => self::$expires,      //过期时间秒数
        ]);


    }

    /**
     * 校验密码
     * @return bool
     */
    public function checkSecret()
    {
        if ($this->secret == $this->clientInfo['secret']) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * 获取用户信息后 验证权限
     * @return mixed
     */
    public function certification()
    {

        if ($this->getAccessTokenInfo($this->access_token) == false) {
            return false;
        } else {
            return true;
        }
    }

    protected function getAccessTokenInfo($accessToken)
    {
        $keys = self::$accessTokenPrefix . $accessToken;
        $info = Cache::get($keys);
        if ($info == false || $info['expires_time'] < time()) return false;
        //验证索引是否正确
        $client_id = $info['client']['client_id'];
        if ($this->getAccessTokenAndClient($client_id) != $accessToken) return false;
        $this->clientInfo = $info['client'];
        return $info;
    }

    protected function getAccessTokenAndClient($client_id)
    {
        return Cache::get(self::$accessTokenAndClientPrefix . $client_id);
    }

    /**
     * 返回用户信息
     * @param $client_id
     * @return array
     */
    public static function getUserInfo($client_id)
    {


        $userList = [
          '20882088'=>[
              'client_id' => '20882088',//app_id
              'secret' => 'nGk5R2wrnZqQ02bed29rjzax1QWRIu1O',
              'name' => 'test_client']

        ];
        //  key $client_id
        return $userList[$client_id];

    }

    /**
     * 获取客户端所有信息
     * @param $client_id
     * @return mixed
     */
    public function getClientInfo($client_id)
    {
        // todo 通过客户端$client_id 获取所有信息
        $this->clientInfo = self::getUserInfo($client_id);
        return $this;
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

    /**
     * 生成AccessToken
     * @return string
     */
    protected static function buildAccessToken()
    {
        //生成AccessToken
        $factory = new Factory();
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
        Cache::set(self::$accessTokenPrefix . $accessToken, $accessTokenInfo, self::$expires);
        //存储用户与信息索引 用于比较
        Cache::set(self::$accessTokenAndClientPrefix . $accessTokenInfo['client']['client_id'], $accessToken, self::$expires);
    }

    /**
     * 获取用户信息
     * @return bool
     */
    public function getUser()
    {
        $info = $this->getAccessTokenInfo($this->access_token);
        if ($info) {
            $this->client_id = $info['client']['client_id'];
            $this->user = $info['client'];
            return $this->user;
        } else {
            return false;
        }

    }

}