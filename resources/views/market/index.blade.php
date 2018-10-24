@extends('layouts.market')
@section('content')
    <div class="row">
        <div id="filter" style="border-top: 1px solid rgba(0,0,0,0.1);margin-top:-15px;width:100%;background: #ffffff">
            <div style="background:#ffffff ;height:auto;position:relative;width: 100%">
                <div style="position:relative;width:100%;height:100%;background:linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.5)),url('/storage/{{ $Market->background }}') center center transparent no-repeat;background-size: cover">
                    <img style="display: inline-block;width:100%;height:auto;min-height:240px; max-height:320px" src="/storage/default-images/market_trans.png"/>
                    <div style="text-align:center;padding:10px;background:transparent;top:60px;right:0;bottom:0;left:0;position:absolute;display: inline-block;">
                        <a href="/{{ $Market->slug }}">
                            <img src="/storage/{{ $Market->icon }}" style="max-width: 80px; border-radius: 50%; border:4px solid #ffffff;">  </a>
                        <a href="/{{ $Market->slug }}"><div style="color:#ffffff;text-shadow: 0 1px 0 black;font-size: 20pt">{{ $Market->name }}</div></a>
                        <div style="text-shadow: 0 1px 0 black;font-size: 12pt;color: #fff">{{ $Market->description }} </div>
                    </div>
                    @if($Market->administrator == auth()->id() or auth()->check() and auth()->user()->role_id == 1 or auth()->check() and auth()->user()->role_id == 3)
                        <div style="display:inline-block;position: absolute; top:15px;left:15px;">
                            <div style="display: inline-block; cursor: pointer;">
                                <a href="/markets/edit/{{ $Market->id }}" title="{{ trans('rent.edit') }}" class="dropdown-toggle bg-purple" style="margin:3px;display:inline-block;text-align:center;color: #ffffff;height:32px;width:32px;font-size:16pt;border-radius: 50%;padding: 2px;"><i class="fa fa-edit"></i> </a><br/>
                                <a href="/{{ $Market->slug }}/category" title="{{ trans('rent.edit_categories') }}" class="dropdown-toggle bg-purple" style="margin:3px;display:inline-block;text-align:center;color: #ffffff;height:32px;width:32px;font-size:16pt;border-radius: 50%;padding: 2px;"><i class="fa fa-server"></i> </a><br/>
                                <a href="/{{ $Market->slug }}/orders" title="{{ trans('rent.orders') }}" class="dropdown-toggle bg-purple" style="position:relative;margin:3px;display:inline-block;text-align:center;color: #ffffff;height:32px;width:32px;font-size:16pt;border-radius: 50%;padding: 2px;">
                                    <i class="fa fa-cart-arrow-down"></i>
                                    @if($order_quantity)<span class="label label-danger">{{ $order_quantity }}</span>@endif
                                </a>
                            </div>
                        </div>
                    @endif
                    <div style="display:inline-block;position: absolute; top:15px;right:15px;">
                        <div style="display: inline-block; cursor: pointer;">
                            <span title="{{ trans('rent.address') }}" class="dropdown-toggle bg-red" data-toggle="dropdown" aria-expanded="false" style="margin:3px;display:inline-block;text-align:center;color: #ffffff;height:32px;width:32px;font-size:16pt;border-radius: 50%;padding: 2px;"><i class="fa fa-map-marker"></i> </span>
                            <ul class="dropdown-menu flat pull-right bg-red">
                                <li style="padding-left: 10px" onclick="window.location.href='https://2gis.kg/search/{{ $Market->address }}'">
                                    {{ $Market->address }}
                                </li>
                            </ul>
                        </div>
                        <div style="display: inline-block; cursor: pointer;">
                            <span title="{{ trans('rent.delivery') }}" class="dropdown-toggle bg-blue" data-toggle="dropdown" aria-expanded="false" style="margin:3px;display:inline-block;text-align:center;color: #ffffff;height:32px;width:32px;font-size:16pt;border-radius: 50%;padding: 2px;"><i class="fa fa-truck"></i> </span>
                            <div class="dropdown-menu flat pull-right bg-blue" style="padding:5px;">
                                <small>{{ trans('rent.delivery') }}:</small>
                                @if($Market->delivery)
                                    @foreach ($Market->delivery as $delivery)
                                        <li>{{ $delivery }}</li>
                                    @endforeach
                                @else
                                    Not available
                                @endif
                            </div>
                        </div>
                        @if(isset($Market->contacts->phone))
                            <div style="display: inline-block; cursor: pointer;">
                                <span title="{{ trans('rent.phone_number') }}" class="dropdown-toggle bg-green" data-toggle="dropdown" aria-expanded="false" style="margin:3px;display:inline-block;text-align:center;color: #ffffff;height:32px;width:32px;font-size:16pt;border-radius: 50%;padding: 2px;"><i class="fa fa-phone"></i> </span>
                                <ul class="dropdown-menu flat pull-right bg-green">
                                    @foreach($Market->contacts->phone as $phone)
                                        <li>
                                            <a target="_blank" href="tel:{{ $phone }}">
                                                <b style="color:#ffffff;font-size:12pt;padding:10px;">
                                                    {{ $phone }}
                                                </b>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(isset($Market->contacts->whatsapp))
                            <div style="display: inline-block;cursor: pointer;">
                                <span title="Whatsapp" class="dropdown-toggle bg-green" data-toggle="dropdown" aria-expanded="false" style="margin:3px;display:inline-block;text-align:center;color: #ffffff;height:32px;width:32px;font-size:16pt;border-radius: 50%;padding: 2px;"><i class="fa fa-whatsapp"></i> </span>
                                <ul class="dropdown-menu flat pull-right bg-green">
                                    <li>
                                        <a target="_blank" href="whatsapp://send?text=https://ijara.kg/{{ $Market->slug }}&amp;phone={{ $Market->contacts->whatsapp }}">
                                            <b style="color:#ffffff;font-size:12pt;padding:10px;">
                                                {{ $Market->contacts->whatsapp }}
                                            </b>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @endif
                        @if(isset($Market->contacts->facebook))<a title="Facebook" target="_blank" href="{{ $Market->contacts->facebook }}"><span style="margin:3px;display:inline-block;text-align:center;color: #ffffff;height:32px;width:32px;font-size:16pt;border-radius: 50%;padding: 2px;background: #555299"><i class="fa fa-facebook"></i> </span></a>@endif
                        @if(isset($Market->contacts->instagram)) <a title="Instagram" target="_blank" href="{{ $Market->contacts->instagram }}"><span class="bg-orange" style="margin:3px;display:inline-block;text-align:center;color: #ffffff;height:32px;width:32px;font-size:16pt;border-radius: 50%;padding: 2px;"><i class="fa fa-instagram"></i> </span></a>@endif
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" style="border-bottom: 1px solid rgba(0,0,0,0.1)"></div>
            <div id="categories" style="background: #ffffff;display:inline-block;width:100%;padding-top:20px;">
                @if(!$ads)
                    <div class="col-md-12">{{ trans('rent.no_products') }}</div>
                @else
                    @foreach($ads as $ad)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="flat">

                                <div class="flat" style="position:relative;height: 158px; background: linear-gradient( rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3) ),url('/storage/{{ $ad->images[0] }}') center center; background-size: cover;">
                                    <div class="badge" style="background: rgb(243, 110, 20);position:absolute;bottom:10px;right:10px;font-size:14pt;">{{ $ad->price }} {{ trans('rent.som') }}</div>
                                    {{--<div class="bg-purple" style="position:absolute;top:0;right:0;font-size:10pt;padding: 5px;">@if($ad->type) {{ trans('rent.sale') }}@else{{ trans('rent.rent') }}@endif</div>--}}
                                    <div class="bg-purple" style="position:absolute;top:0;right:0;font-size:10pt;padding: 5px;">{{ $ad->category_name }}</div>
                                    <a href="/{{ $Market->slug }}/view/{{ $ad->id }}"><div style="background: transparent;right:0;left:0;top:0;bottom:0;position:absolute;"></div></a>
                                    <div data-delivery="{{ trans('rent.delivery') }}: {{ $Market->delivery?implode(", ", $Market->delivery):trans('rent.no_delivery') }}" data-name="{{ $ad->title }}" data-id="{{ $ad->id }}" data-price="{{ $ad->price }}" data-category-name="{{ $ad->category_name }}" data-category-id="{{ $ad->category }}" data-market-slug="{{ $Market->slug }}" data-market-name="{{ $Market->name }}" class="bg-green add-to-cart" style="cursor:pointer;position:absolute;top:0;left:0;font-size:10pt;padding: 5px;">{{ trans('rent.to_cart') }}</div>
                                </div>

                                <div>
                                    <span class="users-list-name" style="font-size: 13pt"><span class="users-list-name" ><a href="/{{ $Market->slug }}/view/{{ $ad->id }}">{{ $ad->title }}</a></span></span>
                                    <div style="position: relative;">
                                        <p title="{{ $ad->features }}" class="dropdown-toggle description" data-toggle="dropdown" aria-expanded="false" style="margin:0;overflow: hidden;white-space: nowrap; text-overflow: ellipsis;">
                                            {{ $ad->features }}
                                        </p>
                                        <div class="dropdown-menu">
                                            {{ $ad->features }}
                                        </div>
                                    </div>
                                    @if(!$ad->features)
                                        <div style="height:20px;"></div>
                                    @endif
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                <div id="add-products" class="bottom-center">
                    <div id="loader" class="pace" style="display: none"></div>
                </div>
            </div>
        </div>
    </div>
    <button style="display: none;" id="cartFunctionLoader"></button>
    <input type="hidden" style="display: none;" id="currentElemForCart" />
