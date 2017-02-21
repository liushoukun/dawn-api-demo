<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | Company: YG | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/2/21 14:50
// +----------------------------------------------------------------------
// | TITLE: Test接口
// +----------------------------------------------------------------------


namespace app\test\controller;


use think\Request;

class Test extends Base
{

    public    $apiAuth = true;
    // 允许访问的请求类型
    protected $restMethodList = 'get|post|';

    /**
     * get的响应
     * @param Request $request
     * @return mixed
     */
    public function getResponse(Request $request)
    {
        return  $this->sendError(1001,'THIS IS GET',400);
    }

    /**
     * post的响应
     * @param Request $request
     * @return mixed
     */
    public function postResponse(Request $request)
    {
        return  $this->sendSuccess('THIS IS POST');
    }



}