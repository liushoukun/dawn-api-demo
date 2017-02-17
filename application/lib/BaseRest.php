<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | Company: YG | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/2/16 14:27
// +----------------------------------------------------------------------
// | TITLE: rest Api 基础类
// +----------------------------------------------------------------------
namespace app\lib;

use think\controller\Rest;
use think\Request;
use think\Response;

class BaseRest extends Rest
{
    // 当前请求类型
    protected $method;
    // 当前资源类型
    protected $type;
    // 允许访问的请求类型
    protected $restMethodList = 'get|post|put|delete|patch|head|options';
    //默认请求类型
    protected $restDefaultMethod = 'get';
    //允许响应的资源类型
    protected $restTypeList = 'html|xml|json|rss';
    //默认响应类型
    protected $restDefaultType = 'json';
    //默认错误提示语
    protected $restDefaultMessage = 'error';
    // 非法
    private $notMethod;
    // REST允许输出的资源类型列表
    protected $restOutputType = [
        'xml' => 'application/xml',
        'json' => 'application/json',
        'html' => 'text/html',
    ];

    //响应数据
    public $errCode;
    public $message;
    public $data;
    //数据集合
    public $responseData;


    //业务错误码的映射表
    public $errMap = [
        0 => 'success',//没有错误

        1001 => '参数错误',
        9999 => '自定义错误'//让程序给出的自定义错误
    ];

    public function __construct()
    {
        //  重写 rest 类
        // 资源类型检测
        $request = Request::instance();
        $ext = $request->ext();
        if ('' == $ext) {
            // 自动检测资源类型
            $this->type = $request->type();
        } elseif (!preg_match('/\(' . $this->restTypeList . '\)$/i', $ext)) {
            // 资源类型非法 则用默认资源类型访问
            $this->type = $this->restDefaultType;
        } else {
            $this->type = $ext;
        }

        // 请求方式检测
        $method = strtolower($request->method());
        if (false === stripos($this->restMethodList, $method)) {
            // 请求方式非法 则用默认请求方法
            $this->notMethod = true;
        }
        $this->method = $method;
        //todo 可以修改为 字段检测
        $this->type = $this->restDefaultType;

    }

    /**
     * 默认调用
     * @return mixed
     */
    public function init()
    {
        // 请求方式非法
        if ($this->notMethod == true) return $this->_notMethod();
        //具体调用
        $action = $this->method . 'Response';
        return $this->$action(Request::instance());
    }

    /**
     * 设置响应数据
     * @param int $errCode
     * @param string $message
     * @param array $data
     * @return $this
     */
    public function setResponseArr($errCode = 0, $message = '', $data = [])
    {
        $this->errCode = $errCode;
        $this->restDefaultMessage = (empty($this->errCode[$errCode])) ? $this->restDefaultMessage : $this->errCode[$errCode];
        $this->message = !empty($message) ? $message : $this->restDefaultMessage;
        $this->data = $data;
        $this->responseData = array(
            'errCode' => $this->errCode,
            'message' => $this->message,
            'data' => $this->data,
        );
        return $this;
    }

    /**
     * 获取响应数据
     * @return mixed
     */
    public  function getResponseArr()
    {
        return $this->responseData;
    }


    /**
     * 非法操作响应
     * @return \think\Response
     */
    protected function _notMethod()
    {
        return $this->response(['errorCode' => '403', 'message' => 'not method!'], $this->type, 403);
    }

    /**
     * 响应
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type 返回类型 JSON XML
     * @param integer $code HTTP状态码
     * @return Response
     */
    protected function response($data, $type = '', $code = 200)
    {
        $data = (empty($data)) ? $this->getResponseArr() : $data;
        $type = (empty($type)) ? $this->type : $type;
        return Response::create($data, $type, $code);
    }

    /**
     * 错误响应
     * @param int $errCode
     * @param string $message
     * @param int $code
     * @return Response
     */
    public function sendError($errCode = 0, $message = '', $code = 400)
    {
        return $this->setResponseArr($errCode, $message)->response('', '', $code);
    }

    /**
     * 正确响应
     * @param $data
     * @param int $code
     * @return Response
     */
    public function sendSuccess($data, $code = 200)
    {

        return $this->setResponseArr('0', 'success', $data)->response('', '', $code);
    }



    // |====================================
    // |具体响应子类重写
    // |====================================
    /**
     * get的响应
     * @param Request $request
     * @return mixed
     */
    public function getResponse(Request $request)
    {
        return $this->setResponseArr(0,'Default  GET Response!')->response('','',403);
    }

    /**
     * post的响应
     * @param Request $request
     * @return mixed
     */
    public function postResponse(Request $request)
    {
        return $this->setResponseArr(0,'Default  POST Response!')->response('','',403);
    }

    /**
     * put的响应
     * @param Request $request
     * @return mixed
     */
    public function putResponse(Request $request)
    {
        return $this->setResponseArr(0,'Default  PUT Response!')->response('','',403);
    }

    /**
     * delete的响应
     * @param Request $request
     * @return mixed
     */
    public function deleteResponse(Request $request)
    {
        return $this->setResponseArr(0,'Default  DELETE Response!')->response('','',403);
    }

    /**
     * patch的响应
     * @param Request $request
     * @return mixed
     */
    public function patchResponse(Request $request)
    {
        return $this->setResponseArr(0,'Default  PATH Response!')->response('','',403);
    }

    /**
     * head的响应
     * @param Request $request
     * @return mixed
     */
    public function headResponse(Request $request)
    {
        return $this->setResponseArr(0,'Default  HEAD Response!')->response('','',403);
    }

    /**
     * options的响应
     * @param Request $request
     * @return mixed
     */
    public function optionsResponse(Request $request)
    {
        return $this->setResponseArr(0,'Default  OPTIONS Response!')->response('','',403);
    }


}