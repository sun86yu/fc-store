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
                        <form id="articleForm" role="form" enctype="multipart/form-data" method="post"
                              action="/admin/articleadd">
                            <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                            <!-- text input -->
                            <div class="form-group">
                                <label>标题</label>
                                <input type="text" value="@if(isset($article)) {{ $article->title }} @endif" name="article_title" class="form-control" placeholder="请输入标题">
                            </div>

                            <div class="form-group">
                                <label>图片</label>
                            </div>

                            <div class="container" style="width:99%">
                                <span class="btn btn-success fileinput-button">
                                    <i class="glyphicon glyphicon-plus"></i>
                                    <span>选择文件...</span>
                                    <input id="fileupload" type="file" name="files[]" multiple>
                                </span>
                                <br>
                                <br>
                                <div id="progress" class="progress">
                                    <div class="progress-bar progress-bar-success"></div>
                                </div>
                                <br>
                                <div id="imagesView">
                                    @if(isset($article)) <img src="{{ $article->head_img }}" height="200px"/> @endif
                                </div>
                                <input type="hidden" name="article_id" value="@if(isset($article)) {{ $article->id }} @endif" />
                                <input type="hidden" value="@if(isset($article)) {{ $article->head_img }} @endif" name="article_img" id="article_img"/>
                            </div>


                            <!-- checkbox -->
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input @if(isset($article) && $article->is_galarry == 1) checked @endif type="checkbox" name="is_galarry">
                                        是否是幻灯
                                    </label>
                                </div>

                                <div class="checkbox">
                                    <label>
                                        <input @if(isset($article) && $article->is_link == 1) checked @endif type="checkbox" name="is_link">
                                        是否是外链
                                    </label>
                                </div>

                            </div>

                            <div class="form-group">
                                <label>外链</label>
                                <input value="@if(isset($article)) {{ $article->link_url }} @endif" type="text" name="link_url" class="form-control" placeholder="如果是外链,请输入">
                            </div>

                            <!-- select -->
                            <div class="form-group">
                                <label>状态</label>
                                <select class="form-control" name="article_status">
                                    <option value="1" @if(isset($article) && $article->status == 1) selected @endif>显示</option>
                                    <option value="0" @if(isset($article) && $article->status == 0) selected @endif>隐藏</option>
                                </select>
                            </div>
                            <input type="hidden" name="article_content" id="article_content" value="@if(isset($article)) {{ $article->content }} @endif" />
                            <div class="box-body pad">
                                <div id="summernote"></div>
                            </div>
                            <button type="button" onclick="submitArticle()" class="btn bg-purple btn-block margin">保存
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <link rel="stylesheet" href="{{ URL::asset('/components/summernote/summernote.css') }}">
    <script type="text/javascript" src="{{ URL::asset('/components/summernote/summernote.js') }}"></script>

    <link rel="stylesheet" href="{{ URL::asset('/components/blueimp-file-upload/css/jquery.fileupload.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/components/blueimp-file-upload/css/jquery.fileupload-ui.min.css') }}">

    <script type="text/javascript"
            src="{{ URL::asset('/components/blueimp-file-upload/vendor/jquery.ui.widget.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ URL::asset('/components/blueimp-file-upload/jquery.iframe-transport.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ URL::asset('/components/blueimp-file-upload/jquery.fileupload.min.js') }}"></script>

    <script>
        function submitArticle() {
            var markupStr = $('#summernote').summernote('code');
            $("#article_content").val(markupStr);
            $("#articleForm").submit();
        }

        $(function () {
            'use strict';
            $('#fileupload').fileupload({
                url: '/admin/articleupload',
                dataType: 'json',
                done: function (e, data) {
                    $.each(data.result.files, function (index, file) {
                        $("#imagesView").html('');
                        $('<img src="' + file.name + '" style="height:200px" />').appendTo('#imagesView');
                        $("#article_img").val(file.name);
                    });
                    // $("#progress").hide();
                },
                progressall: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('#progress .progress-bar').css(
                        'width',
                        progress + '%'
                    );
                }
            }).prop('disabled', !$.support.fileInput)
                .parent().addClass($.support.fileInput ? undefined : 'disabled');
        });

        $(document).ready(function () {

            @if(isset($article))
                var innerhtml = $("#article_content").val();
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
        });

        function sendFile(file, editor, welEditable) {
            data = new FormData();
            data.append("files", file);
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});
            $.ajax({
                data: data,
                type: "POST",
                url: "/admin/articleupload",
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