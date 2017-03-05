<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | Company: YG | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/2/21 14:50
// +----------------------------------------------------------------------
// | TITLE: Test接口
// +----------------------------------------------------------------------


namespace app\test\controller;


use think\Request;

/**
 * @title 测试接口
 * @url /v1/test
 * @version 1.0
 * @desc class测试说明
 * @readme /api/test.md
 */
class Test extends Base
{
    public $apiAuth = false;
    // 允许访问的请求类型
    public $restMethodList = 'get|post|';

    /**
     * 请求数据规则
     * @return array
     */
    public static function getRules()
    {
        $data = [
            'get' => [
                'usernames' => ['name' => 'usernames', 'type' => 'ss', 'require' => 'True', 'default' => 'PHPer', 'desc' => '用户名', 'range' => '1-100',
                ]
            ]
        ];
        return array_merge(parent::getRules(), $data);//可以合并共用数据规则
    }


    /**
     * @title 获取测试信息
     * @desc get的描述
     * @readme /api/test.md
     * @param Request $request
     * @return string title 标题
     * @return string username 用户名
     * @return int age 年龄
     */
    public function getResponse(Request $request)
    {
        return $this->sendSuccess([['title' => '测试信息', 'username' => 'test', 'age' => '17']]);
    }

    /**
     * @title 创建测试信息
     * @desc post的描述
     * @readme /api/test.md
     * @param Request $request
     * @return int id ID
     * @return string id2 ID2
     *
     */
    public function postResponse(Request $request)
    {
        return $this->sendSuccess('1');
    }


}