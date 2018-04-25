@extends('Admin.layout.admin')

@section('content')
    <section class="content-header">
        <h1>
            文章管理
            <small>系统文章管理</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 控制台</a></li>
            <li><a href="#">文章</a></li>
            <li class="active">文章列表</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>名称</th>
                                <th>电话</th>
                                <th style="width: 120px">操作</th>
                            </tr>
                            <tr>
                                <td>1.</td>
                                <td>孙宇</td>
                                <td>
                                    18611432838
                                </td>
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#modal-default">
                                        <i class="fa fa-edit"></i> 编辑
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td>孙璟雯</td>
                                <td>
                                    13521102939
                                </td>
                                <td>
                                    <a href="#">
                                        <i class="fa fa-edit"></i> 编辑
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                        <ul class="pagination pagination-sm no-margin pull-right">
                            <li><a href="#">&laquo;</a></li>
                            <li><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">&raquo;</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Default Modal</h4>
                    </div>
                    <div class="modal-body">
                        <p>One fine body&hellip;</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <div class="modal modal-info fade" id="modal-info">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Info Modal</h4>
                    </div>
                    <div class="modal-body">
                        <p>One fine body&hellip;</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-outline">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </section>
@endsection
