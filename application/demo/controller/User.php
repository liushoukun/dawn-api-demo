<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/3/26 13:26
// +----------------------------------------------------------------------
// | TITLE: 用户接口
// +----------------------------------------------------------------------

namespace app\demo\controller;


use think\Request;

class User extends Base
{


    //是否开启授权认证
    public $apiAuth = true;

    // 允许访问的请求类型
    public $restMethodList = 'get|post|delete';


    /**
     * get
     *
     * @param Request $request
     * @return \think\Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\Xml
     */
    public function get(Request $request)
    {
        $user = self::$app['auth']->getUser();
        // todo find
        return $this->sendSuccess(['name' => 'dawn-api', 'id' => 1, 'user' => $user]);
    }

    /**
     * post
     *
     * @param Request $request
     * @return \think\Response|\think\response\Json|\think\response\Jsonp|\think\response\Xml
     */
    public function post(Request $request)
    {
        //todo create
        return $this->sendError(400, '用户名不能为空');
    }


}