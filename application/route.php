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

//首页
Route::any('/',function (){
    return redirect('wiki');
});




Route::group('v1',function (){
    Route::any('user/sendCode','demo/User/sendCode');
    Route::resource('user','demo/User');
});

Route::any('accessToken','demo/auth/accessToken');//Oauth


// 文档
\DawnApi\route\DawnRoute::wiki();



return [
    '__pattern__' => [
        'name' => '\w+',
    ],
];


