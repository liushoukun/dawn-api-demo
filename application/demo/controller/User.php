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

/**
 * Class User
 * @title 用户接口
 * @url  http://dawn-api.com/v1/user
 * @desc  用户信息相关接口
 * @version 1.0
 * @readme /doc/md/user.md
 */
class User extends Base
{
    //是否开启授权认证
    public $apiAuth = false;
    //附加方法
    protected $extraMethodList = 'sendCode|';

    /**
     * 参数规则
     * @name 字段名称
     * @type 类型
     * @require 是否必须
     * @default 默认值
     * @desc 说明
     * @range 范围
     * @return array
     */
    public static function getRules()
    {
        $rules = [
            'index' => [
            ],
            'create' => [
                'name' => ['name' => 'name', 'type' => 'string', 'require' => 'true', 'default' => '', 'desc' => '名称', 'range' => '',],
                'age' => ['name' => 'age', 'type' => 'string', 'require' => 'true', 'default' => '', 'desc' => '年龄', 'range' => '',],
            ],
            'sendCode' => [
                'name' => ['name' => 'name', 'type' => 'string', 'require' => 'true', 'default' => '', 'desc' => '名称', 'range' => '',],
                'age' => ['name' => 'age', 'type' => 'string', 'require' => 'true', 'default' => '', 'desc' => '年龄', 'range' => '',],
            ]

        ];
        //可以合并公共参数
        return $rules;

    }

    /**
     * @title 发送CODE
     * @readme /doc/md/method.md
     */
    public function sendCode()
    {
        //send message
        return $this->sendSuccess();

    }

    /**
     * @title 获取列表
     * @return string name 名字
     * @return string id  id
     * @return integer age  年龄
     * @readme /doc/md/method.md
     */
    public function index()
    {
        return $this->sendSuccess(self::testUserData());
    }


    /**
     * @title 创建用户
     * @param Request $request
     * @return string name 名字
     * @return string id  id
     * @return object user  用户信息
     * @readme /doc/md/method.md
     * @param  \think\Request $request
     */
    public function save(Request $request)
    {
        $data = $request->input();
        // db save
        $data['id'] = 4;
        return $this->sendSuccess($data);

    }

    /**
     * @title 获取单个用户信息
     * @param  int $id
     * @return string name 名字
     * @return string id  id
     * @return object user  用户信息
     * @readme /doc/md/method.md
     * @return \think\Response
     */
    public function read($id)
    {
        //
        $testUserData = self::testUserData();
        return $this->sendSuccess($testUserData[$id]);
    }


    /**
     * 保存更新的资源
     *
     * @param  \think\Request $request
     * @param  int $id
     * @param  int $id
     * @title 更新用户
     * @return string name 名字
     * @return string id  id
     * @readme /doc/md/method.md
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
        $testUserData = self::testUserData();
        $data = $testUserData[$id];
        //更新
        $data['age'] = $request->post('age');

        return $this->sendSuccess($data);
    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return object user  用户信息
     * @title 删除用户
     * @return \think\Response
     */
    public function delete($id)
    {
        $testUserData = self::testUserData();
        // delete
        $user = $testUserData[$id];
        unset($testUserData[$id]);
        return $this->sendSuccess(['user' => $user], 'User deleted.');
    }


    public static function testUserData()
    {
        return [
            1 => ['id' => '1', 'name' => 'dawn', 'age' => 1],
            2 => ['id' => '2', 'name' => 'dawn1', 'age' => 2],
            3 => ['id' => '3', 'name' => 'dawn3', 'age' => 3],
        ];
    }
}