@extends('layouts.market')
@section('content')
    <div class="box nav-tabs-custom flat" id="filter">
        <div class="box-header with-border">
            <div data-widget="collapse" class="header" style="font-size: 13pt">
                <i style="display: none" class="fa fa-search"></i><i class="fa fa-search"></i>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i style="display: none" class="fa fa-arrow-down"></i><i class="fa fa-arrow-down"></i> {{ trans('rent.show') }}
                    </button>
                </div>
            </div>
        </div>
        <form action="" method="get" class="form-group">
        <div id="filter-body" class="box-body">
                <input type="hidden" name="type" value="1">
                <div class="row">
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label>{{ trans('rent.address') }}</label>
                                <div class="level-1"></div>
                                <div class="text_select" style="display: none;">{{ trans('rent.other') }}</div>
                                <div class="old_address" style="display: none">{{ \request('address')?implode("~", \request('address')):"" }}</div>
                                <div class="locale" style="display: none;">{{ $locale }}</div>
                                <div class="filter" style="display: none;">filter</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label>{{ trans('rent.price')."(".trans('rent.som') }})</label>
                                <div class="row">
                                    <div class="col-sm-6 col-xs-6">{!! Form::number("price_from", request('price_from'), ["class"=>"form-control", "placeholder"=>trans('rent.price_from')]) !!}</div>
                                    <div class="col-sm-6 col-xs-6">{!! Form::number("price_to", request('price_to'), ["class"=>"form-control", "placeholder"=>trans('rent.price_to')]) !!}</div>
                                </div>
                            </div>
                        </div>
                        @if($category->features)
                            @php $request = \request('features') @endphp
                            @foreach($category->features[$locale] as $key=>$features)
                                @if(!@$features['filter'] or !@$features['sale']) @continue @endif
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label>{{ $features['name']}}</label>
                                        @if(!$features['options'][0])
                                            @if($features['addon'])<div class="input-group">{!! Form::text('features['.$key.']', $request[$key], ["autocomplete"=>"off", "list"=>"f-".$key, "class"=>"form-control", "placeholder"=>$features['name']]) !!}<span class="input-group-addon">{{ $features['addon'] }}</span></div>
                                            @else{!! Form::text("features[".$key."]", $request[$key], ["autocomplete"=>"off", "list"=>"f-".$key, "class"=>"form-control", "placeholder"=>$features['name']]) !!}
                                            @endif
                                            <datalist id="f-{{ $key }}">
                                                @if(isset($category->keywords[$key])) @foreach($category->keywords[$key] as $val)
                                                    <option value="{{ $val }}">
                                                @endforeach
                                                @endif
                                            </datalist>
                                        @else
                                            @if($features['addon'])<div class="input-group">{!! Form::select("features[".$key."]", ["-1"=>trans('app.no_matter')]+$features['options'], @$request[$key], ["class"=>"form-control select2", "style"=>"width:100%"]) !!}<span class="input-group-addon">{{ $features['addon'] }}</span></div>
                                            @else{!! Form::select("features[".$key."]", ["-1"=>trans('app.no_matter')]+$features['options'], @$request[$key], ["class"=>"form-control select2"]) !!}
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                </div>
        </div>
            <div class="box-footer">
                <div class="form-horizontal">
                    <button data-widget="collapse" type="button" class="btn btn-box-tool"><i style="display: none" class="fa fa-arrow-up"></i><i class="fa fa-arrow-up"></i> {{ trans('rent.hide') }}</button>
                    <button name="find" value="ok" class="btn btn-flat btn-primary pull-right"><i class="fa fa-search"></i> {{ trans('rent.search') }}</button>
                    @if(\request()->has('find'))<a href="/{{ $Market->slug }}/list/{{ $category->id }}" style="margin-right:5px" class="btn btn-flat btn-default pull-right">{{ trans('rent.reset') }}</a>@endif
                </div>
            </div>
        </form>
    </div>
        @if(\request()->has('find'))<div class="col-md-12 text-center panel">{{ trans('rent.search_results') }}@if(!count($items)): {{ trans('app.nothing_found') }} @endif</div>@elseif(!count($items)) <div class="text-center col-md-12 h4">{{ trans('rent.empty_category') }}</div> @endif
        <div class="row">
        <div id="categories" class="custom-row" style="margin-left:3px;">
        @php $i=0; @endphp
        @foreach($items as $item)
            <div class="col-sm-6 col-md-4 col-xs-12 col-lg-4" style="float: none; display: inline-block; margin: 0 -0.125em;">
                <div class="category-box-back custom-box-for-dropdown-{{ $i }}" style="background: linear-gradient( rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3) ),url('/storage/{{ $item->images[0] }}') center center; background-size: cover;">
                    <div onclick="event.preventDefault();$.Nukura.viewPlus({{ $item->id.",".$i }})">
                        <div style="display: none">{{ $i }}</div>
                        <div class="body-cat">
                        <div id="rent-box-{{ $item->id }}" class="small-box category-box" onclick="$.Nukura.toggleDrop({{ $i }})">
                            <div class="inner">
                                    <p class="category-box-name" style="font-size: 18pt">
                                        @if(!isset($item->features['payment_time']) and !$item->type) {{ trans('rent.'.$category->payment_time) }}: @endif {{ $item->price }}
                                    </p>
                            </div>
                            {{--<div class="bg-purple" style="position: absolute;top:0;right:0;font-size: 12pt;padding: 5px;">@if($item->type) {{ trans('rent.sale') }} @else {{ trans('rent.rent') }}@endif</div>--}}
                            <div class="bg-purple" style="position: absolute;top:0;right:0;font-size: 10pt;padding: 5px;">{{ $item->category_name }}</div>
                            <div class="bg-green" onclick="window.location.href='/'" style="cursor:pointer;position:absolute;top:0;left:0;font-size:10pt;padding: 5px;">{{ trans('rent.to_cart') }}</div>
                            <a href="/{{ $Market->slug }}/view/{{ $item->id }}" onclick="event.preventDefault()">
                            <div id="a-for-searching" class="category-box-footer">
                                <b class="category-box-title">{{ $item->title }}@if(isset($item->features['payment_time'])), {{ $item->features['payment_time'][1] }} @endif</b>
                            </div>
                            </a>
                        </div>
                    </div>
                    </div>
                    <div class="custom-dropdown custom-dropdown-{{ $i }}" style="text-align: left">
                        <div class="custom-self custom-self-{{ $i }}"></div>
                        <div class="custom-title">{{ $item->title }}</div>
                        <div class="custom-item-close flat with-3d-shadow" onclick="$.Nukura.toggleDrop({{ $i }})"><i class="fa fa-close"></i></div>
                        <div class="media col-md-12">
                            @include("market.inc.item")
                        </div>
                        @php $i++ @endphp
                    </div>
                </div>
            </div>
        @endforeach
        </div>
            <div class="col-md-12 text-center">
                <div class="btn-group" style="font-size:12pt">
                    @foreach($pagination as $item)
                        <a @if(!$item['link']) class="btn btn-default disabled btn-sm" @else href="{{ $item['link'] }}" class="btn btn-default {{ $item['class'] }} btn-sm" @endif><i class="{{ $item['icon'] }}"></i>{{ $item['value'] }}</a>
                    @endforeach
                </div>
            </div>
    </div>

