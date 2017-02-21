<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | Company: YG | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/2/21 10:58
// +----------------------------------------------------------------------
// | TITLE: 公共的
// +----------------------------------------------------------------------


namespace app\apilib;


use think\controller\Rest;
use  think\Request;
use think\Response;

class Common extends Rest
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

    public function __construct()
    {
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
        //设置响应类型
        $this->setRestType($request);
    }


    /**
     * 设置响应类型
     * @param $request
     * @return $this
     */
    public function setRestType(Request $request)
    {
        $this->type = $this->restDefaultType;
        return $this;
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
        $this->restDefaultMessage = (empty($this->errMap[$errCode])) ? $this->restDefaultMessage : $this->errMap[$errCode];
        $this->message = !empty($message) ? $message : $this->restDefaultMessage;
        $this->data = $data;
        $this->responseData['errCode'] = $this->errCode;
        $this->responseData['message'] = $this->message;
        if (!empty($this->data)) $this->responseData['data'] = $this->data;
        return $this;
    }

    /**
     * 获取响应数据
     * @return mixed
     */
    public function getResponseArr()
    {
        return $this->responseData;
    }


    /**
     * 非法操作响应
     * @return \think\Response
     */
    protected function notMethod()
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


}