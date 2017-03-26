<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/3/26 14:24
// +----------------------------------------------------------------------
// | TITLE: this to do?
// +----------------------------------------------------------------------

namespace app\demo\controller;


use app\demo\auth\OauthAuth;
use think\Request;

class Auth
{
    public function accessToken()
    {
        $request = Request::instance();
        $OauthAuth = new OauthAuth();
        return $OauthAuth->accessToken($request);
    }

}