@extends('layouts.app')
@section('before_styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection
@section('content')
    <form id="form" action="{{ route('market.update', $market->id) }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
    <div class="row">
        <div id="filter">
            <div style="background:#ffffff ;height:auto;position:relative;width: 100%">
                <div id="background-image" style="position:relative;width:100%;height:100%;background:linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.5)),url('/storage/{{ $market->background }}') center center / cover no-repeat">
                    <img style="display: inline-block;width:100%;height:auto;min-height:270px; max-height:320px" src="/storage/default-images/market_trans.png"/>
                    <div style="text-align:center;padding:10px;background:transparent;top:60px;right:0;bottom:0;left:0;position:absolute;display: inline-block;">
                        <div id="icon-image" style="background:url('/storage/{{ $market->icon }}') center center / 80px 80px no-repeat; display:inline-block;height:80px;width: 80px; border-radius: 50%; border:4px solid #ffffff;"></div>
                        <div style="color: #ffffff; cursor: pointer;" title="{{ trans('rent.change') }}"><i onclick="$('#icon').click()" class="fa fa-camera"></i> </div>
                        <div>
                        <div style="position:relative;display: inline-block;cursor:pointer">
                            <div id="orig_name" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="color:#ffffff;text-shadow: 0 1px 0 black;font-size: 20pt">{{ $market->name }} <i class="fa fa-pencil"></i></div>
                            <div class="dropdown-menu" style="width:100%">
                                <input id="name" placeholder="{{ trans('rent.name_market') }}" name="name" type="text" class="form-control" value="{{ $market->name }}" />
                            </div>
                        </div>
                        </div>
                        <div style="position:relative;display: inline-block;cursor:pointer">
                            <div id="orig_type" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="text-shadow: 0 1px 0 black;font-size: 12pt;color: #fff">{{ $market->description->$locale }} <i class="fa fa-pencil"></i> </div>
                            <div class="dropdown-menu" style="width:100%">
                                <label>{{ trans('rent.description') }}(RU)</label>
                                <textarea id="type_ru" placeholder="{{ trans('rent.description') }}" name="description[ru]" class="form-control">{{ $market->description->$locale }}</textarea>
                                <label>{{ trans('rent.description') }}(EN)</label>
                                <textarea id="type_en" placeholder="{{ trans('rent.description') }}" name="description[en]" class="form-control">{{ $market->description->$locale }}</textarea>
                                <label>{{ trans('rent.description') }}(KG)</label>
                                <textarea id="type_kg" placeholder="{{ trans('rent.description') }}" name="description[kg]" class="form-control">{{ $market->description->$locale }}</textarea>
                            </div>
                        </div>
                    </div>
            </div>
                <div style="display:inline-block;position: absolute; top:15px;left:15px;">
                    <div style="display: inline-block; cursor: pointer;">
                        <span title="{{ trans('rent.change') }}" style="margin:3px;display:inline-block;text-align:center;color: #ffffff;height:32px;width:32px;font-size:16pt;border-radius: 50%;padding: 2px;"><i onclick="$('#background').click()" class="fa fa-camera"></i> </span>
                    </div>
                </div>
        </div>
            <input id="background" class="hidden" type="file" accept="image/*" name="background"/>
            <input id="icon" class="hidden" type="file" accept="image/*" name="icon"/>
        </div>
    </div>
    <div class="row">
        <div class="box box-default">
            <div class="box-header with-border">
                {{ trans('rent.marked_with_required') }}
            </div>
            <div class="box-body">
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ trans('rent.link_to_market') }} ({{ trans('rent.only_latin_and', ["symbols"=>"'-_'"]) }})*</label>
                        <div class="input-group">
                            <span class="input-group-addon">{{ url()->route('home') }}/</span>
                        <input id="market-link" class="form-control" required placeholder="{{ trans('rent.link_to_market') }}" name="slug" type="text" value="{{ $market->slug }}">
                        <div id="old_slug" class="hidden">{{ $market->slug }}</div>
                        </div>
                        <p id="slug-error" style="color:#a94442;display: none">{{ trans('rent.not_free_link') }}</p>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('rent.phone_number') }}*</label>
                        <input required class="form-control" value="{{ implode(",", $market->contacts->phone) }}" placeholder="{{ trans('rent.commas')." (".trans("rent.number").",".trans("rent.number").")" }}" name="phone_number" type="text">
                    </div>
                    <div class="form-group">
                        <label>{{ trans('rent.address') }}*</label>
                        <div class="level-1"></div>
                        <div class="text_select" style="display: none;">{{ trans('rent.other') }}</div>
                        <div class="old_address hidden">{{ $market->address }}</div>
                        <div class="street" style="display: none;">{{ trans('rent.street') }}</div>
                        <input type='text' value="{{ $market->address_text }}" required class='form-control' name='address_text' placeholder="{{ trans('rent.street') }}" />
                    </div>
                </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cat-type">{{ trans("rent.select_categories") }}*</label>
                            {!! Form::select('type[]', $type, explode(":", $market->type), ["multiple"=>"multiple", "required"=>"required", "class"=>"select2 form-control", "id"=>"cat-type"]) !!}
                        </div>
                        <div class="form-group">
                            <label>{{ trans('rent.social_networks') }}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-whatsapp"></i></span>
                                <input class="form-control" placeholder="Whatsapp" name="whatsapp" type="text" value="{{ isset($market->contacts->whatsapp)?$market->contacts->whatsapp:"" }}">
                            </div>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-facebook"></i></span>
                                <input class="form-control" placeholder="Facebook" name="facebook" type="text" value="{{ isset($market->contacts->facebook)?$market->contacts->facebook:"" }}">
                            </div>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-whatsapp"></i></span>
                                <input class="form-control" placeholder="Instagram" name="instagram" type="text" value="{{ isset($market->contacts->instagram)?$market->contacts->instagram:"" }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ trans('rent.delivery') }}</label>
                            <input class="form-control" value="{{ $market->delivery }}" placeholder="{{ trans('rent.commas')." (".trans("rent.address").", ".trans("rent.address").")" }}" name="delivery" type="text">
                            {{ trans('rent.leave_blank_if_not') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button id="submit" class="btn btn-primary flat" type="submit">{{ trans("rent.save") }}</button>
                <button id="delete-btn" class="btn btn-danger flat" name="delete" type="submit">{{ trans("rent.delete") }}</button>
            </div>
        </div>
    </div>
    </form>
    <div id="confirm-deleting" style="display: none">{{ trans('rent.confirm_deleting') }}</div>
@endsection
@section("after_scripts")
    <script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/location.js') }}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
            var err = $("#slug-error"), submit=$("#submit");
            var marketLink = $("#market-link");
            marketLink.on("input", function () {
                marketLink.val($.Nukura.transWord(marketLink.val(), true).replace(/[^0-9a-zA-Z_]+/g, "-").toLowerCase());
                $.get("/markets/check", {slug:this.value}, function (data) {
                    if(data && $("#old_slug").html() !== marketLink.val()) {
                        marketLink.parent().addClass('has-error');
                        err.show();
                        submit.attr("disabled", "disabled");
                    }
                    else {
                        marketLink.parent().removeClass('has-error');
                        err.hide();
                        submit.removeAttr("disabled");
                    }
                });
            });
            $("#name").on("input", function(){
                $("#orig_name").html(this.value+' <i class="fa fa-pencil"></i>');
            });
            $("#type_{{ $locale }}").on("input", function(){
                $("#orig_type").html(this.value+' <i class="fa fa-pencil"></i>');
            });
            var background = $("#background-image"), icon = $("#icon-image");
            $("#background").on("change", function () {
                var file    = this.files[0];
                var reader  = new FileReader();

                reader.onloadend = function () {
                    background.css('background', "linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.5)),url('"+reader.result+"') center center / cover no-repeat");

                };

                if (file) {
                    reader.readAsDataURL(file);
                } else {
                    icon.src = "";
                }
            });
            $("#icon").on("change", function () {
                var file    = this.files[0];
                var reader  = new FileReader();

                reader.onloadend = function () {
                    icon.css('background', "url('"+reader.result+"') center center / 80px 80px no-repeat");
                };

                if (file) {
                    reader.readAsDataURL(file);
                } else {
                    icon.css("background", "url('/storage/default-images/no-image.jpeg') center center no-repeat");
                }
            });
            $("#form").on('submit', function () {
                $.Nukura.formSaver('{{ url()->current() }}', this, function (msg) {
                    sessionStorage.setItem("success", '{{ trans('rent.updating_success') }}');
                    window.location.href=msg;
                }, function () {
                    new PNotify({
                        title: '{{ trans('app.error') }}',
                        text: '{{ trans('rent.connection_seems_dead') }}',
                        type: "error",
                        icon: "fa fa-warning"
                    });
                }, true);
            });
        });
    </script>
@endsection
