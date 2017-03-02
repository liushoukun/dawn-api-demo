<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>{$classDoc.name}-{$titleDoc}</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico">
    <link href="__STATIC__/hadmin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="__STATIC__/hadmin/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="__STATIC__/hadmin/css/animate.css" rel="stylesheet">
    <!--markdown-->
    <link rel="stylesheet" type="text/css" href="__STATIC__/hadmin/css/plugins/markdown/bootstrap-markdown.min.css"/>
    <!--markdown-->
    <style>
        .markdown p img {
            max-width: 100%;
        }
    </style>

    <link href="__STATIC__/hadmin/css/style.css?v=4.1.0" rel="stylesheet">


</head>

<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">

    <div class="row">

        <div class="ibox-content  ">
            <div class="text-center ">
                <h2 class="">{$classDoc.name}</h2>
            </div>
        </div>
        <div class="hr-line-dashed"></div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title" data-toggle="collapse" data-target="#markdown-class">
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content markdown " id="markdown-class">

                        </div>
                    </div>
                </div>
            </div>

    </div>

</div>


<!-- 全局js -->
<script src="__STATIC__/hadmin/js/jquery.min.js?v=2.1.4"></script>
<script src="__STATIC__/hadmin/js/bootstrap.min.js?v=3.3.6"></script>

<!-- simditor -->
<script type="text/javascript" src="__STATIC__/hadmin/js/plugins/markdown/markdown.js"></script>
<script type="text/javascript" src="__STATIC__/hadmin/js/plugins/markdown/to-markdown.js"></script>
<script type="text/javascript" src="__STATIC__/hadmin/js/plugins/markdown/bootstrap-markdown.js"></script>
<script type="text/javascript" src="__STATIC__/hadmin/js/plugins/markdown/bootstrap-markdown.zh.js"></script>

<script>
    //获取class md

    $.get("__ROOT__{$classDoc.readme}", function (data) {
        $('#markdown-class').html(markdown.toHTML(data))
    });

</script>

</body>

</html>
