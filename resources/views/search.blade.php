@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12" @if(!request()->has('query')) style="display: none;" @endif>
            <form id="search" action="{{ route('search') }}" style="line-height: 2.3em">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <input type="hidden" name="query" value="{{ request('query') }}">
            <div class="box box-solid" style="display:inline-block;background: white; margin-bottom: 20px; padding: 5px 5px 5px 5px">
                <span class="btn btn-sm"><i class="fa fa-sliders"></i><span class="hidden-xs">&nbsp;&nbsp;{{ __('rent.filter') }}</span></span>

                    <select name="type" title="{{ __('rent.type') }}" style="width:100px" class="btn select-filter btn-sm btn-default">
                        <option value="0">{{ __('app.no_matter') }}</option>
                        <option @if(request('type')=='sale') selected @endif value="sale">{{ __('rent.sale') }}</option>
                        <option @if(request('type')=='sale_new') selected @endif value="sale_new">{{ __('rent.sale') }} | {{ __('rent.new') }}</option>
                        <option @if(request('type')=='sale_secondhand') selected @endif value="sale_secondhand">{{ __('rent.sale') }} | {{ __('rent.secondhand') }}</option>
                        <option @if(request('type')=='rent') selected @endif value="rent">{{ __('rent.rent') }}</option>
                    </select>

                <span class="pull-right">
                    <select name="order_by" title="{{ __('rent.filter') }}" class="btn select-filter btn-sm btn-default">
                        <option @if(request('order_by')=='price') selected @endif value="price">{{ __('rent.by_price') }}</option>
                        <option @if(request('order_by')=='date') selected @endif value="date">{{ __('rent.by_date') }}</option>
                    </select>
                <button name="order_type" value="descending" class="btn btn-filter btn-default btn-sm @if(request('order_type')=='descending') active @endif" title="{{ __('rent.descending') }}"><i class="fa fa-arrow-down"></i><span class="hidden-xs"> {{ __('rent.descending') }}</span></button>
                <button name="order_type" value="ascending" class="btn btn-filter btn-default btn-sm @if(request('order_type')=='ascending') active @endif" title="{{ __('rent.ascending') }}"><i class="fa fa-arrow-up"></i><span class="hidden-xs"> {{ __('rent.ascending') }}</span></button>
                </span>

            </div>
            </form>
        </div>
        @foreach($items as $item)
            <div class="col-md-6">
                <div class="box box-solid search-result-box">
                    <div class="box-body">
                        <h4 class="header">
                            <a href="{{ route('item.view', $item->id) }}">{{ $item->content->title->$locale }}</a>
                        </h4>
                        <div class="media">
                            <div class="pull-left ad-click-event" style="position: relative;width:150px;">

                                    <img  src="/storage/{{ $item->images[0] }}" alt="MaterialPro" class="media-object" style="margin:auto;max-width: 140px;max-height: 90px;border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
                                <span class="label label-success">
                                        {{ $item->type?$item->state==2?__('rent.sale'):__('rent.secondhand'):__('rent.rent') }}
                                </span>

                            </div>
                            <div class="media-body">
                                <div class="clearfix">
                                    <p class="description">{{ $item->content->category_single->$locale }} | {{ $item->content->body->$locale }} {{ $item->additional_info->$locale }}</p>
                                    <span class="label label-warning pull-left">{{ $item->price." ".__('rent.som') }}</span>
                                    @if($item->type)
                                        @if($item->market)
                                            <a class="btn btn-sm btn-primary pull-right add-to-cart" style="margin-bottom: 0" data-delivery="{{ $item->market_delivery?__('rent.delivery').": ".implode(', ', json_decode($item->market_delivery)):__('rent.delivery_not_available') }}" data-name="{{ $item->content->title->$locale }}" data-id="{{ $item->id }}" data-price="{{ $item->price }}" data-category-name="{{ $item->content->category->$locale }}" data-category-id="{{ $item->category }}" data-market-slug="{{ $item->market }}" data-market-name="{{ $item->market_name }}">
                                                <i class="fa fa-shopping-cart margin-r5"></i> {{ __('rent.to_cart') }}
                                            </a>
                                        @else
                                            <a class="btn btn-sm btn-primary pull-right add-to-cart" style="margin-bottom: 0" data-delivery="{{ $item->author_delivery?__('rent.delivery').": ".$item->author_delivery:__('rent.delivery_not_available') }}" data-name="{{ $item->content->title->$locale }}" data-id="{{ $item->id }}" data-price="{{ $item->price }}" data-category-name="{{ $item->content->category->$locale }}" data-category-id="{{ $item->category }}" data-user-id="{{ $item->author }}" data-user-name="{{ $item->author_name }}">
                                                <i class="fa fa-shopping-cart margin-r5"></i> {{ __('rent.to_cart') }}
                                            </a>
                                        @endif
                                        @else
                                        <a class="btn btn-sm btn-primary pull-right" style="margin-bottom: 0" href="{{ route('item.view', $item->id) }}">
                                            {{ __('rent.open') }}
                                        </a>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="media-footer">
                            <small><i class="fa fa-map-marker"></i> {{ isset($item->content->address)?$item->content->address:__('rent.not_specified') }}</small>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach

            @if(!$items and $is_searching)
                <div class="col-md-12 text-left">
                    {!! __('app.nothing_found_with_query', ['query'=>'<strong>'.$header.'</strong>']) !!}
                    <br/><br/>
                    <p>{{ __('app.suggestions') }}:</p>
                    <ul style="margin: 0px 0px 2em 1.3em; padding: 0px; border: 0px; color: #222222;">
                        <li style="margin: 0px; padding: 0px; border: 0px; text-align: left;">{{ __('app.make_sure_spelling') }}</li>
                        <li style="margin: 0px; padding: 0px; border: 0px; text-align: left;">{{ __('app.try_different_keywords') }}</li>
                        <li style="margin: 0px; padding: 0px; border: 0px; text-align: left;">{{ __('app.try_more_general_keywords') }}</li>
                        <li style="margin: 0px; padding: 0px; border: 0px; text-align: left;">{{ __('app.try_fewer_keywords') }}</li>
                    </ul>
                </div>
                @elseif(!$is_searching)
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="search-page">
                            <div class="col-md-12" style="text-align: center">
                                <img style="border-radius:5px;margin:30px auto 50px auto;max-width: 150px" src="/storage/default-images/logo.png"/>
                            </div>
                            @widget('search')
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-md-12 text-center">
                <div class="btn-group" style="font-size:12pt">
                    @foreach($pagination as $item)
                        <a @if(!$item['link']) class="btn btn-default disabled btn-sm" @else href="{{ $item['link'] }}" class="btn btn-default {{ $item['class'] }} btn-sm" @endif><i class="{{ $item['icon'] }}"></i>{{ $item['value'] }}</a>
                    @endforeach
                </div>
            </div>
    </div>
@endsection
@section('after_scripts')
    <script type="text/javascript">
        $(function () {
            var filterSelect = $(".select-filter"),
                buttons = $(".btn-filter");
//                filterBtnAsc = $('button[name=ascending]'),
//                filterBtnDesc = $('button[name=descending]');

            filterSelect.on('change', function () {
                buttons.removeClass('active');
            });
            $("select[name=type]").on('change', function () {
                $("#search").submit();
            });
        });
    </script>
@endsection