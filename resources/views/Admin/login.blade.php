<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 2 | Log in</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ URL::asset('/components/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/components/ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/admin/css/AdminLTE.min.css') }}">


</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="../../index2.html"><b>FangCun</b>STORE</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">登录商城管理端</p>

        <form action="../../index2.html" method="post">
            <div class="form-group has-feedback">
                <input type="email" class="form-control" placeholder="用户名/邮箱">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="密码">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <a href="#">忘记密码</a><br>

    </div>
</div>

<script type="text/javascript" src="{{ URL::asset('/components/jquery/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/components/bootstrap/js/bootstrap.min.js') }}"></script>

</body>
</html>
