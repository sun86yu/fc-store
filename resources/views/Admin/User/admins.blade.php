@extends('Admin.layout.admin')

@section('content')
    <section class="content-header">
        <h1>
            管理员列表
            <small>管理后台所有管理员</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 控制台</a></li>
            <li><a href="#">管理员</a></li>
            <li class="active">管理员列表</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <button type="button" class="btn btn-success" data-toggle="modal" onclick="clearFrom()" data-target="#modal-edit">+ 添加管理员</button>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>昵称</th>
                                <th>用户名</th>
                                <th>角色</th>
                                <th>邮箱</th>
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
                                <td>{{ $item->role_name  }}</td>
                                <td>{{ $item->admin_email  }}</td>
                                <td>{{ $item->create_time  }}</td>
                                <td>
                                    <a href="javascript:editItem({{$item->id}})">
                                        <i class="fa fa-edit"></i> 编辑
                                    </a>
                                    &nbsp;|&nbsp;
                                    <a href="#" data-toggle="modal" data-target="#modal-alert">
                                        <i class="fa fa-close"></i> 删除
                                    </a>
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
                </div>
            </div>
        </div>

        @component('Admin.layout.alert')
            @slot('title')
                测试
            @endslot

            确定要删除该记录?
        @endcomponent

    </section>

    <script>
        function delItem(_id){
            $("#del_role_id").val(_id);
            $("#modal-alert").modal();
        }
        function confirmAlert(){
            var _id = $("#del_role_id").val();
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

            $.ajax({
                type: "DELETE",
                url: "/admin/role/" + _id,
                dataType: 'json',
                success: function(data){
                    if(data.code == 100) {
                        window.location.href = "/admin/roles";
                    }
                }
            });
        }
        function clearFrom(){
            $("#role_id").val(0);
            $("#role_name").val('');
            $("#editForm").attr("action", "/admin/role/" + 0);
            $("#editForm input[type='checkbox']").each(
                function(){
                    this.checked = false;
                })
        }
        function editItem(_id){
            if(_id > 0){
                $.ajax({
                    type: "GET",
                    url: "/admin/role/" + _id,
                    dataType: 'json',
                    success: function(data){
                        if(data.code == 100) {
                            clearFrom();

                            var modList = new Array();

                            @foreach ($moduleLists as $idx => $mod)
                            modList.push('{{ $idx  }}');
                                    @endforeach

                            var rights = data.data.right;
                            var name = data.data.name;

                            $("#role_name").val(name);
                            $("#role_id").val(_id);
                            $("#editForm").attr("action", "/admin/role/" + _id);

                            for(var i = 0;i<modList.length;i++){
                                if(rights[modList[i]] == 1){
                                    $("#role_right_"+modList[i]).get(0).checked = true;
                                }
                            }

                            $("#modal-edit").modal();
                        }
                    }
                });
            }
        }
    </script>
@endsection
