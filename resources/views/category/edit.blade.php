@extends('layouts.app')
@section('before_styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection
@section('content')
    <div class="nav-tabs-custom flat">
        <ul class="nav nav-tabs">
            <li style="padding-right: 0; margin-right: 0" class="header"><i class="fa fa-pencil-square-o"></i></li>
            @foreach($locales as $key=>$locale)
                <li class="pull-right {{ $key?"":"active" }}" style="margin-right: 0"><a href="#{{ $locale }}" data-toggle="tab" aria-expanded="true">{{ mb_strtoupper($locale) }}</a></li>
            @endforeach
        </ul>
        {!! Form::open(array('route' => ['category.update', $category->id.(\request()->has('redirect')?"?redirect=".\request('redirect'):"")],'files'=>true, "id"=>"create-feature")) !!}
        {{ csrf_field() }}
        <div class="tab-content box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cat-type">{{ trans("rent.parent_category") }}</label>
                        <select id="cat-type" name="parent" class="select2 form-control self" style="width: 100%">
                            @foreach($categories as $cat)
                                @if($cat->id == $category->id) @continue @endif
                                    <option @if(old("parent") == $cat->id) selected @elseif($cat->id == $category->parent_id) selected @endif value="{{ $cat->id }}">
                                        {{ $cat->name->$slocale }}
                                    </option>
                                    @foreach($cat->child as $child)
                                        @if($child->id == $category->id) @continue @endif
                                        <option @if(old("parent") == $child->id) selected @elseif($child->id == $category->parent_id) selected @endif value="{{ $child->id }}">
                                             - {{ $child->name->$slocale }}
                                        </option>
                                    @endforeach
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ trans("rent.image") }}</label><br/>
                            <div class="input-group">
                             <div class="input-group-btn">
                                <a data-toggle="dropdown" aria-expanded="false" class="btn btn-primary btn-flat"><i class="fa fa-image"></i></a>
                                <div class="dropdown-menu pull-left flat" style="width:auto">
                                    <a style="cursor: zoom-in" href="/storage/{{ $category->image }}" target="_blank"><img id="image" src="/storage/{{ $category->image }}" width="200px"></a>
                                </div>
                            </div>
                            {!! Form::file("image", ["accept"=>"image/*", "class"=>"form-control"]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                        <div class="form-group">
                            <label for="cat-type">{{ trans('rent.payment_time') }}</label>
                            <select id="cat-type" name="payment_time" class="select2 form-control self" style="width: 100%">
                                @foreach(config('app.payment_time') as $cat) <option @if(old("payment_time") == $cat) selected @elseif(!old("payment_time") && $cat == $category->payment_time) selected @endif value="{{ $cat }}">{{ trans('rent.'.$cat) }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                <div class="col-md-6">
                        <div class="form-group">
                            <label for="pay-type">{{ trans('rent.cat_type') }}</label>
                            <select id="pay-type" name="type" class="select2 form-control self" style="width: 100%">
                                <option @if(!$category->type) selected @endif value="0">{{ trans('rent.sale_and_rent')  }}</option>
                                <option @if($category->type == 1) selected @endif value="1">{{ trans('rent.only_rent')  }}</option>
                                <option @if($category->type == 2) selected @endif value="2">{{ trans('rent.only_sale')  }}</option>
                            </select>
                        </div>
                    </div>
            <div class="col-md-6">
                        <div class="form-group">
                            <label for="state-type">{{ trans('rent.state') }}</label>
                            <select id="state-type" name="state" class="select2 form-control self" style="width: 100%">
                                <option @if(!$category->state) selected @endif value="0">{{ trans('rent.disabled')  }}</option>
                                <option @if($category->state == 1) selected @endif value="1">{{ trans('rent.only_secondhand')  }}</option>
                                <option @if($category->state == 2) selected @endif value="2">{{ trans('rent.only_new')  }}</option>
                                <option @if($category->state == 3) selected @endif value="3">{{ trans('rent.new_and_secondhand')  }}</option>
                            </select>
                        </div>
                    </div>
            </div>
            @foreach(config("app.locales") as $key=>$locale)
                <div class="tab-pane {{ $key?"":"active" }}" id="{{ $locale }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans("rent.title")."(".$locale.")" }}</label>
                                {!! Form::text("title[$locale]", old("title[$locale]")?old("title[$locale]"):$category->name->$locale, ["class"=>"form-control", "placeholder"=>trans("rent.title")]) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans("rent.title_single")."(".$locale.")" }}</label>
                                {!! Form::text("title_single[$locale]", old("title_single[$locale]")?old("title_single[$locale]"):$category->name_single->$locale, ["class"=>"form-control", "required"=>"required", "placeholder"=>trans("rent.title_single")]) !!}
                            </div>
                        </div>
                        @if($category->parent_id !=null) <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans("rent.item_title")."(".$locale.")" }}</label>
                                {!! Form::text("item_title[$locale]", old("item_title[$locale]")?old("item_title[$locale]"):isset($category->item_title->$locale)?$category->item_title->$locale:"", ["class"=>"form-control", "placeholder"=>trans("rent.item_title")]) !!}
                            </div>
                        </div>@endif
                        <div class="col-md-6 cat-description">
                            <div class="form-group">
                                <label>{{ trans("rent.keywords")."(".$locale.")" }}</label>
                                {!! Form::textarea("description[$locale]", old("description[$locale]")?old("description[$locale]"):$category->description?$category->description->$locale:"", ["style"=>"height:34px","class"=>"form-control"]) !!}
                            </div>
                        </div>
                        <div class="features-content">
                            <div class="col-md-12"><hr style="margin:0;"><h4>{{ trans("rent.features") }}</h4><hr style="margin:0;padding-bottom:10px"></div>
                            @if(old('features'))
                                @php $feature = old('features')  @endphp
                                @foreach($feature[$locale] as $key=>$features)
                                    <div class="feature-{{ $key }}">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ $feature[$slocale][$key]['name']."(".$locale.")" }}</label>
                                                <div class="box-tools pull-right">
                                                    <button onclick="delete_feature('{{ $key }}')" type="button" class="btn btn-box-tool"><i class="fa fa-remove"></i></button>
                                                </div>
                                                <div class="input-group">{!! Form::text("features[".$locale."][".$key."][name]", $features['name'], ["class"=>"form-control"]) !!}<span class="input-group-addon">{!! Form::text("features[".$locale."][".$key."][addon]", $features['addon'], ["class"=>"form-control-input-addon", "placeholder"=>trans('rent.addon')]) !!}</span></div>
                                                <div class="input-group">{!! Form::text('features['.$locale.']['.$key.'][options]', $features['options'], ["class"=>"form-control"]) !!}<span class="input-group-addon">@if($locale=="ru")<label for="multiple-{{ $key }}">{{ trans('rent.multiple') }} &nbsp</label><input id="multiple-{{ $key }}" {{ @$features['multiple']?"checked":"" }} type="checkbox" name="features[{{ $locale }}][{{ $key }}][multiple]">@endif</span></div>
                                                @if($locale=="ru")
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <label for="rent-{{ $key }}">{{ trans('rent.rent') }} &nbsp</label>
                                                            <input id="rent-{{ $key }}" {{ @$features['rent']?"checked":"" }} type="checkbox" name="features[{{ $locale }}][{{ $key }}][rent]">
                                                        </span>
                                                        <span class="input-group-addon">
                                                            <label for="sale-{{ $key }}">{{ trans('rent.sale') }} &nbsp</label>
                                                            <input id="sale-{{ $key }}" {{ @$features['sale']?"checked":"" }} type="checkbox" name="features[{{ $locale }}][{{ $key }}][sale]">
                                                        </span>
                                                        <span class="input-group-addon">
                                                            <label for="required-{{ $key }}">{{ trans('rent.required') }} &nbsp</label>
                                                            <input id="required-{{ $key }}" {{ @$features['required']?"checked":"" }} type="checkbox" name="features[{{ $locale }}][{{ $key }}][required]">
                                                        </span>
                                                        <span class="input-group-addon"><i class="fa fa-search"></i><input id="filter-{{ $key }}" {{ @$features['filter']?"checked":"" }} type="checkbox" name="features[{{ $locale }}][{{ $key }}][filter]"></span></div>@endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @elseif($category->features)
                                @foreach($category->features[$locale] as $key=>$features)
                                    <div class="feature-{{ $key }}">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="box-tools pull-right">
                                                    <button onclick="delete_feature('{{ $key }}')" type="button" class="btn btn-box-tool"><i class="fa fa-remove"></i></button>
                                                </div>
                                                <div class="my-box-tools-separator"></div>
                                                <label>{{ $category->features[$slocale][$key]['name']."(".$locale.")" }}@if(Auth::user()->role_id !=2) {{ "{".$key."}" }} @endif</label>
                                                <div class="input-group">{!! Form::text("features[".$locale."][".$key."][name]", $features['name'], ["class"=>"form-control"]) !!}<span class="input-group-addon">{!! Form::text("features[".$locale."][".$key."][addon]", $features['addon'], ["class"=>"form-control-input-addon", "placeholder"=>trans('rent.addon')]) !!}</span></div>
                                                <div class="input-group">{!! Form::text('features['.$locale.']['.$key.'][options]', implode(",",$features['options']), ["class"=>"form-control"]) !!} <span class="input-group-addon">@if($locale=="ru")<label for="multiple-{{ $key }}">{{ trans('rent.multiple') }} &nbsp</label><input id="multiple-{{ $key }}" {{ @$features['multiple']?"checked":"" }} type="checkbox" name="features[{{ $locale }}][{{ $key }}][multiple]">@endif</span></div>
                                                @if($locale=="ru")
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <label for="rent-{{ $key }}">{{ trans('rent.rent') }} &nbsp</label>
                                                            <input id="rent-{{ $key }}" {{ @$features['rent']?"checked":"" }} type="checkbox" name="features[{{ $locale }}][{{ $key }}][rent]">
                                                        </span>
                                                        <span class="input-group-addon">
                                                            <label for="sale-{{ $key }}">{{ trans('rent.sale') }} &nbsp</label>
                                                            <input id="sale-{{ $key }}" {{ @$features['sale']?"checked":"" }} type="checkbox" name="features[{{ $locale }}][{{ $key }}][sale]">
                                                        </span>
                                                        <span class="input-group-addon">
                                                            <label for="required-{{ $key }}">{{ trans('rent.required') }} &nbsp</label>
                                                            <input id="required-{{ $key }}" {{ @$features['required']?"checked":"" }} type="checkbox" name="features[{{ $locale }}][{{ $key }}][required]">
                                                        </span>
                                                        <span class="input-group-addon"><i class="fa fa-search"></i><input id="filter-{{ $key }}" {{ @$features['filter']?"checked":"" }} type="checkbox" name="features[{{ $locale }}][{{ $key }}][filter]"></span></div>@endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="element-adder">
                            </div>
                                <div id="element-adder">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ trans('rent.add_new_field') }}</label>
                                            <div class="input-group">
                                                {!! Form::text("adder", "", ["id"=>"adder","class"=>"form-control","placeholder"=>trans('rent.new_fields_title'), "autocomplete"=>"off"]) !!}
                                                <span class="input-group-btn"><button id="create-feature-button" type="button" onclick="create_feature($('#adder').val(), '{{ implode(",",$locales) }}', ['{{ trans('rent.field_exists') }}', '{{ trans('rent.addon') }}', '{{ trans('rent.required') }}', '{{ trans('rent.multiple') }}', '{{ trans('rent.type_name') }}'])" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i></button></span>
                                            </div>
                                        </div>
                                    </div>
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
                @if($role!=2)<button name="delete" onclick="document.getElementById('delete-category').submit()" type="button" class="btn btn-flat btn-danger">{{ trans('rent.delete') }}</button>@endif
                <a href="/" class="btn btn-flat btn-default">{{ trans('rent.cancel') }}</a>
            </div>
        </div>
        <!-- /.tab-content -->
        {{ Form::close() }}
        {!! Form::open(array('route' => ['category.update', $category->id.(\request()->has('redirect')?"?redirect=".\request('redirect'):"")],'files'=>true, "id"=>"delete-category")) !!}
        {{ csrf_field() }}
        <input type="hidden" name="delete" value="true"/>
        {!! Form::close() !!}
    </div>