@endsection
@section("after_scripts")
    <script type="text/javascript">
        $(function () {
            var processing = false, page=2, last=false, addProduct = $("#add-products"), loader=$("#loader");
            $(window).scroll(function () {
                if(last) return false;
                if (!processing && $(window).scrollTop() >= ($(document).height() - $(window).height())*0.7){
                    loader.fadeIn();
                    processing = true; //sets a processing AJAX request flag
                    $.post("/{{ $Market->slug }}/getProducts", {"_token":'{{ csrf_token() }}', 'page':page}, function(data){ //or $.ajax, $.get, $.load etc.
                        //load the content to your div
                        if(data){
                            let check = false;
                            for(let i=0;i<data.length; i++){
                                check = true;
                                let product = '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">\n' +
                                    '                        <div class="flat">\n' +
                                    '\n' +
                                    '                                <div class="flat" style="position:relative;height: 158px; background: linear-gradient( rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3) ),url(\'/storage/'+data[i].images[0]+'\') center center; background-size: cover;">\n' +
                                    '                                    <div class="badge" style="background: rgb(243, 110, 20);position:absolute;bottom:10px;right:10px;font-size:14pt;">'+data[i].price+' {{ trans('rent.som') }}</div>\n' +
                                    '                                    <div class="bg-purple" style="position:absolute;top:0;right:0;font-size:10pt;padding: 5px;">'+data[i].category_name+'</div>\n' +
                                    '                                    <a href="/{{ $Market->slug }}/view/'+data[i].id+'"><div style="background: transparent;right:0;left:0;top:0;bottom:0;position:absolute;"></div></a>\n' +
                                    '                                    <div data-delivery="{{ trans('rent.delivery') }}: {{ $Market->delivery?implode(", ", $Market->delivery):trans('rent.no_delivery') }}" data-name="'+data[i].title+'" data-id="'+data[i].id+'" data-price="'+data[i].price+'" data-category-name="'+data[i].category_name+'" data-category-id="'+data[i].category+'" data-market-slug="{{ $Market->slug }}" data-market-name="{{ $Market->name }}" class="bg-green add-to-cart-'+page+'" style="cursor:pointer;position:absolute;top:0;left:0;font-size:10pt;padding: 5px;">{{ trans('rent.to_cart') }}</div>\n' +
                                    '                                </div>\n' +
                                    '\n' +
                                    '                            <div>\n' +
                                    '                                <span class="users-list-name" style="font-size: 13pt"><a class="users-list-name" href="/{{ $Market->slug }}/view/'+data[i].id+'">'+data[i].title+'</a></span>\n' +
                                    '                                <div style="position: relative;">\n' +
                                    '                                    <p title="'+data[i].features+'" class="dropdown-toggle description" data-toggle="dropdown" aria-expanded="false" style="margin:0;overflow: hidden;white-space: nowrap; text-overflow: ellipsis;">\n' +
                                    '                                        '+data[i].features+'\n' +
                                    '                                    </p>\n' +
                                    '                                    <div class="dropdown-menu">\n' +
                                    '                                        '+data[i].features+'\n' +
                                    '                                    </div>\n' +
                                    '                                </div>\n' + (data[i].features?'':'<div style="height:20px;"></div>\n') +
                                    '                                \n' +
                                    '                                <p></p>\n' +
                                    '                            </div>\n' +
                                    '                        </div>\n' +
                                    '                    </div>';
                                addProduct.before(product);
                            }
                            $("#currentElemForCart").val(page);
                            $("#cartFunctionLoader").click();
                            if(!check) last=true;
                            processing = false; //resets the ajax flag once the callback concludes
                            page++;
                            loader.fadeOut();
                        }
                    });
                }
            });
        });
        @if(request()->has("query"))
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
        @endif
    </script>
@endsection
