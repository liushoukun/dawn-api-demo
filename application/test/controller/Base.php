<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/3/26 10:50
// +----------------------------------------------------------------------
// | TITLE:接口基础类
// +----------------------------------------------------------------------

namespace app\test\controller;


use DawnApi\facade\Api;

class Base  extends Api
{
    /**
     * 是否开启权限
     *
     * @var bool
     */
    public $apiAuth = true;

    /**
     * 允许访问的请求类型
     * @var string
     */
    public $restMethodList = 'get|post|put|delete';

}