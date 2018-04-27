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
                    <div class="box-header clearfix dataTables_wrapper form-inline dt-bootstrap">
                        <div class="col-sm-12">
                            <form method="post" action="/admin/geos">
                                <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                                <div class="dataTables_length">
                                    <label>
                                        根地区:&nbsp;
                                        <select name="top_geo" id="top_geo" class="form-control input-sm">
                                            <option value="-1">全部</option>
                                            @foreach( $topGeo as $loopGeo)
                                                <option value="{{ $loopGeo->id  }}" @if ( Request::has('top_geo') && Request::get('top_geo') == $loopGeo->id ) selected @endif>{{ $loopGeo->geo_name  }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    &nbsp;&nbsp;
                                    <label>
                                        地区层级:&nbsp;
                                        <select name="geo_level" id="geo_level" class="form-control input-sm">
                                            <option value="-1">全部</option>
                                            <option value="1" @if ( Request::has('geo_level') && Request::get('geo_level') == 1 ) selected @endif>一级地区</option>
                                            <option value="2" @if ( Request::has('geo_level') && Request::get('geo_level') == 2 ) selected @endif>二级地区</option>
                                            <option value="3" @if ( Request::has('geo_level') && Request::get('geo_level') == 3 ) selected @endif>三级地区</option>
                                            <option value="4" @if ( Request::has('geo_level') && Request::get('geo_level') == 4 ) selected @endif>四级地区</option>
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
                                <th>地区</th>
                                <th>层级</th>
                                <th>父地区</th>
                                <th>状态</th>
                                <th style="width: 120px">操作</th>
                            </tr>
                            @foreach ($lists as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->geo_name }}</td>
                                    <td>{{ $item->geo_level }}</td>
                                    <td></td>
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
                    <input type="hidden" name="del_geo_id" id="del_geo_id" value="0"/>
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
                <form id="editForm" role="form" method="post" action="/admin/geoedit/0">
                    <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">编辑地区</h4>
                        </div>
                        <div class="modal-body">
                            <div id="errorBox" class="alert alert-warning alert-dismissible">
                                <h4><i class="icon fa fa-warning"></i> 操作失败!</h4>
                                <p id="errorInfo"></p>
                            </div>

                            <!-- text input -->
                            <div class="form-group">
                                <label>地区名称</label>
                                <input type="text" id="geo_name" name="geo_name" class="form-control"
                                       placeholder="请输入地区名称">
                            </div>

                            <div class="form-group">
                                <label>状态</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1">启用</option>
                                    <option value="0">删除</option>
                                </select>
                            </div>

                            <input type="hidden" name="geo_id" id="geo_id" value="0"/>
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
                    geo_name: {
                        required: true,
                        maxlength: 45
                    },
                },
                messages: {
                    geo_name: {
                        required: "地区名称必须填写!",
                        maxlength: "地区名称不能超过 45 个字符"
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
            $("#del_geo_id").val(_id);
            $("#modal-alert").modal();
        }

        function submitForm() {
            if($("#editForm").valid()) {
                var _id = $("#geo_id").val();
                $.ajax({
                    type: "POST",
                    url: "/admin/geoedit/" + _id,
                    data: $("#editForm").serialize(),
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 100) {
                            window.location.href = "/admin/geos";
                        } else {
                            $("#errorBox").show();
                            $("#errorInfo").html(data.data.error);
                        }
                    }
                });
            }
        }

        function confirmAlert() {
            var _id = $("#del_geo_id").val();
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

            $.ajax({
                type: "DELETE",
                url: "/admin/geodel/" + _id,
                dataType: 'json',
                success: function (data) {
                    if (data.code == 100) {
                        window.location.href = "/admin/geos";
                    } else {
                        alert('操作失败!');
                    }
                }
            });
        }

        function clearFrom() {
            $("#errorBox").hide();
            $("#errorInfo").html('');
            $("#geo_id").val(0);
            $("#geo_name").val('');
            $("#editForm").attr("action", "/admin/geoedit/" + 0);
        }

        function editItem(_id) {
            if (_id > 0) {
                $.ajax({
                    type: "GET",
                    url: "/admin/geoinfo/" + _id,
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 100) {
                            clearFrom();

                            var name = data.data.geo_name;

                            $("#geo_name").val(name);
                            $("#geo_id").val(data.data.id);
                            $("#is_active").val(data.data.is_active);
                            $("#editForm").attr("action", "/admin/geoedit/" + _id);

                            $("#modal-edit").modal();
                        }
                    }
                });
            }
        }
    </script>
@endsection

