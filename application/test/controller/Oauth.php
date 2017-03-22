<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/3/15 23:36
// +----------------------------------------------------------------------
// | TITLE: 简单 oauth
// +----------------------------------------------------------------------

namespace app\test\controller;


use liushoukun\api\exception\UnauthorizedException;
use think\Request;

class Oauth extends \liushoukun\api\auth\OAuth
{
    public function accessToken(Request $request)
    {
        //获客户端信息
        try {
            $this->getClient($request);
        } catch (UnauthorizedException $e) {
            //错误则返回给客户端
            return $this->sendError(401, $e->getMessage(), 401, [], $e->getHeaders());
        }
        //校验信息
        $this->client_id;
        $this->secret;


        $options =  ["access_token" => "2YotnFZFEjr1zCsicMWpAA",
            "token_type" => "example",
            "expires_in" => 3600,
            "example_parameter" => "example_value"];
        return $this->sendSuccess([],'success',200,[],$options);

    }

    /**
     * 获取用户信息后 验证权限
     * @return mixed
     */
    public function certification()
    {
        $this->access_token;
        //dump( $this->access_token);
        return true;
    }


}