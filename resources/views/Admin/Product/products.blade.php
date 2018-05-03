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
                <div class="box">
                    <div class="box-header with-border">
                        <button type="button" class="btn btn-success" onclick="window.location.href='/admin/productadd'">+ 添加商品</button>
                    </div>
                    <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>名称</th>
                                <th>分类</th>
                                <th>状态</th>
                                <th>价格</th>
                                <th>创建时间</th>
                                <th>库存</th>
                                <th>已售</th>
                                <th style="width: 120px">操作</th>
                            </tr>
                            @foreach ($lists as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>
                                    {{$item->pro_name}}
                                </td>
                                <td>
                                    {{\App\Models\Admin\CategoryModel::getParent($item->cat_id)->cat_name}} - {{$item->category->cat_name}}
                                </td>
                                <td>
                                    @if($item->status == 1)
                                        <span class="label label-primary">上架</span>
                                    @else
                                        <span class="label label-danger">下架</span>
                                    @endif
                                </td>
                                <td>{{$item->price}}</td>
                                <td>{{$item->create_time}}</td>
                                <td>{{$item->remain_cnt}}</td>
                                <td>{{$item->saled_cnt}}</td>
                                <td>
                                    <a href="/admin/productedit/{{$item->id}}">
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
                    <input type="hidden" name="del_product_id" id="del_product_id" value="0"/>
                </div>
            </div>
        </div>

    </section>

    @component('Admin.layout.alert')
        @slot('title')
            删除商品
        @endslot

        确定要删除该记录?
    @endcomponent

    <script>
        function delItem(_id) {
            $("#del_product_id").val(_id);
            $("#modal-alert").modal();
        }
        function confirmAlert() {
            var _id = $("#del_product_id").val();
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

            $.ajax({
                type: "DELETE",
                url: "/admin/productdel/" + _id,
                dataType: 'json',
                success: function (data) {
                    if (data.code == 100) {
                        window.location.href = "/admin/products";
                    } else {
                        alert('操作失败!');
                    }
                }
            });
        }
    </script>
@endsection
