@extends('layouts.market')
@section('before_styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link href="{{ asset('plugins/fileinput/fileinput.min.css') }}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/fileinput/theme.min.css') }}" media="all" rel="stylesheet" type="text/css"/>

@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border"><i class="fa fa-plus"></i><div class="box-title pull-right">{{ trans('rent.'.($Market->type?"sale":"rent"))." | ".$category->name }}</div></div>
        {!! Form::open(array('route' => ['market.item.store', 'slug'=>$Market->slug, 'id'=>$category->id],'files'=>true, "id"=>"create-feature")) !!}
        {{ csrf_field() }}
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ trans('rent.price')}} (@if(!isset($category->features[$locale]['payment_time']) and !$Market->type){{ trans('rent.'.$category->payment_time)."," }}@endif{{ trans("rent.empty_private") }})*</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-money"></i> </div>
                            {!! Form::number("price", old('price'), ["required"=>"required","class"=>"form-control", "placeholder"=>trans('rent.price')]) !!}
                            <div class="input-group-addon">{{ trans('rent.som') }} </div>
                        </div>
                    </div>
                </div>
                @php($old = old('features'))
                @if($category->features)
                    @foreach($category->features[$locale] as $key=>$features)
                        @if($Market->type and $features['sale'] or !$Market->type and $features['rent'] or $features['sale'] and $features['rent'])
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ $features['name'].($features['required']?"*":"") }}</label>
                            @if(!$features['options'][0])
                                @if($features['addon'])<div class="input-group">{!! Form::text('features['.$key.']', $old[$key], ["autocomplete"=>"off", "list"=>"f-".$key, "class"=>"form-control", "placeholder"=>$features['name']]+($features['required']?["required"=>"required"]:[""])) !!}<span class="input-group-addon">{{ $features['addon'] }}</span></div>
                                @else{!! Form::text("features[".$key."]", $old[$key], ["autocomplete"=>"off", "list"=>"f-".$key, "class"=>"form-control", "placeholder"=>$features['name']]+($features['required']?["required"=>"required"]:[""])) !!}
                                @endif
                                <datalist id="f-{{ $key }}">
                                @if(isset($category->keywords[$key])) @foreach($category->keywords[$key] as $val)
                                    <option value="{{ $val }}">
                                @endforeach
                                @endif
                                </datalist>
                                
                            @else
                                @if($features['addon'])<div class="input-group">{!! Form::select("features[".$key."]", $features['options'],$old[$key]?$old[$key]:0, ["class"=>"form-control select2", "style"=>"width:100%"]+($features['multiple']?["multiple"=>"multiple"]:[""])+($features['required']?["required"=>"required"]:[""])) !!}<span class="input-group-addon">{{ $features['addon'] }}</span></div>
                                @else{!! Form::select("features[".$key."]".($features['multiple']?"[]":""), $features['options'], $old[$key]?$old[$key]:0, ["class"=>"form-control select2"]+($features['multiple']?["multiple"=>"multiple"]:[""])+($features['required']?["required"=>"required"]:[""])) !!}
                                @endif
                            @endif
                        </div>
                    </div>
                    @endif
                @endforeach
                @endif
                <div class="col-md-12 cat-description">
                    <div class="nav-tabs-custom flat">
                        <ul class="nav nav-tabs">
                            @foreach($locales as $key=>$local)
                                <li class="{{ $key?"":"active" }}" style="margin-right: 0"><a href="#{{ $local }}" data-toggle="tab" aria-expanded="true">{{ mb_strtoupper($local) }}</a></li>
                            @endforeach
                        </ul>
                        @php($additional=old('additional_info'))
                        <div class="tab-content box-body">
                            @foreach($locales as $key=>$local)
                                <div class="tab-pane {{ $key?"":"active" }}" id="{{ $local }}">
                                    <div class="form-group">
                                        <label>{{ trans("rent.additional_info")."(".$local.")" }}</label>
                                        {!! Form::textarea("additional_info[$local]", $additional[$local], ["style"=>"height:50px","class"=>"form-control"]) !!}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>{{ trans("rent.image") }}</label>
                        <div class="file-loading">
                            <input id="file-0" class="file" accept="image/*" type="file" name="images[]" multiple>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <div class="form-horizontal">
                <input type="hidden" name="save" value="ok">
                <button id="save" class="btn btn-flat btn-primary">{{ trans('rent.save') }}</button>
                <a href="/{{ $Market->slug }}" class="btn btn-flat btn-default">{{ trans('rent.cancel') }}</a>
            </div>
        </div>
        <!-- /.tab-content -->
        {{ Form::close() }}
    </div>
@endsection
@section('after_scripts')
    <script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('plugins/fileinput/sortable.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/fileinput/fileinput.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/fileinput/'.$locale.'.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/fileinput/theme.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/fileinput/theme1.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/location.js') }}"></script>
    <script src="{{ asset('js/rent.js') }}"></script>
    <script>
        $(function () {
            $(".select2").select2();
            $("#create-feature").on("submit", function(e){
                $.Nukura.formSaver('{{ url()->current() }}', this, function (msg) {
                    sessionStorage.setItem("success", '{{ trans('rent.updating_success') }}');
                    window.location.href=msg;
                }, function () {
                    new PNotify({
                        title: '{{ trans('app.error') }}',
                        text: '{{ trans('rent.connection_seems_dead') }}',
                        type: "error",
                        icon: "fa fa-warning"
                    });
                });
            });
                $(".file-caption-name").on('click', function(){
                    $('#file-0').click();
                });
                $(".file-drop-zone-title").html("");
        });
        $("#file-0").fileinput({
            language:"{{ $locale }}",
            theme: "explorer",
            uploadUrl: "#",
            allowedFileExtensions: ['jpg', 'png', 'gif', 'jpeg'],
            overwriteInitial: false,
            browseClass: "btn btn-primary flat",
            browseLabel: "{{ trans('rent.selectButtonCopy') }}",
            browseIcon: "<i class=\"glyphicon glyphicon-picture\"></i> ",
            removeClass: "btn btn-danger",
            removeLabel: "{{ trans('rent.delete') }}",
            removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
            showUpload:false,
            showCancel:false,
            maxFileCount:10
        });
    </script>
@endsection