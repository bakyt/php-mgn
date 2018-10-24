@extends('layouts.app')
@section('content')
    <div class="box box-primary flat @if(\request()->has('find') and $Markets) collapsed-box @endif" id="filter">
        <div class="box-header with-border">
            <div data-widget="collapse" class="header">
                <i style="display: none" class="fa fa-search"></i><h3 class="box-title"><i class="fa fa-search"></i></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i style="display: none" class="fa fa-arrow-down"></i><i class="fa fa-arrow-down"></i> {{ trans('rent.show') }}
                    </button>
                </div>
            </div>
        </div>
        <form>
        <div class="box-body row">
            <div class="col-md-4 col-sm-6">
                <div class="form-group">
                    <label>{{ trans('rent.address') }}</label>
                    <div class="level-1"></div>
                    <div class="text_select" style="display: none;">{{ trans('rent.other') }}</div>
                    <div class="old_address" style="display: none">{{ \request('address')?implode("~", \request('address')):"" }}</div>
                    <div class="filter" style="display: none;">filter</div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="form-group">
                    <label for="type">{{ trans('rent.type') }}</label>
                    <select id="type" name="type" class="form-control">
                        <option value="0">{{ trans('app.no_matter') }}</option>
                        @foreach($Type as $type)
                            <option @if(\request('type') == $type->id) selected @endif value="{{ $type->id }}">{{ json_decode($type->name)->{app()->getLocale()} }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
            <div class="box-footer">
                <button type="submit" name="find" class="btn btn-primary flat pull-right">{{ trans('rent.search') }}</button>
            </div>
        </form>
    </div>
    @if(\request()->has('find'))<div class="col-md-12 text-center panel">{{ trans('rent.search_results') }}@if(!count($Markets)): {{ trans('app.nothing_found') }} @endif</div>@elseif(!count($Markets)) <div class="text-center col-md-12 h4">{{ trans('rent.empty_category') }}</div> @endif
    <div class="row">
        @foreach($Markets as $Market)
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <div class="flat box box-primary" style="background: #ffffff;">
                    <a href="{{ $Market->slug }}">
                        <div class="flat" style="position:relative;height: 158px; background: linear-gradient( rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3) ),url('/storage/{{ $Market->background }}') center center; background-size: cover;">
                            <div style="padding:10px;background:transparent;top:15px;left:0;right:0;position:absolute;display: inline-block; text-align: center">
                                    <a href="/{{ $Market->slug }}">
                                        <img src="/storage/{{ $Market->icon }}" style="background-size: auto 100%;height:50px;max-width: 50px; border-radius: 50%; border:2px solid #ffffff; margin-right:5px">
                                    </a>
                                    <a href="/{{ $Market->slug }}"><div style="color:#ffffff;text-shadow: 0 1px 3px rgba(0,0,0,.3), 0 3px 5px rgba(0,0,0,.2), 0 5px 10px rgba(0,0,0,.25), 0 10px 10px rgba(0,0,0,.2), 0 20px 20px rgba(0,0,0,.15);font-size: 14pt">{{ $Market->name }}</div></a>
                            </div>
                        </div>
                    </a>
                    <div style="padding: 10px">
                        <div style="position: relative;">
                            <p title="{{ $Market->type }}" class="dropdown-toggle description" data-toggle="dropdown" aria-expanded="false" style="margin:0;overflow: hidden;white-space: nowrap; text-overflow: ellipsis;">
                                <i class="fa fa-shopping-cart"></i> {{ $Market->type }}
                            </p>
                            <a class="dropdown-menu" href="/{{ $Market->slug }}">
                            <div>
                                {{ $Market->type }}
                            </div>
                            </a>
                        </div>
                        <a target="_blank" href="https://2gis.kg/search/{{ $Market->address }}"><span class="users-list-date text-nowrap" style="overflow: hidden;text-overflow: ellipsis;" title="{{ $Market->address }}"><i class="fa fa-map-marker"></i> &nbsp;{{ $Market->address }}</span></a>
                    </div>
                </div>
            </div>
        @endforeach
            <div id="add-products" class="bottom-center">
                <div id="loader" class="pace" style="display: none"></div>
            </div>
    </div>
@endsection
@section('after_scripts')
    <script src="{{ asset('js/location.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            var processing = false, page=2, last=false, addProduct = $("#add-products"), loader=$("#loader");
            $(window).scroll(function () {
                if(last) return false;
                if (!processing && $(window).scrollTop() >= ($(document).height() - $(window).height())*0.7){
                    loader.fadeIn();
                    processing = true; //sets a processing AJAX request flag
                    $.post("/markets/getMarkets", {"_token":'{{ csrf_token() }}', 'page':page, find:'{{ request('find') }}', address:'{{ json_encode(request('address'), true) }}', type:'{{ request('type') }}'}, function(data){ //or $.ajax, $.get, $.load etc.
                        //load the content to your div
                        if(data){
                            let check = false;
                            for(let i=0;i<data.length; i++){
                                check = true;
                                let product = '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">\n' +
                                    '                <div class="flat box box-primary" style="background: #ffffff;">\n' +
                                    '                    <a href="'+data[i].slug+'">\n' +
                                    '                        <div class="flat" style="position:relative;height: 158px; background: linear-gradient( rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3) ),url(\'/storage/'+data[i].background+'\') center center; background-size: cover;">\n' +
                                    '                            <div style="padding:10px;background:transparent;top:15px;left:0;right:0;position:absolute;display: inline-block; text-align: center">\n' +
                                    '                                    <a href="/'+data[i].slug+'">\n' +
                                    '                                        <img src="/storage/'+data[i].icon+'" style="background-size: auto 100%;height:50px;max-width: 50px; border-radius: 50%; border:2px solid #ffffff; margin-right:5px">\n' +
                                    '                                    </a>\n' +
                                    '                                    <a href="/'+data[i].slug+'"><div style="color:#ffffff;text-shadow: 0 1px 3px rgba(0,0,0,.3), 0 3px 5px rgba(0,0,0,.2), 0 5px 10px rgba(0,0,0,.25), 0 10px 10px rgba(0,0,0,.2), 0 20px 20px rgba(0,0,0,.15);font-size: 14pt">'+data[i].name+'</div></a>\n' +
                                    '                            </div>\n' +
                                    '                        </div>\n' +
                                    '                    </a>\n' +
                                    '                    <div style="padding: 10px">\n' +
                                    '                        <div style="position: relative;">\n' +
                                    '                            <p title="'+data[i].type+'" class="dropdown-toggle description" data-toggle="dropdown" aria-expanded="false" style="margin:0;overflow: hidden;white-space: nowrap; text-overflow: ellipsis;">\n' +
                                    '                                <i class="fa fa-shopping-cart"></i> '+data[i].type+'\n' +
                                    '                            </p>\n' +
                                    '                            <a class="dropdown-menu" href="/'+data[i].slug+'">\n' +
                                    '                            <div>\n' +
                                    '                                '+data[i].type+'\n' +
                                    '                            </div>\n' +
                                    '                            </a>\n' +
                                    '                        </div>\n' +
                                    '                        <a target="_blank" href="https://2gis.kg/search/'+data[i].address+'"><span class="users-list-date text-nowrap" style="overflow: hidden;text-overflow: ellipsis;" title="'+data[i].address+'"><i class="fa fa-map-marker"></i> &nbsp;'+data[i].address+'</span></a>\n' +
                                    '                    </div>\n' +
                                    '                </div>\n' +
                                    '            </div>';
                                addProduct.before(product);
                            }
                            if(!check) last=true;
                            processing = false; //resets the ajax flag once the callback concludes
                            page++;
                            loader.fadeOut();
                        }
                    });
                }
            });
            if(window.location.hash === "#messenger") {
                $("#rent-auth-"+$.Nukura.request("messenger")).click();
                setTimeout(function () {
                    $("#rent-box-"+$.Nukura.request("messenger")).click();
                }, 500)
            }
        });
    </script>
@endsection
