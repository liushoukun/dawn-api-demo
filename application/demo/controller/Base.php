<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/3/26 13:24
// +----------------------------------------------------------------------
// | TITLE: 业务基础类
// +----------------------------------------------------------------------
namespace app\demo\controller;


use DawnApi\facade\Api;
class Base extends Api
{

    /**
     * 允许访问的请求类型
     * @var string
     */
    public $restMethodList = 'get|post|put|delete|patch|head|options';

    //是否开启授权认证
    public    $apiAuth = false;

}