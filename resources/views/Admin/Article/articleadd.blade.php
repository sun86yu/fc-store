@extends('Admin.layout.admin')

@section('content')
    <section class="content-header">
        <h1>
            创建文章
            <small>系统文章管理</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 控制台</a></li>
            <li><a href="#">文章</a></li>
            <li class="active">创建文章</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">General Elements</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form role="form">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Text</label>
                                <input type="text" class="form-control" placeholder="Enter ...">
                            </div>
                            <div class="form-group">
                                <label>Text Disabled</label>
                                <input type="text" class="form-control" placeholder="Enter ..." disabled>
                            </div>

                            <!-- textarea -->
                            <div class="form-group">
                                <label>Textarea</label>
                                <textarea class="form-control" rows="3" placeholder="Enter ..."></textarea>
                            </div>
                            <div class="form-group">
                                <label>Textarea Disabled</label>
                                <textarea class="form-control" rows="3" placeholder="Enter ..." disabled></textarea>
                            </div>

                            <!-- input states -->
                            <div class="form-group has-success">
                                <label class="control-label" for="inputSuccess"><i class="fa fa-check"></i> Input with success</label>
                                <input type="text" class="form-control" id="inputSuccess" placeholder="Enter ...">
                                <span class="help-block">Help block with success</span>
                            </div>
                            <div class="form-group has-warning">
                                <label class="control-label" for="inputWarning"><i class="fa fa-bell-o"></i> Input with
                                    warning</label>
                                <input type="text" class="form-control" id="inputWarning" placeholder="Enter ...">
                                <span class="help-block">Help block with warning</span>
                            </div>
                            <div class="form-group has-error">
                                <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> Input with
                                    error</label>
                                <input type="text" class="form-control" id="inputError" placeholder="Enter ...">
                                <span class="help-block">Help block with error</span>
                            </div>

                            <!-- checkbox -->
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox">
                                        Checkbox 1
                                    </label>
                                </div>

                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox">
                                        Checkbox 2
                                    </label>
                                </div>

                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" disabled>
                                        Checkbox disabled
                                    </label>
                                </div>
                            </div>

                            <!-- radio -->
                            <div class="form-group">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
                                        Option one is this and that&mdash;be sure to include why it's great
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                                        Option two can be something else and selecting it will deselect option one
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="optionsRadios" id="optionsRadios3" value="option3" disabled>
                                        Option three is disabled
                                    </label>
                                </div>
                            </div>

                            <!-- select -->
                            <div class="form-group">
                                <label>Select</label>
                                <select class="form-control">
                                    <option>option 1</option>
                                    <option>option 2</option>
                                    <option>option 3</option>
                                    <option>option 4</option>
                                    <option>option 5</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Select Disabled</label>
                                <select class="form-control" disabled>
                                    <option>option 1</option>
                                    <option>option 2</option>
                                    <option>option 3</option>
                                    <option>option 4</option>
                                    <option>option 5</option>
                                </select>
                            </div>

                            <!-- Select multiple-->
                            <div class="form-group">
                                <label>Select Multiple</label>
                                <select multiple class="form-control">
                                    <option>option 1</option>
                                    <option>option 2</option>
                                    <option>option 3</option>
                                    <option>option 4</option>
                                    <option>option 5</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Select Multiple Disabled</label>
                                <select multiple class="form-control" disabled>
                                    <option>option 1</option>
                                    <option>option 2</option>
                                    <option>option 3</option>
                                    <option>option 4</option>
                                    <option>option 5</option>
                                </select>
                            </div>

                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>

                <div class="box">
                    <div class="box-body pad">
                        <div id="summernote">Hello Summernote</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <link rel="stylesheet" href="{{ URL::asset('/components/summernote/summernote.css') }}">
    <script type="text/javascript" src="{{ URL::asset('/components/summernote/summernote.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 400,
            });
        });
    </script>
@endsection