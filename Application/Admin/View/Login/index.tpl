<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>欢迎登录后台</title>
    <link href="__CSS__/bootstrap.min.css" rel="stylesheet">
    <link href="__CSS__/login.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body class="main_bg">
    <div class="container-fluid">
        <div class="row">
            <div class="login_title">
                <span class="glyphicon glyphicon-leaf green"></span> Welcome
            </div>
            <div class="col-md-6 col-md-offset-3">
                <div class="login_body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-1">
                                <input type="text" name="username" id="username" class="form-control" placeholder="请输入用户名">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-1">
                                <input type="password" name="password" id="password" class="form-control" placeholder="请输入密码">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-1">
                                <input type="text" name="code" id="code" class="form-control" placeholder="请输入验证码，不区分大小写">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-1">
                                    <img src="/Admin/Login/getCode" alt="验证码" title="验证码"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-1">
                                <button type="submit" class="btn btn-primary" >登录</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="dialog"></div>

  </body>
<script src="__JS__/jquery.js"></script>
<script src="__JS__/bootstrap.min.js"></script>
<script type="text/javascript">
    var Think   = {
        'URL' : '__URL__',
        'MODULE':'__MODULE__',
    };
</script>
<script src="__JS__/login.js"></script>
</html>