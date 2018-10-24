@extends('layouts.app')
@section('content')
    <div class="row">
        {!! Form::open(array('route' => 'item.createCatRed')) !!}
        <div class="col-md-6 col-lg-offset-3">
            <div class="form-group">
                <label for="cat-type">{{ trans("rent.choose_category") }}</label>
                <div class="input-group">
                    <span class="input-group-btn">{!! Form::select("type", [trans('rent.rent'), trans('rent.sale')], 1, ["id"=>"rent-type", "required"=>"required","class"=>"form-control","style"=>"width: auto"]) !!}</span>
                    <select id="cat-type" name="category" onchange="document.getElementById('go-create').click()" class="select2 form-control self" style="width: 100%">
                    @foreach($categories as $cat)
                        <optgroup  label="{{ $cat->name->$locale }}">
                        @foreach($cat->child as $sub_cat)
                            <option value="{{ $sub_cat->id }}">{{ $sub_cat->name_single->$locale }}</option>
                        @endforeach
                        </optgroup>
                    @endforeach
                </select>
                    <span class="input-group-btn"><button id="go-create" type="submit" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i></button></span>
                </div>
            </div>
            <div class="from-group pull-left">
                <a class="btn btn-default btn-flat" href="/category/create">
                    <i class="fa fa-folder"></i>
                   {{ trans('rent.did_not_find_category') }}
                </a>
            </div>
            @if(!auth()->check() or !auth()->user()->market)
            <a href="/markets/create" class="btn btn-success flat pull-right"><i class="fa fa-plus"></i> {{ trans('rent.new_market') }}</a>
            @else
                <a href="/{{ auth()->user()->market }}" class="btn btn-success flat pull-right"><i class="fa fa-shopping-cart"></i> {{ trans('rent.to_market') }}</a>
            @endif
        </div>
        {!! Form::close() !!}
    </div>
@endsection
@section('after_scripts')

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

        });
    </script>
    <script src="{{ asset('js/rent.js') }}"></script>
@endsection