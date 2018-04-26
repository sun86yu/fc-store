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
                    <div class="box-header clearfix dataTables_wrapper form-inline dt-bootstrap">
                        <div class="col-sm-12">
                            <form method="post" action="/admin/logs">
                                <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                                <div class="dataTables_length">
                                    <label>
                                        状态:&nbsp;
                                        <select name="search_is_admin" id="search_is_admin" class="form-control input-sm">
                                            <option value="1" @if ( Request::has('search_is_admin') && Request::get('search_is_admin') == 1 ) selected @endif>管理端</option>
                                            <option value="0" @if ( Request::has('search_is_admin') && Request::get('search_is_admin') == 0 ) selected @endif>商城</option>
                                        </select>
                                    </label>
                                    &nbsp;&nbsp;
                                    <label>
                                        模块:&nbsp;
                                        <select name="search_module_id" id="search_module_id" class="form-control input-sm">
                                            <option value="-1">全部</option>
                                            @foreach($moduleLists as $modKey => $loopModule)
                                                <option value="{{ $modKey  }}" @if ( Request::has('search_module_id') && Request::get('search_module_id') == $modKey ) selected @endif>{{ $loopModule  }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    &nbsp;&nbsp;
                                    <label>用户ID: <input type="search" name="search_user_id" class="form-control input-sm" value="{{ Request::get('search_user_id')  }}" ></label>
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
                                <th>用户</th>
                                <th>动作</th>
                                <th>内容</th>
                                <th>来源</th>
                                <th>时间</th>
                                <th>对象</th>
                            </tr>
                            @foreach ($lists as $item)
                            <tr>
                                <td>{{ $item->id  }}</td>
                                <td>
                                    @if($item->is_admin == 1)
                                        {{ $item->admin->nick_name . ' [ID:  ' . $item->user_id . ' ]' }}
                                    @else
                                        {{ $item->user_id }}
                                    @endif
                                </td>
                                <td>
                                    @foreach($actionList as $loopModule => $loopActionList)
                                        @foreach($loopActionList as $loopAction => $actId)
                                            @if($actId == $item->action_type)
                                                {{  $moduleLists[$loopModule] . " - " . $loopAction }}
                                            @endif
                                        @endforeach
                                    @endforeach
                                </td>
                                <td>{{ $item->action_detail  }}</td>
                                <td>
                                    @if($item->is_admin == 1)
                                        <span class="label label-primary">管理员</span>
                                    @else
                                        <span class="label label-success">商城用户</span>
                                    @endif
                                </td>
                                <td>{{ $item->act_time  }}</td>
                                <td>{{ $item->target_id  }}</td>
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

    </section>
@endsection
