<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[v1]' => [
        'user' => ['demo/User/init',], //用户模块接口
    ],

    //认证
    '[oauth]' => [
        'accessToken' => ['demo/Auth/accessToken',],//获取令牌
        'refreshToken' => ['demo/Auth/refreshToken',],//刷新令牌
        'getServerTime' => ['demo/Auth/getServerTime',],//获取服务器时间戳
    ],

    '[v1]' => [
        'test' => ['test/Index/restful',], //用户模块接口
    ],
    'accessToken'=>'test/Index/accessToken',




];
