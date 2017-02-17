<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | Company: YG | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/2/17 10:08
// +----------------------------------------------------------------------
// | TITLE: 模块 API 公共类
// +----------------------------------------------------------------------


namespace app\location\controller;


use app\lib\BaseRest;

class Base extends BaseRest
{
    // 允许访问的请求类型
    protected $restMethodList = 'get|post|put|delete|patch|head|options';
    //业务错误码的映射表
    public $errMap = [
        0 => 'success',//没有错误

        1001 => '参数错误',
        9999 => '自定义错误'//让程序给出的自定义错误
    ];

}