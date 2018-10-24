@extends('layouts.app')
@section('php')
    @php $title = trans('auth.sign_up') @endphp
@endsection
@section('content')
    <div class="row">
    <div class="register-box box box-primary">
        <div class="box-header">
            <i class="fa fa-user-plus"></i> {{ trans('auth.enter_your_details_correctly') }}
        </div>
        <div class="box-body">

            <form id="register" action="{{ route('register') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group has-feedback">
                    <input required type="text" name="name" class="form-control{{ $errors->has('name') ? ' has-error' : '' }}" placeholder="{{ trans('auth.full_name') }}">
                    <span class="glyphicon glyphicon-info-sign form-control-feedback"></span>
                </div>
                <div class="form-group">
                    <label>{{ trans('auth.phone_number') }}</label>
                    <div class="input-group">
                        <span class="input-group-btn"><select id="phone-code" name="phone_code" class="form-control" style="width:auto;padding-right:0"><option value="996">+996</option></select></span>
                    <input required id="phone-number" maxlength="9" type="text" src="{{ trans('auth.example') }}" name="phone_number" class="form-control{{ $errors->has('phone_number') ? ' has-error' : '' }}" placeholder="{{ trans('auth.example') }}: 702772317">
                    <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                    </div>
                    <span id="phone-error-message" class="text-red"></span>
                </div>
                <div id="confirm-content" @if(!setting('site.activation')) style="display:none" @endif>
                <div class="form-group">
                    <button class="btn btn-primary btn-flat pull-right" disabled type="button" id="send-code">{{ trans('auth.register') }}</button>
                </div>
                <div class="form-group" id="code-content" style="display: none">
                    <div class="input-group">
                        <span class="input-group-addon">{{ trans('auth.code') }}</span>
                        <input id="verificationcode" maxlength="6" type="number" name="code" class="form-control" placeholder="{{ trans('auth.enter_code') }}">
                        <span class="input-group-btn">
                            <button class="btn btn-primary btn-flat" id="verify" type="button">{{ trans('auth.verify') }}</button>
                        </span>
                    </div>
                </div>
                </div>
                <div id="other-content" @if(setting('site.activation')) style="display:none" @endif>
                <div class="form-group has-feedback">
                    <label for="birth-date">{{ trans('auth.birth_date') }}</label>
                    <input id="birth-date" type="date" name="birth_date" class="form-control{{ $errors->has('phone_number') ? ' has-error' : '' }}">
                    <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <label for="gender">{{ trans('auth.gender') }}</label>
                    <select id="gender" name="gender" class="form-control{{ $errors->has('gender') ? ' has-error' : '' }}">
                        <option value="0">{{ trans('auth.select') }}</option>
                        <option value="1">{{ trans('auth.male') }}</option>
                        <option value="2">{{ trans('auth.female') }}</option>
                    </select>
                    <span class="fa fa-venus-mars form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input required type="password" id="password" name="password" class="form-control{{ $errors->has('password') ? ' has-error' : '' }}" placeholder="{{ trans('auth.password') }}">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input required type="password" id="password-confirm" class="form-control" name="password_confirmation" placeholder="{{ trans('auth.retype_password') }}">
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        {{ trans('auth.agreement') }}

                    </div>
                    <!-- /.col -->
                    <div class="col-xs-12">
                        <a href="{{ route('login') }}" class="text-center">{{ trans('auth.sign_in') }}</a>
                        <button type="submit" id="sign-up" disabled class="btn btn-primary btn-flat pull-right">{{ trans('auth.register') }}</button>
                    </div>
                    <!-- /.col -->
                </div>
                </div>
            </form>

            {{--<div class="social-auth-links text-center">--}}
                {{--<p>- {{ trans('auth.or') }} -</p>--}}
                {{--<a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> {{ trans('auth.sign_up_using',["messenger"=>'Facebook']) }}--}}
                    {{--</a>--}}
                {{--<a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> {{ trans('auth.sign_up_using', ["messenger"=>'Google+']) }}--}}
                    {{--</a>--}}
            {{--</div>--}}
        </div>
        <!-- /.form-box -->
    </div>
        <div id="already_registered" style="display: none">{{ trans('auth.already_taken') }}</div>
        <div id="not-match" style="display: none">{{ trans('auth.passwords_not_match') }}</div>
        <!-- /.register-box -->
    </div>
