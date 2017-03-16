<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | Company: YG | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/3/9 13:53
// +----------------------------------------------------------------------
// | TITLE: this to do?
// +----------------------------------------------------------------------


namespace app\test\controller;


use liushoukun\api\facade\Api;
use think\Request;
use think\Response;


class Index extends Api
{


    public function accessToken()
    {

        return Response::create(
            ["access_token" => "2YotnFZFEjr1zCsicMWpAA",
            "token_type" => "example",
            "expires_in" => 3600,
            "example_parameter" => "example_value"],'json');


    }

    public $apiAuth = true;

    public function index()
    {
        $request = Request::instance();
        dump($request->header());
        dump($_SERVER);
    }

    public function get()
    {
        return $this->sendSuccess(['username' => 'restfulapi-tp5', 'age' => 1]);

    }


    public function post()
    {

        return 4444;
    }

    public function put()
    {
        return 4444;
    }


}