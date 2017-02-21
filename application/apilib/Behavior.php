<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | Company: YG | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/2/17 18:24
// +----------------------------------------------------------------------
// | TITLE: this to do?
// +----------------------------------------------------------------------


namespace app\apilib;


use think\Log;

class Behavior
{
    public function apiEnd(&$param)
    {
        Log::write('apiEnd', json_encode($param));
    }

}