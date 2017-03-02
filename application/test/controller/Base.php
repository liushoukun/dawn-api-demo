<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | Company: YG | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/2/17 10:08
// +----------------------------------------------------------------------
// | TITLE: 业务基础类
// +----------------------------------------------------------------------


namespace app\test\controller;


use app\apilib\BaseRest;

/**
 * 业务基础类
 * Class Base
 * @package app\test\controller
 */
class Base extends BaseRest
{

    //业务错误码的映射表
    public $errMap = [
        0 => 'success',//没有错误

        1001 => '参数错误',
        9999 => '自定义错误'//让程序给出的自定义错误
    ];

    //是否开启权限认证
    public    $apiAuth = true;
}