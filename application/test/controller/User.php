<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/3/26 10:50
// +----------------------------------------------------------------------
// | TITLE: 用户接口
// +----------------------------------------------------------------------

namespace app\test\controller;

/**
 * Class User
 * @title 用户接口标题
 * @url  /v1/user
 * @version 1.0
 * @readme /doc/md/user.md
 * @desc 这是一个接口案例说明
 * @package app\test\controller
 */
class User extends Base
{

    public $restMethodList = 'get|post';
    /**
     * 参数规则 生成文档时需要
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
            //共用参数
            'all' => [
                'time' => ['name' => 'time', 'type' => 'int', 'require' => 'true', 'default' => '', 'desc' => '时间戳', 'range' => '',]
            ],

            'get' => [
                'id' => ['name' => 'id', 'type' => 'int', 'require' => 'true', 'default' => '', 'desc' => '用户id', 'range' => '',]
            ],
            'post' => [
                'username' => ['name' => 'username', 'type' => 'string', 'require' => 'true', 'default' => '', 'desc' => '用户名', 'range' => '',],
                'age' => ['name' => 'age', 'type' => 'int', 'require' => 'true', 'default' => '18', 'desc' => '年龄', 'range' => '0-200',],
            ],
            'put' => [
                'username' => ['name' => 'username', 'type' => 'string', 'require' => 'true', 'default' => '', 'desc' => '用户名', 'range' => '',],
                'age' => ['name' => 'age', 'type' => 'int', 'require' => 'true', 'default' => '18', 'desc' => '年龄', 'range' => '0-200',],
            ],

        ];

        return $rules;
    }


    /**
     * @title 获取用户信息
     * @desc 获取用户信息
     * @readme /doc/md/method.md
     * @return int id ID
     * @return string username 错误信息
     * @return int age 年龄
     */
    public function get()
    {
        return $this->sendSuccess([
                'id' => 1,
                'username' => 'dawn-api',
                'age' => 1]
        );
    }

    /**
     * @title 添加用户信息
     * @desc  添加用户信息
     * @readme /doc/md/method.md
     * @return string message 错误信息
     * @return int errCode 错误号
     */
    public function post()
    {
        // todo 添加操作
        return $this->sendSuccess(['id' => '2']);
    }


}