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
                                data-target="#modal-edit">+ 添加角色
                        </button>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>角色</th>
                                <th>权限</th>
                                <th>已分配人员</th>
                                <th style="width: 120px">操作</th>
                            </tr>
                            @inject('rightTools', 'App\Services\RightTools')
                            @foreach ($lists as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->role_name }}</td>
                                    <td>
                                        @foreach ($rightTools->getRightList($item->role_right) as $rightKey => $loopRight)
                                            @if ( $loopRight == 1 )
                                                {{ $moduleLists[$rightKey]  }}&nbsp;&nbsp;&nbsp;&nbsp;
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($item->user as $loopUser)
                                            {{ $loopUser->nick_name  }}&nbsp;&nbsp;
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="javascript:editItem({{$item->id}})">
                                            <i class="fa fa-edit"></i> 编辑
                                        </a>
                                        &nbsp;|&nbsp;
                                        <a href="javascript:delItem({{$item->id}})">
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
                    <input type="hidden" name="del_role_id" id="del_role_id" value="0"/>
                </div>
            </div>
        </div>

        @component('Admin.layout.alert')
            @slot('title')
                测试
            @endslot

            确定要删除该记录?
        @endcomponent

        <div class="modal fade" id="modal-edit">
            <div class="modal-dialog">
                <form id="editForm" role="form" method="post" action="/admin/role/0">
                    <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">编辑角色</h4>
                        </div>
                        <div class="modal-body">
                            <div id="errorBox" class="alert alert-warning alert-dismissible">
                                <h4><i class="icon fa fa-warning"></i> 操作失败!</h4>
                                <p id="errorInfo"></p>
                            </div>

                            <!-- text input -->
                            <div class="form-group">
                                <label>角色名称</label>
                                <input type="text" id="role_name" name="role_name" class="form-control"
                                       placeholder="请输入角色名称">
                            </div>

                            <div class="form-group">
                                <label>权限</label>
                                @foreach ($moduleLists as $idx => $mod)
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="role_right_{{ $idx  }}"
                                                   name="role_right_{{ $idx  }}">
                                            {{ $mod }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="role_id" id="role_id" value="0"/>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">关闭</button>
                            <button type="button" onclick="submitForm()" class="btn btn-primary">确定</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </section>

    <script type="text/javascript"
            src="{{ URL::asset('/components/jquery-validation/jquery.validate.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $("#editForm").validate({
                rules: {
                    role_name: {
                        required: true,
                        maxlength: 45
                    },
                },
                messages: {
                    role_name: {
                        required: "角色名称必须填写!",
                        maxlength: "角色名称不能超过 45 个字符"
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
            $("#del_role_id").val(_id);
            $("#modal-alert").modal();
        }

        function submitForm() {
            if($("#editForm").valid()) {
                var _id = $("#role_id").val();
                $.ajax({
                    type: "POST",
                    url: "/admin/role/" + _id,
                    data: $("#editForm").serialize(),
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 100) {
                            window.location.href = "/admin/roles";
                        } else {
                            $("#errorBox").show();
                            $("#errorInfo").html(data.data.error);
                        }
                    }
                });
            }
        }

        function confirmAlert() {
            var _id = $("#del_role_id").val();
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

            $.ajax({
                type: "DELETE",
                url: "/admin/role/" + _id,
                dataType: 'json',
                success: function (data) {
                    if (data.code == 100) {
                        window.location.href = "/admin/roles";
                    } else {
                        alert('操作失败!');
                    }
                }
            });
        }

        function clearFrom() {
            $("#errorBox").hide();
            $("#errorInfo").html('');
            $("#role_id").val(0);
            $("#role_name").val('');
            $("#editForm").attr("action", "/admin/role/" + 0);
            $("#editForm input[type='checkbox']").each(
                function () {
                    this.checked = false;
                })
        }

        function editItem(_id) {
            if (_id > 0) {
                $.ajax({
                    type: "GET",
                    url: "/admin/role/" + _id,
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 100) {
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

                            for (var i = 0; i < modList.length; i++) {
                                if (rights[modList[i]] == 1) {
                                    $("#role_right_" + modList[i]).get(0).checked = true;
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

