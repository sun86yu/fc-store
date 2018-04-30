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
                                data-target="#modal-edit">+ 添加分类
                        </button>
                    </div>
                    <div class="box-header clearfix dataTables_wrapper form-inline dt-bootstrap">
                        <div class="col-sm-12">
                            <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                            <form method="get" action="/admin/cats">
                                <div class="dataTables_length">
                                    <label>
                                        父分类:&nbsp;
                                        <select name="search_top_cat" id="search_top_cat" class="form-control input-sm">
                                            <option value="-1">全部</option>
                                            @foreach( $topCat as $loopCat)
                                                <option value="{{ $loopCat->id  }}" @if ( Request::has('search_top_cat') && Request::get('search_top_cat') == $loopCat->id ) selected @endif>{{ $loopCat->cat_name  }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label>
                                        分类层级:&nbsp;
                                        <select name="search_cat_level" id="search_cat_level" class="form-control input-sm">
                                            <option value="-1">全部</option>
                                            <option value="1" @if ( Request::has('search_cat_level') && Request::get('search_cat_level') == 1 ) selected @endif>一级分类</option>
                                            <option value="2" @if ( Request::has('search_cat_level') && Request::get('search_cat_level') == 2 ) selected @endif>二级分类</option>
                                        </select>
                                    </label>
                                    &nbsp;&nbsp;
                                    <button type="submit" class="btn btn-default input-sm">查询</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>分类</th>
                                <th>层级</th>
                                <th>父分类</th>
                                <th>状态</th>
                                <th style="width: 120px">操作</th>
                            </tr>
                            @foreach ($lists as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->cat_name }}</td>
                                    <td>{{ $item->cat_level }}</td>
                                    <td>
                                        @if($item->cat_parent > 0)
                                            {{ $item->parent->cat_name }}
                                        @else
                                            --根目录--
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->is_active == 1)
                                            <span class="label label-primary">正常</span>
                                        @else
                                            <span class="label label-danger">删除</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="javascript:editItem({{$item->id}})">
                                            <i class="fa fa-edit"></i> 编辑
                                        </a>
                                        @if($item->is_active == 1)
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
                    <input type="hidden" name="del_cat_id" id="del_cat_id" value="0"/>
                </div>
            </div>
        </div>

        @component('Admin.layout.alert')
            @slot('title')
                删除分类
            @endslot

            确定要删除该记录?
        @endcomponent

        <div class="modal fade" id="modal-edit">
            <div class="modal-dialog">
                <form id="editForm" role="form" method="post" action="/admin/catedit/0">
                    <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">编辑分类</h4>
                        </div>
                        <div class="modal-body">
                            <div id="errorBox" class="alert alert-warning alert-dismissible">
                                <h4><i class="icon fa fa-warning"></i> 操作失败!</h4>
                                <p id="errorInfo"></p>
                            </div>

                            <!-- text input -->
                            <div class="form-group">
                                <label>分类名称</label>
                                <input type="text" id="cat_name" name="cat_name" class="form-control"
                                       placeholder="请输入分类名称">
                            </div>

                            <div class="form-group">
                                <label>层级</label>
                                <select name="cat_level" id="cat_level" onchange="selectParent()" class="form-control">
                                    <option value="1">一级</option>
                                    <option value="2">二级</option>
                                </select>
                            </div>

                            <div class="form-group" id="parent_box">
                                <label>父分类</label>
                                <select name="cat_parent" id="cat_parent" class="form-control">
                                    @foreach( $topCat as $loopTop)
                                        <option value="{{ $loopTop->id  }}">{{ $loopTop->cat_name  }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>状态</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1">启用</option>
                                    <option value="0">删除</option>
                                </select>
                            </div>

                            <input type="hidden" name="cat_id" id="cat_id" value="0"/>
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
        function selectParent(){
            var nowLevel = $("#cat_level").val();

            if(nowLevel == 2){
                $("#parent_box").show();
            }else{
                $("#parent_box").hide();
            }
        }
        $(document).ready(function () {
            $("#editForm").validate({
                rules: {
                    cat_name: {
                        required: true,
                        maxlength: 45
                    },
                },
                messages: {
                    cat_name: {
                        required: "分类名称必须填写!",
                        maxlength: "分类名称不能超过 45 个字符"
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
            $("#del_cat_id").val(_id);
            $("#modal-alert").modal();
        }

        function submitForm() {
            if($("#editForm").valid()) {
                var _id = $("#cat_id").val();
                $.ajax({
                    type: "POST",
                    url: "/admin/catedit/" + _id,
                    data: $("#editForm").serialize(),
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 100) {
                            window.location.href = "/admin/cats";
                        } else {
                            $("#errorBox").show();
                            $("#errorInfo").html(data.data.error);
                        }
                    }
                });
            }
        }

        function confirmAlert() {
            var _id = $("#del_cat_id").val();
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

            $.ajax({
                type: "DELETE",
                url: "/admin/catdel/" + _id,
                dataType: 'json',
                success: function (data) {
                    if (data.code == 100) {
                        window.location.href = "/admin/cats";
                    } else {
                        alert('操作失败!');
                    }
                }
            });
        }

        function clearFrom() {
            $("#errorBox").hide();
            $("#errorInfo").html('');
            $("#cat_id").val(0);
            $("#cat_name").val('');
            $("#parent_box").hide();
            $("#editForm").attr("action", "/admin/catedit/" + 0);
        }

        function editItem(_id) {
            if (_id > 0) {
                $.ajax({
                    type: "GET",
                    url: "/admin/catinfo/" + _id,
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 100) {
                            clearFrom();

                            var name = data.data.cat_name;

                            $("#cat_name").val(name);
                            $("#cat_id").val(data.data.id);
                            $("#cat_level").val(data.data.cat_level);
                            $("#cat_parent").val(data.data.cat_parent);

                            if(data.data.cat_level == 2){
                                $("#parent_box").show();
                            }

                            $("#is_active").val(data.data.is_active);
                            $("#editForm").attr("action", "/admin/catedit/" + _id);

                            $("#modal-edit").modal();
                        }
                    }
                });
            }
        }
    </script>
@endsection

