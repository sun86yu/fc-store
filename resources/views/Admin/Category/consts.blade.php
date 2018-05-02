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
                                data-target="#modal-edit">+ 添加常量
                        </button>
                    </div>
                    <div class="box-header clearfix dataTables_wrapper form-inline dt-bootstrap">
                        <div class="col-sm-12">
                            <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                            <form method="get" action="/admin/catconst">
                                <div class="dataTables_length">
                                    <label>
                                        一级分类:&nbsp;
                                        <select name="search_top_cat" id="search_top_cat" class="form-control input-sm"
                                                onchange="setSearchCatSon()">
                                            <option value="-1">全部</option>
                                            @foreach( $topCat as $loopCat)
                                                <option value="{{ $loopCat->id  }}"
                                                        @if ( Request::has('search_top_cat') && Request::get('search_top_cat') == $loopCat->id ) selected @endif>{{ $loopCat->cat_name  }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label>
                                        二级分类:&nbsp;
                                        <select name="search_sec_cat" id="search_sec_cat" class="form-control input-sm"
                                                onchange="setSearchCatModule()">
                                            <option value="-1">全部</option>
                                            @foreach($firstSecondCat as $loopSec)
                                                <option value="{{ $loopSec->id  }}"
                                                        @if ( Request::has('search_sec_cat') && Request::get('search_sec_cat') == $loopSec->id ) selected @endif>{{ $loopSec->cat_name }}</option>
                                            @endforeach
                                        </select>
                                    </label>

                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label>
                                        模块:&nbsp;
                                        <select class="form-control input-sm" name="search_cat_module" id="search_cat_module">
                                            <option value="-1">全部</option>
                                            @foreach($defaultCatModule as $loopModel)
                                                <option value="{{ $loopModel->id  }}"
                                                        @if ( Request::has('search_cat_module') && Request::get('search_cat_module') == $loopModel->id ) selected @endif>{{ $loopModel->mod_name }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
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
                                <th>名称</th>
                                <th>值</th>
                                <th>模块</th>
                                <th>权重</th>
                                <th style="width: 120px">操作</th>
                            </tr>
                            @foreach ($lists as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->const_text }}</td>
                                    <td>{{ $item->const_val }}</td>
                                    <td>{{$item->module->category->parent->cat_name}} - {{ $item->module->category->cat_name }} - {{ $item->module->mod_name }}</td>
                                    <td>{{ $item->show_order }}</td>
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
                    <input type="hidden" name="del_const_id" id="del_const_id" value="0"/>
                </div>
            </div>
        </div>

        @component('Admin.layout.alert')
            @slot('title')
                删除常量
            @endslot

            确定要删除该记录?
        @endcomponent

        <div class="modal fade" id="modal-edit">
            <div class="modal-dialog">
                <form id="editForm" role="form" method="post" action="/admin/constedit/0">
                    <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">编辑常量</h4>
                        </div>
                        <div class="modal-body">
                            <div id="errorBox" class="alert alert-warning alert-dismissible">
                                <h4><i class="icon fa fa-warning"></i> 操作失败!</h4>
                                <p id="errorInfo"></p>
                            </div>

                            <!-- text input -->
                            <div class="form-group col-xs-12">
                                <label>常量文字</label>
                                <input type="text" id="const_text" name="const_text" class="form-control"
                                       placeholder="请输入常量文字">
                            </div>

                            <div class="form-group col-xs-12">
                                <label>常量值</label>
                                <input type="text" id="const_val" name="const_val" class="form-control"
                                       placeholder="请输入常量值">
                            </div>

                            <div class="form-group col-xs-12">
                                <label>显示顺序</label>
                                <input type="text" id="show_order" name="show_order" class="form-control"
                                       placeholder="该值越大显示越靠前">
                            </div>

                            <div class="form-group col-xs-6">
                                <label>一级分类</label>
                                <select class="form-control" name="top_cat" id="top_cat" onchange="setCatSon()">
                                    @foreach($topCat as $loopTop)
                                        <option value="{{ $loopTop->id  }}">{{ $loopTop->cat_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xs-6">
                                <label>二级分类</label>
                                <select class="form-control" name="sec_cat" id="sec_cat" onchange="setModuleList()">
                                    @foreach($firstSecondCat as $loopSec)
                                        <option value="{{ $loopSec->id  }}">{{ $loopSec->cat_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-xs-12">
                                <label>模块</label>
                                <select class="form-control" name="cat_module" id="cat_module">
                                    @foreach($defaultCatModule as $loopModel)
                                        <option value="{{ $loopModel->id  }}">{{ $loopModel->mod_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="hidden" name="const_id" id="const_id" value="0"/>
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
        function setSearchCatModule() {
            var selectCat = $("#search_sec_cat").val();

            $.ajax({
                type: "GET",
                url: "/admin/catmoduleinfo/" + selectCat,
                dataType: 'json',
                success: function (data) {
                    if (data.code == 100) {
                        var lists = data.data;

                        $("#search_cat_module").empty();
                        $("#search_cat_module").append("<option value=\"-1\">全部</option>");
                        for (var i = 0; i < lists.length; i++) {
                            $("#search_cat_module").append("<option value=\"" + lists[i].id + "\">" + lists[i].mod_name + "</option>");
                        }
                    }
                }
            });
        }

        function setSearchCatSon() {
            var selectTopCat = $("#search_top_cat").val();
            if (selectTopCat == -1) {
                $('#search_sec_cat').empty();
                $('#search_cat_module').empty();

                $("#search_sec_cat").append("<option value=\"-1\">全部</option>");
                $("#search_cat_module").append("<option value=\"-1\">全部</option>");
            } else {
                $.ajax({
                    type: "GET",
                    url: "/admin/catsoninfo/" + selectTopCat,
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 100) {
                            var lists = data.data;

                            $("#search_sec_cat").empty();
                            $("#search_sec_cat").append("<option value=\"-1\">全部</option>");
                            for (var i = 0; i < lists.length; i++) {
                                $("#search_sec_cat").append("<option value=\"" + lists[i].id + "\">" + lists[i].cat_name + "</option>");
                            }
                        }
                    }
                });
            }

        }

        function setCatSon() {
            var selectTopCat = $("#top_cat").val();

            $.ajax({
                type: "GET",
                url: "/admin/catsoninfo/" + selectTopCat,
                dataType: 'json',
                success: function (data) {
                    if (data.code == 100) {
                        var lists = data.data;

                        $("#sec_cat").empty();
                        for (var i = 0; i < lists.length; i++) {
                            $("#sec_cat").append("<option value=\"" + lists[i].id + "\">" + lists[i].cat_name + "</option>");
                        }
                    }
                }
            });
        }

        function setModuleList() {
            var selectCat = $("#sec_cat").val();

            $.ajax({
                type: "GET",
                url: "/admin/catmoduleinfo/" + selectCat,
                dataType: 'json',
                success: function (data) {
                    if (data.code == 100) {
                        var lists = data.data;

                        $("#cat_module").empty();
                        for (var i = 0; i < lists.length; i++) {
                            $("#cat_module").append("<option value=\"" + lists[i].id + "\">" + lists[i].mod_name + "</option>");
                        }
                    }
                }
            });
        }

        $(document).ready(function () {
            $("#editForm").validate({
                rules: {
                    const_text: {
                        required: true,
                        maxlength: 45
                    },
                    const_val: {
                        required: true,
                        digits: true,
                        maxlength: 5
                    },
                },
                messages: {
                    const_text: {
                        required: "常量文字必须填写!",
                        maxlength: "常量文字不能超过 45 个字符"
                    },
                    const_val: {
                        required: "常量值必须填写!",
                        digits: "常量值必须是数字!",
                        maxlength: "常量值不能超过 5 个字符"
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
            $("#del_const_id").val(_id);
            $("#modal-alert").modal();
        }

        function submitForm() {
            if ($("#editForm").valid()) {
                var _id = $("#const_id").val();
                $.ajax({
                    type: "POST",
                    url: "/admin/constedit/" + _id,
                    data: $("#editForm").serialize(),
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 100) {
                            window.location.href = "/admin/catconst";
                        } else {
                            $("#errorBox").show();
                            $("#errorInfo").html(data.data.error);
                        }
                    }
                });
            }
        }

        function confirmAlert() {
            var _id = $("#del_const_id").val();
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

            $.ajax({
                type: "DELETE",
                url: "/admin/constdel/" + _id,
                dataType: 'json',
                success: function (data) {
                    if (data.code == 100) {
                        window.location.href = "/admin/catconst";
                    } else {
                        alert('操作失败!');
                    }
                }
            });
        }

        function clearFrom() {
            $("#errorBox").hide();
            $("#errorInfo").html('');

            $("#const_id").val(0);
            $("#const_text").val('');
            $("#const_val").val('');
            $("#show_order").val('');
            $("#editForm").attr("action", "/admin/constedit/" + 0);
        }

        function editItem(_id) {
            if (_id > 0) {
                $.ajax({
                    type: "GET",
                    url: "/admin/constinfo/" + _id,
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 100) {
                            clearFrom();

                            $("#const_id").val(data.data.id);
                            $("#const_text").val(data.data.const_text);
                            $("#const_val").val(data.data.const_val);
                            $("#show_order").val(data.data.show_order);

                            $("#top_cat").val(data.first_cat);

                            $("#sec_cat").empty();
                            var lists = data.sec_cats;
                            for (var i = 0; i < lists.length; i++) {
                                $("#sec_cat").append("<option value=\"" + lists[i].id + "\">" + lists[i].cat_name + "</option>");
                            }
                            $("#sec_cat").val(data.data.module.cat_id);

                            $("#cat_module").empty();
                            var modules = data.data.module.category.module_list;
                            for (var i = 0; i < modules.length; i++) {
                                $("#cat_module").append("<option value=\"" + modules[i].id + "\">" + modules[i].mod_name + "</option>");
                            }
                            $("#cat_module").val(data.data.mod_id);

                            $("#editForm").attr("action", "/admin/constedit/" + _id);

                            $("#modal-edit").modal();
                        }
                    }
                });
            }
        }
    </script>
@endsection