@endsection
@section('after_styles')
    <link rel='stylesheet' href='{{ asset('plugins/unitegallery/themes/default/ug-theme-default.css')}}' type='text/css' />
    <link rel='stylesheet' href='{{ asset('plugins/unitegallery/css/unite-gallery.css')}}' type='text/css' />
    <script src="https://api-maps.yandex.ru/2.1/?load=Geolink&amp;lang={{ app()->getLocale() ==='en'?'en_US':'ru_RU' }}" type="text/javascript"></script>
@endsection
@section('after_scripts')
    <script src="{{ asset('js/location.js') }}"></script>
    <script type='text/javascript' src='{{ asset('plugins/unitegallery/js/unitegallery.min.js')}}'></script>
    <script type='text/javascript' src='{{ asset('plugins/unitegallery/themes/compact/ug-theme-compact.js')}}'></script>
    <script type="text/javascript">
        jQuery(document).ready(function(){
            @for($j=0; $j<$i;$j++)
            jQuery("#gallery"+'{{ $j }}').unitegallery();
            @endfor
        });
        $(function () {
            if(window.location.hash === "#messenger") {
                $("#rent-auth-"+$.Nukura.request("messenger")).click();
                setTimeout(function () {
                    $("#rent-box-"+$.Nukura.request("messenger")).click();
                }, 500)
            }
        });
    </script>
@endsection
