<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/3/15 23:36
// +----------------------------------------------------------------------
// | TITLE: this to do?
// +----------------------------------------------------------------------

namespace app\test\controller;


use liushoukun\api\exception\UnauthorizedException;
use think\Exception;
use think\Request;
use think\Response;

class Oauth extends \liushoukun\api\auth\OAuth
{
    public function accessToken(Request $request)
    {
        //获客户端信息
        try {
            $this->getClient($request);
        } catch (UnauthorizedException $e) {
            //错误则返回给客户端
            return $this->sendError(401, $e->getMessage(), 401, [],$e->getHeaders());
        }

        //校验信息
        $this->client_id;
        dump($this->client_id);
        die();

    }

}