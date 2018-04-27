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
                        <button type="button" class="btn btn-success" onclick="window.location.href='/admin/articleadd'">+ 添加文章
                        </button>
                    </div>
                    <div class="box-header clearfix dataTables_wrapper form-inline dt-bootstrap">
                        <div class="col-sm-12">
                            <form method="post" action="/admin/articles">
                                <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                                <div class="dataTables_length">
                                    <label>
                                        状态:&nbsp;
                                        <select name="search_status" id="search_status" class="form-control input-sm">
                                            <option value="-1">全部</option>
                                            <option value="1" @if ( Request::has('search_status') && Request::get('search_status') == 1 ) selected @endif>显示</option>
                                            <option value="0" @if ( Request::has('search_status') && Request::get('search_status') == 0 ) selected @endif>隐藏</option>
                                        </select>
                                    </label>
                                    &nbsp;&nbsp;
                                    <label>
                                        <input type="checkbox" name="search_is_gallarry" @if (Request::has('search_is_gallarry') && Request::get('search_is_gallarry') == 'on') checked @endif>
                                        幻灯片
                                    </label>&nbsp;&nbsp;
                                    &nbsp;&nbsp;
                                    <label>
                                        <input type="checkbox" name="search_is_link" @if (Request::has('search_is_link') && Request::get('search_is_link') == 'on') checked @endif>
                                        外链
                                    </label>&nbsp;&nbsp;
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
                                <th>标题</th>
                                <th>图片</th>
                                <th>状态</th>
                                <th>时间</th>
                                <th>幻灯</th>
                                <th>外链</th>
                                <th>链接地址</th>
                                <th>操作</th>
                            </tr>
                            @foreach ($lists as $item)
                                <tr>
                                    <td>{{ $item->id  }}</td>
                                    <td>{{ $item->title  }}</td>
                                    <td><img src="{{ $item->head_img  }}" height="100px" />></td>
                                    <td>
                                        @if($item->status == 1)
                                            <span class="label label-primary">显示</span>
                                        @else
                                            <span class="label label-danger">隐藏</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->create_time  }}</td>
                                    <td>
                                        @if($item->is_galarry == 1)
                                            <span class="label label-primary">是</span>
                                        @else
                                            <span class="label label-warning">否</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->is_link == 1)
                                            <span class="label label-primary">是</span>
                                        @else
                                            <span class="label label-warning">否</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->link_url  }}</td>
                                    <td>
                                        <a href="/admin/articleedit/{{ $item->id  }}">
                                            <i class="fa fa-edit"></i> 编辑
                                        </a>
                                        @if ( $item->status == 1 )
                                            &nbsp;|&nbsp;
                                            <a href="javascript:delItem({{$item->id}})">
                                                <i class="fa fa-close"></i> 删除
                                            </a>
                                        @endif
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

                    <input type="hidden" name="del_article_id" id="del_article_id" value="0"/>
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
        function delItem(_id) {
            $("#del_article_id").val(_id);
            $("#modal-alert").modal();
        }

        function confirmAlert() {
            var _id = $("#del_article_id").val();
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

            $.ajax({
                type: "DELETE",
                url: "/admin/articledel/" + _id,
                dataType: 'json',
                success: function (data) {
                    if (data.code == 100) {
                        window.location.href = "/admin/articles";
                    }else{
                        alert("操作失败");
                    }
                }
            });
        }
    </script>
@endsection
