<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/3/26 13:24
// +----------------------------------------------------------------------
// | TITLE: 业务基础类
// +----------------------------------------------------------------------
namespace app\demo\controller;


use DawnApi\facade\ApiController;
class Base extends ApiController
{

    //是否开启授权认证
    public    $apiAuth = true;

}