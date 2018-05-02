@foreach($moduleList as $loopModule)
    <div class="form-group">
        <label>{{$loopModule->mod_name}}</label>
    @switch($loopModule->mod_type)
        @case (1):
            <input value="" type="text" name="{{$loopModule->mod_en_name}}" class="form-control" placeholder="请输入{{$loopModule->mod_name}}">
            @break
        @case (2):
            <textarea class="form-control" name="{{$loopModule->mod_en_name}}" rows="3" placeholder="请输入{{$loopModule->mod_name}}"></textarea>
            @break
        @case (3):
            <select class="form-control" name="{{$loopModule->mod_en_name}}">
                @foreach($loopModule->constant as $loopConst)
                    <option {{$loopConst->const_val}}>{{$loopConst->const_text}}</option>
                @endforeach
            </select>
            @break
        @case (4):
            @foreach($loopModule->constant as $loopConst)
                <div class="radio">
                    <label>
                        <input type="radio" name="{{$loopModule->mod_en_name}}" id="{{$loopModule->mod_en_name}}" value="{{$loopConst->const_val}}" checked="">
                        {{$loopConst->const_text}}
                    </label>
                </div>
            @endforeach
            @break
        @case (5):
            @foreach($loopModule->constant as $loopConst)
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="{{$loopModule->mod_en_name}}" id="{{$loopModule->mod_en_name}}" value="{{$loopConst->const_val}}">
                    {{$loopConst->const_text}}
                </label>
            </div>
            @endforeach
            @break
        @case (6):
            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" name="{{$loopModule->mod_en_name}}" id="{{$loopModule->mod_en_name}}">
            </div>
            @break

    @endswitch
    </div>
@endforeach
