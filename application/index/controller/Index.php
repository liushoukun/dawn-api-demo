<?php
namespace app\index\controller;

use think\Response;

class Index
{
    public function index()
    {

     return   Response::create(['ss'=>123],'json',403);


    }
}
