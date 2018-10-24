@extends('layouts.app')
@section('before_styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection
@section('content')
    <div class="nav-tabs-custom flat">
        <ul class="nav nav-tabs">
            <li style="padding-right: 0; margin-right: 0" class="header"><i class="fa fa-plus"></i></li>
            @foreach($locales as $key=>$locale)
                <li class="pull-right {{ $key?"":"active" }}" style="margin-right: 0"><a href="#{{ $locale }}" data-toggle="tab" aria-expanded="true">{{ mb_strtoupper($locale) }}</a></li>
            @endforeach
        </ul>
        {!! Form::open(array('route' => 'category.store','files'=>true, "id"=>"create-feature")) !!}
        {{ csrf_field() }}
        <div class="tab-content box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cat-type">{{ trans("rent.parent_category") }}</label>
                        <select id="cat-type" name="parent" class="select2 form-control self" style="width: 100%">
                            <option value="0">{{ trans('rent.root_category') }}</option>
                            @foreach($categories as $cat)
                                <option @if(old("parent") == $cat->id) selected @endif value="{{ $cat->id }}">
                                    {{ $cat->name->$slocale }}
                                </option>
                                @foreach($cat->child as $child)
                                    <option @if(old("parent") == $child->id) selected @endif value="{{ $child->id }}">
                                        - {{ $child->name->$slocale }}
                                    </option>
                                @endforeach
                            @endforeach                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ trans("rent.image") }}</label>
                        {!! Form::file("image", ["accept"=>"image/*", "class"=>"form-control"]) !!}
                    </div>
                </div>
            </div>
            @foreach(config("app.locales") as $key=>$locale)
                <div class="tab-pane {{ $key?"":"active" }}" id="{{ $locale }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans("rent.title") }}</label>
                                {!! Form::text("title[$locale]", old("title[$locale]"), ["class"=>"form-control", "placeholder"=>trans("rent.title")]+($locale=='ru'?["required"=>"required"]:[])) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ trans("rent.description") }}</label>
                        {!! Form::textarea("description[".$locale."]", "", ["class"=>"form-control","style"=>"height:68px", "placeholder"=>trans('rent.description_placeholder')]) !!}
                    </div>
                </div>
                        <div class="features-content">
                        
                            @if(old('features'))
                                @php $feature = old('features')  @endphp
                                @foreach($feature[$locale] as $key=>$features)
                                    <div class="feature-{{ $key }}">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ $feature[$slocale][$key]['name']." <<".$locale.">>" }}</label>
                                                <div class="box-tools pull-right">
                                                    <button onclick="delete_feature('{{ $key }}')" type="button" class="btn btn-box-tool"><i class="fa fa-remove"></i></button>
                                                </div>
                                                <div class="my-box-tools-separator"></div>
                                                <div class="input-group">{!! Form::text("features[".$locale."][".$key."][name]", $features['name'], ["class"=>"form-control", "placeholder"=>trans('rent.addon')]) !!}<span class="input-group-addon">{!! Form::text("features[".$locale."][".$key."][addon]", $features['addon'], ["class"=>"form-control-input-addon"]) !!}</span>@if($locale=="ru")<span class="input-group-addon"><label for="required-{{ $key }}">{{ trans('rent.required') }} &nbsp</label><input id="required-{{ $key }}" {{ @$features['required']?"checked":"" }} type="checkbox" name="features[{{ $locale }}][{{ $key }}][required]"></span>@endif</div>
                                                <div class="input-group">{!! Form::text('features['.$locale.']['.$key.'][options]', $features['options'], ["class"=>"form-control"]) !!}@if($locale=="ru")<span class="input-group-addon"><label for="multiple-{{ $key }}">{{ trans('rent.multiple') }} &nbsp</label><input id="multiple-{{ $key }}" {{ @$features['multiple']?"checked":"" }} type="checkbox" name="features[{{ $locale }}][{{ $key }}][multiple]"></span>@endif</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="element-adder">
                            </div>
                            
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
            @endforeach
        </div>
        <div class="box-footer">
            <div class="form-horizontal">
                <input type="hidden" name="save" value="ok">
                <button id="save" class="btn btn-flat btn-primary">{{ trans('rent.save') }}</button>
                <a href="/" class="btn btn-flat btn-default">{{ trans('rent.cancel') }}</a>
            </div>
        </div>
        <!-- /.tab-content -->
        {{ Form::close() }}
    </div>
@endsection
@section('after_scripts')
    <script src="{{ asset('plugins/select2/select2.min.js') }}"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            $("#create-feature").on('submit', function(){
                $("#save").attr('disabled', 'disabled');
                if($('input[name="image"]').val()) $("#pace-message").html("Your image is uploading...");
                $("#loading").fadeIn();
            });
        });
    </script>
    <script src="{{ asset('js/rent.js') }}"></script>
@endsection