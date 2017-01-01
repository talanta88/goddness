<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>欢迎登录后台</title>
    <link href="__CSS__/bootstrap.min.css" rel="stylesheet">
    <link href="__CSS__/base.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="__MODULE__/">LOGO</a>
            </div>
            <a href="{:U('Login/logout')}" class="btn btn-navbar btn-sm navbar-btn navbar-right"><span class="glyphicon glyphicon-off"></span> 退出</a>
            <p class="navbar-text navbar-right"><span class="glyphicon glyphicon-user"></span>：<strong><?php echo session(C('ADMIN_NAME'));?></strong>　　<span class="glyphicon glyphicon-time"></span>：<strong><?php echo session('temp_admin_addr');?> <?php echo session('temp_admin_time');?></strong></p>
        </div>
    </nav>

    <div class="con_box">
        <div class="con_box_left">
            <ul class="left_sidebar">
                <?php 
                    foreach($rule_info as $key => $value) :
                    if($value['is_show'] == 0)
                        continue;
                ?>       
                <li>
                    <a href="javascript:void(0)" class="left_sidebar_a">
                        <span class="glyphicon glyphicon-th-list color_share sidebar_span_left"></span>
                        <span class="sidebar_title"><?php echo $value['name'];?></span>
                        <span class="glyphicon glyphicon-chevron-down sidebar_span_right"></span>
                    </a>
                    <ul class="sidebar_list" style="display: none;">
                        <?php 
                            foreach($value['son'] as $k => $v) :
                            if($v['is_show'] == 0)
                                continue;
                        ?>
                        <li><a href="__MODULE__/<?php echo $v['contro_name'];?>/<?php echo $v['action_name'];?>" target="content" class="sidebar_list_a"><?php echo $v['name'];?></a></li>
                    <?php endforeach;?>
                    </ul>
                </li>
            <?php endforeach;?>
            </ul>
        </div>
        <div class="con_box_right">
            <iframe name="content" class="iframe" src="__MODULE__/Index/showIndex">

            </iframe>
        </div>
    </div>


  </body>
<script src="__JS__/jquery.js"></script>
<script src="__JS__/bootstrap.min.js"></script>
<script src="__JS__/base.js"></script>
<script type="text/javascript">
  
</script>
</html>