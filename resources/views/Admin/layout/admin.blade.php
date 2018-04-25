<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FangCunStore | 用户列表</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="{{ URL::asset('/components/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/components/ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/components/datatables.net-bs/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/admin/css/AdminLTE.min.css') }}">

    <link rel="stylesheet" href="{{ URL::asset('/admin/css/skin-purple.min.css') }}">

    <script type="text/javascript" src="{{ URL::asset('/components/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('/components/bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ URL::asset('/components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('/components/fastclick/fastclick.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('/admin/js/adminlte.min.js') }}"></script>
</head>
<body class="hold-transition sidebar-mini skin-purple">
<div class="wrapper">

    <header class="main-header">
        <a href="../../index2.html" class="logo">
            <span class="logo-mini"><b>FC</b>S</span>
            <span class="logo-lg"><b>FangCun</b>STORE</span>
        </a>
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">切换菜单</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-envelope-o"></i>
                            <span class="label label-success">4</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">有 4 个待审核信息</li>
                            <li>
                                <ul class="menu">
                                    <li>
                                        <a href="#">
                                            <div class="pull-left">
                                                <img src="/admin/img/user2-160x160.jpg" class="img-circle"
                                                     alt="User Image">
                                            </div>
                                            <h4>
                                                枫院夜宇
                                                <small><i class="fa fa-clock-o"></i> 5 分钟</small>
                                            </h4>
                                            <p>实名认证待审核</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer"><a href="#">查看全部</a></li>
                        </ul>
                    </li>
                    <!-- Notifications: style can be found in dropdown.less -->
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">10</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">您有 10 个待发货订单</li>
                            <li>
                                <ul class="menu">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-users text-aqua"></i> 5 个新用户注册
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer"><a href="#">查看全部</a></li>
                        </ul>
                    </li>
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="/admin/img/user2-160x160.jpg" class="user-image" alt="User Image">
                            <span class="hidden-xs">孙宇</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="/admin/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                                <p>
                                    孙宇 - 超级管理员
                                    <small>欢迎您登录!</small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">个人资料</a>
                                </div>
                                <div class="pull-right">
                                    <a href="#" class="btn btn-default btn-flat">退出</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="/admin/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>孙宇</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> 已登录</a>
                </div>
            </div>
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">管理菜单</li>
                <li>
                    <a href="/admindash">
                        <i class="fa fa-dashboard"></i> <span>控制台</span>
                        <span class="pull-right-container"></span>
                    </a>
                </li>
                <li class="treeview @if (($adminActive === true) or ($roleActive === true)) active menu-open @endif">
                    <a href="#">
                        <i class="fa fa-laptop"></i>
                        <span>管理员</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="@if ($adminActive === true) active @endif">
                            <a href="/admin/managers"><i class="fa fa-user"></i>管理员列表</a>
                        </li>
                        <li class="@if ($roleActive === true) active @endif">
                            <a href="/admin/roles"><i class="fa fa-group"></i>角色管理</a>
                        </li>
                    </ul>
                </li>
                <li class="treeview @if ($newsActive === true or $newAddActive)  active menu-open @endif">
                    <a href="#">
                        <i class="fa fa-newspaper-o"></i>
                        <span>文章管理</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="@if ($newsActive === true) active @endif">
                            <a href="/admin/articles"><i class="fa fa-file"></i>文章列表</a>
                        </li>
                        <li class="@if ($newAddActive === true) active @endif">
                            <a href="/admin/articleadd"><i class="fa fa-file-text-o"></i>添加文章</a>
                        </li>
                    </ul>
                </li>
                <li class="treeview @if (($userActive === true) or($identyActive === true))  active menu-open @endif">
                    <a href="#">
                        <i class="fa fa-users"></i>
                        <span>用户</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="@if ($userActive === true) active @endif">
                            <a href="/admin/users"><i class="fa fa-user-secret"></i>用户列表</a>
                        </li>
                        <li class="@if ($identyActive === true) active @endif">
                            <a href="/admin/identy"><i class="fa fa-check-square"></i>认证审核</a>
                        </li>
                    </ul>
                </li>
                <li class="treeview @if (($productActive === true) or($orderActive === true))  active menu-open @endif">
                    <a href="#">
                        <i class="fa fa-th"></i>
                        <span>商品</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="@if ($productActive === true) active @endif">
                            <a href="/admin/products"><i class="fa fa-shopping-cart"></i>商品列表</a>
                        </li>
                        <li class="@if ($orderActive === true) active @endif">
                            <a href="/admin/orders"><i class="fa fa-list"></i>订单列表</a>
                        </li>
                    </ul>
                </li>
                <li class="treeview @if ($cateActive === true or $moduleActive === true or $constActive === true)  active menu-open @endif">
                    <a href="#">
                        <i class="fa fa-fax"></i>
                        <span>分类管理</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="@if ($cateActive === true) active @endif">
                            <a href="/admin/cats"><i class="fa fa-bookmark"></i>分类管理</a>
                        </li>
                        <li class="@if ($moduleActive === true) active @endif">
                            <a href="/admin/catmodule"><i class="fa fa-asterisk"></i>模块管理</a>
                        </li>
                        <li class="@if ($constActive === true) active @endif">
                            <a href="/admin/catconst"><i class="fa fa-book"></i>常量管理</a>
                        </li>
                    </ul>
                </li>
                <li class="@if ($geoActive === true)  active menu-open @endif">
                    <a href="/admin/geos">
                        <i class="fa fa-map"></i>
                        <span>地区管理</span>
                    </a>
                </li>
                <li class="@if ($logActive === true)  active menu-open @endif">
                    <a href="/admin/logs">
                        <i class="fa fa-file-o"></i>
                        <span>系统日志</span>
                    </a>
                </li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        @yield('content')
    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 1.0.0
        </div>
        <strong>Copyright &copy; 2014-2019 <a href="https://www.edeng.cn">方寸世界(北京)文化传媒有限公司</a>.</strong> All rights
        reserved.
    </footer>

</div>

<script>
    $(document).ready(function () {
        $('.sidebar-menu').tree();
    })
</script>
</body>
</html>
