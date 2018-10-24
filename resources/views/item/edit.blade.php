@extends('layouts.app')
@section('before_styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link href="{{ asset('plugins/fileinput/fileinput.min.css') }}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/fileinput/theme.min.css') }}" media="all" rel="stylesheet" type="text/css"/>

@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border"><i class="fa fa-pencil-square-o"></i><div class="box-title pull-right">{{ trans('rent.'.($type?"sale":"rent"))." | ".$category->name }}</div></div>
        {!! Form::open(array('route' => ['item.update', $item->id, $type],'files'=>true, "id"=>"create-feature")) !!}
        {{ csrf_field() }}
        <div class="box-body">
            <div class="row">
                @if($category->state == 3 and $type !=0)
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ trans('rent.new')}}?*</label>
                            {!! Form::select("state", ["2"=>trans('app.yes'), "1"=>trans('app.no')],old('state')?old('state'):$item->state, ["required"=>"required","class"=>"form-control"]) !!}

                        </div>
                    </div>
                @elseif($category->state == 2)
                    <input type="hidden" name="state" value="2" />
                @elseif($category->state == 1)
                    <input type="hidden" name="state" value="1" />
                @elseif(!$type)
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ trans('rent.quantity')}}?*</label>
                            {!! Form::select("state", ["0"=>trans('rent.one'), "2"=>trans('rent.many')],old('state')?old('state'):$item->state, ["required"=>"required","class"=>"form-control"]) !!}

                        </div>
                    </div>
                @endif
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ trans('rent.price') }} (@if(!isset($category->features[$locale]['payment_time']) and !$type){{ trans('rent.'.$category->payment_time).", " }}@endif {{ trans("rent.empty_private") }})*</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-money"></i> </div>
                                {!! Form::text("price", old('price')?old('price'):$item->price, ["required"=>"required","class"=>"form-control", "placeholder"=>trans('rent.price')]) !!}
                                <div class="input-group-addon">{{ trans('rent.som') }} </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ trans('rent.phone_number') }}*</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-phone"></i> </div>
                                {!! Form::text("phone_number[phone]", isset(old('phone_number')['phone'])?old('phone_number')['phone']:$item->phone_number->phone, ["required"=>"required","class"=>"form-control", "placeholder"=>trans('rent.commas')." (".trans("rent.number").",".trans("rent.number").")"]) !!}
                                
                            </div>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-whatsapp"></i> </div>
                                {!! Form::text("phone_number[whatsapp]", isset(old('phone_number')['whatsapp'])?old('phone_number')['whatsapp']:(isset($item->phone_number->whatsapp)?$item->phone_number->whatsapp:""), ["class"=>"form-control", "placeholder"=>"Whatsapp"]) !!}
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ trans('rent.address') }}*</label>
                            <div class="level-1"></div>
                            <div class="old_address" style="display: none">{{ old('address')?implode("~", old('address')):$item->address }}</div>
                            <div class="old_address_text" style="display: none">{{ old('address_text') }}</div>
                            <div class="text_select" style="display: none;">{{ trans('rent.other') }}</div>
                            <div class="locale" style="display: none;">{{ $locale }}</div>
                            <div class="street" style="display: none;">{{ trans('rent.street') }}</div>
                            <input type='text' class='form-control' name='address_text' value="{{ old('address_text')?old('address_text'):$item->address_text }}" placeholder="{{ trans('rent.street') }}" />
                        </div>
                    </div>
                @php($old = old('features'))
                @if($category->features) @foreach($category->features[$locale] as $key=>$features)
                    @if($type and $features['sale'] or !$type and $features['rent'] or $features['sale'] and $features['rent'])
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ $features['name'].($features['required']?"*":"") }}</label>
                            @if(!$features['options'][0])
                                @if($features['addon'])<div class="input-group">{!! Form::text("features[".$key."]", array_has($old,$key)?$old[$key]:isset($item->features->$key)?$item->features->$key:"", ["autocomplete"=>"off", "list"=>"f-".$key, "class"=>"form-control", "placeholder"=>$features['name']]+($features['required']?["required"=>"required"]:[""])) !!}<span class="input-group-addon">{{ $features['addon'] }}</span></div>
                                @else{!! Form::text("features[".$key."]", array_has($old,$key)?$old[$key]:isset($item->features->$key)?$item->features->$key:"", ["autocomplete"=>"off", "list"=>"f-".$key, "class"=>"form-control", "placeholder"=>$features['name']]+($features['required']?["required"=>"required"]:[""])) !!}
                                @endif
                                <datalist id="f-{{ $key }}">
                                @if(isset($category->keywords[$key])) @foreach($category->keywords[$key] as $val)
                                    <option value="{{ $val }}">
                                @endforeach
                                @endif
                                
                            </datalist>
                            @else
                                @if($features['addon'])<div class="input-group">{!! Form::select("features[".$key."]", $features['options'],array_has($old,$key)?$old[$key]:isset($item->features->$key)?$item->features->$key:"", ["class"=>"form-control select2", "style"=>"width:100%"]+($features['multiple']?["multiple"=>"multiple"]:[""])+($features['required']?["required"=>"required"]:[""])) !!}<span class="input-group-addon">{{ $features['addon'] }}</span></div>
                                @else{!! Form::select("features[".$key."]".($features['multiple']?"[]":""), $features['options'], array_has($old,$key)?$old[$key]:isset($item->features->$key)?$item->features->$key:"", ["class"=>"form-control select2"]+($features['multiple']?["multiple"=>"multiple"]:[""])+($features['required']?["required"=>"required"]:[""])) !!}
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
                        <div class="tab-content box-body">
                            @foreach($locales as $key=>$local)
                                <div class="tab-pane {{ $key?"":"active" }}" id="{{ $local }}">
                                    <div class="form-group">
                                        <label>{{ trans("rent.additional_info")."(".$local.")" }}</label>
                                        {!! Form::textarea("additional_info[$local]", old("additional_info[$local]")?old("additional_info[$local]"):$item->additional_info[$local], ["style"=>"height:50px","class"=>"form-control"]) !!}
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
                        <div class="file-input theme-explorer">
                                    <div class="file-preview-thumbnails row">
                                        @if($item->images) @foreach($item->images as $key=>$image)
                                            <div id="old_image_{{ $key }}" class="col-md-3 col-xs-12 col-sm-6">
                                                <div class="my-image-box">
                                                    <div class="kv-file-content">
                                                        <input type="hidden" name="old_images[{{ $key }}]" value="{{ $image }}">
                                                        <img src="/storage/{{ $image }}" class="file-preview-image kv-preview-data rotate-12095 " style="width: auto; height: 116px; max-width: 100%; max-height: 100%; margin-top: 0px;">                                            </div><div class="file-thumbnail-footer">
                                                        <div class="file-actions">
                                                            <div class="file-footer-buttons" style="padding-top: 5px">
                                                                <button id="remove_old_image_{{ $key }}" type="button" class="remove_old_image kv-file-remove btn btn-sm btn-kv btn-default btn-outline-secondary" title="{{ trans('rent.delete') }}"><i class="glyphicon glyphicon-trash"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach @endif
                                            <div class="clearfix"></div>
                                    </div>
                        </div>
                        <div class="file-loading">
                            <input id="file-0" class="file" type="file" name="images[]" accept="image/*" multiple>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <div class="form-horizontal">
                <input type="hidden" name="save" value="ok">
                <button id="save" class="btn btn-flat btn-primary">{{ trans('rent.save') }}</button>
                <button name="delete" class="btn btn-flat btn-danger">{{ trans('rent.delete') }}</button>
                <a href="/" class="btn btn-flat btn-default">{{ trans('rent.cancel') }}</a>
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
            //Initialize Select2 Elements
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