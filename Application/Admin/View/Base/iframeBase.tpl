<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="__CSS__/bootstrap.min.css" rel="stylesheet">
        <link href="__CSS__/iframebase.css" rel="stylesheet">
<?php if(file_exists(C('BASE_CSS') . CONTROLLER_NAME . '.css')) :?>
        <link href="__CSS__/{$Think.CONTROLLER_NAME}.css" rel="stylesheet">
<?php endif ?>
        <script src="__JS__/jquery.js"></script>
        <script type="text/javascript">
            var Think   = {
                'URL'       : '__URL__',
                'MODULE'    : '__MODULE__',
                'IMG'       : '__IMAGE__',
            };
        </script>
        <block name="need_three"></block>
        <!--[if lt IE 9]>
        <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="list-group">
                        <p class="list-group-item">
                            <span class="glyphicon glyphicon-refresh btn btn-xs btn-danger pull-right reload tooltip_class" data-toggle="tooltip" data-placement="bottom" title="刷新"></span>
                            <block name="title"></block>
                        </p>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="iframe_main">
                        <block name="info"></block>
                    </div>
                </div>
            </div>
        </div>

        <div class="screen_shade" style="display: none;"></div>
        <div class="dialog" style="display: none;">处理中...请稍后...</div>
    </body>
<script src="__JS__/bootstrap.min.js"></script>
<script src="__JS__/iframebase.js"></script>
<?php if(file_exists(C('BASE_JS') . CONTROLLER_NAME . '.js')) :?>
    <script src="__JS__/{$Think.CONTROLLER_NAME}.js"></script>
<?php endif ?>
</html>