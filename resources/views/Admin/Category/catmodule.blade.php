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
                                data-target="#modal-edit">+ 添加模块
                        </button>
                    </div>
                    <div class="box-header clearfix dataTables_wrapper form-inline dt-bootstrap">
                        <div class="col-sm-12">
                            <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                            <form method="get" action="/admin/catmodule">
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
                                        <select name="search_sec_cat" id="search_sec_cat" class="form-control input-sm">
                                            <option value="-1">全部</option>
                                            @foreach($firstSecondCat as $loopSec)
                                                <option value="{{ $loopSec->id  }}"
                                                        @if ( Request::has('search_sec_cat') && Request::get('search_sec_cat') == $loopSec->id ) selected @endif>{{ $loopSec->cat_name }}</option>
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
                                <th>模块</th>
                                <th>表单名</th>
                                <th>类型</th>
                                <th>单位</th>
                                <th>默认值</th>
                                <th>顺序</th>
                                <th>数字</th>
                                <th>电话</th>
                                <th>邮箱</th>
                                <th>日期</th>
                                <th>分类</th>
                                <th style="width: 120px">操作</th>
                            </tr>
                            @foreach ($lists as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->mod_name }}</td>
                                    <td>{{ $item->mod_en_name }}</td>
                                    <td>
                                        @foreach($moduleTypeList as $typeId => $typeName)

                                            @if($item->mod_type == $typeId)
                                                {{$typeName}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{ $item->mod_dw }}</td>
                                    <td>{{ $item->default_value }}</td>
                                    <td>{{ $item->show_order }}</td>
                                    <td>
                                        @if($item->is_number == 1)
                                            <span class="label label-primary">是</span>
                                        @else
                                            <span class="label label-danger">否</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->is_phone == 1)
                                            <span class="label label-primary">是</span>
                                        @else
                                            <span class="label label-danger">否</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->is_email == 1)
                                            <span class="label label-primary">是</span>
                                        @else
                                            <span class="label label-danger">否</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->is_date == 1)
                                            <span class="label label-primary">是</span>
                                        @else
                                            <span class="label label-danger">否</span>
                                        @endif
                                    </td>
                                    <td>{{\App\Models\Admin\CategoryModel::getParent($item->category->id)->cat_name}} - {{ $item->category->cat_name }}</td>
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
                    <input type="hidden" name="del_module_id" id="del_module_id" value="0"/>
                </div>
            </div>
        </div>

        @component('Admin.layout.alert')
            @slot('title')
                删除模块
            @endslot

            确定要删除该记录?
        @endcomponent

        <div class="modal fade" id="modal-edit">
            <div class="modal-dialog">
                <form id="editForm" role="form" method="post" action="/admin/moduleedit/0">
                    <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">编辑分类模块</h4>
                        </div>
                        <div class="modal-body">
                            <div id="errorBox" class="alert alert-warning alert-dismissible">
                                <h4><i class="icon fa fa-warning"></i> 操作失败!</h4>
                                <p id="errorInfo"></p>
                            </div>

                            <!-- text input -->
                            <div class="form-group col-xs-6">
                                <label>模块名称</label>
                                <input type="text" id="mod_name" name="mod_name" class="form-control"
                                       placeholder="请输入模块名称">
                            </div>
                            <div class="form-group col-xs-6">
                                <label>表单名称</label>
                                <input type="text" id="mod_en_name" name="mod_en_name" class="form-control"
                                       placeholder="请输入表单名称">
                            </div>

                            <div class="form-group col-xs-4">
                                <label>类型</label>
                                <select class="form-control" name="mod_type" id="mod_type">
                                    @foreach($moduleTypeList as $typeId => $loopType)
                                        <option value="{{ $typeId  }}">{{ $loopType  }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-xs-4">
                                <label>单位</label>
                                <input type="text" id="mod_dw" name="mod_dw" class="form-control"
                                       placeholder="请输入单位,如:元">

                            </div>

                            <div class="form-group col-xs-4">
                                <label>默认值</label>
                                <input type="text" id="default_value" name="default_value" class="form-control"
                                       placeholder="请输入默认值!">

                            </div>

                            <div class="form-group col-xs-4">
                                <label>最小长度</label>
                                <input type="text" id="min_length" name="min_length" class="form-control"
                                       placeholder="模块内容的最小长度">
                            </div>
                            <div class="form-group col-xs-4">
                                <label>最大长度</label>
                                <input type="text" id="max_length" name="max_length" class="form-control"
                                       placeholder="模块内容的最大长度">
                            </div>
                            <div class="form-group col-xs-4">
                                <label>显示顺序</label>
                                <input type="text" id="show_order" name="show_order" class="form-control"
                                       placeholder="该值越大显示越靠前">
                            </div>
                            <div class="form-group col-xs-12">
                                <label>
                                    <input type="checkbox" id="is_number" name="is_number"/>
                                    是否是数字
                                </label>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <label>
                                    <input type="checkbox" id="is_phone" name="is_phone"/>
                                    是否是电话
                                </label>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <label>
                                    <input type="checkbox" id="is_email" name="is_email"/>
                                    是否是邮箱
                                </label>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <label>
                                    <input type="checkbox" id="is_date" name="is_date"/>
                                    是否是日期
                                </label>
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
                                <select class="form-control" name="sec_cat" id="sec_cat">
                                    @foreach($firstSecondCat as $loopSec)
                                        <option value="{{ $loopSec->id  }}">{{ $loopSec->cat_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="module_id" id="module_id" value="0"/>
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
        function setSearchCatSon() {
            var selectTopCat = $("#search_top_cat").val();

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

        $(document).ready(function () {
            $("#editForm").validate({
                rules: {
                    mod_name: {
                        required: true,
                        maxlength: 45
                    },
                    mod_en_name: {
                        required: true,
                        maxlength: 45
                    },
                    min_length: {
                        digits: true,
                        maxlength: 8
                    },
                    max_length: {
                        digits: true,
                        maxlength: 8
                    },
                    show_order: {
                        digits: true,
                        maxlength: 8
                    },
                },
                messages: {
                    mod_name: {
                        required: "模块名称必须填写!",
                        maxlength: "模块名称不能超过 45 个字符"
                    },
                    mod_en_name: {
                        required: "表单名称必须填写!",
                        maxlength: "表单名称不能超过 45 个字符"
                    },
                    min_length: {
                        digits: "最小长度值必须是数字!",
                        maxlength: "最小长度不能超过 8 个字符"
                    },
                    max_length: {
                        digits: "最大长度值必须是数字!",
                        maxlength: "最大长度不能超过 8 个字符"
                    },
                    show_order: {
                        digits: "最大长度值必须是数字!",
                        maxlength: "最大长度不能超过 8 个字符"
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
            $("#del_module_id").val(_id);
            $("#modal-alert").modal();
        }

        function submitForm() {
            if ($("#editForm").valid()) {
                var _id = $("#module_id").val();
                $.ajax({
                    type: "POST",
                    url: "/admin/moduleedit/" + _id,
                    data: $("#editForm").serialize(),
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 100) {
                            window.location.href = "/admin/catmodule";
                        } else {
                            $("#errorBox").show();
                            $("#errorInfo").html(data.data.error);
                        }
                    }
                });
            }
        }

        function confirmAlert() {
            var _id = $("#del_module_id").val();
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

            $.ajax({
                type: "DELETE",
                url: "/admin/moduledel/" + _id,
                dataType: 'json',
                success: function (data) {
                    if (data.code == 100) {
                        window.location.href = "/admin/catmodule";
                    } else {
                        alert('操作失败!');
                    }
                }
            });
        }

        function clearFrom() {
            $("#errorBox").hide();
            $("#errorInfo").html('');

            $("#module_id").val(0);
            $("#module_name").val('');
            $("#mod_en_name").val('');
            $("#mod_dw").val('');
            $("#default_value").val('');
            $("#min_length").val('');
            $("#max_length").val('');

            $("#editForm input[type='checkbox']").each(
                function () {
                    this.checked = false;
                }
            )

            $("#editForm").attr("action", "/admin/moduleedit/" + 0);
        }

        function editItem(_id) {
            if (_id > 0) {
                $.ajax({
                    type: "GET",
                    url: "/admin/moduleinfo/" + _id,
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 100) {
                            clearFrom();

                            $("#mod_name").val(data.data.mod_name);
                            $("#module_id").val(data.data.id);
                            $("#mod_en_name").val(data.data.mod_en_name);
                            $("#mod_type").val(data.data.mod_type);
                            $("#mod_dw").val(data.data.mod_dw == null ? '' : data.data.mod_dw);
                            $("#default_value").val(data.data.default_value);
                            $("#min_length").val(data.data.min_length);
                            $("#max_length").val(data.data.max_length);
                            $("#show_order").val(data.data.show_order);

                            if(data.data.is_number == 1){
                                $("#is_number").prop("checked","checked");
                            }
                            if(data.data.is_phone == 1){
                                $("#is_phone").prop("checked","checked");
                            }
                            if(data.data.is_email == 1){
                                $("#is_email").prop("checked","checked");
                            }
                            if(data.data.is_date == 1){
                                $("#is_date").prop("checked","checked");
                            }

                            $("#top_cat").val(data.first_cat);

                            $("#sec_cat").empty();
                            var lists = data.sec_cats;
                            for (var i = 0; i < lists.length; i++) {
                                $("#sec_cat").append("<option value=\"" + lists[i].id + "\">" + lists[i].cat_name + "</option>");
                            }
                            $("#sec_cat").val(data.data.cat_id);

                            $("#editForm").attr("action", "/admin/moduleedit/" + _id);

                            $("#modal-edit").modal();
                        }
                    }
                });
            }
        }
    </script>
@endsection

