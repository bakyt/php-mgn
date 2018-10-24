@extends('layouts.app')
@section('before_styles')
<link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection
@section('content')
<div class="row">
    {!! Form::open(['route' => ['market.select']]) !!}
    {{ csrf_field() }}
    <div class="col-md-12">
        <div style="padding: 15px 15px; background: rgb(243, 156, 18); z-index: 999999; font-size: 16px; font-weight: 600; color: #ffffff;">
            {{ trans('rent.warning_market_text') }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="cat-type">{{ trans("rent.select_categories") }}</label>
            {!! Form::select('type[]', $types, key($types), ["multiple"=>"multiple", "required"=>"required", "class"=>"select2 form-control", "id"=>"cat-type"]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="cat-type">{{ trans("rent.name_market") }}</label>
                {!! Form::text('name', "", ["class"=>"form-control", "placeholder"=>trans('rent.name_market'), "required"=>"required"]) !!}
        </div>
        {{--<div class="from-group">--}}
            {{--<a href="/category/create">--}}
                {{--{{ trans('rent.did_not_find_category') }}?--}}
            {{--</a>--}}
        {{--</div>--}}
    </div>
    <div class="col-md-6"><button type="submit" class="btn btn-primary flat">{{ trans('rent.create') }}</button></div>
    {!! Form::close() !!}
</div>
@endsection
@section('after_scripts')
<script src="{{ asset('plugins/select2/select2.min.js') }}"></script>

<script>
    $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();

    });
</script>
<script src="{{ asset('js/rent.js') }}"></script>
@endsection