@endsection
@section('after_scripts')
    <script src="{{ asset('plugins/select2/select2.min.js') }}"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            $("#create-feature").on('submit', function(){
                if($('input[name="image"]').val()) $("#pace-message").html("Your image is uploading...");
                $.Nukura.formSaver('{{ url()->current() }}', this, function (link) {
                    new PNotify({
                        title: '{{ trans('app.success') }}',
                        text: '{{ trans('rent.updating_success') }}',
                        type: "success",
                        icon: "fa fa-check"
                    });
                    var img = $('#image');
                    img.attr("src","/storage/"+link);
                    img.parent().attr("href", '/storage/'+link);
                }, function () {
                    new PNotify({
                        title: '{{ trans('app.success') }}',
                        text: '{{ trans('rent.connection_seems_dead') }}',
                        type: "error",
                        icon: "fa fa-warning"
                    });
                }, true);
            });
            var type = $("#pay-type"), state = $("#state-type");
            if(type.val() === "1") state.attr('disabled', 'disabled');
            else state.removeAttr('disabled');
            type.on('change', function(){
                if(type.val() === "1") state.attr('disabled', 'disabled');
                else state.removeAttr('disabled');
            });
        });
        function create_feature(feature, tabs, labels) {
            tabs = tabs.split(",");
            if (feature) {
                if($(".feature-"+feature).length) new PNotify({
                    // title: 'Regular Notice',
                    text: labels[0],
                    type: "error",
                    icon: false
                });
                else {
                    var adder = document.getElementsByClassName("element-adder");
                    var newdiv1="";
                    var trans = transtext(feature);
                    var req=tabs[0], check=false, requ, mult;
                    for(var i=0; i<tabs.length; i++) {
                        if(req === tabs[i]){
                            requ='<div class="input-group">' +
                                '<span class="input-group-addon">' +
                                '<label for="rent-' + trans + '-'+tabs[i]+'">{{ trans('rent.rent') }} &nbsp</label>' +
                                '<input id="rent-' + trans + '-'+tabs[i]+'" type="checkbox" name="features['+tabs[i]+'][' + trans + '][rent]">' +
                                '</span>' +
                                '<span class="input-group-addon">' +
                                '<label for="sale-' + trans + '-'+tabs[i]+'">{{ trans('rent.sale') }} &nbsp</label>' +
                                '<input id="sale-' + trans + '-'+tabs[i]+'" type="checkbox" name="features['+tabs[i]+'][' + trans + '][sale]">' +
                                '</span>' +
                                '<span class="input-group-addon">' +
                                '<label for="required-' + trans + '-'+tabs[i]+'">'+labels[2]+' &nbsp</label>' +
                                '<input id="required-' + trans + '-'+tabs[i]+'" type="checkbox" name="features['+tabs[i]+'][' + trans + '][required]">' +
                                '</span>' +
                                '<span class="input-group-addon">' +
                                '<i class="fa fa-search"></i> ' +
                                '<input id="filter-' + trans + '-'+tabs[i]+'" type="checkbox" name="features['+tabs[i]+'][' + trans + '][filter]">' +
                                '</span>' +
                                '</div>';
                            mult='<label for="multiple-' + trans + '-'+tabs[i]+'">'+labels[3]+' &nbsp</label><input id="multiple-' + trans + '-'+tabs[i]+'" type="checkbox" name="features['+tabs[i]+'][' + trans + '][multiple]">';
                        }
                        else {
                            mult="";
                            requ="";
                        }
                        newdiv1 = $('<div class="feature-' + trans+'"><div class="col-md-6">\n' +
                            '                                <div class="form-group">\n' +
                            '                                    <label>' + feature + "("+tabs[i]+")" + '</label>\n' +
                            '                                    <div class="box-tools pull-right">\n' +
                            '                                        <button onclick="delete_feature(\'' + feature + '\')" type="button" class="btn btn-box-tool"><i class="fa fa-remove"></i></button>\n' +
                            '                                    </div>\n' + '<div class="my-box-tools-separator"></div><div class="input-group"><input value="' + feature + '" type="text" name="features['+tabs[i]+'][' + trans + '][name]" class="form-control"><span class="input-group-addon"><input value="" type="text" name="features['+tabs[i]+'][' + trans + '][addon]" class="form-control-input-addon" placeholder="'+labels[1]+'"></span></div>' +
                            '                                    <div class="input-group"><input type="text" name="features['+tabs[i]+'][' + trans + '][options]" class="form-control"><span class="input-group-addon">'+mult+'</span></div>' +
                            '                                '+requ+
                            '                                </div>\n' +
                            '                            </div></div>');

                        $(adder[i]).first().before(newdiv1);
                        // $('html, body, .content-header').animate({
                        //     scrollTop: $('.feature-' + trans).offsetTop
                        // }, 500);
                    }
                    $('#adder').val("");
                }
            }
            else new PNotify({
                // title: 'Regular Notice',
                text: labels[4],
                type: "error",
                icon: false
            });
            return false;
        }
    </script>
    <script src="{{ asset('js/rent.js') }}"></script>
@endsection