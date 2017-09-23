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
    public $apiAuth = true;


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
            //共用参数
            'all' => [
                'access_token' => ['name' => 'access_token', 'type' => 'string', 'require' => 'true', 'default' => '', 'desc' => '授权令牌', 'range' => '',]
            ],


            'get' => [
                'id' => ['name' => 'id', 'type' => 'string', 'require' => 'true', 'default' => '', 'desc' => 'id', 'range' => '',],
          ],
            'post' => [
                'name' => ['name' => 'name', 'type' => 'string', 'require' => 'true', 'default' => '', 'desc' => '名称', 'range' => '',],
                'age' => ['name' => 'age', 'type' => 'string', 'require' => 'true', 'default' => '', 'desc' => '年龄', 'range' => '',],
            ]
        ];
        //可以合并公共参数
        return $rules;
    }

    /**
     * get响应
     * @title 获取用户
     * @return string name 名字
     * @return string id  id
     * @return object user  用户信息
     * @readme /doc/md/method.md
     * @param Request $request
     */
    public function get(Request $request)
    {
        $user = self::$app['auth']->getUser();
        // todo find
        return $this->sendSuccess(['name' => 'dawn-api', 'id' => 1, 'user' => $user]);
    }

    /**
     * post
     *

     * @return \think\Response|\think\response\Json|\think\response\Jsonp|\think\response\Xml
     */
    /**
     * get响应
     * @title 新增用户
     * @return int id 用户id
     * @readme /doc/md/method.md
     * @param Request $request
     * @param Request $request
     */
    public function post(Request $request)
    {
        //todo create
        return $this->sendError(400, '用户名不能为空');
    }


    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        echo  'index';
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        echo  'create';
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
        echo  'save';
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
        echo 'read';
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
        echo  'edit';
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
        echo  'update';
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
        echo  'delete';
    }
}