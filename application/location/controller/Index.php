<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | Company: YG | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/2/16 14:41
// +----------------------------------------------------------------------
// | TITLE: 单个api
// +----------------------------------------------------------------------


namespace app\location\controller;


use think\Request;

class Index extends Base
{

    // 允许访问的请求类型
    protected $restMethodList = 'get|post|';




    /**
     * get的响应
     * @param Request $request
     * @return mixed
     */
    public function getResponse(Request $request)
    {
        return $this->sendError(9999,'sss');
    }




}