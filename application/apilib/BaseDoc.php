<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | Company: YG | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2017/2/28 18:08
// +----------------------------------------------------------------------
// | TITLE: 文档
// +----------------------------------------------------------------------


namespace app\apilib;


use think\Config;
use think\Request;
use think\Response;
use think\Url;

class BaseDoc extends Common
{
    const METHOD_POSTFIX = 'Response';
    public static $titleDoc = 'API文档';
    /**
     * 返回字段
     * @var array
     */
    public static $returnFieldMaps = [
        'name' => '参数名',
        'type' => '类型',
        'desc' => '说明',
    ];
    /**
     * 请求字段
     * @var array
     */
    public static $dataFieldMaps = [
        'name' => '参数名',
        'require' => '必须',
        'type' => '类型',
        'default' => '默认值',
        'range' => '范围',
        'desc' => '说明',
    ];

    /**
     * 文档首页
     */
    public function main()
    {
        echo '主页';

    }

    /**
     * 接口列表
     * @return \think\response\View
     */
    public function apiList()
    {
        $apiList = self::getApiDocList();
        $apiListHtmlPath = APP_PATH . 'apilib' . DS . 'view' . DS . 'apiList.tpl';
        $apiListHtmlPath = (Config::get('apiListHtmlPath')) ? Config::get('apiListHtmlPath') : $apiListHtmlPath;
        return view($apiListHtmlPath, ['menu' => self::buildMenuHtml(Tree::makeTree($apiList)), 'titleDoc' => self::$titleDoc]);

    }

    /**
     * 接口详细文档
     * @param Request $request
     * @return \think\response\View
     */
    public function apiInfo(Request $request)
    {
        $id = $request->param('id');
        $apiOne = self::getApiDocOne($id);
        $module = $apiOne['module'];
        $controller = $apiOne['controller'];

        $className = 'app\\' . $module . '\\' . 'controller\\' . $controller;

        //获取接口类注释
        $classDoc = self::getClassDoc($className);

        //没有接口类  判断是否有 Markdown文档
        if ($classDoc == false ) {
           //输出 Markdown文档
            if ( !isset($apiOne['readme']) || empty($apiOne['readme'])) return $this->sendError('','没有接口');
            $apiMarkdownHtmlPath = APP_PATH . 'apilib' . DS . 'view' . DS . 'apiMarkdown.tpl';
            $apiMarkdownHtmlPath = (Config::get('apiMarkdownHtmlPath')) ? Config::get('apiMarkdownHtmlPath') : $apiMarkdownHtmlPath;
            return view($apiMarkdownHtmlPath, ['classDoc' => $apiOne,'titleDoc' => self::$titleDoc]);
        }
        $classDoc['module'] = $module;
        $classDoc['controller'] = $controller;
        //获取请求列表文档
        $methodDoc = self::getMethodListDoc($className);
        //模板位置
        $apiInfoHtmlPath = APP_PATH . 'apilib' . DS . 'view' . DS . 'apiInfo.tpl';
        $apiInfoHtmlPath = (Config::get('apiInfoHtmlPath')) ? Config::get('apiInfoHtmlPath') : $apiInfoHtmlPath;

        //字段
        $fieldMaps['return'] = self::$returnFieldMaps;
        $fieldMaps['data'] = self::$dataFieldMaps;
        $fieldMaps['type'] = self::$typeMaps;

        return view($apiInfoHtmlPath, ['classDoc' => $classDoc, 'methodDoc' => $methodDoc, 'fieldMaps' => $fieldMaps, 'titleDoc' => self::$titleDoc]);
    }

    /**
     * 获取文档
     * @return mixed
     */
    public static function getApiDocList()
    {
        //todo 可以写配置文件或数据
        $apiList = Config::get('api_doc');
        return $apiList;
    }

    public static function getApiDocOne($id)
    {
        $apiList = Config::get('api_doc');
        return $apiList[$id];
    }

