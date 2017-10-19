<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// |  User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/3/9 15:18
// +----------------------------------------------------------------------
// | TITLE: 测试的基础验证
// +----------------------------------------------------------------------
namespace app\demo\auth;

use DawnApi\auth\Basic;
use think\Request;

class BasicAuth extends Basic
{
    public function getUser()
    {
        return $this->getUserInfo();
    }


    /**
     * 获取用户信息后 验证权限,
     * @param Request $request
     * @return bool
     */
    public function certification(Request $request)
    {
        return ($this->username == 'test' && $this->password == 'test') ? true : false;
    }

    /**
     * 获取用户信息
     * @return mixed
     */
    public function getUserInfo()
    {
        return [
            'client_id' => $this->username,//app_id
            'secret' => $this->password,
            'name' => 'test_client'
        ];
    }


}