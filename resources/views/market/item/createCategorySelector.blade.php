@extends('layouts.market')
@section('before_styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection
@section('content')
    <div class="row">
        {!! Form::open(['route' => ['market.item.catselect', $Market->slug]]) !!}
        {{ csrf_field() }}
        <div class="col-md-6 col-md-offset-3">
            <div class="form-group">
                <label for="cat-type">{{ trans("rent.choose_category") }}</label>
                <div class="input-group">
                    <select id="cat-type" name="category" onchange="document.getElementById('go-create').click()" class="select2 form-control self" style="width: 100%">
                    @foreach($categories as $cat)
                        <optgroup label="{{ $cat->name }}">
                            @foreach($cat->child as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                    <span class="input-group-btn"><button id="go-create" type="submit" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i></button></span>
                </div>
            </div>
            <div class="from-group">
                <a href="/category/create">
                   {{ trans('rent.did_not_find_category') }}?
                </a>
            </div>
        </div>
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