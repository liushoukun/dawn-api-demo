<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | Company: YG | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/3/9 15:18
// +----------------------------------------------------------------------
// | TITLE: this to do?
// +----------------------------------------------------------------------
namespace app\test\auth;

use liushoukun\api\contract\AuthContract;
use think\Request;

class TestAuth implements AuthContract
{
    /**
     * 认证授权 通过用户信息和路由
     * @param Request $request
     * @return bool
     */
    public function authenticate(Request $request)
    {
        return false;
    }





}