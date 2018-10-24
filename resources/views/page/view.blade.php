@extends('layouts.app')
@section('title', ' - '. $page->title)
@section('content')
    <div class="box box-primary color-palette-box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $page->title }}</h3>
        </div>
        <div class="box-body">
            {!! $page->body !!}
        </div>
        <!-- /.box-body -->
    </div>
@endsection
