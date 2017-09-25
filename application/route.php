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

use think\Route;

Route::resource('user','demo/User');
return [
    '__pattern__' => [
        'name' => '\w+',
    ],

    '__rest__'=>[
        'index'=>'demo/IndexController',
    ],

    'accessToken'=>'demo/Auth/accessToken',//Oauth认证
    'wiki'=>'demo/Wiki/index',//文档

];


