<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">

    <title>{$titleDoc}</title>

    <meta name="keywords" content="">
    <meta name="description" content="">

    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->

    <link rel="shortcut icon" href="favicon.ico"> <link href="__STATIC__/hadmin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="__STATIC__/hadmin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="__STATIC__/hadmin/css/animate.css" rel="stylesheet">
    <link href="__STATIC__/hadmin/css/style.css?v=4.1.0" rel="stylesheet">
</head>

<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
<div id="wrapper">
    <!--左侧导航开始-->
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close"><i class="fa fa-times-circle"></i>
        </div>
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                                    <span class="block m-t-xs" style="font-size:20px;">
                                        <strong class="font-bold">{$titleDoc}</strong>
                                    </span>
                                </span>
                        </a>
                    </div>
                    <div class="logo-element">{$titleDoc}
                    </div>
                </li>


                <li>
                    <a class="J_menuItem" href="{:url('main')}" >
                        <i class="fa fa-home"></i>
                        <span class="nav-label">主页</span>
                    </a>
                </li>
                <li class="line dk"></li>
                {$menu}


            </ul>
        </div>
    </nav>
    <!--左侧导航结束-->
    <!--右侧部分开始-->
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row J_mainContent" id="content-main">
            <iframe  id="J_iframe" width="100%" height="100%" src="{:url('main')}" frameborder="0" data-id="{:url('main')}" seamless></iframe>
        </div>
    </div>
    <!--右侧部分结束-->
</div>

<!-- 全局js -->
<script src="__STATIC__/hadmin/js/jquery.min.js?v=2.1.4"></script>
<script src="__STATIC__/hadmin/js/bootstrap.min.js?v=3.3.6"></script>
<script src="__STATIC__/hadmin/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="__STATIC__/hadmin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="__STATIC__/hadmin/js/plugins/layer/layer.min.js"></script>

<!-- 自定义js -->
<script src="__STATIC__/hadmin/js/hAdmin.js?v=4.1.0"></script>
<script type="text/javascript" src="__STATIC__/hadmin/js/index.js"></script>

<!-- 第三方插件 -->
<script src="__STATIC__/hadmin/js/plugins/pace/pace.min.js"></script>

</body>

</html>
