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

//    '[v1]' => [
//        'user' => ['demo/User/restful',], //用户模块接口
//    ],

    '__rest__'=>[
        // 指向index模块的blog控制器
        'user'=>'demo/User',
        'index'=>'demo/IndexController',
    ],

    'accessToken'=>'demo/Auth/accessToken',//Oauth认证
    'wiki'=>'demo/Wiki/index',//文档




];
