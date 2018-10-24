@extends('layouts.market')
@section('before_styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection
@section('content')
    <div class="row">
    <form id="form" action="/{{ $Market->slug }}/category" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="ajax" value="true"/>
        <div>
        @php($i=0)
        @foreach($categories as $category)
            <div class="col-md-4" id="section-content-{{ $i }}">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="section" id="section-{{ $i }}">{{ $category->section->$locale }}</div>
                        <div class="box-tools pull-right" style="background: #ffffff;">
                            <div style="display: inline-block;">
                                <button onclick="$(this).parent().parent().parent().parent().css('box-shadow', '0 1px 1px rgba(0, 0, 0, 0.1)')" type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-edit"></i></button>
                                <div class="dropdown-menu" style="width:200px; padding:5px">
                                    <label>{{ trans('rent.title') }}(RU)</label>
                                    <input @if($locale == 'ru') id="input-{{ $i }}" @endif onkeydown="if(event.keyCode===13) event.preventDefault()" type="text" class="form-control" value="{{ $category->section->ru }}" name="section[{{ $i }}][section][ru]" placeholder="{{ trans('rent.title') }}(RU)"/>
                                    <label>{{ trans('rent.title') }}(EN)</label>
                                    <input @if($locale == 'en') id="input-{{ $i }}" @endif onkeydown="if(event.keyCode===13) event.preventDefault()" type="text" class="form-control" value="{{ $category->section->en }}" name="section[{{ $i }}][section][en]" placeholder="{{ trans('rent.title') }}(EN)"/>
                                    <label>{{ trans('rent.title') }}(KG)</label>
                                    <input @if($locale == 'kg') id="input-{{ $i }}" @endif onkeydown="if(event.keyCode===13) event.preventDefault()" type="text" class="form-control" value="{{ $category->section->kg }}" name="section[{{ $i }}][section][kg]" placeholder="{{ trans('rent.title') }}(KG)"/>
                                </div>
                            </div>
                            <button onclick="replacerUp($(this).parent().parent().parent().parent())" type="button" class="btn btn-box-tool"><i class="fa fa-arrow-up"></i></button>
                            <button onclick="replacerDown($(this).parent().parent().parent().parent())" type="button" class="btn btn-box-tool"><i class="fa fa-arrow-down"></i></button>
                            <button id="close-{{ $i }}" type="button" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div id="body-{{ $i }}" class="box-body no-padding" style="height:100px;overflow: auto">
                        <ul id="body-scroll-{{ $i }}" class="nav nav-stacked">
                            @php($h=0)
                            @foreach($category->cats as $cat)
                                <li class="category-{{ $cat->id }}">
                                    <input type="hidden" name="section[{{ $i }}][cats][]" value="{{ $cat->id }}"/>
                                    <a style="font-size: 10pt !important;">{{ json_decode($cat->name)->$locale }}
                                        <span class="pull-right badge bg-red cursor-pointer" onclick="$(this).parent().parent().remove()"><i class="fa fa-trash"></i></span>
                                        <span class="pull-right badge bg-none text-purple cursor-pointer" onclick="replacerUp($(this).parent().parent())"><i class="fa fa-arrow-up"></i></span>
                                        <span class="pull-right badge bg-none text-purple cursor-pointer" onclick="replacerDown($(this).parent().parent())"><i class="fa fa-arrow-down"></i></span>
                                    </a></li>
                                @php($h++)
                            @endforeach
                            <li style="display: none" id="category-adder-li-{{ $i }}"></li>
                        </ul>
                    </div>
                    <div class="box-footer" style="text-align:center;">
                        <a data-toggle="modal" data-target="#modal-default" id="category-adder-{{ $i }}" style="cursor: pointer;font-size: 11pt !important;"><i class="fa fa-plus"></i> &nbsp;{{ trans('rent.add') }}</a>
                    </div>
                </div>
            </div>
            @php($i++)
        @endforeach
    <div class="col-md-4">
        <div class="box box-success">
            <div class="box-header with-border">
                <i class="fa fa-plus"></i> {{ trans('rent.create_section') }}
            </div>
            <div class="box-body" style="text-align: left">
                <label>{{ trans('rent.title') }}(RU)</label>
                <input onkeydown="if(event.keyCode===13) {$('#section-adder').click();event.preventDefault()}" id="input-adder-ru" type="text" class="form-control" placeholder="{{ trans('rent.title') }}(RU)"/>
                <label>{{ trans('rent.title') }}(EN)</label>
                <input onkeydown="if(event.keyCode===13) {$('#section-adder').click();event.preventDefault()}" id="input-adder-en" type="text" class="form-control" placeholder="{{ trans('rent.title') }}(EN)"/>
                <label>{{ trans('rent.title') }}(KG)</label>
                <input onkeydown="if(event.keyCode===13) {$('#section-adder').click();event.preventDefault()}" id="input-adder-kg" type="text" class="form-control" placeholder="{{ trans('rent.title') }}(KG)"/>
            </div>
            <div class="box-footer">
                <button id="section-adder" type="button" class="btn btn-primary flat pull-right"><i class="fa fa-plus"></i> {{ trans('rent.add') }}</button>
            </div>
        </div>
    </div>
        </div>
        <div class="col-md-12">
            <button id="form-submit" type="submit" class="btn btn-primary flat">{{ trans('rent.save') }}</button>
            <a href="/{{ $Market->slug }}" class="btn btn-default flat">{{ trans('rent.cancel') }}</a>
        </div>
    </form>
    </div>
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">{{ trans('rent.select') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-group">
                            <select id="categories" onchange="$('#categories-btn').click()" class="form-control select2" style="width:100%">
                                @foreach($categoriesSelect as $cat)
                                    <option value="0">{{ trans('rent.select') }}</option>
                                    <optgroup  label="{{ $cat->name }}">
                                        @foreach($cat->child as $sub_cat)
                                            <option value="{{ $sub_cat->id }}">{{ json_decode($sub_cat->name)->$locale }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <span class="input-group-btn"><button id="categories-btn" type="button" class="btn btn-primary flat"><i class="fa fa-arrow-right"></i></button></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ trans('rent.cancel') }}</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div style="display: none" id="confirm_category_already_have">{{ trans('rent.confirm_category_already_have') }}</div>
    <div style="display: none" id="required_field">{{ trans('rent.required_field') }}</div>
