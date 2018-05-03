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
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if(isset($error))
                            <div id="errorBox" class="alert alert-warning alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-warning"></i> 操作失败!</h4>
                                <p id="errorInfo">{{ $error  }}</p>
                            </div>
                        @endif
                        <form id="productForm" role="form" enctype="multipart/form-data" method="post"
                              action="/admin/productadd">
                            <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                            <!-- text input -->
                            <div class="form-group col-xs-12">
                                <label>标题</label>
                                <input type="text" value="@if(isset($product)){{ $product->pro_name }}@endif" name="pro_name" class="form-control" placeholder="请输入标题">
                            </div>

                            <div class="form-group col-xs-12">
                                <label>图片</label>
                            </div>

                            <div class="container col-xs-12" style="width:99%">
                                <span class="btn btn-success fileinput-button">
                                    <i class="glyphicon glyphicon-plus"></i>
                                    <span>选择文件...</span>
                                    <input id="fileupload" type="file" name="files[]" multiple>
                                </span>
                                <br>
                                <br>

                                <table role="presentation" class="table table-striped">
                                    <tbody class="files">
                                        @if(isset($product) and strlen($product->pro_img) > 1)
                                            @php
                                                $imgList = explode(',', $product->pro_img)
                                            @endphp
                                            @foreach($imgList as $key => $loopImg)
                                            <tr class="template-download" id="imgBox_{{$key}}">
                                                <td>
                                                    <div><span class="label"><img height="200px" src="{{$loopImg}}" /> </span> </div>
                                                </td>
                                                <td>
                                                    <button onclick="delImg({{$key}});return false;" class="btn btn-warning">
                                                        <i class="glyphicon glyphicon-ban-circle"></i>
                                                        <span>删除</span>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <input type="hidden" name="product_id" value="@if(isset($product)){{ $product->id }}@endif" />
                                <input type="hidden" value="@if(isset($product)){{ $product->pro_img }}@endif" name="pro_img" id="pro_img"/>
                            </div>

                            <div class="form-group col-xs-6">
                                <label>一级分类</label>
                                <select class="form-control" name="top_cat" id="top_cat" onchange="setCatSon()">
                                    @foreach($topCat as $loopTop)
                                        <option @if(isset($product) and isset($product->category) and $product->category->parent->id == $loopTop->id) selected @endif value="{{ $loopTop->id  }}">{{ $loopTop->cat_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xs-6">
                                <label>二级分类</label>
                                <select class="form-control" name="sec_cat" id="sec_cat" onchange="setModuleForm()">
                                    @foreach($firstSecondCat as $loopSec)
                                        <option @if(isset($product) and $product->cat_id == $loopSec->id) selected @endif value="{{ $loopSec->id  }}">{{ $loopSec->cat_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-xs-12" id="catFormBox">

                            </div>

                            <div class="form-group col-xs-12">
                                <label>价格</label>
                                <input value="@if(isset($product)){{ $product->price}}@endif" type="text" name="price" class="form-control" placeholder="商品价格">
                            </div>
                            <div class="form-group col-xs-12">
                                <label>库存</label>
                                <input value="@if(isset($product)){{$product->remain_cnt}}@endif" type="text" name="remain_cnt" class="form-control" placeholder="库存数量">
                            </div>
                            <div class="form-group col-xs-12">
                                <label>已售</label>
                                <input value="@if(isset($product)){{ $product->saled_cnt}}@endif" type="text" name="saled_cnt" class="form-control" placeholder="已售数量">
                            </div>

                            <!-- select -->
                            <div class="form-group col-xs-12">
                                <label>状态</label>
                                <select class="form-control" name="product_status">
                                    <option value="1" @if(isset($product) && $product->status == 1) selected @endif>上架</option>
                                    <option value="0" @if(isset($product) && $product->status == 0) selected @endif>下架</option>
                                </select>
                            </div>
                            <input type="hidden" name="product_content" id="product_content" value="@if(isset($product)) {{ $product->content }} @endif" />
                            <div class="form-group box-body pad col-xs-12">
                                <div id="summernote"></div>
                            </div>
                            <button type="button" onclick="submitProduct()" class="btn bg-purple btn-block margin">保存
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>上传</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>取消</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}


</script>
    <!-- The template to display files available for download -->
    <script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    {% $("#pro_img").val($("#pro_img").val() + "," + file.name); %}
    <tr class="template-download fade">
        <td>
            {% if (file.error) { %}
                <div><span class="label label-danger">{%=file.name%} 错误</span> {%=file.error%}</div>
            {% } else { %}
                <div><span class="label"><img height="200px" src="{%=file.name%}" /> </span> {%=file.msg%}</div>
            {% } %}
        </td>
    </tr>
{% } %}


</script>

    <link rel="stylesheet" href="{{ URL::asset('/components/summernote/summernote.css') }}">
    <script type="text/javascript" src="{{ URL::asset('/components/summernote/summernote.js') }}"></script>

    <link rel="stylesheet" href="{{ URL::asset('/components/blueimp-file-upload/css/jquery.fileupload.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/components/blueimp-file-upload/css/jquery.fileupload-ui.min.css') }}">

    <script type="text/javascript"
            src="{{ URL::asset('/components/blueimp-file-upload/vendor/jquery.ui.widget.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ URL::asset('/components/blueimp-file-upload/tmpl.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ URL::asset('/components/blueimp-file-upload/jquery.iframe-transport.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ URL::asset('/components/blueimp-file-upload/jquery.fileupload.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ URL::asset('/components/blueimp-file-upload/jquery.fileupload-process.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ URL::asset('/components/blueimp-file-upload/jquery.fileupload-ui.min.js') }}"></script>

    <script type="text/javascript"
            src="{{ URL::asset('/components/jquery-validation/jquery.validate.min.js') }}"></script>

    <script>
        function delImg(idx){
            var imgStr = $("#pro_img").val();

            var imgArr = imgStr.split(',');
            var newArr = new Array();
            for(var i = 0;i<imgArr.length;i++){
                if(i == idx){
                    continue;
                }
                newArr.push(imgArr[i]);
            }
            var newStr = newArr.join(',');
            $("#imgBox_" + idx).remove();
            $("#pro_img").val(newStr);
        }
        function submitProduct() {
            if ($("#productForm").valid()) {
                var markupStr = $('#summernote').summernote('code');
                $("#product_content").val(markupStr);
                $("#productForm").submit();
            }
        }

        $(function () {
            'use strict';

            $('#productForm').fileupload({
                url: '/admin/productupload'
            });
        });

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
                        setModuleForm();
                    }
                }
            });
        }


        function setModuleForm(){
            var selectCat = $("#sec_cat").val();

            $.ajax({
                type: "GET",
                url: "/admin/catforminfo/" + selectCat,
                success: function (data) {
                    $("#catFormBox").empty();

                    $(data).appendTo('#catFormBox');

                    @if(isset($product) && $product->id > 0)
                        @foreach($product->category->moduleList as $loopModule)
                            @if($loopModule->is_active != 1)
                                @continue
                            @endif

                            @php
                                $extInfo = $product->info;
                                $extInfoArr = json_decode($extInfo, true);
                            @endphp

                            @switch ($loopModule->mod_type)
                                @case (1)
                                    $("#{{$loopModule->mod_en_name}}").val('{{$extInfoArr[$loopModule->id]}}')
                                    @break
                                @case (2)
                                    $("#{{$loopModule->mod_en_name}}").val('{{$extInfoArr[$loopModule->id]}}')
                                    @break
                                @case (3)
                                    $("#{{$loopModule->mod_en_name}}").val('{{$extInfoArr[$loopModule->id]}}')
                                    @break
                                @case (4)
                                    $("input:radio[name={{$loopModule->mod_en_name}}][value={{$extInfoArr[$loopModule->id]}}]").prop("checked","checked")
                                    @break
                                @case (5)
                                    @foreach($extInfoArr[$loopModule->id] as $loopVal)
                                        $("input:checkbox[name='{{$loopModule->mod_en_name}}[]'][value={{$loopVal}}]").attr("checked","checked")
                                    @endforeach
                                    @break
                                @case (6)
                                    $("#{{$loopModule->mod_en_name}}").val('{{$extInfoArr[$loopModule->id]}}')
                                    @break
                            @endswitch
                        @endforeach
                    @endif
                }
            });
        }

        $(document).ready(function () {

            setModuleForm();

            @if(isset($product))
                var innerhtml = $("#product_content").val();
                $(innerhtml).appendTo('#summernote');
            @endif

            $('#summernote').summernote({
                height: 400,
                callbacks: {
                    onImageUpload: function (files, editor, welEditable) {
                        sendFile(files[0], editor, welEditable);
                    }
                },
            });

            $("#productForm").validate({
                rules: {
                    pro_name: {
                        required: true,
                        maxlength: 100
                    },
                    price: {
                        required: true,
                        number: true
                    },
                    remain_cnt: {
                        required: true,
                        digits: true,
                        maxlength: 10
                    },
                    saled_cnt: {
                        required: true,
                        digits: true,
                        maxlength: 10
                    },
                },
                messages: {
                    pro_name: {
                        required: "商品名称必须填写!",
                        maxlength: "商品名称不能超过 100 个字符"
                    },
                    price: {
                        required: "商品价格必须填写!",
                        number: "商品价格必须是数字"
                    },
                    remain_cnt: {
                        required: "库存数量必须填写!",
                        digits: "库存数量是整数!",
                        maxlength: "库存数量不能超过 10 个字符"
                    },
                    saled_cnt: {
                        required: "已售数量必须填写!",
                        digits: "已售数量必须是整数!",
                        maxlength: "已售数量不能超过 10 个字符"
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

        function sendFile(file, editor, welEditable) {
            data = new FormData();
            data.append("files", file);
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});
            $.ajax({
                data: data,
                type: "POST",
                url: "/admin/productupload",
                cache: false,
                contentType: false,
                dataType: 'json',
                processData: false,
                success: function (data) {
                    $('#summernote').summernote('editor.insertImage', data.files[0].name);
                }
            });
        }

    </script>
@endsection