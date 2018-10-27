@extends('layouts.app')
@section('content')
    <div class="row" style="background: #ffffff">
        <div style="border-bottom: 1px solid rgba(0,0,0,0.1)"></div>
        <div id="filter" style="margin-top:-15px;width:100%;background: #ffffff">
            @include('inc.slider')
        </div>
        <div style="border-bottom: 1px solid rgba(0,0,0,0.1)"></div>
    </div>
    @foreach($categories as $category)
        @if(!isset($ads[$category->id])) @continue @endif
        <div class="row" style="background: #ffffff">
            <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                <a href="/category/{{ $category->id }}"><h4 class="users-list-name" style="line-height: 2.5em"><img src="/storage/{{ $category->image }}" style="background-size: auto 100%;height:30px;max-width: 30px; border-radius: 50%; margin-right:5px"> {{ $category->name }} <p style="margin:10px 0 0 0;display: inline-block" class="btn btn-default btn-sm cursor-pointer pull-right"><i class="fa fa-chevron-right"></i></p></h4></a>
            </div>
        </div>
        <div class="row" style="background: #ffffff">
            @foreach($ads[$category->id] as $ad)
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div class="flat">
                            <div class="flat" style="position:relative;height: 158px; background: linear-gradient( rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3) ),url('/storage/{{ $ad->images[0] }}') center center; background-size: cover;">
                                <a href="/view/{{ $ad->id }}" style="position: absolute; top:0;right:0;left:0;bottom:0"></a>
                                <div class="bg-orange" style="padding:2px;position:absolute;bottom:0;right:0;font-size:14pt;"><b>{{ $ad->price }} {{ (is_numeric($ad->price)?" ".trans('rent.som'):"") }}</b></div>
                                <div class="bg-purple" style="position:absolute;top:0;right:0;font-size:10pt;padding: 5px;">@if($ad->type) {{ trans('rent.sale') }}@else{{ trans('rent.rent') }}@endif</div>
                                @if($ad->market)
                                        <div class="bg-primary" style="position:absolute;top:0;left:0;font-size:10pt;padding: 5px;">{{ trans('rent.new') }}</div>
                                    <div data-delivery="{{ trans('rent.delivery') }}: {{ $ad->market->delivery?implode(", ", json_decode($ad->market->delivery)):trans('rent.no_delivery') }}" data-name="{{ $ad->title }}" data-id="{{ $ad->id }}" data-price="{{ $ad->price }}" data-category-name="{{ $cat_tits[$ad->category][0] }}" data-category-id="{{ $ad->category }}" data-market-slug="{{ $ad->market->slug }}" data-market-name="{{ $ad->market->name }}" class="bg-green add-to-cart" style="cursor:pointer;position:absolute;bottom:0;left:0;font-size:10pt;padding: 5px;"><i class="fa fa-shopping-cart"></i> {{ trans('rent.to_cart') }}</div>
                                @elseif($ad->type and $cat_tits[$ad->category][1])
                                        @if($ad->state==1)
                                            <div class="bg-aqua" style="position:absolute;top:0;left:0;font-size:10pt;padding: 5px;">{{ trans('rent.secondhand') }}</div>
                                        @else
                                            <div class="bg-primary" style="position:absolute;top:0;left:0;font-size:10pt;padding: 5px;">{{ trans('rent.new') }}</div>
                                        @endif
                                            <div data-delivery="{{ trans('rent.delivery') }}: {{ $ad->author->delivery?$ad->author->delivery:trans('rent.no_delivery') }}" data-name="{{ $ad->title }}" data-id="{{ $ad->id }}" data-price="{{ $ad->price }}" data-category-name="{{ $cat_tits[$ad->category][0] }}" data-category-id="{{ $ad->category }}" data-user-id="{{ $ad->author->id }}" data-user-name="{{ $ad->author->name }}" class="bg-green add-to-cart" style="cursor:pointer;position:absolute;bottom:0;left:0;font-size:10pt;padding: 5px;"><i class="fa fa-shopping-cart"></i> {{ trans('rent.to_cart') }}</div>
                                @endif
                            </div>
                        <div>
                            <span class="users-list-name" style="font-size: 13pt"><a href="/view/{{ $ad->id }}">{{ $cat_tits[$ad->category][0] }}</a></span>
                            <div style="position: relative;">
                            <p title="{{ $ad->features }}" class="dropdown-toggle description" data-toggle="dropdown" aria-expanded="false" style="margin:0;overflow: hidden;white-space: nowrap; text-overflow: ellipsis;">
                                {{ $ad->features }}
                            </p>
                            <div class="dropdown-menu">
                                {{ $ad->features }}
                            </div>
                            </div>
                            <span class="ymaps-geolink users-list-date" style="overflow: hidden;white-space: nowrap; text-overflow: ellipsis;"><i class="fa fa-map-marker"></i> {{ $ad->address }} </span>
                            @if(is_object($ad->market))
                                <p><a href="/{{ $ad->market->slug }}"><span class="users-list-date"><i class="fa fa-shopping-cart"></i> {{ $ad->market->name }}</span></a></p>
                            @else
                                <p><a href="/users/{{ $ad->author->id }}"><span class="users-list-date"><i class="fa fa-user"></i> {{ $ad->author->name }}</span></a></p>
                            @endif
                            @if(!$ad->features)
                            <div style="height:20px;"></div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row" style="background: #ffffff">
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" style="border-bottom: 1px solid rgba(0,0,0,0.1)"></div>
        </div>
    @endforeach
@endsection
@section("after_scripts")
    @if(request()->has("query"))
    <script type="text/javascript">
        $(function () {
            window.onload = function() {
                var sear = document.getElementById("searching");
                sear.focus();
                sear.setSelectionRange(sear.value.length, sear.value.length);
                @if(request()->has("click"))
                $("#search-0").click();
                @endif
            };
        });
    </script>
    @endif
@endsection