@endsection
@section('after_scripts')
    <script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            var myCurrentCat = 0, saveBtn=false, sectionAdder=$("#section-adder"), categoriesBtn = $("#categories-btn"), categories=$("#categories"), counter = '{{ $i }}';
            categoriesBtn.on('click', function () {
                if(categories.val() === '0') return false;
                var check = true, sub = $('.category-'+categories.val());
                if(sub.length) check = confirm($("#confirm_category_already_have").html().replace(":first",sub.parent().parent().parent().find('.section').html().replace('&amp;', '&')).replace(":second",$("#section-"+myCurrentCat).html().replace("&amp;", "&")));
                if(check){
                    var newCat = '<li class="bg-success category-'+categories.val()+'">' +
                        '<input type="hidden" name="section['+myCurrentCat+'][cats][]" value="'+categories.val()+'"/>' +
                        '<a style="font-size: 10pt !important;">'+categories.find(':selected').text()+' ' +
                        '<span class="pull-right badge bg-red cursor-pointer" onclick="$(this).parent().parent().remove()"><i class="fa fa-trash"></i></span>' +
                        '<span class="pull-right badge bg-none text-purple cursor-pointer" onclick="replacerUp($(this).parent().parent())"><i class="fa fa-arrow-up"></i></span>\n' +
                        '<span class="pull-right badge bg-none text-purple cursor-pointer" onclick="replacerDown($(this).parent().parent())"><i class="fa fa-arrow-down"></i></span>\n' +
                        '</a></li>';
                    $('#category-adder-li-'+myCurrentCat).first().before(newCat);
                    $('#body-'+myCurrentCat).animate({scrollTop:$('#body-scroll-'+myCurrentCat).prop("scrollHeight")});
                    $("#modal-default").modal('hide');
                }
            });
            sectionAdder.on('click', function () {
                var requiredField = $("#required_field").html(), inputRu=$("#input-adder-ru"), inputEn=$("#input-adder-en"), inputKg=$("#input-adder-kg");
                if(!inputRu.val()) new PNotify({
                    // title: 'Regular Notice',
                    text: requiredField.replace(":field", '{{ trans('rent.title') }}(RU)'),
                    type: "error",
                    icon: false
                });
                else if(!inputEn.val()) new PNotify({
                    // title: 'Regular Notice',
                    text: requiredField.replace(":field", '{{ trans('rent.title') }}(EN)'),
                    type: "error",
                    icon: false
                });
                else if(!inputKg.val()) new PNotify({
                    // title: 'Regular Notice',
                    text: requiredField.replace(":field", '{{ trans('rent.title') }}(KG)'),
                    type: "error",
                    icon: false
                });
                else {
                    var section = '<div class="col-md-4" id="section-content-' + counter + '">\n' +
                        '                <div class="box box-primary">\n' +
                        '                    <div class="box-header with-border">\n' +
                        '                        <div class="section" id="section-' + counter + '">'+$("#input-adder-{{ $locale }}").val()+'</div>\n' +
                        '                        <div class="box-tools pull-right" style="background: #ffffff;">\n' +
                        '                            <div style="display: inline-block;">\n' +
                        '                                <button onclick="$(this).parent().parent().parent().parent().css(\'box-shadow\', \'0 1px 1px rgba(0, 0, 0, 0.1)\')" type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-edit"></i></button>\n' +
                        '                                <div class="dropdown-menu" style="width:200px; padding:5px">\n' +
                        '                                    <label>{{ trans('rent.title') }}(RU)</label>\n' +
                        '                                    <input @if($locale == 'ru') id="input-' + counter + '" @endif onkeydown="if(event.keyCode===13) event.preventDefault()" type="text" class="form-control" value="'+inputRu.val()+'" name="section[' + counter + '][section][ru]" placeholder="{{ trans('rent.title') }}(RU)"/>\n' +
                        '                                    <label>{{ trans('rent.title') }}(EN)</label>\n' +
                        '                                    <input @if($locale == 'en') id="input-' + counter + '" @endif onkeydown="if(event.keyCode===13) event.preventDefault()" type="text" class="form-control" value="'+inputEn.val()+'" name="section[' + counter + '][section][en]" placeholder="{{ trans('rent.title') }}(EN)"/>\n' +
                        '                                    <label>{{ trans('rent.title') }}(KG)</label>\n' +
                        '                                    <input @if($locale == 'kg') id="input-' + counter + '" @endif onkeydown="if(event.keyCode===13) event.preventDefault()" type="text" class="form-control" value="'+inputKg.val()+'" name="section[' + counter + '][section][kg]" placeholder="{{ trans('rent.title') }}(KG)"/>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                            <button onclick="replacerUp($(this).parent().parent().parent().parent())" type="button" class="btn btn-box-tool"><i class="fa fa-arrow-up"></i></button>\n' +
                        '                            <button onclick="replacerDown($(this).parent().parent().parent().parent())" type="button" class="btn btn-box-tool"><i class="fa fa-arrow-down"></i></button>\n' +
                        '                            ' +
                        '                            <button id="close-' + counter + '" type="button" class="btn btn-box-tool"><i class="fa fa-times"></i></button>\n' +
                        '                        </div>\n' +
                        '                    </div>\n' +
                        '                    <div id="body-' + counter + '" class="box-body no-padding" style="height:100px;overflow: auto">\n' +
                        '                        <ul id="body-scroll-' + counter + '" class="nav nav-stacked">\n' +
                        '                            <li style="display: none" id="category-adder-li-' + counter + '"></li>\n' +
                        '                        </ul>\n' +
                        '                    </div>\n' +
                        '                    <div class="box-footer" style="text-align:center;">\n' +
                        '                        <a data-toggle="modal" data-target="#modal-default" id="category-adder-' + counter + '" style="cursor: pointer;font-size: 11pt !important;"><i class="fa fa-plus"></i> &nbsp;{{ trans('rent.add') }}</a>\n' +
                        '                    </div>\n' +
                        '                </div>\n' +
                        '            </div>';
                    sectionAdder.parent().parent().parent().first().before(section);
                    sectionAdderFunc(counter);
                    $('html, body').animate({scrollTop:$("#section-content-" + counter).offset().top-60});
                    counter++;
                    inputKg.val("");
                    inputEn.val("");
                    inputRu.val("");
                }
            });
            @for($j=0; $j<$i;$j++)
            $("#input-{{ $j }}").on('input', function () {
                $("#section-{{ $j }}").html(this.value?this.value:'{{ trans('rent.title') }}');
            });
            $("#close-{{ $j }}").on('click', function () {
                if(confirm("{{ trans('rent.confirm_deleting') }}")) document.getElementById("section-content-{{ $j }}").outerHTML = "";
            });
            $('#category-adder-{{ $j }}').on('click', function () {
                myCurrentCat = '{{ $j }}';
            });
                    @endfor
            var form = $('#form');
            form.data('serialize',form.serialize()); // On load save form current state
            form.on('submit', function () {
                var requiredField = $("#required_field").html(), err = false;
                for(var i=0; i<counter; i++){
                    var check = $("#section-content-"+i);
                    if(!check.length) continue;
                    var input = check.find(".dropdown-menu").find("input");
                    if(!$(input[0]).val()) {
                        new PNotify({
                            // title: 'Regular Notice',
                            text: requiredField.replace(":field", '{{ trans('rent.title') }}(RU)'),
                            type: "error",
                            icon: false
                        });
                        err=check;
                        break;

                    }
                    else if(!input[1].value) {
                        new PNotify({
                            // title: 'Regular Notice',
                            text: requiredField.replace(":field", '{{ trans('rent.title') }}(EN)'),
                            type: "error",
                            icon: false
                        });
                        err=check;
                        break;
                    }
                    else if(!input[2].value) {
                        new PNotify({
                            // title: 'Regular Notice',
                            text: requiredField.replace(":field", '{{ trans('rent.title') }}(KG)'),
                            type: "error",
                            icon: false
                        });
                        err=check;
                        break;
                    }
                }
                if(err) {
                    event.preventDefault();
                    $('html, body').animate({scrollTop:err.offset().top-60});
                    err.children().css('box-shadow', '0 0 10px red');
                }
                else saveBtn = true;
                $.Nukura.formSaver('{{ url()->current() }}', this, function (msg) {
                    new PNotify({
                        title: '{{ trans('app.success') }}',
                        text: msg,
                        type: "success",
                        icon: "fa fa-check"
                    });
                }, function () {
                    new PNotify({
                        title: '{{ trans('app.error') }}',
                        text: '{{ trans('rent.connection_seems_dead') }}',
                        type: "error",
                        icon: "fa fa-warning"
                    });
                }, true);
            });
            $(window).bind('beforeunload', function(e){
                if(saveBtn) e=null;
                else if(form.serialize()!==form.data('serialize'))return true;
                else e=null; // i.e; if form state change show warning box, else don't show it.
            });
            function sectionAdderFunc(counter) {
                $("#input-"+ counter).on('input', function () {
                    $("#section-"+counter).html(this.value?this.value:'{{ trans('rent.title') }}');
                });
                $("#close-"+counter).on('click', function () {
                    if(confirm("{{ trans('rent.confirm_deleting') }}")) document.getElementById("section-content-"+counter).outerHTML = "";
                });
                $('#category-adder-'+counter).on('click', function () {
                    myCurrentCat = counter;
                });
            }
        });
        function replacerUp($this) {
            $this.prev().hide(500, function () {
                $this.prev().before($this);
                });
            $this.prev().show(500);
        }
        function replacerDown($this) {
            $this.next().hide(500, function () {
                $this.next().after($this);
                });
            $this.next().show(500);
        }
    </script>
@endsection