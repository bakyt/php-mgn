@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="row">
        @php($i=0)
        <div id="sub-cat-box-{{ $i }}" class="col-md-12 col-lg-12 col-sm-12 col-xs-12" style="text-align: center">
            @foreach($categories as $sub)
                <div class="sub-cats-{{ $i }}" style="display: inline">
                    <div  class="col-sm-6 col-md-4 col-xs-6 col-lg-3">
                        <div class="panel" style="border-radius: 0">
                            <div class="panel-body sub-cat" style="padding:0;border-radius:0;background: linear-gradient( rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3) ),url('/storage/{{ $sub->image }}') center center; background-size: cover;">
                                <a class="sub-url" href="/{{ isset($sub->type)?"list":"category" }}/{{ $sub->id }}">
                                    <!-- Add the bg color to the header using any of the bg-* classes -->
                                    <div class="widget-user-header custom-category-title">
                                        <span><h4 class="widget-user-username">{{ $sub->name }}</h4></span>
                                        <span style="display: none">{{ $sub->description }}&nbsp</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
                @if(!$categories) <h4 style="padding-bottom:10px" class="widget-user-username">{{ trans("rent.empty_category") }}</h4>
                @endif
        </div>
        </div>
    </div>

@endsection