    /**
     * 获取数据
     * @param Request $request
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public function tableData(Request $request)
    {
        $id = $request->param('id');
        $apiOne = self::getApiDocOne($id);
        $module = $apiOne['module'];
        $controller = $apiOne['controller'];
        $className = 'app\\' . $module . '\\' . 'controller\\' . $controller;

        $method = $request->param('method', 'get');
        $dataType = $request->param('dataType', 'data');
        //获取接口类注释
        $methodDoc = self::getMethodListDoc($className);
        switch ($dataType) {
            case 'data':
                $responseData = array_values($methodDoc[$method]['rules']);
                break;
            case 'return':
                $responseData = array_values($methodDoc[$method]['return']);
                break;
            default:
                $responseData = [];
                break;
        }

        return Response::create($responseData, 'json');
    }


    /**
     * 获取接口类文档
     * @param $className
     * @return array
     */
    public static function getClassDoc($className)
    {
        try {
            $reflection = new \ReflectionClass($className);
        } catch (\ReflectionException  $e) {
            return false;
        }
        $docComment = $reflection->getDocComment();
        return self::getDoc($docComment);
    }

    /**
     * 获取各种方式响应文档
     * @param $className
     * @return mixed
     */
    public static function getMethodListDoc($className)
    {
        //获取参数规则
        $rules = $className::getRules();
        $restMethodList = self::getRestMethodList($className);
        foreach ($restMethodList as $method) {
            $reflection = new \ReflectionMethod($className, $method . self::METHOD_POSTFIX);
            $docComment = $reflection->getDocComment();
            //获取title,desc,readme,return等说明
            $methodDoc[$method] = self::getDoc($docComment);
            $methodDoc[$method]['rules'] = array_merge($rules['all'], $rules[$method]);
        }
        return $methodDoc;
    }

    /**
     * 获取接口所有请求方式
     * @param $className
     * @return array
     */
    public static function getRestMethodList($className)
    {
        $reflection = new \ReflectionClass($className);
        $Properties = $reflection->getDefaultProperties();
        $restMethodList = explode('|', $Properties['restMethodList']);
        $restMethodList = array_filter($restMethodList, function ($item) {
            if (stripos(' get|post|put|delete|patch|head|options', $item)) return $item;
        });
        return $restMethodList;
    }


    /**
     * 获取注释转换成数组
     * @param $docComment
     * @return mixed
     */
    private static function getDoc($docComment)
    {
        $docCommentArr = explode("\n", $docComment);
        foreach ($docCommentArr as $comment) {
            $comment = trim($comment);
            //接口名称
            $pos = stripos($comment, '@title');
            if ($pos !== false) {
                $data['title'] = trim(substr($comment, $pos + 6));
                continue;
            }
            //接口描述
            $pos = stripos($comment, '@desc');
            if ($pos !== false) {
                $data['desc'] = trim(substr($comment, $pos + 5));
                continue;
            }
            //接口说明文档
            $pos = stripos($comment, '@readme');
            if ($pos !== false) {
                $data['readme'] = trim(substr($comment, $pos + 7));
                continue;
            }
            //接口url
            $pos = stripos($comment, '@url');
            if ($pos !== false) {
                $data['url'] = trim(substr($comment, $pos + 4));
                continue;
            }
            //接口url versions
            $pos = stripos($comment, '@version');
            if ($pos !== false) {
                $data['version'] = trim(substr($comment, $pos + 8));
                continue;
            }

            //返回字段说明
            //@return注释
            $pos = stripos($comment, '@return');
            //以上都没有匹配到直接下一行
            if ($pos === false) {
                continue;
            }
            $returnCommentArr = explode(' ', substr($comment, $pos + 8));
            //将数组中的空值过滤掉，同时将需要展示的值返回
            $returnCommentArr = array_values(array_filter($returnCommentArr));
            //如果小于3个也过滤
            if (count($returnCommentArr) < 2) {
                continue;
            }
            if (!isset($returnCommentArr[2])) {
                $returnCommentArr[2] = '';    //可选的字段说明
            } else {
                //兼容处理有空格的注释
                $returnCommentArr[2] = implode(' ', array_slice($returnCommentArr, 2));
            }
            $returnCommentArr[0] = (in_array(strtolower($returnCommentArr[0]), array_keys(self::$typeMaps))) ? self::$typeMaps[strtolower($returnCommentArr[0])] : $returnCommentArr[0];
            $data['return'][] = [
                'name' => $returnCommentArr[1],
                'type' => $returnCommentArr[0],
                'desc' => $returnCommentArr[2],
            ];

        }
        $data['title'] = (isset($data['title'])) ? $data['title'] : '';
        $data['desc'] = (isset($data['desc'])) ? $data['desc'] : '';
        $data['readme'] = (isset($data['readme'])) ? $data['readme'] : '';
        $data['return'] = (isset($data['return'])) ? $data['return'] : [];
        $data['url'] = (isset($data['url'])) ? $data['url'] : [];
        $data['version'] = (isset($data['version'])) ? $data['version'] : [];
        return $data;
    }