@endsection
@section("after_styles")
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/square/blue.css') }}">
    <script>
        function captchaExcepted(){
            $('#sign-up').removeAttr('disabled');
        }
    </script>
@endsection
@section("after_scripts")
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' 
            });
        });
    </script>
    <script type="text/javascript">
        
        $(function () {
            $.Nukura.initializePhoneActivator('send-code');
            $.Nukura.initPhoneField("phone-code", "phone-number", true);
            var sendCode = $('#send-code');
            sendCode.on('click', function () {
                $.Nukura.sendActivationCode("+" + document.getElementById("phone-code").value + document.getElementById("phone-number").value, function () {
                    sendCode.hide();
                    $('#code-content').slideToggle();
                    new PNotify({
                        title: '{{ trans("app.success") }}',
                        text: document.getElementById('code-sent').innerHTML,
                        type: "success",
                        icon: "fa fa-check"
                    });
                }, function (error) {
                    //alert(error);
                });
            });
            $('#verify').on('click', function () {
                $.Nukura.confirmActivationCode('verificationcode', function () {
                    new PNotify({
                        title: '{{ trans("app.success") }}',
                        text: document.getElementById('verified').innerHTML,
                        type: "success",
                        icon: "fa fa-check"
                    });
                    $('#confirm-content').slideToggle();
                    $('#other-content').slideToggle();
                    $('#sign-up').removeAttr("disabled");
                    $('#phone-code').attr("disabled", "disabled");
                    $('#phone-number').attr("disabled", "disabled");
                }, function (error) {
                    new PNotify({
                        //title: '{{ trans("app.success") }}',
                        text: error,
                        type: "error",
                        icon: "fa fa-close",
                        buttons:{

                        }
                    });
                });
            });
            $('#register').on('submit', function () {
                if($('#password').val() !== $('#password-confirm').val()) {
                    new PNotify({
                        title: '{{ trans("app.error") }}',
                        text: document.getElementById('not-match').innerHTML,
                        type: "error",
                        icon: "fa fa-close"
                    });
                    event.preventDefault();
                }
                else {
                    $("#phone-code").removeAttr("disabled");
                    $("#phone-number").removeAttr("disabled");
                    sessionStorage.removeItem("verified");
                }
            });
            $('#phone-number').on('input', function () {
                var errorMessage = $("#phone-error-message"), already_reg = $("#already_registered").html(),$this=$(this);
                $.Nukura.checkPhoneNumber($this, $("#phone-code").val(), function (data) {
                    //success
                    if(data['has']) {
                        $this.parent().addClass('has-error');
                        errorMessage.html(already_reg);
                        $('#sign-up').attr("disabled", "disabled");
                    }
                    else {
                        if($this.parent().hasClass('has-error')) {
                            errorMessage.html("");
                            $this.parent().removeClass('has-error');
                        }
                        errorMessage.html("");
                        sendCode.removeAttr("disabled");
                        $this.parent().addClass('has-success');
                        @if(!setting('site.activation'))
                        $('#sign-up').removeAttr("disabled");
                        @endif
                    }
                }, function () {
                    //error
                    $('#sign-up').attr("disabled", "disabled");
                    sendCode.attr("disabled","disabled");
                    if($this.parent().hasClass('has-error')) {
                        errorMessage.html("");
                        $this.parent().removeClass('has-error');
                    }
                    else if($this.parent().hasClass('has-success')) $this.parent().removeClass('has-success');
                    $('#sign-up').attr("disabled", "disabled");
                    sendCode.show();
                    $('#code-content').hide();
                });
            });
        });
    </script>
@endsection