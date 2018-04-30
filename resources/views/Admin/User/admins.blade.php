@extends('Admin.layout.admin')

@section('content')
    @component('Admin.layout.navigator')
        @slot('title')
            {{ $pageTitle  }}
        @endslot

        @slot('subTitle')
            {{ $subTitle  }}
        @endslot

        @slot('moduleName')
            {{ $moduleName  }}
        @endslot

        @slot('moduleUrl')
            {{ $moduleUrl  }}
        @endslot

        @slot('funcName')
            {{ $funcName  }}
        @endslot

    @endcomponent

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <button type="button" class="btn btn-success" data-toggle="modal" onclick="clearFrom()"
                                data-target="#modal-edit">+ 添加管理员
                        </button>
                    </div>
                    <div class="box-header clearfix dataTables_wrapper form-inline dt-bootstrap">
                        <div class="col-sm-12">
                            <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                            <form method="get" action="/admin/managers">
                            <div class="dataTables_length">
                                <label>
                                    状态:&nbsp;
                                    <select name="search_status" id="search_status" class="form-control input-sm">
                                        <option value="-1">全部</option>
                                        <option value="1" @if ( Request::has('search_status') && Request::get('search_status') == 1 ) selected @endif>启用</option>
                                        <option value="0" @if ( Request::has('search_status') && Request::get('search_status') == 0 ) selected @endif>禁用</option>
                                    </select>
                                </label>
                                &nbsp;&nbsp;
                                <label>
                                    角色:&nbsp;
                                    <select name="search_role_id" id="search_role_id" class="form-control input-sm">
                                        <option value="-1">全部</option>
                                        @foreach ($roleList as $role)
                                            <option value="{{ $role->id  }}" @if ( Request::has('search_role_id') && (Request::get('search_role_id') == $role->id) ) selected @endif>{{ $role->role_name }}</option>
                                        @endforeach
                                    </select>
                                </label>
                                &nbsp;&nbsp;
                                <label>昵称: <input type="search" name="search_name" class="form-control input-sm" value="{{ Request::get('search_name')  }}" ></label>
                                <button type="submit" class="btn btn-default input-sm">查询</button>
                            </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-hover table-bordered">
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>昵称</th>
                                <th>用户名</th>
                                <th>角色</th>
                                <th>邮箱</th>
                                <th style="width: 60px">状态</th>
                                <th>创建时间</th>
                                <th style="width: 120px">操作</th>
                            </tr>
                            @foreach ($lists as $item)
                                <tr>
                                    <td>{{ $item->id  }}</td>
                                    <td>{{ $item->nick_name  }}</td>
                                    <td>
                                        {{ $item->admin_name  }}
                                    </td>
                                    <td>{{ $item->role->role_name  }}</td>
                                    <td>{{ $item->admin_email  }}</td>
                                    <td>
                                        @switch($item->status)
                                            @case(1)
                                            <span class="label label-success">启用</span>
                                            @break

                                            @case(0)
                                            <span class="label label-danger">禁用</span>
                                            @break
                                        @endswitch
                                    </td>
                                    <td>{{ $item->create_time  }}</td>
                                    <td>
                                        <a href="javascript:showItem({{$item->id}})">
                                            <i class="fa fa-edit"></i> 编辑
                                        </a>
                                        @if ( $item->status == 1 )
                                        &nbsp;|&nbsp;
                                        <a href="javascript:delItem({{$item->id}})">
                                            <i class="fa fa-close"></i> 删除
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix dataTables_wrapper form-inline dt-bootstrap">
                        <div class="col-sm-5">
                            <div class="dataTables_info">总计: {{ $lists->total()  }} 条</div>
                        </div>
                        <div class="col-sm-7">
                            <div class="dataTables_paginate paging_simple_numbers pull-right">
                                {{ $lists->links() }}
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="del_admin_id" id="del_admin_id" value="0"/>
                </div>
            </div>
        </div>

        @component('Admin.layout.alert')
            @slot('title')
                删除管理员
            @endslot

            确定要删除该记录?
        @endcomponent

    </section>

    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog">
            <form id="editForm" role="form" method="post" action="/admin/admin/0">
                <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">添加管理员</h4>
                    </div>
                    <div class="modal-body">
                        <div id="errorBox" class="alert alert-warning alert-dismissible">
                            <h4><i class="icon fa fa-warning"></i> 操作失败!</h4>
                            <p id="errorInfo"></p>
                        </div>

                        <!-- text input -->
                        <div class="form-group">
                            <label>管理员名称</label>
                            <input type="text" id="admin_name" name="admin_name" class="form-control"
                                   placeholder="请输入新建帐号">
                        </div>
                        <div class="form-group">
                            <label>管理员密码</label>
                            <input type="text" id="admin_pwd" name="admin_pwd" class="form-control"
                                   placeholder="默认密码是{{ Config::get('constants.DEFAULT_PWD')  }}">
                        </div>
                        <div class="form-group">
                            <label>重复密码</label>
                            <input type="text" id="admin_pwd2" name="admin_pwd2" class="form-control"
                                   placeholder="默认密码是{{ Config::get('constants.DEFAULT_PWD')  }}">
                        </div>
                        <div class="form-group">
                            <label>管理员邮箱</label>
                            <input type="text" id="admin_email" name="admin_email" class="form-control"
                                   placeholder="请输入新建帐号的邮箱">
                        </div>
                        <div class="form-group">
                            <label>管理员昵称</label>
                            <input type="text" id="nick_name" name="nick_name" class="form-control"
                                   placeholder="请输入新建帐号的昵称">
                        </div>

                        <div class="form-group">
                            <label>启用帐号</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1">启用</option>
                                <option value="0">禁用</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>权限</label>
                            <select name="role_id" id="role_id" class="form-control">
                            @foreach ($roleList as $role)
                                <option value="{{ $role->id  }}">{{ $role->role_name }}</option>
                            @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="admin_id" id="admin_id" value="0"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">关闭</button>
                        <button type="button" onclick="submitForm()" class="btn btn-primary">确定</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript"
            src="{{ URL::asset('/components/jquery-validation/jquery.validate.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $("#editForm").validate({
                rules: {
                    admin_name: {
                        required: true,
                        maxlength: 45
                    },
                    admin_email: {
                        required: true,
                        email: true,
                        maxlength: 200
                    },
                    nick_name: {
                        maxlength: 45
                    },
                },
                messages: {
                    admin_name: {
                        required: "管理员帐号必须填写!",
                        maxlength: "角色名称不能超过 45 个字符"
                    },
                    admin_email: {
                        required: "管理员邮箱必须填写!",
                        email: "邮箱格式不正确!",
                        maxlength: "重复密码不能超过 200 个字符"
                    },
                    nick_name: {
                        maxlength: "昵称不能超过 45 个字符"
                    },
                },
                errorElement: "em",
                errorPlacement: function (error, element) {
                    error.addClass("bg-red-active");
                    error.insertAfter(element);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).parents(".form-group").addClass("has-error").removeClass("has-success");
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).parents(".form-group").addClass("has-success").removeClass("has-error");
                }
            });
        });

        function delItem(_id) {
            $("#del_admin_id").val(_id);
            $("#modal-alert").modal();
        }

        function confirmAlert() {
            var _id = $("#del_admin_id").val();
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

            $.ajax({
                type: "DELETE",
                url: "/admin/admin/" + _id,
                dataType: 'json',
                success: function (data) {
                    if (data.code == 100) {
                        window.location.href = "/admin/managers";
                    }else{
                        alert("操作失败");
                    }
                }
            });
        }

        function clearFrom() {
            $("#errorBox").hide();
            $("#errorInfo").html('');
            $("#admin_id").val(0);
            $("#editForm").attr("action", "/admin/admin/" + 0);
        }

        function showItem(_id) {
            if (_id > 0) {
                $.ajax({
                    type: "GET",
                    url: "/admin/admin/" + _id,
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 100) {
                            clearFrom();

                            $("#admin_id").val(data.data.id);
                            $("#admin_name").val(data.data.admin_name);
                            $("#admin_email").val(data.data.admin_email);
                            $("#nick_name").val(data.data.nick_name);
                            $("#status").val(data.data.status);
                            $("#role_id").val(data.data.role_id);

                            $("#modal-edit").modal();
                        }
                    }
                });
            }
        }

        function submitForm() {
            if($("#editForm").valid()) {
                var _id = $("#admin_id").val();
                $.ajax({
                    type: "POST",
                    url: "/admin/admin/" + _id,
                    data: $("#editForm").serialize(),
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 100) {
                            window.location.href = "/admin/managers";
                        } else {
                            $("#errorBox").show();
                            $("#errorInfo").html(data.data.error);
                        }
                    }
                });
            }
        }
    </script>
@endsection