    /**
     * 生成 接口菜单
     * @param $data
     * @param string $html
     * @return string
     */
    private static function buildMenuHtml($data, $html = '')
    {
        foreach ($data as $k => $v) {
            $html .= '<li >';
            if (isset($v['children']) && is_array($v['children'])) {
                $html .= '<a href="javascript:;"><i class="fa fa-folder"></i> <span class="nav-label">' . $v['name'] . '</span><span class="fa arrow"></span></a>';//name
            } else {
                $html .= '<a href="' . Url::build('apiInfo', ['module' => $v['module'], 'controller' => $v['controller'], 'id' => $v['id']]) . '" class="J_menuItem"><i class="fa fa-file"></i> <span class="nav-label">' . $v['name'] . '</span></a>';//
            }
            //需要验证是否有子菜单
            if (isset($v['children']) && is_array($v['children'])) {

                $html .= '<ul class="nav nav-second-level">';
                $html .= self::buildMenuHtml($v['children']);
                //验证是否有子订单
                $html .= '</ul>';

            }
            $html .= '</li>';

        }
        return $html;

    }


}

class Tree
{

    protected static $config = array(
        /* 主键 */
        'primary_key' => 'id',
        /* 父键 */
        'parent_key' => 'parent',
        /* 展开属性 */
        'expanded_key' => 'expanded',
        /* 叶子节点属性 */
        'leaf_key' => 'leaf',
        /* 孩子节点属性 */
        'children_key' => 'children',
        /* 是否展开子节点 */
        'expanded' => false
    );

    /* 结果集 */
    protected static $result = array();

    /* 层次暂存 */
    protected static $level = array();

    /**
     * @name 生成树形结构
     * @param array 二维数组
     * @return mixed 多维数组
     */
    public static function makeTree($data, $options = array())
    {
        $dataset = self::buildData($data, $options);
        $r = self::makeTreeCore(0, $dataset, 'normal');
        return $r;
    }

    /* 生成线性结构, 便于HTML输出, 参数同上 */
    public static function makeTreeForHtml($data, $options = array())
    {

        $dataset = self::buildData($data, $options);
        $r = self::makeTreeCore(0, $dataset, 'linear');
        return $r;
    }

    /* 格式化数据, 私有方法 */
    private static function buildData($data, $options)
    {
        $config = array_merge(self::$config, $options);
        self::$config = $config;
        extract($config);
        $r = array();
        foreach ($data as $item) {
            $id = $item[$primary_key];
            $parent_id = $item[$parent_key];
            $r[$parent_id][$id] = $item;
        }
        return $r;
    }

    /* 生成树核心, 私有方法  */
    private static function makeTreeCore($index, $data, $type = 'linear')
    {
        extract(self::$config);
        foreach ($data[$index] as $id => $item) {
            if ($type == 'normal') {
                if (isset($data[$id])) {
                    $item[$expanded_key] = self::$config['expanded'];
                    $item[$children_key] = self::makeTreeCore($id, $data, $type);
                } else {
                    $item[$leaf_key] = true;
                }
                $r[] = $item;
            } else if ($type == 'linear') {
                $parent_id = $item[$parent_key];
                self::$level[$id] = $index == 0 ? 0 : self::$level[$parent_id] + 1;
                $item['level'] = self::$level[$id];
                self::$result[] = $item;
                if (isset($data[$id])) {
                    self::makeTreeCore($id, $data, $type);
                }

                $r = self::$result;
            }
        }
        return $r;
    }
}
